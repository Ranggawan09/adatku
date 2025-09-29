<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pakaianAdat()
    {
        return $this->belongsTo(PakaianAdat::class);
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(PakaianVariant::class, 'pakaian_variant_id');
        
    }
}
