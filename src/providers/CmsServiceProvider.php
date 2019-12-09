<?php

namespace Hellotreedigital\Cms\Providers;

use Illuminate\Support\ServiceProvider;
use Artisan;

Class CmsServiceProvider extends ServiceProvider
{
	public function boot()
	{
		// Routes
		include __DIR__ . '/../routes.php';

		// Artisan::call('vendor:publish --tag=cms_assets --force');
	    
		// Migrations
		$this->loadMigrationsFrom(__DIR__ . '/../migrations');
		
		if ($this->app->runningInConsole()) {
			$this->bootForConsole();
		}
	}

	public function register()
	{
		// Controllers
		$this->app->make('Hellotreedigital\Cms\Controllers\AdminRolesController');
		$this->app->make('Hellotreedigital\Cms\Controllers\AdminsController');
		$this->app->make('Hellotreedigital\Cms\Controllers\CmsController');
		$this->app->make('Hellotreedigital\Cms\Controllers\CmsPagesController');

		// Views
		$this->loadViewsFrom(__DIR__ . '/../views', 'cms');
		
		// Admin middleware
		$this->app['router']->aliasMiddleware('admin', \Hellotreedigital\Cms\Middlewares\AdminPermissions::class);
	}

	/**
	 * Console-specific booting.
	 *
	 * @return void
	 */
	protected function bootForConsole()
	{
		// Publish cms assets
		$this->publishes([__DIR__ . '/../assets' => public_path('cms/')], 'cms_assets');
		Artisan::call('vendor:publish --tag=cms_assets --force');
		Artisan::call('migrate --path=vendor/hellotreedigital/cms/src/migrations');
		Artisan::call('db:seed --class="Hellotreedigital/cms/src/migrations');
		// $this->publishes([
		// 	__DIR__.'/../config/pagereview.php' => config_path('pagereview.php'),
		// ], 'pagereview.config');

		// $this->publishes([
		// 	__DIR__.'/../resources/views' => base_path('resources/views/vendor/acme'),
		// ], 'pagereview.views');

		// $this->publishes([
		// 	__DIR__ . '/../database/migrations/' => database_path('migrations'),
		// ], 'migrations');
	}
}