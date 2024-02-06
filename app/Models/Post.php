<?php

namespace App\Models;

use App\Models\Scopes\ReverseScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    use HasFactory;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected $fillable = ['body'];

    protected static function booted()
    {
        static::addGlobalScope(ReverseScope::class);
        // remember booted function is static. 
        // the above code sorts the posts by descending order of id by default.  
    }
}
