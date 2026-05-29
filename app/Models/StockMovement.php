<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StockMovement extends Model
{
    use HasFactory;

    protected $table = 'stock_movements';

    protected $fillable = [
        'product_id',
        'user_id',
        'type',       // in / out
        'quantity'
    ];

    // relationship: each stock movement belongs to a product
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    // relationship: each stock movement belongs to a user (admin/employee)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}