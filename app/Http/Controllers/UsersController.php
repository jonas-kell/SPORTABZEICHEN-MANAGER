<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
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
     * Get the year, the user is currently set on
     * Get all avaliable years for the dropdown generation
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getYears(Request $request)
    {
        if ($request->ajax()) {
            if (Auth::user()) {
                return response()->json(Auth::user()->getYearArray());
            } else {
                return response()->json(["error" => "no user"]);
            }
        }
        return redirect("/");
    }

    /**
     * Set the year, the user is currently set on
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function setYear(Request $request)
    {

        if ($request->ajax()) {
            $request->validate([
                "year" => "integer|required",
            ]);
            $year = $request->input("year");
            if (!in_array($year, User::getAllYearsArray())) {
                return response()->json(["error" => "malformatted year"]);
            }

            if (Auth::user()) {
                $user = Auth::user();
                $user->year = $year;
                $user->save();

                return $this->getYears($request);
            } else {
                return response()->json(["error" => "no user"]);
            }
        } else {
            return redirect("/");
        }
    }

    /**
     * Get an array of favourites
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getFavourites(Request $request)
    {
        if ($request->ajax()) {
            return response()->json(["asdasd" => true]);
        } else {
            return redirect("/");
        }
    }
}
