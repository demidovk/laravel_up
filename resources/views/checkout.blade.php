@extends('layout.index')

@section('title', 'Оплата заказа')
@section('meta')
    <meta name="robots" content="noindex, nofollow">
@endsection

@section('main')
    <h1>Окно оплаты</h1>
    <p>Вы бронируете следующие билеты:</p>

    <table border="1" cellpadding="8" style="border-collapse: collapse; margin-bottom: 20px;">
        <thead>
            <tr>
                <th>Фильм</th>
                <th>Зал</th>
                <th>Ряд / Место</th>
                <th>Цена</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tickets as $ticket)
                <tr>
                    <td>{{ $ticket->screening->movie->title }}</td>
                    <td>{{ $ticket->screening->hall->name }}</td>
                    <td>{{ $ticket->row_number }} / {{ $ticket->seat_number }}</td>
                    <td>{{ $ticket->screening->price }} ₽</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h3>Итого к оплате: {{ $totalPrice }} ₽</h3>

    <form action="{{ route('process.payment') }}" method="POST">
        @csrf
        <input type="hidden" name="ticket_ids" value="{{ $tickets->pluck('id')->implode(',') }}">
        <button type="submit" style="padding: 10px 20px; font-size: 16px; background-color: #008CBA; color: white; border: none; cursor: pointer;">
            Оплатить
        </button>
    </form>
@endsection