<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'screening_id',
        'booking_code',
        'customer_name',
        'customer_email',
        'customer_phone',
        'total_price',
        'seat_codes',
        'status',
        'booked_at',
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
        'booked_at' => 'datetime',
        'seat_codes' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function screening()
    {
        return $this->belongsTo(Screening::class);
    }

    public function seats()
    {
        return $this->hasMany(BookingSeat::class);
    }

    // Riwayat kursi tetap bisa ditampilkan
    // meskipun kursi sudah dilepaskan setelah pembatalan.
    public function getSeatCodesListAttribute(): array
    {
        $savedSeats = $this->seat_codes;

        if (is_array($savedSeats) && count($savedSeats) > 0) {
            return $savedSeats;
        }

        if ($this->relationLoaded('seats')) {
            return $this->seats
                ->pluck('seat_code')
                ->values()
                ->all();
        }

        return $this->seats()
            ->pluck('seat_code')
            ->values()
            ->all();
    }
}
