<?php

namespace Fengxin2017\Ding;

use Fengxin2017\Ding\Contracts\CoreContract;
use Illuminate\Support\ServiceProvider;

class DingServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('ding', function ($app, $params) {
            return new Ding($params);
        });

        $this->app->alias('ding', CoreContract::class);
    }

    public function boot()
    {
        if ($this->app->runningInConsole() && function_exists('config_path')) {
            $this->publishes([
                __DIR__.'/../config/ding.php' => config_path('ding.php'),
            ], 'config');
        }

        $this->mergeConfigFrom(__DIR__.'/../config/ding.php', 'ding');
    }
}
