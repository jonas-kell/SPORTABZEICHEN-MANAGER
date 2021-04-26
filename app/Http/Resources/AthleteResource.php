<?php

namespace App\Http\Resources;

use Auth;
use Illuminate\Http\Resources\Json\JsonResource;

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
        ];

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
