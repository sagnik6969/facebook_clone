<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Friend extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $casts = [
        'confirmed_at' => 'datetime',
        // tells laravel that confirmed at is a datetime so that we can
        // laravel can cast it into Carbon class => which allows us to use diffForHumans() function. 
    ];
}
