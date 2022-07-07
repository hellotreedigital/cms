<?php

namespace Hellotreedigital\Cms\Providers;

use Illuminate\Support\ServiceProvider;
use Artisan;
use Schema;
use Auth;
use DB;

class CmsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // To prevent key too long error
        Schema::defaultStringLength(191);

        // Set publishes
        $this->publishes([__DIR__ . '/../publishable/config' => config_path('/')], 'cms_config');
        $this->publishes([__DIR__ . '/../publishable/intouch-config' => config_path('/')], 'cms_intouch_config');
        $this->publishes([__DIR__ . '/../publishable/ripply-config' => config_path('/')], 'cms_ripply_config');
        $this->publishes([__DIR__ . '/../publishable/scratch-and-courage-config' => config_path('/')], 'cms_scratch_and_courage_config');
        $this->publishes([__DIR__ . '/../publishable/imagine-labs-config' => config_path('/')], 'cms_imagine_labs_config');
        $this->publishes([__DIR__ . '/../publishable/purple-brains-config' => config_path('/')], 'cms_purple_brains_config');
        $this->publishes([__DIR__ . '/../publishable/routes' => base_path('routes/')], 'cms_routes');

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
        $this->app->make('Hellotreedigital\Cms\Controllers\CmsLogsController');
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
        Artisan::call('vendor:publish --tag=cms_config --force');
        Artisan::call('vendor:publish --tag=cms_routes --force');
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
            $table->string('order_display')->nullable();
            $table->string('sort_by')->nullable();
            $table->string('sort_by_direction')->nullable();
            $table->string('preview_path')->nullable();
            $table->longtext('fields')->nullable();
            $table->longtext('translatable_fields')->nullable();
            $table->tinyInteger('add')->nullable();
            $table->tinyInteger('edit')->nullable();
            $table->tinyInteger('delete')->nullable();
            $table->tinyInteger('show')->nullable();
            $table->tinyInteger('single_record')->nullable();
            $table->tinyInteger('apis')->nullable();
            $table->tinyInteger('server_side_pagination')->nullable();
            $table->tinyInteger('with_export')->nullable();
            $table->tinyInteger('hidden')->default(0);
            $table->tinyInteger('custom_page')->default(0);
            $table->string('parent_title')->nullable();
            $table->string('parent_icon')->nullable();
            $table->integer('ht_pos')->nullable();
            $table->timestamps();
        });

        DB::table('cms_pages')->insert([
            [
                'icon' => 'fa-window-restore',
                'display_name' => null,
                'display_name_plural' => 'CMS Pages',
                'database_table' => null,
                'route' => 'cms-pages',
                'model_name' => null,
                'custom_page' => 1,
                'fields' => null,
                'translatable_fields' => null,
                'add' => null,
                'edit' => null,
                'delete' => null,
                'show' => null,
                'single_record' => null,
                'hidden' => 1,
                'apis' => null,
                'parent_title' => null,
                'parent_icon' => null,
            ],
            [
                'icon' => 'fa-language',
                'display_name' => null,
                'display_name_plural' => 'Languages',
                'database_table' => null,
                'route' => 'languages',
                'model_name' => null,
                'custom_page' => 1,
                'fields' => null,
                'translatable_fields' => null,
                'add' => null,
                'edit' => null,
                'delete' => null,
                'show' => null,
                'single_record' => null,
                'hidden' => 0,
                'apis' => null,
                'parent_title' => null,
                'parent_icon' => null,
            ],
            [
                'icon' => 'fa-lock',
                'display_name' => null,
                'display_name_plural' => 'Admin Roles',
                'database_table' => null,
                'route' => 'admin-roles',
                'model_name' => null,
                'custom_page' => 1,
                'fields' => null,
                'translatable_fields' => null,
                'add' => null,
                'edit' => null,
                'delete' => null,
                'show' => null,
                'single_record' => null,
                'hidden' => 0,
                'apis' => null,
                'parent_title' => 'Admins',
                'parent_icon' => 'fa-user-secret',
            ],
            [
                'icon' => ' fa-user-secret',
                'display_name' => null,
                'display_name_plural' => 'Admins',
                'database_table' => null,
                'route' => 'admins',
                'model_name' => null,
                'custom_page' => 1,
                'fields' => null,
                'translatable_fields' => null,
                'add' => null,
                'edit' => null,
                'delete' => null,
                'show' => null,
                'single_record' => null,
                'hidden' => 0,
                'apis' => null,
                'parent_title' => 'Admins',
                'parent_icon' => 'fa-user-secret',
            ],
            [
                'icon' => 'fa-align-left',
                'display_name' => null,
                'display_name_plural' => 'Logs',
                'database_table' => null,
                'route' => 'logs',
                'model_name' => null,
                'custom_page' => 1,
                'fields' => null,
                'translatable_fields' => null,
                'add' => null,
                'edit' => null,
                'delete' => null,
                'show' => null,
                'single_record' => null,
                'hidden' => 0,
                'apis' => null,
                'parent_title' => 'Admins',
                'parent_icon' => 'fa-user-secret',
            ],
            [
                'icon' => 'fa-bar-chart',
                'display_name' => 'SEO Page',
                'display_name_plural' => 'SEO Pages',
                'database_table' => 'seo_pages',
                'route' => 'seo-pages',
                'model_name' => 'SeoPage',
                'custom_page' => 0,
                'fields' => '[{"name":"slug","migration_type":"string","form_field":"slug","form_field_additionals_1":"en[title]","form_field_additionals_2":"0","description":null,"hide_index":0,"hide_create":0,"hide_edit":0,"hide_show":0,"nullable":"0","unique":"0"},{"name":"image","migration_type":"string","form_field":"image","form_field_additionals_1":null,"form_field_additionals_2":null,"description":null,"hide_index":0,"hide_create":0,"hide_edit":0,"hide_show":0,"nullable":"1","unique":"0"}]',
                'translatable_fields' => '[{"name":"title","migration_type":"string","form_field":"text","description":null,"hide_index":0,"hide_create":0,"hide_edit":0,"hide_show":0,"nullable":"1"},{"name":"description","migration_type":"text","form_field":"textarea","description":null,"hide_index":0,"hide_create":0,"hide_edit":0,"hide_show":0,"nullable":"1"}]',
                'add' => 0,
                'edit' => 1,
                'delete' => 0,
                'show' => 1,
                'single_record' => 0,
                'hidden' => 0,
                'apis' => 0,
                'parent_title' => null,
                'parent_icon' => null,
            ],
        ]);

        // Create languages table
        Schema::create('languages', function ($table) {
            $table->increments('id');
            $table->string('slug');
            $table->string('title');
            $table->string('direction');
            $table->timestamps();
        });

        DB::table('languages')->insert([
            'slug' => 'en',
            'title' => 'English',
            'direction' => 'ltr',
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

        // Create cms logs table
        Schema::create('cms_logs', function ($table) {
            $table->increments('id');
            $table->integer('admin_id')->unsigned();
            $table->integer('cms_page_id')->unsigned();
            $table->string('record_id')->nullable();
            $table->string('action');
            $table->timestamps();

            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('cascade');
            $table->foreign('cms_page_id')->references('id')->on('cms_pages')->onDelete('cascade');
        });

        // Create http logs table
        Schema::create('http_logs', function ($table) {
            $table->increments('id');
            $table->string('ip');
            $table->string('method');
            $table->text('url');
            $table->longText('headers');
            $table->longText('request');
            $table->longText('response');
            $table->timestamps();
        });

        // Create seo pages table
        Schema::create('seo_pages', function ($table) {
            $table->increments('id');
            $table->string('slug');
            $table->string('image')->nullable();
            $table->timestamps();
        });

        // Create seo pages table
        Schema::create('seo_pages_translations', function ($table) {
            $table->increments('id');
            $table->integer('seo_page_id');
            $table->string('locale');
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Create seo pages model
        app('Hellotreedigital\Cms\Controllers\CmsPagesController')->createModel([
            'model_name' => 'SeoPage',
            'database_table' => 'seo_pages',
            'translatable_name' => ['title', 'description'],
            'form_field' => []
        ]);
    }
}
