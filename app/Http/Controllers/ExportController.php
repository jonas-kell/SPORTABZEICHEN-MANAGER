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
                    "age" => $athlete->sportabzeichen_age,
                    "birth_year" => $athlete->birth_year,
                    "birth_month" => $athlete->birth_month,
                    "birth_day" => $athlete->birth_day,
                    "scores" => $scores,
                    "total_points" => intval($scores["coordination"]["points"]) + intval($scores["strength"]["points"]) + intval($scores["endurance"]["points"]) + intval($scores["speed"]["points"]),
                    "finished" => $athlete->hasFinished(),
                    "expect_data_present" => $athlete->expect_data_present,
                    "proof_of_swimming" => $athlete->proofOfSwimming,
                    "ident_no" => $athlete->lastYearIdentNo,
                ];
            }

            return json_encode($out);
        } else {
            return redirect("/");
        }
    }
}
