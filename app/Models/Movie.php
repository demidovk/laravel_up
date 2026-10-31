<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    protected $fillable = [
        'title', 'description', 'duration_minutes', 'release_year', 
        'age_rating', 'poster_url', 'trailer_url', 'seo_title', 'seo_description'
    ];

    public function genres()
    {
        return $this->belongsToMany(Genre::class);
    }

    public function images()
    {
        return $this->hasMany(MovieImage::class);
    }

    public function screenings()
    {
        return $this->hasMany(Screening::class);
    }
}
