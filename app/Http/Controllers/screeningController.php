<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Screening;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class screeningController extends Controller
{
    public function showScreening($id)
    {
        $screening = Screening::with(['movie', 'hall'])->findOrFail($id);

        if ($screening->start_time->isPast()) {
            return redirect()->route('show.movie', $screening->movie_id)
                ->withErrors(['time' => 'Этот сеанс уже начался или завершился']);
        }

        $bookedSeats = Ticket::where('screening_id', $id)
            ->where('status', '!=', 'cancelled')
            ->get(['row_number', 'seat_number'])
            ->map(function ($ticket) {
                return $ticket->row_number . '-' . $ticket->seat_number;
            })->toArray();

        return view('screening', compact('screening', 'bookedSeats'));
    }

        public function storeBooking(Request $request, $id)
        {
            if (!Auth::check()) {
                return redirect()->route('login')->withErrors(['auth' => 'Войдите в систему, чтобы купить билет']);
            }
    
            $request->validate([
                'seats' => 'required|array|min:1',
            ], [
                'seats.required' => 'Выберите место.',
            ]);
    
            $screening = Screening::findOrFail($id);
    
            if ($screening->start_time->isPast()) {
                return back()->withErrors(['time' => 'Сеанс уже начался. Покупка недоступна']);
            }
    
            $createdTickets = [];
    
            try {
                DB::transaction(function () use ($request, $screening, &$createdTickets) {
                    foreach ($request->seats as $seatKey) {
                        list($row, $seat) = explode('-', $seatKey);
    
                        $createdTickets[] = Ticket::create([
                            'user_id' => Auth::id(),
                            'screening_id' => $screening->id,
                            'row_number' => $row,
                            'seat_number' => $seat,
                            'status' => 'pending',
                            'unique_code' => \Illuminate\Support\Str::uuid()->toString(),
                        ]);
                    }
                });
            } catch (\Illuminate\Database\QueryException $e) {
                if ($e->errorInfo[1] == 1062 || str_contains($e->getMessage(), 'unique_seat_per_screening')) {
                    return back()->withErrors(['seats' => 'Одно или несколько выбранных мест только что купил другой пользователь. Выберите другие места']);
                }
                throw $e;
            }

            $ticketIds = collect($createdTickets)->pluck('id')->implode(',');
            return redirect()->route('show.checkout', ['tickets' => $ticketIds]);
        }
    
        public function showCheckout($ticketIds)
        {
            $ids = explode(',', $ticketIds);
            
            $tickets = Ticket::with('screening.movie', 'screening.hall')
                ->whereIn('id', $ids)
                ->where('user_id', Auth::id())
                ->where('status', 'pending')
                ->get();
    
            if ($tickets->isEmpty()) {
                return redirect()->route('show.user')->withErrors(['error' => 'Билеты не найдены или уже оплачены']);
            }
    
            $totalPrice = $tickets->sum(function($ticket) {
                return $ticket->screening->price;
            });
    
            return view('checkout', compact('tickets', 'totalPrice'));
        }
    
        public function processPayment(Request $request)
        {
            $request->validate([
                'ticket_ids' => 'required|string'
            ]);
    
            $ids = explode(',', $request->ticket_ids);
    
            Ticket::whereIn('id', $ids)
                ->where('user_id', Auth::id())
                ->where('status', 'pending')
                ->update(['status' => 'paid']);
    
            return redirect()->route('show.user')->with('success', 'Билеты успешно оплачены');
        }
}