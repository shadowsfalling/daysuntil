<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Countdown;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'color', 'user_id'];

    /**
     * Get the user that owns the category.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the countdowns associated with the category.
     */
    public function countdowns()
    {
        return $this->hasMany(Countdown::class);
    }
}