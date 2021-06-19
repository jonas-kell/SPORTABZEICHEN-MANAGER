<?php

namespace App\Http\Resources;

use Auth;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Providers\RequirementsServiceProvider;

class AthleteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $array = [
            'id' => $this->id,
            'name' => $this->name,
            'year' => $this->year,
            'birthday' => $this->birthday,
            'gender' => $this->gender,
            'notes' => $this->notes,
        ];

        $sportabzeichen_age = $this->year - Carbon::parse($this->birthday)->year;
        $sportabzeichen_year_array = RequirementsServiceProvider::getYearArray($sportabzeichen_age);

        //requirements_tag -> short code for the requirements age region
        $array["requirements_tag"] = $sportabzeichen_year_array["tag"];

        //only calculate the following info, if the "requirements" attribute of the athlete is set
        //this saves computation time and bandwidth
        if ($this->requirements) {

            //calculate the year information
            $sportabzeichen_year_array["actual_sportabzeichen_age"] = $sportabzeichen_age;
            $array["sportabzeichen_year_array"] = $sportabzeichen_year_array;

            //get information on the fields, the user is supposed to have:
            $needed_requirements = RequirementsServiceProvider::getRequirementsArray($this->gender, $sportabzeichen_age);
            $array["needed_requirements"] = $needed_requirements;

            //get the performances, the athlete has already registered to it's account:
            //also get keys from the "needed_requirements" initialized with empty templates (saves extra checks/special initialization inside vue.js)
            $performances = $this->getMergedPerformances($needed_requirements);
            $array["performances"] = $performances;
        }


        // if there is a user authenticated, include user specific info
        // has the user favorited the athlete?
        $user = Auth::user();
        if ($user) {
            if (Auth::user()->favourites->find($this->id)) { //TODO make more efficient
                $array["favourite"] = true;
            } else {
                $array["favourite"] = false;
            }
        }

        return $array;
    }
}
