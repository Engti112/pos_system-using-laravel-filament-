<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_amount',
        'discount',
        'final_amount',
        'payment_method'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($sale) {
            // Ensure saleItems relationship is loaded
            if ($sale->relationLoaded('saleItems') || $sale->saleItems) {
                $totalAmount = $sale->saleItems->sum(fn($item) => $item->quantity * $item->selling_price);
                $sale->total_amount = $totalAmount;
                $sale->final_amount = $totalAmount - ($sale->discount ?? 0);
            } else {
                $sale->total_amount = 0;
                $sale->final_amount = 0;
            }
        });
    }
}
