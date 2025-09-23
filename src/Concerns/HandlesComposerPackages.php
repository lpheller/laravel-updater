<?php

namespace Heller\LaravelUpdater\Concerns;

use Heller\LaravelUpdater\Support\Console;

trait HandlesComposerPackages
{
    public function requireComposerPackage($package, $version, $dev = false)
    {
        $flag = $dev ? '--dev' : '';
        Console::log("Requiring $package:$version");
        exec("composer require $package:$version $flag --no-update > /dev/null 2>&1");
    }

    public function updateComposerPackages($packages = [], $dev = false)
    {
        $flag = $dev ? '--dev' : '';

        foreach ($packages as $package => $version) {
            Console::log("Updating $package to $version");
            exec("composer require $package:$version $flag --no-update > /dev/null 2>&1");
        }
    }

    public function updateOptionalCompsoerPackages($packages = [], $dev = false)
    {
        $flag = $dev ? '--dev' : '';
        $dependencies = $dev ? $this->getComposerDevDependencies() : $this->getComposerDependencies();

        foreach ($packages as $package => $version) {
            if (! array_key_exists($package, $dependencies)) {
                continue;
            }
            Console::log("Updating $package to $version");
            exec("composer require $package:$version $flag --no-update > /dev/null 2>&1");

        }
    }

    public function removeComposerPackage($removedPackages = [], $dev = false)
    {
        $dependencies = $dev ? $this->getComposerDevDependencies() : $this->getComposerDependencies();

        $flag = $dev ? '--dev' : '';
        foreach ($removedPackages as $removal) {
            if (array_key_exists($removal, $dependencies)) {
                Console::log("Removing $removal package");
                exec("composer remove $removal $flag --no-update > /dev/null 2>&1");
            } else {
                Console::log("Package $removal is not currently installed. Skipping removal.");
            }
        }
    }

    protected function getComposerDevDependencies()
    {
        $composerJson = json_decode(file_get_contents($this->getComposerJsonPath()), true);

        return $composerJson['require-dev'] ?? [];
    }

    protected function getComposerDependencies()
    {
        $composerJson = json_decode(file_get_contents($this->getComposerJsonPath()), true);

        return $composerJson['require'] ?? [];
    }

    protected function getComposerJsonPath()
    {
        return 'composer.json';
    }
}
