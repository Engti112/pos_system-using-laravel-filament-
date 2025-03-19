<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\LowStockAlert;
use App\Models\ExpiryAlert;

class StockEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'quantity',
        'purchase_price',
        'selling_price',
        'expiry_date',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saved(function ($stockEntry) {
            $currentStock = StockEntry::where('product_id', $stockEntry->product_id)->sum('quantity');
            
            // Get threshold from Product model if available, otherwise use default
            $threshold = $stockEntry->product->low_stock_threshold ?? 10;

            // Update Low Stock Alert
            LowStockAlert::updateOrCreate(
                ['product_id' => $stockEntry->product_id],
                [
                    'current_stock' => $currentStock,
                    'threshold' => $threshold,
                    'alert_sent' => $currentStock <= $threshold,
                ]
            );

            // Check for Expiry Alert
            if ($stockEntry->expiry_date) {
                ExpiryAlert::updateOrCreate(
                    [
                        'product_id' => $stockEntry->product_id,
                        'stock_entry_id' => $stockEntry->id,
                    ],
                    [
                        'expiry_date' => $stockEntry->expiry_date,
                        'alert_sent' => false,
                    ]
                );
            }
        });
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
