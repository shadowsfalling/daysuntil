<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Category;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: "Countdown", description: "Countdown model")]
class Countdown extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'datetime', 'is_public', 'user_id', 'category_id'];

    /**
     * Get the user that owns the countdown.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the category associated with the countdown.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}