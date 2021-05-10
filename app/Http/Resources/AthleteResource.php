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

        //only calculate the following info, if the "requirements" attribute of the athlete is set
        //this saves computation time and bandwidth
        if ($this->requirements) {

            //calculate the year information
            $sportabzeichen_age = $this->year - Carbon::parse($this->birthday)->year;
            $sportabzeichen_year_array = RequirementsServiceProvider::getYearArray($sportabzeichen_age);
            $sportabzeichen_year_array["actual_sportabzeichen_age"] = $sportabzeichen_age;
            $array["sportabzeichen_year_array"] = $sportabzeichen_year_array;

            //get information on the fields, the user is supposed to have:
            $needed_requirements = RequirementsServiceProvider::getRequirementsArray($this->gender, $sportabzeichen_age);
            $array["needed_requirements"] = $needed_requirements;

            //get the performances, the athlete has already registered to it's account:
            $performances = $this->gender;
            $array["performances"] = $performances;
        }


        // if there is a user authenticated, include user specific info
        // has the user favourited the athlete?
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
