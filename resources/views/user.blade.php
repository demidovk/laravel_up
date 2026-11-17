@extends('layout.index')

@section('title', 'Личный кабинет')
@section('meta')
    <meta name="robots" content="noindex, nofollow">
@endsection

@section('main')
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h1>Личный кабинет</h1>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit">Выйти</button>
        </form>
    </div>

    <h2>Мои билеты</h2>

    @if(session('success'))
        <div style="color: green; margin-bottom: 15px;">{{ session('success') }}</div>
    @endif

    @if($tickets->isEmpty())
        <p>У вас пока нет купленных билетов.</p>
    @else
        <table border="1" cellpadding="8" style="border-collapse: collapse; width: 100%;">
            <thead>
                <tr>
                    <th>Фильм</th>
                    <th>Дата и время</th>
                    <th>Зал</th>
                    <th>Ряд / Место</th>
                    <th>Статус</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tickets as $ticket)
                    <tr>
                        <td>{{ $ticket->screening->movie->title }}</td>
                        <td>{{ $ticket->screening->start_time->format('d.m.Y H:i') }}</td>
                        <td>{{ $ticket->screening->hall->name }}</td>
                        <td>{{ $ticket->row_number }} / {{ $ticket->seat_number }}</td>
                        <td>
                            @if($ticket->status === 'paid')
                                <span style="color: green;">Оплачен</span>
                            @elseif($ticket->status === 'pending')
                                <span style="color: orange;">Ожидает оплаты</span>
                            @else
                                <span style="color: red;">Отменен</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection