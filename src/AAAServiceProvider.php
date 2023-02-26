<?php

namespace dnj\AAA;

use dnj\AAA\Contracts\ITypeManager;
use dnj\AAA\Contracts\IUserManager;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AAAServiceProvider extends ServiceProvider
{
	use MakeGates;

	public function register()
	{
		$this->mergeConfigFrom(__DIR__ . '/../config/aaa.php', 'aaa');
		$this->app->singleton(ITypeManager::class, TypeManager::class);
		$this->app->singleton(IUserManager::class, UserManager::class);
		$this->registerUserProvider();
	}

	public function boot()
	{
		$this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
		$this->integrateToGates();
		if ($this->app->runningInConsole()) {
			$this->publishes([
				__DIR__ . '/../config/aaa.php' => config_path('aaa.php'),
			], 'config');
			$this->commands([
				Console\PolicyMakeCommand::class,
			]);
		}
	}


	public function registerUserProvider(): void
	{
		/**
		 * @param \Illuminate\Auth\AuthManager $auth
		 */
		Auth::resolved(function ($auth) {
			$auth->provider("aaa", function () {
				return new UserProvider();
			});
		});
	}

}
