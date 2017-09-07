<?php namespace Claymm\ArrowDB;

use Illuminate\Support\ServiceProvider;

class ArrowDBServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/arrowdb.php' => config_path('arrowdb.php'),
        ]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app['arrowdb'] = $this->app->share(function ($app) {
            return new ArrowDB;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array('arrowdb');
    }
}
