<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Athlete extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function getSportabzeichenAgeAttribute()
    {
        return $this->year - Carbon::parse($this->birthday)->year;
    }

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
        //if nothing is stored, initialize with empty categories
        return json_decode($this->performances ?? "{\"coordination\": {},\"endurance\": {},\"speed\": {},\"strength\": {}}", true);
    }

    /**
     * get the info, whether the athlete is done or is missing results
     * @return boolean
     */
    public function hasFinished()
    {

        $proofOfSwimmingOk = (($this->year - $this->proofOfSwimming ?? 0) < 5);

        if (!$proofOfSwimmingOk) {
            return false;
        }

        $scores = $this->getMedalScores();
        $total = 0;
        foreach ([
            'coordination',
            'endurance',
            'speed',
            'strength'
        ] as $category) {
            if ($scores[$category]["points"] <= 0) {
                return false;
            }
            $total += $scores[$category]["points"];
        }

        if ($total < 4) {
            return false; // shouldn't be needed
        }

        return true;
    }

    /**
     * get the medal score for each category
     * @return array
     */
    public function getMedalScores()
    {
        $performances = $this->getCurrentPerformances();

        $medals = [];

        foreach ([
            'coordination',
            'endurance',
            'speed',
            'strength'
        ] as $category) {
            $medals[$category] = [];
            $medals[$category]["value"] = "none";
            $medals[$category]["points"] = 0;
            $medals[$category]["discipline_name"] = "";
            $medals[$category]["performance"] = "";

            foreach ($performances[$category] as $discipline_name => $discipline) {
                if ($medals[$category]["value"] == "none" && ($discipline["bronze_highlighted"] ?? false)) {
                    $medals[$category]["value"] = "bronze";
                    $medals[$category]["points"] = 1;
                    $medals[$category]["discipline_name"] = $discipline_name;
                    $medals[$category]["performance"] = $discipline["performance"];
                }
                if (($medals[$category]["value"] == "none" || $medals[$category]["value"] == "bronze") && ($discipline["silver_highlighted"] ?? false)) {
                    $medals[$category]["value"] = "silver";
                    $medals[$category]["points"] = 2;
                    $medals[$category]["discipline_name"] = $discipline_name;
                    $medals[$category]["performance"] = $discipline["performance"];
                }
                if (($medals[$category]["value"] == "none" || $medals[$category]["value"] == "bronze" || $medals[$category]["value"] == "silver") && ($discipline["gold_highlighted"] ?? false)) {
                    $medals[$category]["value"] = "gold";
                    $medals[$category]["points"] = 3;
                    $medals[$category]["discipline_name"] = $discipline_name;
                    $medals[$category]["performance"] = $discipline["performance"];
                }
            }
        }

        return $medals;
    }

    /**
     * get the performances, the athlete has already registered to it's account:
     * also get keys from the $additional_array initialized with empty templates
     * 
     * @param array $additional_array
     * 
     * @return array
     */
    public function getMergedPerformances($additional_array)
    {
        $template = [];
        $template["performance"] = ""; // TODO adapt, if new parameters get added in addition to "performance"

        $all_performances = $this->getCurrentPerformances();

        foreach ([
            'coordination',
            'endurance',
            'speed',
            'strength'
        ] as $category) {
            if (!array_key_exists($category, $all_performances)) {
                $all_performances[$category] = []; // always add categories, should they be missing.
            }
            if (array_key_exists($category, $additional_array)) {
                foreach ($additional_array[$category] as $key => $value) {
                    if (!array_key_exists($key, $all_performances[$category])) {
                        $all_performances[$category][$key] = $template;  //TODO parse with checks up until lowest level
                    }
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
     * @param string (json) $updated_performances
     * 
     * @return boolean $success
     */
    public function mergePerformances($updated_performances)
    {
        $updated_performances = json_decode($updated_performances, true);
        $current_performances = $this->getCurrentPerformances();

        foreach ([
            'coordination',
            'endurance',
            'speed',
            'strength'
        ] as $category) {
            if (!array_key_exists($category, $current_performances)) {
                $current_performances[$category] = []; // always add categories, should they be missing.
            }
            if (array_key_exists($category, $updated_performances)) {
                foreach ($updated_performances[$category] as $key => $value) { //TODO parse with checks up until lowest level
                    $current_performances[$category][$key] = $updated_performances[$category][$key]; // TODO adapt, if new parameters get added in addition to "performance"
                }
            }
        }

        $this->performances = json_encode($current_performances);
        $this->save();

        return true;
    }
}
