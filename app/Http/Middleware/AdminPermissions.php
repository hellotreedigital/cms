<?php

namespace App\Http\Middleware;

use App\AdminRole;
use App\AdminRolePermission;
use App\CmsPage;
use Closure;
use Auth;
use View;

class AdminPermissions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        // Get admin
        $admin = session('admin');

        // Get CMS pages
        $cms_pages = [];
        $cms_pages_db = CmsPage::orderBy('ht_pos')->get()->toArray();
        foreach ($cms_pages_db as $cms_page_db) $cms_pages[$cms_page_db['route']] = $cms_page_db;

        // If admin doesn't have a role id then he is a super admin, otherwise he is not and should get the admin permissions
        if ($admin['role_id']) {
            // Get role permissions
            $admin_role_permissions = [];
            $admin_role_permissions_db = AdminRolePermission::where('admin_role_id', $admin['role_id'])->get();

            // Add permissions to $cms_pages array
            foreach ($cms_pages as $key => $cms_page) {
                $page_found_in_permissions = false;
                foreach ($admin_role_permissions_db as $admin_role_permission_db) {
                    if ($admin_role_permission_db['cms_page_id'] == $cms_pages[$key]['id']) {
                        $cms_pages[$key]['permissions'] = [
                            'browse' => $admin_role_permission_db['browse'],
                            'read' => $admin_role_permission_db['read'],
                            'edit' => $admin_role_permission_db['edit'],
                            'add' => $admin_role_permission_db['add'],
                            'delete' => $admin_role_permission_db['delete'],
                        ];
                        $page_found_in_permissions = true;
                    }
                }
                if (!$page_found_in_permissions) {
                    $cms_pages[$key]['permissions'] = [
                        'browse' => 0,
                        'read' => 0,
                        'edit' => 0,
                        'add' => 0,
                        'delete' => 0,
                    ];
                }
            }
        } else {
            // Give the super admin all permissions
            foreach ($cms_pages as $key => $cms_page) {
                $cms_pages[$key]['permissions'] = [
                    'browse' => 1,
                    'read' => 1,
                    'edit' => 1,
                    'add' => 1,
                    'delete' => 1,
                ];
            }
        }

        // Group cms pages by parent
        $cms_pages_grouped = [];
        $last_page_added = null;
        foreach ($cms_pages as $cms_page) {
            if ($cms_page['permissions']['browse']) {
                if ($last_page_added && $last_page_added['parent_icon'] == $cms_page['parent_icon'] && $last_page_added['parent_title'] == $cms_page['parent_title']) {
                    $cms_pages_grouped[count($cms_pages_grouped) - 1]['pages'][] = $cms_page;
                } else {
                    $cms_pages_grouped[] = [
                        'icon' => $cms_page['parent_icon'],
                        'title' => $cms_page['parent_title'],
                        'pages' => [$cms_page],
                    ];
                }
                $last_page_added = $cms_page;
            }
        }

        // Save $admin in session
        $admin['cms_pages'] = $cms_pages;
        $admin['cms_pages_grouped'] = $cms_pages_grouped;
        session(['admin' => $admin]);

        // If admin have role id then he is not a super then, therefore we should check the permissions
        if ($admin['role_id']) {
            // Get requested page route
            $request_path_array = explode('/', request()->path());
            if (!isset($request_path_array[1])) $request_path_array[1] = '';

            // Check if the requested page is not the home page nor profile
            if ($request_path_array[1] != 'home' && $request_path_array[1] != 'profile' && $request_path_array[1] != 'logout') {
                $route = $request_path_array[1];

                // Checking if requested page is available in the CMS pages array
                if (!isset($admin['cms_pages'][$route])) abort(403);

                // Check permissions
                $admin_page_permission = $admin['cms_pages'][$route]['permissions'];
                if ($request->isMethod('post')) {
                    if (!$admin_page_permission['add']) abort(403);
                } elseif ($request->isMethod('delete')) {
                    if (!$admin_page_permission['delete']) abort(403);
                } elseif ($request->isMethod('patch')) {
                    if (!$admin_page_permission['edit']) abort(403);
                } else {
                    // Get Method
                    if (!isset($request_path_array[2])) { // Index page
                        if (!$admin_page_permission['browse']) abort(403);
                    } elseif ($request_path_array[2] == 'create') { // Create page
                        if (!$admin_page_permission['add']) abort(403);
                    } elseif ($request_path_array[2] == 'order') { // Order page
                        if (!$admin_page_permission['edit']) abort(403);
                    } elseif (isset($request_path_array[3]) && $request_path_array[3] == 'edit') { // Edit page
                        if (!$admin_page_permission['edit']) abort(403);
                    } else { // Show page
                        if (!$admin_page_permission['read']) abort(403);
                    }
                }
            }
        }

        return $next($request);
    }
}