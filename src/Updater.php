<?php

require_once __DIR__.'/Support/Console.php';
require_once __DIR__.'/Concerns/HandlesComposerPackages.php';

class Updater
{
    use HandlesCompoerPackages;

    public $useGit = false;

    public function getCurrentlyInstalledLaravelMajorVersion(){
        $laravelVersion = exec("composer show laravel/framework | grep versions |awk '{print $4}'");
        $laravelVersion = str_replace('v', '', $laravelVersion);

        return $laravelVersion;
    }

    public function run()
    {
        Console::info("Starting Laravel Update Script...");
        $currentLaravelVersion = $this->getCurrentlyInstalledLaravelMajorVersion();
        
        Console::log("  Currently running Laravel Version: ". red($currentLaravelVersion) );
        Console::log("  Currently using PHP Version: ". blue(PHP_VERSION) ."\n");

        if(version_compare($currentLaravelVersion, '9.0', '<')) {
            Console::log("Updating Laravel $currentLaravelVersion to 9");
            $this->updateToLaravel9();
        }

        if(version_compare($currentLaravelVersion, '10.0', '<') && version_compare($currentLaravelVersion, '9.0', '>=')) {
            Console::log("Updating Laravel $currentLaravelVersion to 10");
            $this->updateToLaravel10();
        }


        if(version_compare($currentLaravelVersion, '11.0', '<') && version_compare($currentLaravelVersion, '10.0', '>=')) {
            Console::log("Updating Laravel $currentLaravelVersion to 11");
            $this->updateToLaravel11();
        }

    }

    public function updateToLaravel9(){
         if(version_compare(PHP_VERSION, '8.0.2', '<')) {
            Console::warning("PHP version must be >= 8.0.2 to support Laravel 9. Please update PHP and try again.");
            return;
        }

        $updatePackages = [
            "laravel/framework" => "^9.0",
        ];

        $updateDevPackages = [
            "spatie/laravel-ignition" => "^1.0",
            "nunomaduro/collision" => "^6.1"
        ];

        $removals = [
            'fruitcake/laravel-cors',
            'fideloper/proxy'
        ];

        $devRemovals = [
            'facade/ignition'
        ];

        if($this->useGit){
            exec("git checkout -b update/laravel-9");
        }

        $this->updateComposerPackages($updatePackages);
        $this->updateComposerPackages($updateDevPackages, true);

        $this->removeComposerPackage($removals);
        $this->removeComposerPackage($devRemovals, true);

        $optionalUpdates = [
            // "litstack/pages" => "*",
            // "litstack/meta" => "*"
        ];

        $this->updateOptionalCompsoerPackages($optionalUpdates);

        exec("composer update -W", $output, $result_code);
        
        if($this->useGit) {
            exec("git add .");
            exec("git commit -m 'Update to Laravel 9'");
        }
      

        if($result_code !== 0) {
            Console::error("Error occurred during composer update. Please check logs and try again.");

            if($this->useGit){
                exec("git restore composer.json");
                exec("git checkout - "); // back to previous branch
                exec("git branch -D update/laravel-9");
            }
            return;
        }

        Console::success("Laravel 9 updated successful!");

        // https://laravel.com/docs/9.x/upgrade#the-assert-deleted-methody
        $trustProxyMiddleware = file_get_contents('app/Http/Middleware/TrustProxies.php');
        if(str_contains($trustProxyMiddleware, 'use Fideloper\Proxy\TrustProxies as Middleware;')) {
            $trustProxyMiddleware = str_replace('use Fideloper\Proxy\TrustProxies as Middleware;', 'use Illuminate\Http\Middleware\TrustProxies as Middleware;', $trustProxyMiddleware);
            file_put_contents('app/Http/Middleware/TrustProxies.php', $trustProxyMiddleware);
        }

        //https://github.com/fruitcake/laravel-cors?tab=readme-ov-file#note-for-users-upgrading-to-laravel-9-10-or-higher
        $httpKernel = file_get_contents('app/Http/Kernel.php');
        if(str_contains($httpKernel, '\Fruitcake\Cors\HandleCors::class')) {
            $httpKernel = str_replace('\Fruitcake\Cors\HandleCors::class', '\Illuminate\Http\Middleware\HandleCors::class', $httpKernel);
            file_put_contents('app/Http/Kernel.php', $httpKernel);
        }
    }

    public function updateToLaravel10(){
        if(version_compare(PHP_VERSION, '8.1', '<')) {
            Console::warning("PHP version must be >= 8.1 to support Laravel 10. Please update PHP and try again.");
            return;
        }

        $removals = ['fruitcake/laravel-cors'];

        $devUpdates = [
            'nunomaduro/collision' => '^7.0',
            'spatie/laravel-ignition' => '^2.0',
            'phpunit/phpunit' => '^10.0'
        ];

        $packageUpdates = [
            "laravel/framework" => "^10.0",
        ];

        $optionalUpdates = [
            "laravel/sanctum" => "^3.0",
        ];

        if($this->useGit){
            exec("git checkout -b update/laravel-10", $output, $result_code);
        }

        $this->updateComposerPackages($packageUpdates);
        $this->updateComposerPackages($devUpdates, true);
        $this->removeComposerPackage($removals);
        $this->updateOptionalCompsoerPackages($optionalUpdates);


        exec("composer update -W", $output, $result_code);

        if($this->useGit){
            exec("git add .");
            exec("git commit -m 'Update to Laravel 10'");
        }

    
        if($result_code !== 0) {
            Console::error("Error occurred during composer update. Please check logs and try again.");

            if($this->useGit){
                exec("git restore composer.json");
                exec("git checkout - "); // back to previous branch
                exec("git branch -D update/laravel-10");
            }

            return;
        }
    }

    public function updateToLaravel11(){
        if(version_compare(PHP_VERSION, '8.2', '<')) {
            Console::warning("PHP version must be >= 8.2 to support Laravel 11. Please update PHP and try again.");
            return;
        }

        $packageUpdates = [
            "laravel/framework" => "^11.0",
        ];

        $devPackageUpdates = [
            "nunomaduro/collision" => "^8.1",
        ];

        $optionalUpdates = [
            "laravel/sanctum" => "^4.0",
            "inertiajs/inertia-laravel" => "^1.0",
            "laravel/breeze" => "^2.0",
        ];

        
        if($this->useGit){
            exec("git checkout -b update/laravel-11", $output, $result_code);
        }

        $this->updateComposerPackages($packageUpdates);
        $this->updateComposerPackages($devPackageUpdates, true);

        $this->updateOptionalCompsoerPackages($optionalUpdates);


        exec("composer update -W", $output, $result_code);

        if($this->useGit){
            exec("git add .");
            exec("git commit -m 'Update to Laravel 11'");
        }
    
        if($result_code !== 0) {
            Console::error("Error occurred during composer update. Please check logs and try again.");

            if($this->useGit){
                exec("git restore composer.json");
                exec("git checkout - "); // back to previous branch
                exec("git branch -D update/laravel-11");
            }

            return;
        }

        // Publish Sanctum migrations if Sanctum is installed
        // https://laravel.com/docs/11.x/upgrade#updating-dependencies
        if(in_array('laravel/sanctum', array_keys($this->getComposerDependencies()))){
            exec("php artisan vendor:publish --tag=sanctum-migrations");
        }
    }


}