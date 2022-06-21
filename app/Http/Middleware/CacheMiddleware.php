<?php

namespace App\Http\Middleware;

use App\Http\Controllers\CacheController;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CacheMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if ($response instanceof JsonResponse) {
            $data = $response->getData(assoc: true);

            if (key_exists("athletes", $data)) {
                foreach ($data["athletes"] as &$athlete) {
                    if (key_exists("needed_requirements", $athlete)) {
                        $athlete["needed_requirements"] = CacheController::storeAndGenerateKey($athlete["needed_requirements"]);
                    }
                    $athlete = CacheController::storeAndGenerateKey($athlete);
                }
            }
            if (key_exists("data", $data)) {
                if (key_exists("needed_requirements", $data["data"])) {
                    $data["data"]["needed_requirements"] = CacheController::storeAndGenerateKey($data["data"]["needed_requirements"]);
                }
            }

            $response->setData($data);
            $response->headers->add(["chached-object-needs-parsing" => "true"]);
        }

        return $response;
    }
}
