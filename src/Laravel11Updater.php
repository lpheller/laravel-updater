<?php

namespace Heller\LaravelUpdater;

class Laravel11Updater extends BaseUpdater
{
    public $requiredPHPVersion = '8.2';

    public $updatedPackages = [
        'laravel/framework' => '^11.0',
    ];

    public $updatedDevPackages = [
        'nunomaduro/collision' => '^8.1',
    ];

    public $optionalPackageUpdates = [
        'laravel/sanctum' => '^4.0',
        'inertiajs/inertia-laravel' => '^1.0',
        'aw-studio/laravel-redirects' => '^0.7',
        'spatie/laravel-sitemap' => '^7.2',
    ];

    public $optionalDevPackageUpdates = [
        'pestphp/pest' => '^2.0',
        'pestphp/pest-plugin-laravel' => '^2.0',
        'laravel/breeze' => '^2.0',
    ];

    public $removedPackages = [];

    public $removedDevPackages = [];

    public function additionalTasks()
    {
        // ...
        // Publish Sanctum migrations if Sanctum is installed
        // https://laravel.com/docs/11.x/upgrade#updating-dependencies
        if (in_array('laravel/sanctum', array_keys($this->getComposerDependencies()))) {
            exec('php artisan vendor:publish --tag=sanctum-migrations');
        }
    }
}
