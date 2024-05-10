<?php

trait HandlesCompoerPackages {

    public function updateComposerPackages($packages = [], $dev = false){
        $flag = $dev ? '--dev' : '';

        foreach($packages as $package => $version){
            Console::log("Updating $package to $version");
            exec("composer require $package:$version $flag --no-update");
        }
    }

    public function updateOptionalCompsoerPackages($packages = [], $dev = false){
        $flag = $dev ? '--dev' : '';
        $dependencies = $dev ? $this->getComposerDevDependencies() : $this->getComposerDependencies();

        foreach($packages as $package => $version){
            if(array_key_exists($package, $dependencies)) {
                Console::log("Updating $package to $version");
                exec("composer require $package:$version $flag --no-update");
            }
        }
    }

    public function removeComposerPackage($removedPackages = [], $dev = false) {
        $dependencies = $dev ? $this->getComposerDevDependencies() : $this->getComposerDependencies();

        $flag = $dev ? '--dev' : '';
        foreach($removedPackages as $removal){
            if(array_key_exists($removal, $dependencies)) {
                Console::log("Removing $removal package");
                exec("composer remove $removal $flag --no-update");
            } else
            {
                Console::log("Package $removal is not currently installed. Skipping removal." );
            }
        }
    }

    protected function getComposerDevDependencies(){
        $composerJson = json_decode(file_get_contents('composer.json'), true);
        return $composerJson['require-dev'];
    }

    protected function getComposerDependencies(){
        $composerJson = json_decode(file_get_contents('composer.json'), true);
        return $composerJson['require'];
    }
}