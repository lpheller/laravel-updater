<?php

namespace Heller\LaravelUpdater;

class Laravel12Updater extends BaseUpdater
{
    public $requiredPHPVersion = '8.2';

    public $updatedPackages = [
        'php' => '^8.2',
        'laravel/framework' => '^12.0',
    ];

    public $updatedDevPackages = [
        'phpunit/phpunit' => '^11.0',
    ];

    public $optionalPackageUpdates = [
        'diglactic/laravel-breadcrumbs' => '^10.0',
    ];

    public $optionalDevPackageUpdates = [
        'pestphp/pest' => '^3.0',
        'pestphp/pest-plugin-laravel' => '^3.0',
        'luvi-ui/laravel-luvi' => '^0.6'
    ];

    public $removedPackages = [];

    public $removedDevPackages = [
    ];

    public function additionalTasks()
    {
    }
}
