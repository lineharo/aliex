<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductClick extends Model
{
    use HasFactory;

    public $timestamps = false;

    const AFF_LINK = 'https://dhwnh.com/g/vv3q4oey1v3bdea9edefb6d1781017/?erid=LatgBbQo6';

    public function __construct()
    {
        $this->transition_at = now();
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
