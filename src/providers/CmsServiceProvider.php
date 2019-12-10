<?php

namespace Hellotreedigital\Cms\Providers;

use Illuminate\Support\ServiceProvider;
use Artisan;
use Auth;

Class CmsServiceProvider extends ServiceProvider
{
	public function boot()
	{
		// Routes
		include __DIR__ . '/../routes/web.php';
		
		// First installation from console
		if (!$this->app['config']->get('hellotree.cms_installed')) $this->firstInstallation();
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

	protected function firstInstallation()
	{
		// Include cms.php routes in web.php
		$routes = file_get_contents(base_path('routes/web.php'));
		$routes = str_replace('<?php', file_get_contents(base_path('vendor/hellotreedigital/cms/src/routes/web.stub')), $routes);
		file_put_contents(base_path('routes/web.php'), $routes);
		
		// Migrate
		Artisan::call('migrate --path=vendor/hellotreedigital/cms/src/migrations');

		// Add seeds
		Artisan::call('db:seed --class="Hellotreedigital\\\\Cms\\\\Seeds\\\\DatabaseSeeder"');
		
		// Publish cms assets
		$this->publishes([
			__DIR__ . '/../publishable/assets' => public_path('cms/'),
			__DIR__ . '/../publishable/config' => config_path('/'),
			__DIR__ . '/../publishable/routes' => base_path('routes/'),
		], 'cms_assets');
		Artisan::call('vendor:publish --tag=cms_assets --force');
	}
}