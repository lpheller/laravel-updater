<?php

namespace Heller\LaravelUpdater;

use Heller\LaravelUpdater\Concerns\HandlesComposerPackages;
use Heller\LaravelUpdater\Support\Console;

class BaseUpdater
{
    use HandlesComposerPackages;

    public $requiredPHPVersion = '';

    public $updateToLaravelVersion = '';

    public $updatedPackages = [];

    public $updatedDevPackages = [];

    public $optionalDevPackageUpdates = [];

    public $optionalPackageUpdates = [];

    public $removedPackages = [];

    public $removedDevPackages = [];

    public function run()
    {
        if ($this->requiredPHPVersion != '') {
            if (version_compare(PHP_VERSION, $this->requiredPHPVersion, '<')) {
                Console::warning('PHP version must be >= '.$this->requiredPHPVersion.' to support '.$this->updateToLaravelVersion.'. Please update PHP and try again.');

                return;
            }
        }

        $this->backupComposerJson();

        $this->updateComposerPackages($this->updatedPackages);
        $this->updateComposerPackages($this->updatedDevPackages, true);

        $this->removeComposerPackage($this->removedPackages);
        $this->removeComposerPackage($this->removedDevPackages, true);

        $this->updateOptionalCompsoerPackages($this->optionalPackageUpdates);
        $this->updateOptionalCompsoerPackages($this->optionalDevPackageUpdates, true);

        $result_code = $this->executeComposerUpdate();

        if ($result_code !== 0) {
            Console::error('Error occurred during composer update. Please check logs and try again.');

            $this->restoreComposerJson();

            return;
        }

        $this->additionalTasks();

        Console::success('Laravel update successful!');
    }

    protected function executeComposerUpdate()
    {
        exec('composer update -W', $output, $result_code);

        return $result_code;

    }

    protected function restoreComposerJson()
    {
        copy('composer.json.bak', 'composer.json');
        unlink('composer.json.bak');
    }

    protected function backupComposerJson()
    {
        copy('composer.json', 'composer.json.bak');
    }

    public function additionalTasks()
    {
        // Add any additional tasks here
    }
}
