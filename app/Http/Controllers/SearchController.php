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

            $user_year = 0;
            if (Auth::user()) {
                $user_year = Auth::user()->year;
            } else {
                $all_years = User::getAllYearsArray();
                $user_year = end($all_years);
            }

            // TODO keep in mind, could be used later
            // where(function ($query) use ($user_year) {
            //     $query->where("year", $user_year)
            //         ->orWhereNull('year');
            // })->

            $resulting_athletes = Athlete::where(function ($query) use ($request_string) {
                $query->where("name", "like", "%" . $request_string . "%");
            })->take(10)->get(); //limit to 10 results //TODO order, so that current year comes first, than the other years.

            $resource_athletes = [];
            foreach ($resulting_athletes as $athlete) {
                $resource_athletes[] = new AthleteResource($athlete);
            }

            return response()->json(["athletes" => $resource_athletes]);
        }
        return redirect("/");
    }
}
