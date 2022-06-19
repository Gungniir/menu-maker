<?php

namespace App\Providers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Storage::disk('local')->buildTemporaryUrlsUsing(function ($path, $expiration, $options) {
            $subfolder = env('APP_SUBFOLDER');

            if ($subfolder) {
                $subfolder = Str::replace('/', '.', $subfolder);
                $subfolder .= '.';
            }

            return URL::temporarySignedRoute(
                $subfolder . 'local.temp',
                $expiration,
                array_merge($options, ['path' => $path])
            );
        });
    }
}
