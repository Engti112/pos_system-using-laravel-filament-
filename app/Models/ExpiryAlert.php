<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpiryAlert extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'stock_entry_id',
        'expiry_date',
        'alert_sent'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function stockEntry()
    {
        return $this->belongsTo(StockEntry::class);
    }
}
