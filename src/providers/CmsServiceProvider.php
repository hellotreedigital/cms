<?php

namespace Hellotreedigital\Cms\Providers;

use Illuminate\Support\ServiceProvider;
use Artisan;

Class CmsServiceProvider extends ServiceProvider
{
	public function boot()
	{
		// Routes
		include __DIR__ . '/../routes/web.php';
	    
		// Migrations
		$this->loadMigrationsFrom(__DIR__ . '/../migrations');
		
		// First installation from console
		if ($this->app->runningInConsole()) $this->bootForConsole();
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
		$this->app['router']->aliasMiddleware('admin', \Hellotreedigital\Cms\Middlewares\AdminMiddleware::class);
	}

	protected function bootForConsole()
	{
		// Publish cms assets
		$this->publishes([__DIR__ . '/../assets' => public_path('cms/')], 'cms_assets');
		Artisan::call('vendor:publish --tag=cms_assets --force');

		// Migrate
		Artisan::call('migrate --path=vendor/hellotreedigital/cms/src/migrations');

		// Add seeds
		Artisan::call('db:seed --class="Hellotreedigital\\\\Cms\\\\Seeds\\\\DatabaseSeeder"');
	}
}