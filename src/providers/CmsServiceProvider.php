<?php

namespace Hellotreedigital\Cms\Providers;

use Illuminate\Support\ServiceProvider;
use Artisan;
use Schema;
use Auth;
use DB;

Class CmsServiceProvider extends ServiceProvider
{
	public function boot()
	{
        // To prevent key too long error
        Schema::defaultStringLength(191);

		// First installation from console
		if (!is_array($this->app['config']->get('hellotree'))) $this->firstInstallation();

		// Routes
		$this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
	}

	public function register()
	{
		// Controllers
		$this->app->make('Hellotreedigital\Cms\Controllers\AdminRolesController');
		$this->app->make('Hellotreedigital\Cms\Controllers\AdminsController');
		$this->app->make('Hellotreedigital\Cms\Controllers\CmsController');
		$this->app->make('Hellotreedigital\Cms\Controllers\CmsPagesController');
        $this->app->make('Hellotreedigital\Cms\Controllers\CmsPageController');
        $this->app->make('Hellotreedigital\Cms\Controllers\LogsController');
        $this->app->make('Hellotreedigital\Cms\Controllers\ApisController');

		// Views
		$this->loadViewsFrom(__DIR__ . '/../views', 'cms');

		// Admin middleware
		$this->app['router']->aliasMiddleware('admin', \Hellotreedigital\Cms\Middlewares\AdminMiddleware::class);
	}

	protected function firstInstallation()
	{
		// Include cms.php routes in web.php
		$routes = file_get_contents(base_path('routes/web.php'));
		if (strpos($routes, "include 'cms.php';") === false) {
			$routes = str_replace('<?php', file_get_contents(base_path('vendor/hellotreedigital/cms/src/routes/web.stub')), $routes);
			file_put_contents(base_path('routes/web.php'), $routes);
		}

		$this->createDatabase();

		// Publish cms assets
		$this->publishes([
			__DIR__ . '/../publishable/assets' => public_path('cms/'),
			__DIR__ . '/../publishable/config' => config_path('/'),
			__DIR__ . '/../publishable/routes' => base_path('routes/'),
		], 'cms_assets');
		Artisan::call('vendor:publish --tag=cms_assets --force');
        Artisan::call('vendor:publish --tag=translatable --force');
	}

	protected function createDatabase()
	{
		// Create cms pages
        Schema::create('cms_pages', function ($table) {
            $table->increments('id');
            $table->string('icon')->nullable();
            $table->string('display_name')->nullable();
            $table->string('display_name_plural')->nullable();
            $table->string('database_table')->unique()->nullable();
            $table->string('route')->unique()->nullable();
            $table->string('model_name')->unique()->nullable();
            $table->string('order_display')->nullable()->nullable();
            $table->longtext('fields')->nullable();
            $table->longtext('translatable_fields')->nullable();
            $table->tinyInteger('add')->nullable();
            $table->tinyInteger('edit')->nullable();
            $table->tinyInteger('delete')->nullable();
            $table->tinyInteger('show')->nullable();
            $table->tinyInteger('single_record')->nullable();
            $table->tinyInteger('apis')->nullable();
            $table->tinyInteger('custom_page')->default(0);
            $table->string('parent_title')->nullable();
            $table->string('parent_icon')->nullable();
            $table->integer('ht_pos')->nullable();
            $table->timestamps();
        });

        DB::table('cms_pages')->insert([
    		[
                'icon' => 'fa-window-restore',
    			'display_name_plural' => 'CMS Pages',
    			'route' => 'cms-pages',
                'custom_page' => 1,
    		],
            [
                'icon' => 'fa-align-left',
                'display_name_plural' => 'Logs',
                'route' => 'logs',
                'custom_page' => 1,
            ],
    		[
                'icon' => ' fa-user-secret',
    			'display_name_plural' => 'Admins',
    			'route' => 'admins',
                'custom_page' => 1,
    		],
    		[
                'icon' => 'fa-lock',
    			'display_name_plural' => 'Admin Roles',
    			'route' => 'admin-roles',
                'custom_page' => 1,
    		],
    	]);

        // Create admin roles table
        Schema::create('admin_roles', function ($table) {
            $table->increments('id');
            $table->string('title');
            $table->timestamps();
        });

        // Create admin roles permissions table
        Schema::create('admin_role_permissions', function ($table) {
            $table->increments('id');
            $table->integer('admin_role_id')->unsigned();
            $table->integer('cms_page_id')->unsigned();
            $table->integer('browse')->unsigned();
            $table->integer('read')->unsigned();
            $table->integer('edit')->unsigned();
            $table->integer('add')->unsigned();
            $table->integer('delete')->unsigned();
            $table->timestamps();

            $table->foreign('admin_role_id')->references('id')->on('admin_roles')->onDelete('cascade');
            $table->foreign('cms_page_id')->references('id')->on('cms_pages')->onDelete('cascade');
        });

		// Create admin table
        Schema::create('admins', function ($table) {
            $table->increments('id');
            $table->string('name');
            $table->string('image')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->integer('admin_role_id')->nullable()->unsigned();
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('admin_role_id')->references('id')->on('admin_roles')->onDelete('cascade');
        });

    	DB::table('admins')->insert([
    		'name' => 'HELLOTREE',
    		'email' => 'support@hellotree.co',
    		'password' => bcrypt('$h1e2l3#'),
    	]);

        // Create logs table
        Schema::create('logs', function ($table) {
            $table->increments('id');
            $table->integer('admin_id')->unsigned();
            $table->integer('cms_page_id')->unsigned();
            $table->integer('record_id')->nullable()->unsigned();
            $table->string('action');
            $table->timestamps();

            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('cascade');
            $table->foreign('cms_page_id')->references('id')->on('cms_pages')->onDelete('cascade');
        });
	}
}