@extends('layout.index')

@section('main')

    <h1>Афиша кинотеатра</h1>

    <form action="/catalog" method="GET" style="margin-bottom:15px;">
        <input type="text" name="q" placeholder="Поиск фильма..." value="{{ request('q') }}">
        <button type="submit">Найти</button>
    </form>

    <h3>Жанры:</h3>
    <p>
        <a href="/catalog">Все</a>
        @foreach($genres as $genre)
            | <a href="/catalog?genre={{ $genre->id }}">{{ $genre->name }}</a>
        @endforeach
    </p>

    <h3>Новые фильмы:</h3>

    @forelse($movies as $movie)
        @include('movie-card', ['movie' => $movie])
    @empty
        <p>Фильмов пока нет.</p>
    @endforelse

    <p style="margin-top:15px;">
        <a href="/catalog">Перейти в полный каталог →</a>
    </p>

@endsection