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
	}

	protected function createDatabase()
	{
		// Create cms pages
        Schema::create('cms_pages', function ($table) {
            $table->increments('id');
            $table->string('icon')->nullable();
            $table->string('display_name');
            $table->string('display_name_plural');
            $table->string('database_table')->unique();
            $table->string('route')->unique();
            $table->string('model_name')->unique();
            $table->string('controller_name')->unique();
            $table->string('migration_name')->unique();
            $table->string('order_display')->nullable();
            $table->longtext('fields');
            $table->string('page_type');
            $table->string('parent_title')->nullable();
            $table->string('parent_icon')->nullable();
            $table->longtext('notes')->nullable();;
            $table->tinyInteger('deletable')->default(1);
            $table->tinyInteger('ht_pos')->nullable();
            $table->timestamps();
        });

        DB::table('cms_pages')->insert([
    		[
                'icon' => 'fa-window-restore',
    			'display_name' => 'CMS Page',
    			'display_name_plural' => 'CMS Pages',
    			'database_table' => 'cms_pages',
    			'route' => 'cms-pages',
    			'model_name' => 'CmsPage',
    			'controller_name' => 'CmsPagesController',
    			'migration_name' => '2014_10_12_000000_create_cms_pages_table',
                'fields' => '[]',
                'page_type' => 'regular',
                'deletable' => 0,
    		],
    		[
                'icon' => ' fa-user-secret',
    			'display_name' => 'Admin',
    			'display_name_plural' => 'Admins',
    			'database_table' => 'admins',
    			'route' => 'admins',
    			'model_name' => 'Admin',
    			'controller_name' => 'AdminsController',
    			'migration_name' => '2014_10_12_000000_create_admins_table',
                'fields' => '[]',
                'page_type' => 'regular',
                'deletable' => 0,
    		],
    		[
                'icon' => 'fa-lock',
    			'display_name' => 'Admin role',
    			'display_name_plural' => 'Admin Roles',
    			'database_table' => 'admin_roles',
    			'route' => 'admin-roles',
    			'model_name' => 'adminRole',
    			'controller_name' => 'AdminRolesController',
    			'migration_name' => '2014_10_10_000000_create_admin_roles_table',
                'fields' => '[]',
                'page_type' => 'regular',
                'deletable' => 0,
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
	}
}