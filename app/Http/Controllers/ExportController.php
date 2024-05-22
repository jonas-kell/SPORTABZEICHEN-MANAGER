<?php

namespace App\Http\Controllers;

use App\Models\Athlete;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @return jsonResource
     */
    public function json(Request $request)
    {
        if ($request->ajax()) {
            $athleteIds = $request->input("athlete_ids");

            $athletes = Athlete::whereIn("id", $athleteIds)->get()->sortBy(function ($athlete, $key) use ($athleteIds) {
                return array_search($athlete->id, $athleteIds);
            });

            $out = [];

            foreach ($athletes as $athlete) {
                $scores = $athlete->getMedalScores();

                $out[] = [
                    "id" => $athlete->id,
                    "name" => $athlete->name,
                    "age" => $athlete->getSportabzeichenAgeAttribute(),
                    "scores" => $scores,
                    "total_points" => intval($scores["coordination"]["points"]) + intval($scores["strength"]["points"]) + intval($scores["endurance"]["points"]) + intval($scores["speed"]["points"]),
                    "finished" => $athlete->hasFinished(),
                ];
            }

            return json_encode($out);
        } else {
            return redirect("/");
        }
    }
}
