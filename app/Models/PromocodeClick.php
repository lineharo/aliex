<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PromocodeClick extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function __construct()
    {
        $this->transition_at = now();
    }

    public function promocode(): BelongsTo
    {
        return $this->belongsTo(Promocode::class);
    }
}
