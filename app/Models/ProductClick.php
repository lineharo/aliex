<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductClick extends Model
{
    use HasFactory;

    public $timestamps = false;

    const AFF_LINK = 'https://dhwnh.com/g/vv3q4oey1vd18fa14721b6d1781017/?erid=2bL9aMPo2e49hMef4pdzo6JkYp';

    public function __construct()
    {
        $this->transition_at = now();
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
