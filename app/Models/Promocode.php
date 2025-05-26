<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promocode extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'admitad_id',
        'description',
        'name',
        'offer_amount',
        'offer_currency',
        'store_name',
        'date_from',
        'date_to',
        'url'
    ];

    protected $casts = [
        'date_from' => 'datetime:d.m.Y',
        'date_to' => 'datetime:d.m.Y',
    ];

}
