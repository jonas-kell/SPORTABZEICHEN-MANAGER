<?php

namespace App\Http\Controllers;

use App\Http\Resources\AthleteResource;
use Illuminate\Http\Request;
use App\Models\Athlete;

class AthleteController extends Controller
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
     * get an athlete
     * 
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function get(Request $request, $id)
    {
        if ($request->ajax()) {
            $athlete = Athlete::find($id);


            if ($athlete) {
                //load athlete with requirements
                $athlete->requirements = true;
                return new AthleteResource($athlete);
            } else {
                return response()->json(["error" => "Athlete not found"]);
            }
        } else {
            return redirect("/");
        }
    }


    /**
     * create a new athlete
     * 
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create(Request $request)
    {
        if ($request->ajax()) {
            $request->validate([
                "name" => "string|required|min:3",
                "year" => "integer|required",
                "birthday" => "date|nullable",
                "gender" => "string",
                "proofOfSwimming" => "integer|nullable",
                "lastBadgeYear" => "integer|nullable",
                "numberOfBadgesSoFar" => "integer|nullable",
            ]);


            $athlete = new Athlete();
            $athlete->name = $request->input("name");
            $athlete->year = $request->input("year");

            if ($request->input("birthday")) {
                $athlete->birthday = $request->input("birthday");
            }

            if ($request->input("gender") && in_array($request->input("gender"), Athlete::avaliableGenders())) {
                $athlete->gender = $request->input("gender");
            }

            if ($request->input("proofOfSwimming")) {
                $athlete->proofOfSwimming = intval($request->input("proofOfSwimming"));
            }

            if ($request->input("lastBadgeYear")) {
                $athlete->lastBadgeYear = intval($request->input("lastBadgeYear"));
            }

            if ($request->input("numberOfBadgesSoFar")) {
                $athlete->numberOfBadgesSoFar = intval($request->input("numberOfBadgesSoFar"));
            } else {
                $athlete->numberOfBadgesSoFar = 0;
            }

            $athlete->save();

            if ($athlete) {
                //load athlete with requirements
                $athlete->requirements = true;
                return new AthleteResource($athlete);
            } else {
                return response()->json(["error" => "Athlete not found"]);
            }
        } else {
            return redirect("/");
        }
    }

    /**
     * update an athlete's base information
     * 
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function update(Request $request, $id)
    {
        if ($request->ajax()) {
            $request->validate([
                "name" => "string|min:3",
                "year" => "integer",
                "birthday" => "date|nullable",
                "gender" => "string",
                "proofOfSwimming" => "integer|nullable",
                "lastBadgeYear" => "integer|nullable",
                "numberOfBadgesSoFar" => "integer|nullable",
                "lastYearIdentNo" => "string|nullable",
                "newIdentNo" => "string|nullable",
            ]);


            $athlete = Athlete::find($id);

            if ($athlete) {
                if ($request->input("name")) {
                    $athlete->name = $request->input("name");
                }

                if ($request->input("year")) {
                    $athlete->year = $request->input("year");
                }

                if ($request->input("birthday")) {
                    $athlete->birthday = $request->input("birthday");
                }

                if ($request->input("gender") && in_array($request->input("gender"), Athlete::avaliableGenders())) {
                    $athlete->gender = $request->input("gender");
                }

                if ($request->has("proofOfSwimming")) {
                    $athlete->proofOfSwimming = $request->input("proofOfSwimming");
                }

                if ($request->has("lastBadgeYear")) {
                    $athlete->lastBadgeYear = $request->input("lastBadgeYear");
                }

                if ($request->has("numberOfBadgesSoFar")) {
                    if ($request->input("numberOfBadgesSoFar") && $request->input("numberOfBadgesSoFar") > 0) {
                        $athlete->numberOfBadgesSoFar = $request->input("numberOfBadgesSoFar");
                    } else {
                        $athlete->numberOfBadgesSoFar = 0;
                    }
                }

                if ($request->has("lastYearIdentNo")) {
                    $athlete->lastYearIdentNo = $request->input("lastYearIdentNo");
                }
                if ($request->has("newIdentNo")) {
                    $athlete->newIdentNo = $request->input("newIdentNo");
                }

                $athlete->save();

                //load athlete with requirements
                $athlete->requirements = true;
                return new AthleteResource($athlete);
            } else {
                return response()->json(["error" => "Athlete not found"]);
            }
        } else {
            return redirect("/");
        }
    }

    /**
     * update an athlete's notes
     * 
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function updateNotes(Request $request, $id)
    {
        if ($request->ajax()) {

            $athlete = Athlete::find($id);

            if ($athlete) {
                $athlete->notes = $request->input("notes");

                $athlete->save();

                //do not reload the athlete on actions that can be triggered rapidly by e.g. typing.
                //TODO maybe force reload, when update from other source has been encountered
                return response()->json(["success" => "Notes updated."]);
            } else {
                return response()->json(["error" => "Athlete not found"]);
            }
        } else {
            return redirect("/");
        }
    }

    /**
     * update an athlete's performances
     * 
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function updatePerformances(Request $request, $id)
    {
        if ($request->ajax()) {
            $request->validate([
                "performances" => "json|required",
            ]);

            $athlete = Athlete::find($id);

            if ($athlete) {
                if ($athlete->mergePerformances($request->input("performances"))) { // Merge function also STORES the data

                    //do not reload the athlete on actions that can be triggered rapidly by e.g. typing.
                    //TODO maybe force reload, when update from other source has been encountered
                    return response()->json(["success" => "Performances updated."]);
                }
            }
            return response()->json(["error" => "Athlete not found"]);
        } else {
            return redirect("/");
        }
    }

    /**
     * delete an athlete
     * 
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function delete(Request $request, $id)
    {
        if ($request->ajax()) {
            $athlete = Athlete::find($id);

            if ($athlete) {
                $athlete->delete();

                return response()->json(["success" => "Athlete deleted"]);
            } else {
                return response()->json(["error" => "Athlete not found"]);
            }
        } else {
            return redirect("/");
        }
    }

    /**
     * Get an array of the relevant athletes
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getRelevantAthletes(Request $request, $year, $requirements = false)
    {
        if ($request->ajax()) {
            $year = intval($year);

            $resulting_athletes = Athlete::query();

            if ($year != -1) {
                $resulting_athletes = $resulting_athletes->where("year", $year);
            }
            $resulting_athletes = $resulting_athletes->take(400)->get(); //TODO (think about limit) limit to 400 results

            $resulting_athletes = $resulting_athletes->sortByDesc([
                ['year', 'desc'],
                ['gender', 'desc'],
                ['birthday', 'desc'],
            ]);

            $resource_athletes = [];
            foreach ($resulting_athletes as $athlete) {
                if ($requirements) {
                    $athlete->requirements = true;
                }

                $resource_athletes[] = new AthleteResource($athlete);
            }

            return response()->json(["athletes" => $resource_athletes]);
        } else {
            return redirect("/");
        }
    }
}
