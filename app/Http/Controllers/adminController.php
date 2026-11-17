<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class adminController extends Controller
{
    public function showAdmin()
{
    $genres = \App\Models\Genre::all();
    $halls = \App\Models\Hall::all();
    $movies = \App\Models\Movie::all();
    $screenings = \App\Models\Screening::with('movie', 'hall')
        ->orderBy('start_time', 'desc')
        ->get();

    return view('admin', compact('genres', 'halls', 'movies', 'screenings'));
}

    public function storeGenre(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:genres,name|max:100'
        ]);

        \App\Models\Genre::create([
            'name' => $request->name
        ]);

        return redirect()->back()->with('success', 'Жанр добавлен');
    }

    public function storeHall(Request $request)
    {
        $request->validate([
            'name' => 'required|max:100',
            'rows_count' => 'required|integer|min:1',
            'seats_per_row' => 'required|integer|min:1',
        ]);

        \App\Models\Hall::create([
            'name' => $request->name,
            'rows_count' => $request->rows_count,
            'seats_per_row' => $request->seats_per_row,
        ]);

        return redirect()->back()->with('success', 'Кинозал создан');
    }

        public function storeMovie(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'duration_minutes' => 'required|integer|min:1',
            'release_year' => 'required|integer',
            'age_rating' => 'required|max:10',
            'genres' => 'required|array',
            'poster' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'trailer' => 'required|file|mimes:mp4,mov,avi,mkv|max:51200'
        ]);

        $posterPath = $request->file('poster')->store('posters', 'public');
        $trailerPath = $request->file('trailer')->store('trailers', 'public');

        $movie = \App\Models\Movie::create([
            'title' => $request->title,
            'description' => $request->description,
            'duration' => $request->duration_minutes, 
            'year' => $request->release_year,         
            'age_rating' => $request->age_rating,
            'poster_image' => $posterPath,
            'trailer_url' => $trailerPath,
            'seo_title' => $request->title, 
            'seo_description' => \Illuminate\Support\Str::limit($request->description, 150),
        ]);

        $movie->genres()->attach($request->genres);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $imagePath = $file->store('movies_gallery', 'public');
                $movie->images()->create([
                    'image_path' => $imagePath
                ]);
            }
        }

        return redirect()->back()->with('success', 'Фильм добавлен в каталог');
    }


    public function destroyGenre($id)
    {
        \App\Models\Genre::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Жанр удален');
    }

    public function destroyHall($id)
    {
        \App\Models\Hall::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Кинозал удален');
    }

    public function destroyMovie($id)
    {
        \App\Models\Movie::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Фильм удален');
    }
    public function storeScreening(Request $request)
{
    $request->validate([
        'movie_id'   => 'required|exists:movies,id',
        'hall_id'    => 'required|exists:halls,id',
        'start_time' => 'required|date',
        'format'     => 'required|max:20',
        'price'      => 'required|numeric|min:0',
    ]);

    $movie = \App\Models\Movie::findOrFail($request->movie_id);
    $start = \Carbon\Carbon::parse($request->start_time);
    $end   = $start->copy()->addMinutes($movie->duration);

    $conflict = \App\Models\Screening::where('hall_id', $request->hall_id)
        ->with('movie')
        ->get()
        ->first(function ($existing) use ($start, $end) {
            $existingStart = $existing->start_time;
            $existingEnd   = $existingStart->copy()->addMinutes($existing->movie->duration);
            return $start->lt($existingEnd) && $end->gt($existingStart);
        });

    if ($conflict) {
        return redirect()->back()
            ->withErrors(['start_time' => 'В этом зале уже есть сеанс в это время (с учётом длительности фильма).'])
            ->withInput();
    }

    \App\Models\Screening::create([
        'movie_id'   => $request->movie_id,
        'hall_id'    => $request->hall_id,
        'start_time' => $request->start_time,
        'format'     => $request->format,
        'price'      => $request->price,
    ]);

    return redirect()->back()->with('success', 'Сеанс создан');
}

public function destroyScreening($id)
{
    \App\Models\Screening::findOrFail($id)->delete();
    return redirect()->back()->with('success', 'Сеанс удалён');
}

}
