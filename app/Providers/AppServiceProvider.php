<?php

namespace App\Providers;

use App\Observers\ReportObserver;
use App\Report;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        Report::observe(ReportObserver::class);

        Validator::extend('isBase64', function ($attribute, $value, $params, $validator) {
            $decodedValue = base64_decode($value);

            if ($decodedValue === '')
                $decodedValue = true;

            return $decodedValue;
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
