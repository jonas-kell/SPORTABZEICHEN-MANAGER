<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;

class Athlete extends Model
{
    use HasFactory;
    use SoftDeletes;


    /**
     * The users, that favorited this athlete
     */
    public function favourited_by()
    {
        return $this->belongsToMany(User::class, "favourites");
    }


    /**
     * The genders, that can be set
     * @return array
     */
    public static function avaliableGenders()
    {
        return ["male", "female"];
    }

    /**
     * get the performances, the athlete has already registered to it's account
     * @return array
     */
    public function getCurrentPerformances()
    {
        //in order to avoid encoding of empty performances to array instead of object, we put the athlete id here to ensure correct parsing
        return json_decode($this->performances ?? "{\"coordination\": {},\"endurance\": {},\"speed\": {},\"strength\": {}}", true);
    }

    /**
     * get the performances, the athlete has already registered to it's account:
     * also get keys from the $additional_array initialized with empty fields
     * 
     * @param array $additional_array
     * 
     * @return array
     */
    public function getMergedPerformances($additional_array)
    {
        $template = [];
        $template["performance"] = "";

        $all_performances = $this->getCurrentPerformances();

        foreach ([
            'coordination',
            'endurance',
            'speed',
            'strength'
        ] as $category) {
            foreach ($additional_array[$category] as $key => $value) {
                if (!in_array($key, $all_performances[$category])) {
                    $all_performances[$category][$key] = $template;
                }
            }
        }

        return $all_performances;
    }


    /**
     * update the performances, the athlete has already registered to it's account
     * 
     * intelligently merges the existing and incoming array
     * 
     * @param json_string $updated_performances
     * 
     * @return boolean $success
     */
    public function mergePerformances($updated_performances)
    {
        $this->performances = $updated_performances;
        $this->save();

        return true;
    }
}
