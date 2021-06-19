<?php

namespace App\Http\Controllers;

use App\Http\Resources\AthleteResource;
use App\Models\Athlete;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
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
     * Get an array of athletes, that match the search string
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getAthletes(Request $request, $search)
    {
        if ($request->ajax()) {

            $request_string = $search; //TODO validate search

            $user_year = -1;
            if (Auth::user()) {
                $user_year = Auth::user()->year;
            } else {
                $all_years = User::getAllYearsArray();
                $user_year = end($all_years);
            }

            $resulting_athletes = Athlete::where(function ($query) use ($request_string) {
                $query->where("name", "like", "%" . $request_string . "%");
            });

            if ($user_year != -1) {
                $resulting_athletes = $resulting_athletes->where("year", $user_year);
            }

            $resulting_athletes = $resulting_athletes->orderBy("gender", "desc")->orderBy("birthday", "desc");

            $resulting_athletes = $resulting_athletes->take(20)->get(); //limit to 20 results

            $resource_athletes = [];
            foreach ($resulting_athletes as $athlete) {
                $resource_athletes[] = new AthleteResource($athlete);
            }

            return response()->json(["athletes" => $resource_athletes]);
        }
        return redirect("/");
    }
}
