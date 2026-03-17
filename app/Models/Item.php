<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'category_id',
        'unit',
        'price',
        'stock',
        'minimum_stock',
        'image',
        'description',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
        'minimum_stock' => 'integer',
    ];

    /* =========================
     | RELATIONS
     ========================= */

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function stockIns()
    {
        return $this->hasMany(StockIn::class);
    }

    public function stockOuts()
    {
        return $this->hasMany(StockOut::class);
    }

    /* =========================
     | OPTIONAL HELPER
     ========================= */
    public function getStockStatusAttribute()
    {
        if ($this->stock == 0) {
            return 'Habis';
        }

        if ($this->stock <= $this->minimum_stock) {
            return 'Menipis';
        }

        return 'Aman';
    }
}
