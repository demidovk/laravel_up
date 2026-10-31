<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <header>
         <ul>
            @guest
                <a href="{{ route('show.register') }}">Регистрация</a>
                <a href="{{ route('show.login') }}">Авторизация</a>
            @endguest
        </ul>
    </header>

    <main>
        
        @yield('main')
    </main>

    <footer>
        <h2>footer</h2>
    </footer>
</body>
</html>