<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Model
{
    use HasFactory;

    protected $table = 'users';

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'author_id');
    }

    public function votes(): hasMany
    {
        return $this->hasMany(Vote::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function($user) {
            $user->votes()->delete();
            $user->posts()->delete();
        });
    }
}