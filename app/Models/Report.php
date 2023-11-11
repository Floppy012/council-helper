<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    public $casts = [
        'raw' => 'json',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public $hidden = [
        'id',
    ];
}
