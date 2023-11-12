<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Raid extends Model
{
    public $timestamps = false;

    protected $hidden = [
        'id',
    ];

    public function encounters(): HasMany
    {
        return $this->hasMany(Encounter::class)->orderBy('order');
    }
}
