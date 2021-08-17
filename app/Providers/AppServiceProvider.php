<?php

namespace App\Providers;

use App\Http\Controllers\Auth\GiceProvider;
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
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        if ($this->app->environment('production')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
        $socialite = $this->app->make('Laravel\Socialite\Contracts\Factory');
        $socialite->extend(
            'gice',
            function ($app) use ($socialite) {
                $config = $app['config']['services.gice'];
                return $socialite->buildProvider(GiceProvider::class, $config);
            }
        );
    }
}
