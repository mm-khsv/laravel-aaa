<?php

namespace dnj\AAA\Tests;

use dnj\AAA\Contracts\ITypeManager;
use dnj\AAA\Contracts\IUserManager;
use dnj\AAA\ServiceProvider;
use dnj\AAA\TypeManager;
use dnj\AAA\UserManager;
use dnj\UserLogger\ServiceProvider as UserLoggerServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TestCase extends \Orchestra\Testbench\TestCase
{
    use RefreshDatabase;

    public function getTypeManager(): TypeManager
    {
        return $this->app->make(ITypeManager::class);
    }

    public function getUserManager(): UserManager
    {
        return $this->app->make(IUserManager::class);
    }

    protected function getPackageProviders($app)
    {
        return [
            UserLoggerServiceProvider::class,
            ServiceProvider::class,
        ];
    }
}
