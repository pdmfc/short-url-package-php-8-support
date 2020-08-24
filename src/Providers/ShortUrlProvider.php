<?php

namespace Pdmfc\Shorturl\Providers;

use Illuminate\Support\ServiceProvider;

class ShortUrlProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/shorturl.php' =>  config_path('shorturl.php'),
        ], 'short_config');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->configure();
    }

    /**
     * Configure the service provider
     *
     * @return void
     */
    private function configure()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/shorturl.php', 'shorturl');
    }

}
