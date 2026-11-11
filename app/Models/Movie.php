<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Movie extends Model
{
    use HasFactory;


    protected $fillable = [
        'title',
        'slug',
        'genre',
        'duration',
        'age_rating',
        'synopsis',
        'poster_url',
        'release_date',
        'rating',
        'ticket_price',
        'status',
    ];


    protected $casts = [
        'release_date' => 'date',
        'rating' => 'decimal:1',
        'ticket_price' => 'decimal:2',
    ];

    public function screenings()
    {
        return $this->hasMany(Screening::class);
    }

    public function getPosterDisplayUrlAttribute(): ?string
{
    $path = $this->poster_url;

    if (!$path) {
        return null;
    }

    // Mendukung poster yang sudah menggunakan URL eksternal.
    if (Str::startsWith($path, ['http://', 'https://'])) {
        return $path;
    }

    // Mengambil gambar dari storage Laravel.
    return Storage::disk('public')->url($path);
}
}