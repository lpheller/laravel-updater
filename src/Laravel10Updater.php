<?php

namespace Heller\LaravelUpdater;

class Laravel10Updater extends BaseUpdater
{
    public $requiredPHPVersion = '8.1';

    public $updatedPackages = [
        'laravel/framework' => '^10.0',
    ];

    public $updatedDevPackages = [
        'nunomaduro/collision' => '^7.0',
        'spatie/laravel-ignition' => '^2.0',
        'phpunit/phpunit' => '^10.0',
    ];

    public $optionalPackageUpdates = [
        'laravel/sanctum' => '^3.0',
        'inertiajs/inertia-laravel' => '^0.6.5',
        'aw-studio/laravel-redirects' => '^0.4',

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
