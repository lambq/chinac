<?php

namespace Lambq\Chinac;

use Illuminate\Support\ServiceProvider;

class ChinacServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/chinac.php','chinac');
        $this->registerPublishing();
    }

    public function register()
    {
        $this->app->singleton('Chinac',function (){
            return new Chinac();
        });
    }

    /**
     * @return array
     */
    public function provides()
    {
        return ['Chinac'];
    }

    /**
     * 资源发布注册.
     *
     * @return void
     */
    protected function registerPublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([__DIR__ . '/config/chinac.php' => config_path('chinac.php'), 'lambq-chinac-config']);
        }
    }
}