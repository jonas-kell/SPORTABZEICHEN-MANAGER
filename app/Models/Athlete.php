<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Athlete extends Model
{
    use HasFactory;
    use SoftDeletes;


    /**
     * The users, that favouried this athlete
     */
    public function favourited_by()
    {
        return $this->belongsToMany(User::class, "favourites");
    }
}
