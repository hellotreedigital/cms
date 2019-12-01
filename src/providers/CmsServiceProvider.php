<?php

namespace Hellotreedigital\Cms\Providers;

use Illuminate\Support\ServiceProvider;
use Artisan;

Class CmsServiceProvider extends ServiceProvider
{
	public function boot()
	{
		include __DIR__ . '/../routes.php';
		$this->publishes([__DIR__ . '/../assets' => public_path('cms/')], 'cms_assets');
		Artisan::call('vendor:publish --tag=cms_assets --force');
	    $this->loadMigrationsFrom(__DIR__ . '/../migrations');
	}

	public function register()
	{
		$this->app->make('Hellotreedigital\Cms\Controllers\AdminRolesController');
		$this->app->make('Hellotreedigital\Cms\Controllers\AdminsController');
		$this->app->make('Hellotreedigital\Cms\Controllers\CmsController');
		$this->app->make('Hellotreedigital\Cms\Controllers\CmsPagesController');

		$this->loadViewsFrom(__DIR__ . '/../views', 'cms');
		$this->app['router']->aliasMiddleware('admin', \Hellotreedigital\Cms\Middlewares\AdminPermissions::class);
	}
}