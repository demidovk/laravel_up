@extends('layout.index')
@section('main')
    <form action="{{ route('store.register') }}" method="POST" enctype="multipart/form-data">
        <h1>Регистрация</h1>
        @csrf
        
        <div><input type="text" name="full_name" placeholder="Введите ФИО" required></div><br>
        <div><input type="text" name="login" placeholder="Введите логин" required></div><br>
        <div><input type="password" name="password" placeholder="Введите пароль" required></div><br>
        <div><label for=role>Выберите роль:<br>
        <label for=role id=role required><br>
        <select name=role id=role class=form-control require><br>
            <option value=user>Пользователь<br>
            <option value=admin>Администратор</div><br>
        <div><input type="submit" value="Зарегистрироваться"></div>
    </form>
@endsection