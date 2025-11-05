<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PakaianAdat extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama', 'jenis', 'asal', 'deskripsi', 'warna',
        'price_per_day', 'status', 'reduce', 'image'
    ];

    /**
     * Get the full URL for the pakaian adat image.
     *
     * @return string
     */
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return Storage::url($this->image);
        }
        // Return a default image if no image is set
        return Storage::url('default-image.png'); // Ganti dengan path gambar default Anda
    }

    public function variants()
    {
        return $this->hasMany(PakaianVariant::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function getTotalQuantityAttribute()
    {
        return $this->variants()->sum('quantity');
    }
}