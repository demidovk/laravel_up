<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = ['user_id', 'screening_id', 'row_number', 'seat_number', 'status', 'ticket_code', 'qr_code_url'];

    public function screening()
    {
        return $this->belongsTo(Screening::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
