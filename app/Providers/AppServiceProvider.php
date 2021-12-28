<?php

namespace App\Providers;

use App\Http\Controllers\Auth\GiceProvider;
use Illuminate\Support\ServiceProvider;
use Protoqol\Prequel\PrequelServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment('local', 'develop', 'staging', 'production')) {
            //$this->app->register(\Cuonggt\Dibi\DibiServiceProvider::class);
            //$this->app->register(DibiServiceProvider::class);
            //$this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            //$this->app->register(TelescopeServiceProvider::class);
            // $this->app->register(\protoqol\prequel\PrequelServiceProvider::class);
            $this->app->register(PrequelServiceProvider::class);
            $this->app->register(\Laravel\Horizon\HorizonServiceProvider::class);
            $this->app->register(HorizonServiceProvider::class);
        }
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
