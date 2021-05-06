<?php

namespace App\Providers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;

class RequirementsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Cache::rememberForever('requirements', function () {
            return $this->getRequirements();
        });
    }

    private function getRequirements()
    {
        $path = app_path("requirements.json");

        return collect(json_decode(file_get_contents($path), true));
    }
}
