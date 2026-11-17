<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class publicController extends Controller
{
    public function showHome()
    {
        $genres = \App\Models\Genre::all();
        $movies = \App\Models\Movie::with('genres')
            ->orderBy('id', 'desc')
            ->limit(6)
            ->get();

        return view('welcome', [
            'genres' => $genres,
            'movies' => $movies,
            'title' => 'Кинотеатр — афиша фильмов и расписание сеансов',
            'description' => 'Афиша фильмов, расписание сеансов, покупка билетов онлайн.',
            'keywords' => 'кино, афиша, фильмы, расписание, сеансы, билеты',
        ]);
    }

    public function showCatalog(Request $request)
    {
        $genres = \App\Models\Genre::all();

        $query = \App\Models\Movie::with('genres');

        if ($request->filled('genre')) {
            $genreId = $request->input('genre');
            $query->whereHas('genres', function ($q) use ($genreId) {
                $q->where('genres.id', $genreId);
            });
        }

        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        $movies = $query->orderBy('id', 'desc')->get();

        return view('catalog', [
            'genres' => $genres,
            'movies' => $movies,
            'selectedGenre' => $request->input('genre'),
            'searchQuery' => $request->input('q'),
            'title' => 'Афиша фильмов — каталог кинотеатра',
            'description' => 'Полный каталог фильмов с фильтрацией по жанрам и поиском.',
            'keywords' => 'афиша, каталог фильмов, жанры, поиск фильмов',
        ]);
    }

    public function showMovie($id)
{
    $movie = \App\Models\Movie::with([
        'genres',
        'images',
        'screenings' => function ($q) {
            $q->with('hall')->orderBy('start_time');
        },
    ])->findOrFail($id);

    $screeningsByDate = $movie->screenings->groupBy(function ($s) {
        return $s->start_time->format('Y-m-d');
    });

    return view('movie', [
        'movie' => $movie,
        'screeningsByDate' => $screeningsByDate,
    ]);
}
}