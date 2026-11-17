@extends('layout.index')

@section('title', 'Покупка билета — ' . $screening->movie->title)
@section('description', 'Выбор мест на сеанс ' . $screening->movie->title)

@section('main')

    <h1>{{ $screening->movie->title }}</h1>
    <h3>Зал: {{ $screening->hall->name }} | Время: {{ $screening->start_time->format('d.m.Y H:i') }}</h3>

    @if($errors->has('time'))
        <div style="color:red; font-weight:bold; margin-bottom:15px;">
            {{ $errors->first('time') }}
        </div>
    @endif

    <div style="margin-bottom: 20px;">
        <strong>Экран</strong>
        <div style="width: 300px; height: 10px; background: #ccc; border-radius: 50% 50% 0 0; margin-top: 5px;"></div>
    </div>

    {{-- Схема зала --}}
        @if($errors->has('seats'))
        <div style="color:red; font-weight:bold; margin-bottom:15px;">
            {{ $errors->first('seats') }}
        </div>
    @endif
    @if($errors->has('auth'))
        <div style="color:red; font-weight:bold; margin-bottom:15px;">
            {{ $errors->first('auth') }}
        </div>
    @endif

    <form action="{{ route('store.booking', $screening->id) }}" method="POST">
        @csrf
        
        {{-- Схема зала --}}
        @if($errors->has('seats'))
        <div style="color:red; font-weight:bold; margin-bottom:15px;">
            {{ $errors->first('seats') }}
        </div>
    @endif
    @if($errors->has('auth'))
        <div style="color:red; font-weight:bold; margin-bottom:15px;">
            {{ $errors->first('auth') }}
        </div>
    @endif

    <form action="{{ route('store.booking', $screening->id) }}" method="POST">
        @csrf
        
        {{-- Схема зала --}}
        <div class="hall-scheme" style="display: flex; flex-direction: column; gap: 10px; align-items: center; width: max-content; margin-bottom: 30px;">
            @for($row = 1; $row <= $screening->hall->rows_count; $row++)
                <div style="display: flex; gap: 5px; align-items: center;">
                    <span style="width: 20px; font-size: 12px; color: #666;">{{ $row }}</span>
                    
                    @for($seat = 1; $seat <= $screening->hall->seats_per_row; $seat++)
                        @php 
                            $seatKey = $row . '-' . $seat;
                            $isBooked = in_array($seatKey, $bookedSeats);
                        @endphp
                        
                        <input type="checkbox" name="seats[]" value="{{ $seatKey }}" 
                               id="seat-{{ $seatKey }}" 
                               class="seat-checkbox"
                               {{ $isBooked ? 'disabled' : '' }}
                               style="width: 25px; height: 25px; cursor: {{ $isBooked ? 'not-allowed' : 'pointer' }}; {{ $isBooked ? 'background-color: #ffcccc;' : '' }}">
                    @endfor
                </div>
            @endfor
        </div>
        
        <p style="color: #666; font-size: 14px;">* Красные места — уже куплены.</p>
        <p><strong>Цена билета:</strong> {{ $screening->price }} ₽</p>

        <button type="submit" style="padding: 10px 20px; font-size: 16px; background-color: #4CAF50; color: white; border: none; cursor: pointer;">
            Забронировать выбранные места
        </button>
    </form>

@endsection