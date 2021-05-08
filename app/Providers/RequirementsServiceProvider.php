<?php

namespace App\Providers;

use ErrorException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;

class RequirementsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Cache::rememberForever('requirements', function () {
            $path = resource_path("requirements.json");

            return collect(json_decode(file_get_contents($path), true));
        });

        Cache::rememberForever('sportabzeichen_years', function () {
            //gets parsed from json, because it is copied from the py script for backwards compatibility
            $path = resource_path("years.json");

            return collect(json_decode(file_get_contents($path), true));
        });
    }

    //returns the correct year array from the cache 
    public static function getYearArray($age)
    {
        foreach (cache("sportabzeichen_years") as $year_array) {
            if ($age == $year_array["lower_year"] || ($age > $year_array["lower_year"] && $age <= $year_array["upper_year"])) {
                return $year_array;
            }
        }
        //default is the youngest audience, because the faulty ones are most likely those unter 6
        return cache("sportabzeichen_years")[0];
    }

    //returns the correct requirements array from the cache 
    public static function getRequirementsArray($gender, $age)
    {
        $year_array = self::getYearArray($age);

        if (!in_array($gender, ["male", "female"])) {
            throw new ErrorException("Gender undefined");
        } else {
            $requirement_array = cache("requirements")[$gender][$year_array["lower_year"]];

            return $requirement_array; //because of filter in getYearArray, this should always return a value
        }
    }
}
