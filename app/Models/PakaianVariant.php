<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PakaianVariant extends Model
{
    use HasFactory;

    protected $fillable = ['pakaian_adat_id', 'size', 'quantity'];

    public function pakaianAdat(): BelongsTo
    {
        return $this->belongsTo(PakaianAdat::class);
    }
}
