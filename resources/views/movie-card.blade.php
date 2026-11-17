<article class="movie-card" style="border:1px solid #ccc; padding:10px; margin:10px; width:220px; display:inline-block; vertical-align:top;">
    <a href="/movie/{{ $movie->id }}">
        @if($movie->poster_image)
            <img src="{{ asset('storage/' . $movie->poster_image) }}" alt="{{ $movie->title }}" style="width:100%; height:300px; object-fit:cover;">
        @else
            <div style="width:100%; height:300px; background:#eee; display:flex; align-items:center; justify-content:center;">Нет постера</div>
        @endif
    </a>

    <h3 style="margin:8px 0;">
        <a href="/movie/{{ $movie->id }}">{{ $movie->title }}</a>
    </h3>

    <p style="margin:4px 0;">
        {{ $movie->year }} • {{ $movie->duration }} мин • {{ $movie->age_rating }}
    </p>

    <p style="margin:4px 0;">
        @foreach($movie->genres as $genre)
            <span style="background:#eee; padding:2px 6px; font-size:12px;">{{ $genre->name }}</span>
        @endforeach
    </p>
</article>