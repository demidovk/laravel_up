@extends('layout.index')

@section('title', $movie->seo_title ?: $movie->title)
@section('description', $movie->seo_description ?: \Illuminate\Support\Str::limit($movie->description, 150))
@section('keywords', $movie->title . ', ' . $movie->genres->pluck('name')->implode(', ') . ', ' . $movie->age_rating)

@section('meta')
    @if($movie->poster_image)
        <meta property="og:image" content="{{ asset('storage/' . $movie->poster_image) }}">
    @endif
    <meta property="og:title" content="{{ $movie->title }}">
    <meta property="og:description" content="{{ \Illuminate\Support\Str::limit($movie->description, 150) }}">
    <meta property="og:type" content="video.movie">
@endsection

@section('main')

    <h1>{{ $movie->title }}</h1>

    <div style="display:flex; gap:20px; align-items:flex-start; flex-wrap:wrap;">

        <div style="flex:0 0 300px;">
            @if($movie->poster_image)
                <img src="{{ asset('storage/' . $movie->poster_image) }}"
                     alt="{{ $movie->title }} — постер"
                     style="width:100%; border:1px solid #ccc;">
            @else
                <div style="width:100%; height:400px; background:#eee; display:flex; align-items:center; justify-content:center;">
                    Нет постера
                </div>
            @endif
        </div>

        <div style="flex:1; min-width:300px;">
            <p><strong>Год:</strong> {{ $movie->year }}</p>
            <p><strong>Хронометраж:</strong> {{ $movie->duration }} мин</p>
            <p><strong>Возрастной рейтинг:</strong> {{ $movie->age_rating }}</p>
            <p>
                <strong>Жанры:</strong>
                @foreach($movie->genres as $genre)
                    <span style="background:#eee; padding:2px 6px; margin-right:4px; font-size:12px;">{{ $genre->name }}</span>
                @endforeach
            </p>

            <h3>Описание</h3>
            <p>{{ $movie->description }}</p>
        </div>
    </div>

    <h2 style="margin-top:25px;">Трейлер</h2>
    @if($movie->trailer_url)
        <video controls style="max-width:100%; width:720px;" preload="metadata">
            <source src="{{ asset('storage/' . $movie->trailer_url) }}" type="video/mp4">
            Ваш браузер не поддерживает воспроизведение видео
        </video>
    @else
        <p>Трейлер пока не загружен</p>
    @endif

    {{-- Галерея кадров --}}
    <h2 style="margin-top:25px;">Кадры из фильма</h2>
    @if($movie->images->count())
        <div style="display:flex; flex-wrap:wrap; gap:10px;">
            @foreach($movie->images as $image)
                <img src="{{ asset('storage/' . $image->image_path) }}"
                     alt="{{ $movie->title }} — кадр {{ $loop->iteration }}"
                     style="width:220px; height:140px; object-fit:cover; border:1px solid #ccc;">
            @endforeach
        </div>
    @else
        <p>Кадров пока нет</p>
    @endif

    <h2 style="margin-top:25px;">Расписание сеансов</h2>

    @forelse($screeningsByDate as $date => $screenings)
        <h3 style="margin-top:15px;">{{ \Carbon\Carbon::parse($date)->format('d.m.Y') }}</h3>
        <table border="1" cellpadding="6" style="border-collapse:collapse;">
            <thead>
                <tr>
                    <th>Время</th>
                    <th>Зал</th>
                    <th>Формат</th>
                    <th>Цена</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($screenings as $screening)
                    @php $isPast = $screening->start_time->isPast(); @endphp
                    <tr @if($isPast) style="opacity:0.5;" @endif>
                        <td>{{ $screening->start_time->format('H:i') }}</td>
                        <td>{{ $screening->hall->name }}</td>
                        <td>{{ $screening->format }}</td>
                        <td>{{ $screening->price }} ₽</td>
                        <td>
                            @if($isPast)
                                <em>Сеанс прошёл</em>
                            @else
                                <a href="/screening/{{ $screening->id }}">Купить билет</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @empty
        <p>На этот фильм пока нет сеансов</p>
    @endforelse

@endsection