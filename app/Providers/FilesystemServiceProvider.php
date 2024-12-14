<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Filesystem\Factory;

class FilesystemServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('files', function () {
            return new \Illuminate\Filesystem\Filesystem;
        });

        $this->app->singleton(Factory::class, function ($app) {
            return new \Illuminate\Filesystem\FilesystemManager($app);
        });
    }
}