<?php

namespace Heller\LaravelUpdater;

use Heller\LaravelUpdater\Concerns\HandlesComposerPackages;
use Heller\LaravelUpdater\Support\Console;

class Updater
{
    use HandlesComposerPackages;

    public $useGit = false;

    public function getCurrentlyInstalledLaravelMajorVersion()
    {
        $laravelVersion = exec("composer show laravel/framework | grep versions |awk '{print $4}'");
        $laravelVersion = str_replace('v', '', $laravelVersion);

        return $laravelVersion;
    }

    public function run()
    {
        Console::info('Starting Laravel Update Script...');
        $currentLaravelVersion = $this->getCurrentlyInstalledLaravelMajorVersion();

        Console::log('  Currently running Laravel Version: '.red($currentLaravelVersion));
        Console::log('  Currently using PHP Version: '.blue(PHP_VERSION)."\n");

        if (version_compare($currentLaravelVersion, '9.0', '<')) {
            Console::log("Updating Laravel $currentLaravelVersion to 9");
            (new Laravel9Updater())->run();
        }

        if (version_compare($currentLaravelVersion, '10.0', '<') && version_compare($currentLaravelVersion, '9.0', '>=')) {
            Console::log("Updating Laravel $currentLaravelVersion to 10");
            (new Laravel10Updater())->run();
        }

        if (version_compare($currentLaravelVersion, '11.0', '<') && version_compare($currentLaravelVersion, '10.0', '>=')) {
            Console::log("Updating Laravel $currentLaravelVersion to 11");
            (new Laravel11Updater())->run();
        }

    }
}
