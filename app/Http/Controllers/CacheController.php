<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class CacheController extends Controller
{
    private static string $seperator = "$";

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public static function storeAndGenerateKey($element)
    {
        if (!Auth::check()) {
            return $element;
        }

        $json = json_encode($element);

        $key = md5($json) . self::$seperator . Auth::user()->id;

        if (!Cache::has($key)) {
            Cache::put($key, $json, 60 * 60 * 24);
            return ["hash" => $key, "data" => $json]; // piggiback data and hash to save request
        } else {
            // very likely already cached on the users frontend. If not will be requested vie /cache/ route
            return $key;
        }
    }

    public function getCacheElements($uuid_array)
    {
        $array = [];

        foreach (json_decode($uuid_array) as $uuid) {
            $user_id = explode(self::$seperator, $uuid, 2)[1];

            if ($user_id != Auth::id()) {
                throw new Exception("Cache doesn't belong to user");
            }

            if (!Cache::has($uuid)) {
                throw new Exception("Cache not present");
            }

            $array[$uuid] = Cache::get($uuid);
        }

        return $array;
    }
}
