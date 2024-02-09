<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
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

    public static function friendship($id): ?static
    {
        return static::where(
            fn(Builder $query) =>
            $query->where('user_id', auth()->user()->id)
                ->where('friend_id', $id)
        )
            ->orWhere(
                fn(Builder $query) =>
                $query->where('user_id', $id)
                    ->where('friend_id', auth()->user()->id)
            )
            ->first();
    }

    public static function friendships(): Collection
    {
        return static::whereNotNull('confirmed_at')
            ->where(
                fn(Builder $query) =>
                $query
                    ->where('user_id', auth()->user()->id)
                    ->orWhere('friend_id', auth()->user()->id)
            )->get();

    }


}
