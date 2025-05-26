<?php

namespace App\Models;

use App\Traits\UrlMapTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;
    use UrlMapTrait;

    protected $casts = [
        'published_at' => 'datetime',
    ];
}
