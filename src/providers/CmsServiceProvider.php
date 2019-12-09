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
		
		// Publish cms assets
		$this->publishes(
			[
				__DIR__ . '/../assets' => public_path('cms/'),
				__DIR__ . '/../models' => app_path('/'),
			],
		'cms_assets');

		Artisan::call('vendor:publish --tag=cms_assets --force');
	    
		// Migrations
	    $this->loadMigrationsFrom(__DIR__ . '/../migrations');
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
}