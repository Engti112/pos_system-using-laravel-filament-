<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LowStockAlert extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'threshold',
        'current_stock',
        'alert_sent'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}