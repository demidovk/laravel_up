<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Screening extends Model
{
    protected $fillable = ['movie_id', 'hall_id', 'start_time', 'format', 'price'];

    protected $casts = [
        'start_time' => 'datetime',
    ];

    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }

    public function hall()
    {
        return $this->belongsTo(Hall::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
