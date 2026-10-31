@extends('layout.index')
@section('main')

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Выйти</button>
    </form>
    <br><br>user section <br><br>
   
@endsection