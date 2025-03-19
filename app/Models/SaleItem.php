<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_id',
        'stock_entry_id',
        'quantity',
        'price', // Ensure this is included
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function stockEntry()
    {
        return $this->belongsTo(StockEntry::class);
    }
    


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($saleItem) {
            if (!$saleItem->price) {
                $saleItem->price = optional($saleItem->stockEntry)->selling_price ?? 0;
            }
        });
    }
}
