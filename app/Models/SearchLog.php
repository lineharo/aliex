<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SearchLog extends Model
{
    use HasFactory;

    const UPDATED_AT = null;

    protected $fillable = [
        'ip',
        'user_agent',
        'query',
        'results_count',
        'referer',
    ];
}
