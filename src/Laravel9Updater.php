<?php

namespace Heller\LaravelUpdater;

use Heller\LaravelUpdater\Support\Console;

class Laravel9Updater extends BaseUpdater
{
    public $requiredPHPVersion = '8.0.2';

    public $updatedPackages = [
        'laravel/framework' => '^9.0',
    ];

    public $updatedDevPackages = [
        'spatie/laravel-ignition' => '^1.0',
        'nunomaduro/collision' => '^6.1',
    ];

    public $optionalPackageUpdates = [
        'inertiajs/inertia-laravel' => '^0.5',
        'aw-studio/laravel-redirects' => '^0.2',
        'litstack/meta' => '^2.0',
        'litstack/litstack' => '^3.8',
    ];

    public $optionalDevPackageUpdates = [];

    public $removedPackages = [
        'fruitcake/laravel-cors',
        'fideloper/proxy',
    ];

    public $removedDevPackages = [
        'facade/ignition',
    ];

    public function additionalTasks()
    {
        // https://laravel.com/docs/9.x/upgrade#the-assert-deleted-methody
        $trustProxyMiddleware = file_get_contents('app/Http/Middleware/TrustProxies.php');
        if (str_contains($trustProxyMiddleware, 'use Fideloper\Proxy\TrustProxies as Middleware;')) {
            $trustProxyMiddlewareModified = str_replace('use Fideloper\Proxy\TrustProxies as Middleware;', 'use Illuminate\Http\Middleware\TrustProxies as Middleware;', $trustProxyMiddleware);

            // remove deprecated headers property (https://laravel.com/docs/9.x/upgrade#the-assert-deleted-method)
            $remove = "/**
     * The headers that should be used to detect proxies.
     *
     * @var int
     */
    protected \$headers = Request::HEADER_X_FORWARDED_ALL;";

            $trustProxyMiddlewareModified = str_replace($remove, '', $trustProxyMiddlewareModified);


            file_put_contents('app/Http/Middleware/TrustProxies.php', $trustProxyMiddlewareModified);
            Console::log('Updated app/Http/Middleware/TrustProxies.php to use Illuminate\Http\Middleware\TrustProxies as Middleware');
        }

        //https://github.com/fruitcake/laravel-cors?tab=readme-ov-file#note-for-users-upgrading-to-laravel-9-10-or-higher
        $httpKernel = file_get_contents('app/Http/Kernel.php');
        if (str_contains($httpKernel, '\Fruitcake\Cors\HandleCors::class')) {
            $httpKernelModified = str_replace('\Fruitcake\Cors\HandleCors::class', '\Illuminate\Http\Middleware\HandleCors::class', $httpKernel);


            file_put_contents('app/Http/Kernel.php', $httpKernelModified);
            Console::log('Updated app/Http/Kernel.php to use Illuminate\Http\Middleware\HandleCors::class');
        }
    }
}
