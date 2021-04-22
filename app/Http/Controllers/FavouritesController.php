<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
     * Get an array of favourites
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function get(Request $request)
    {
        if ($request->ajax()) {
            return response()->json(["asdasd" => true]);
        } else {
            return redirect("/home");
        }
    }
}
