@extends('layout.index')

@section('main')

    <h1>Афиша фильмов</h1>

    <form action="/catalog" method="GET" style="margin-bottom:15px;">
        <input type="text" name="q" placeholder="Поиск фильма..." value="{{ $searchQuery }}">
        @if($selectedGenre)
            <input type="hidden" name="genre" value="{{ $selectedGenre }}">
        @endif
        <button type="submit">Найти</button>
        @if($searchQuery || $selectedGenre)
            <a href="/catalog">Сбросить</a>
        @endif
    </form>

    <h3>Жанры:</h3>
    <p>
        <a href="/catalog{{ $searchQuery ? '?q=' . urlencode($searchQuery) : '' }}"
           style="{{ !$selectedGenre ? 'font-weight:bold;' : '' }}">Все</a>
        @foreach($genres as $genre)
            |
            @php
                $params = array_filter([
                    'genre' => $genre->id,
                    'q' => $searchQuery,
                ]);
            @endphp
            <a href="/catalog?{{ http_build_query($params) }}"
               style="{{ $selectedGenre == $genre->id ? 'font-weight:bold;' : '' }}">
                {{ $genre->name }}
            </a>
        @endforeach
    </p>

    <h3>
        Найдено фильмов: {{ $movies->count() }}
        @if($searchQuery)
            — по запросу «{{ $searchQuery }}»
        @endif
    </h3>

    @forelse($movies as $movie)
        @include('movie-card', ['movie' => $movie])
    @empty
        <p>По вашему запросу ничего не найдено.</p>
    @endforelse

@endsection