<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PakaianAdat extends Model
{
    use HasFactory;

    // ... properti lainnya ...

    public function variants(): HasMany
    {
        return $this->hasMany(PakaianVariant::class);
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * Get the total quantity from all variants.
     */
    public function getTotalQuantityAttribute(): int
    {
        return $this->variants()->sum('quantity');
    }
}
