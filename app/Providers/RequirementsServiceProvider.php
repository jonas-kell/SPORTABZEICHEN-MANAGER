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
        Cache::rememberForever('requirements-before-2024', function () {
            $path = resource_path("requirements-before-2024.json");

            return collect(json_decode(file_get_contents($path), true));
        });

        Cache::rememberForever('requirements-since-2024', function () {
            $path = resource_path("requirements-since-2024.json");

            return collect(json_decode(file_get_contents($path), true));
        });

        Cache::rememberForever('sportabzeichen_years', function () {
            //gets parsed from json, because it is copied from the py script for backwards compatibility
            $path = resource_path("years.json");

            return collect(json_decode(file_get_contents($path), true));
        });


        Cache::rememberForever('name_number_map', function () {
            $path = resource_path("name_number_map.json");

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
        //default is the youngest audience, because the faulty ones are most likely those under 6
        return cache("sportabzeichen_years")[0];
    }

    //returns the correct requirements array from the cache 
    public static function getRequirementsArray($gender, $age, $year)
    {
        $requirement_resource = null;
        if ($year < 2024) {
            $requirement_resource = cache("requirements-before-2024");
        } else {
            $requirement_resource = cache("requirements-since-2024");
        }

        $year_array = self::getYearArray($age);

        if (!in_array($gender, ["male", "female"])) {
            throw new ErrorException("Gender undefined");
        } else {
            $requirement_array = $requirement_resource[$gender][$year_array["lower_year"]];

            return $requirement_array; //because of filter in getYearArray, this should always return a value
        }
    }

    //translates a name into a printout number
    public static function formatPerformance($category, $discipline_name, $performance, $points)
    {
        if ($discipline_name == "") {
            return ["number" => "", "performance" => $performance];
        } else {
            if (array_key_exists("FULL: " . $discipline_name, cache("name_number_map")[$category])) {
                // Geräteturnen sub-Übung
                $char = $points == 1 ? "B" : ($points == 2 ? "S" : ($points == 3 ? "G" : "_"));
                return ["number" => cache("name_number_map")[$category][$discipline_name], "performance" => cache("name_number_map")[$category]["FULL: " . $discipline_name] . "." . $char];
            } else {
                return ["number" => cache("name_number_map")[$category][$discipline_name], "performance" => $performance];
            }
        }
    }
}
