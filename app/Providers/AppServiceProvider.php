<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        \Illuminate\Support\Facades\DB::listen(function ($query) {
            info('Query', [
                "Query" => $query->sql,
                "Bindings" => $query->bindings,
                "Time" => $query->time,
            ]);
        });
    }
}
