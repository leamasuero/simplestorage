<?php


namespace Lebenlabs\SimpleStorage\Providers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use Lebenlabs\SimpleStorage\Services\SimpleStorageService;

class SimpleStorageServiceProvider extends ServiceProvider
{

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {

        // Publish Migrations
        $this->publishes([__DIR__.'/Database/Migrations' => database_path('migrations')]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        // Merge config of simple cms package
        $this->mergeConfigFrom(__DIR__.'/../../config/simplestorage.php', 'simplestorage');

        // Register the service the package provides.
        $this->app->bind(SimpleStorageService::class, function() {
            return new SimpleStorageService(
                app('em'),
                Storage::disk('archivos')
            );
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['SimpleStorage'];
    }
}
