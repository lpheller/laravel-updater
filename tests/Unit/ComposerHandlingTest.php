<?php

beforeEach(function () {
    copy(__DIR__.'/../fixtures/composer.json', __DIR__.'/../fixtures/composer.json.bak');
});

afterEach(function () {
    copy(__DIR__.'/../fixtures/composer.json.bak', __DIR__.'/../fixtures/composer.json');
    unlink(__DIR__.'/../fixtures/composer.json.bak');
});

test('It handles composer.json updates', function () {
    $updater = new ExampleUpdater;

    expect($updater->getComposerDependencies())->toBe([
        'laravel/framework' => '^8.0',
    ]);

    $updater->updateComposerPackages([
        'laravel/framework' => '^9.0',
    ]);

    expect($updater->getComposerDependencies())->toBe([
        'laravel/framework' => '^9.0',
    ]);
});

test('It handles composer.json dev updates', function () {
    $updater = new ExampleUpdater;

    expect($updater->getComposerDevDependencies())->toBe([
        'pestphp/pest' => '^1.0',
    ]);

    $updater->updateComposerPackages([
        'pestphp/pest' => '^2.0',
    ], true);

    expect($updater->getComposerDevDependencies())->toBe([
        'pestphp/pest' => '^2.0',
    ]);
});

test('It handles composer.json removals', function () {
    $updater = new ExampleUpdater;
    expect(array_keys($updater->getComposerDependencies()))->toContain('laravel/framework');

    $updater->removeComposerPackage([
        'laravel/framework',
    ]);

    expect($updater->getComposerDependencies())->toBe([]);
});
test('It handles composer.json dev removals', function () {
    $updater = new ExampleUpdater;
    expect(array_keys($updater->getComposerDevDependencies()))->toContain('pestphp/pest');

    $updater->removeComposerPackage([
        'pestphp/pest',
    ], true);

    expect($updater->getComposerDevDependencies())->toBe([]);
});

test('It handles optional composer.json updates', function () {
    $updater = new ExampleUpdater;
    //prepare composer.json
    $updater->requireComposerPackage('laravel/sanctum', '^2.0');

    $updater->updateOptionalCompsoerPackages([
        'laravel/sanctum' => '^3.0',
    ]);

    //expect bumped
    expect((object) $updater->getComposerDependencies())->toHaveProperties([
        'laravel/sanctum' => '^3.0',
    ]);
});

test('It doesnt require optional composer.json updates', function () {
    $updater = new ExampleUpdater;

    $updater->updateOptionalCompsoerPackages([
        'laravel/sanctum' => '^3.0',
    ]);

    //expect not required
    expect((object) $updater->getComposerDependencies())->not->toHaveProperties([
        'laravel/sanctum' => '^3.0',
    ]);
});

class ExampleUpdater
{
    use \Heller\LaravelUpdater\Concerns\HandlesComposerPackages{
        getComposerDependencies as public;
        getComposerDevDependencies as public;
    }

    public function __construct()
    {
        chdir(__DIR__.'/../fixtures');
    }

    public function getComposerJsonPath()
    {
        return __DIR__.'/../fixtures/composer.json';
    }
}
