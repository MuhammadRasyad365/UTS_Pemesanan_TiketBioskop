<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingSeat extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'screening_id',
        'seat_code',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function screening()
    {
        return $this->belongsTo(Screening::class);
    }
}
