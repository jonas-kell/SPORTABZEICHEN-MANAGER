<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    /**
     * Get the year, the user is currently set on
     * Get all available years for the dropdown generation
     *
     * @return array
     */
    public function getYearArray()
    {
        if (!$this->year) {
            $all_years = self::getAllYearsArray();
            $this->year = end($all_years);
            $this->save();
        }
        return ["current" => $this->year, "allYears" => self::getAllYearsArray()];
    }

    /**
     * Get all available years for the dropdown generation
     *
     * @return array
     */
    public static function getAllYearsArray()
    {
        return [-1, 2020, 2021, 2022]; //TODO make dynamic year array
    }

    /**
     * The athletes, the user has favorited
     */
    public function favourites()
    {
        return $this->belongsToMany(Athlete::class, "favourites");
    }
}
