<?php

namespace Heller\LaravelUpdater;

class Laravel10Updater extends BaseUpdater
{
    public $requiredPHPVersion = '8.1';

    public $updatedPackages = [
        'php' => '^8.1',
        'laravel/framework' => '^10.0',

    ];

    public $updatedDevPackages = [
        'nunomaduro/collision' => '^7.0',
        'spatie/laravel-ignition' => '^2.0',
        'phpunit/phpunit' => '^10.0',
        'pestphp/pest' => '^2.0',
        'pestphp/pest-plugin-laravel' => '^2.0',
    ];

    public $optionalPackageUpdates = [
        'doctrine/dbal' => '^3.0',
        'laravel/ui' => '^4.0',
        'laravel/sanctum' => '^3.2',
        'inertiajs/inertia-laravel' => '^0.6.5',
        'aw-studio/laravel-redirects' => '^0.4',
        'larabug/larabug' => '^3.0',
        'barryvdh/laravel-dompdf' => '^2.0.1',
        'codebar-ag/laravel-prerender' => '^3.2',
    ];

    public $optionalDevPackageUpdates = [];

    public $removedPackages = [
        'fruitcake/laravel-cors',
    ];

    public $removedDevPackages = [
        'facade/ignition',
    ];

    public function additionalTasks()
    {
        // ..,
    }
}
