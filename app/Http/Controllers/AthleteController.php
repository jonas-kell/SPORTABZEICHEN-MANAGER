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
     * create a new athlete
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
}
