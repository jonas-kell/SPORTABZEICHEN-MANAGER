<?php

namespace App\Http\Controllers;


use App\Http\Resources\AthleteResource;
use App\Models\Athlete;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavouritesController extends Controller
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
     * Get an array of the users current favourites
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getFavourites(Request $request)
    {
        if ($request->ajax()) {
            $user = Auth::user();
            if (!$user) {
                return response()->json(["error" => "no user"]);
            }

            $resulting_athletes = $user->favourites;

            if ($user->year != -1) {
                $resulting_athletes = $resulting_athletes->where("year", $user->year);
            }

            $resulting_athletes = $resulting_athletes->sortByDesc([
                ['gender', 'desc'],
                ['birthday', 'desc'],
            ]);

            $resulting_athletes = $resulting_athletes->take(100); //TODO (think about limit) limit to 100 results

            $resource_athletes = [];
            foreach ($resulting_athletes as $athlete) {
                $resource_athletes[] = new AthleteResource($athlete);
            }

            return response()->json(["athletes" => $resource_athletes]);
        } else {
            return redirect("/");
        }
    }

    /**
     * add the athlete to the users currently active favourites
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function addFavourite(Request $request, $athlete_id)
    {
        if ($request->ajax()) {
            $athlete = Athlete::find($athlete_id);

            if (!$athlete) {
                return response()->json(["error" => "athlete not found"]);
            }

            if (Auth::user()) {
                $user = Auth::user();
                $user->favourites()->attach($athlete);

                return $this->getFavourites($request);
            } else {
                return response()->json(["error" => "no user"]);
            }
        } else {
            return redirect("/");
        }
    }


    /**
     * remove the athlete from the users currently active favourites
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function dropFavourite(Request $request, $athlete_id)
    {
        if ($request->ajax()) {
            $athlete = Athlete::find($athlete_id);

            if (!$athlete) {
                return response()->json(["error" => "athlete not found"]);
            }

            if (Auth::user()) {
                $user = Auth::user();
                $user->favourites()->detach($athlete);

                return $this->getFavourites($request);
            } else {
                return response()->json(["error" => "no user"]);
            }
        } else {
            return redirect("/");
        }
    }
}
