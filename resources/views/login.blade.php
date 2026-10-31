@extends('layout.index')
@section('main')
    <form action="{{ route('store.login') }}" method="POST">
        <h1>Авторизация</h1>
        @csrf

        <div><input type="text" name="login" placeholder="Введите логин" required></div><br>
        <div><input type="password" name="password" placeholder="Введите пароль" required></div><br>
        <div><input type="submit" value="Войти"></div>
    </form>
@endsection