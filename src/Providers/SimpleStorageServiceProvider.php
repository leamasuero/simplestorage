<?php


namespace Lebenlabs\SimpleStorage\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use Lebenlabs\SimpleStorage\Facades\SimpleStorage;
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
        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        // Merge config of simple cms package
        $this->mergeConfigFrom(__DIR__.'/../config/simplestorage.php', 'simplestorage');

        // Register the service the package provides.
        $this->app->bind(SimpleStorageService::class, function() {
            return new SimpleStorage(
                app('em'),
                Storage::disk('archivos')
            );
        });
    }
}
