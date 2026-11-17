<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Ticket;

class userController extends Controller
{
    public function showUser()
    {
        $tickets = Ticket::with(['screening.movie', 'screening.hall'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('User', compact('tickets'));
    }
}
