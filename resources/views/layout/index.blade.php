<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Кинотеатр')</title>
    <meta name="description" content="@yield('description', 'Афиша фильмов и расписание сеансов кинотеатра')">
    <meta name="keywords" content="@yield('keywords', 'кино, афиша, расписание, сеансы, билеты')">

    @yield('meta')
</head>
<body>
    <header>
        <nav>
            <a href="/">Главная</a>
            <a href="/catalog">Афиша</a>

            @guest
                <a href="{{ route('show.register') }}">Регистрация</a>
                <a href="{{ route('login') }}">Авторизация</a>
            @endguest
        </nav>
    </header>

    <main>
        
        @yield('main')
    </main>

    <footer>
        <h2>footer</h2>
    </footer>
</body>
</html>