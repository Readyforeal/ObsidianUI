<?php

namespace Obsidian\Tests;

use MallardDuck\LucideIcons\BladeLucideIconsServiceProvider;
use BladeUI\Icons\BladeIconsServiceProvider;
use Livewire\Blaze\BlazeServiceProvider;
use Livewire\LivewireServiceProvider;
use Obsidian\ObsidianServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            BladeIconsServiceProvider::class,
            BladeLucideIconsServiceProvider::class,
            LivewireServiceProvider::class,
            BlazeServiceProvider::class,
            ObsidianServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('app.key', 'base64:'.base64_encode(random_bytes(32)));
    }
}
