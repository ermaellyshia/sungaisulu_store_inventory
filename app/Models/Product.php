<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\StockMovement;

class Product extends Model
{
    protected $fillable = [
        'product_name',
        'price',
        'category_id',
        'stock',
        'min_stock',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class, 'product_id');
    }
    
    public function transactionItems()
    {
        return $this->hasMany(TransactionItem::class);
    }
    
    public function isLowStock()
    {
        return $this->stock <= $this->min_stock;
    }
}