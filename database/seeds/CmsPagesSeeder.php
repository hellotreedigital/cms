<?php

use Illuminate\Database\Seeder;

class CmsPagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
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
    }
}
