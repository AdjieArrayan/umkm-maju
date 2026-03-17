<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockOut extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'quantity',
        'date',
        'description',
        'is_over_limit',
    ];

    protected $casts = [
        'date' => 'date',
        'quantity' => 'integer',
        'is_over_limit' => 'boolean',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
