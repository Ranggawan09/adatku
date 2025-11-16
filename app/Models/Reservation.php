<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'pakaian_adat_id',
        'pakaian_variant_id',
        'order_id',
        'quantity',
        'start_date',
        'end_date',
        'days',
        'price_per_day',
        'total_price',
        'late_fee',
        'status',
        'payment_status',
        'snap_token',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

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
    public function testimonial()
    {
    return $this->hasOne(Testimonial::class);
    }

}
