<?php

namespace Hellotreedigital\Cms\Middlewares;

use Hellotreedigital\Cms\Models\AdminRole;
use Hellotreedigital\Cms\Models\AdminRolePermission;
use Hellotreedigital\Cms\Models\CmsPage;
use Hellotreedigital\Cms\Models\Log;
use Closure;
use Route;
use Auth;
use View;
use Str;

class AdminMiddleware
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
        $admin = Auth::guard('admin')->user();

        // Check if login page
        if (Route::currentRouteName() == 'admin-login') {
            if ($admin) return redirect(route('admin-home'));
            return $next($request);
        }

        if (!$admin) return redirect()->guest(route('admin-login'));
        $admin = $admin->toArray();

        // Get CMS pages
        $cms_pages = [];
        $cms_pages_db = CmsPage::orderBy('ht_pos')->get()->toArray();
        foreach ($cms_pages_db as $cms_page_db) $cms_pages[$cms_page_db['route']] = $cms_page_db;

        // If admin doesn't have a role id then he is a super admin, otherwise he is not and should get the admin permissions
        if ($admin['admin_role_id']) {
            // Get role permissions
            $admin_role_permissions = [];
            $admin_role_permissions_db = AdminRolePermission::where('admin_role_id', $admin['admin_role_id'])->get();

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

        // Save $admin in request
        $admin['cms_pages'] = $cms_pages;
        $admin['cms_pages_grouped'] = $cms_pages_grouped;
        $request->attributes->add(compact('admin'));

        // If admin have role id then he is not a super then, therefore we should check the permissions
        if ($admin['admin_role_id']) {
            // Get route prefix
            $cms_route_prefix = config('hellotree.cms_route_prefix');

            // Get requested path route
            $requested_path = substr(request()->path(), strlen($cms_route_prefix));
            if (Str::startsWith($requested_path, '/')) $requested_path = substr($requested_path, 1);

            $request_path_array = explode('/', $requested_path);
            if (!isset($request_path_array[0]) || !$request_path_array[0]) $request_path_array[0] = 'home';

            // Check if the requested page is not the home page nor profile
            if ($request_path_array[0] != 'home' && $request_path_array[0] != 'profile' && $request_path_array[0] != 'logout' && $request_path_array[0] != 'ckeditor') {
                $route = $request_path_array[0];

                // Checking if requested page is available in the CMS pages array
                if (!isset($admin['cms_pages'][$route])) abort(403);

                // Check permissions
                $admin_page_permission = $admin['cms_pages'][$route]['permissions'];
                if ($request->isMethod('post')) {
                    if (isset($request_path_array[1]) && isset($request_path_array[2]) && $request_path_array[1] == 'edit' && $request_path_array[2] == 'images') {
                        // Uploading images
                    } else {
                        Log::create([
                            'admin_id' => $admin['id'],
                            'cms_page_id' => $admin['cms_pages'][$route]['id'],
                            'action' => 'created',
                        ]);
                    }
                    if (!$admin_page_permission['add']) abort(403);
                } elseif ($request->isMethod('delete')) {
                    Log::create([
                        'admin_id' => $admin['id'],
                        'cms_page_id' => $admin['cms_pages'][$route]['id'],
                        'record_id' => $request['id'],
                        'action' => 'deleted',
                    ]);
                    if (!$admin_page_permission['delete']) abort(403);
                } elseif ($request->isMethod('put')) {
                    if (isset($request_path_array[1]) && $request_path_array[1] == 'order') {
                        // Order
                        Log::create([
                            'admin_id' => $admin['id'],
                            'cms_page_id' => $admin['cms_pages'][$route]['id'],
                            'record_id' => '',
                            'action' => 'ordered',
                        ]);
                    } elseif (isset($request_path_array[1]) && isset($request_path_array[2]) && $request_path_array[1] == 'edit' && $request_path_array[2] == 'images') {
                        // Uploading images
                    } else {
                        // Edit
                        Log::create([
                            'admin_id' => $admin['id'],
                            'cms_page_id' => $admin['cms_pages'][$route]['id'],
                            'record_id' => $request['id'],
                            'action' => 'edited',
                        ]);
                    }
                    if (!$admin_page_permission['edit']) abort(403);
                } else {
                    // Get Method
                    if (!isset($request_path_array[1])) { // Index page
                        if (!$admin_page_permission['browse']) abort(403);
                    } elseif ($request_path_array[1] == 'create') { // Create page
                        if (!$admin_page_permission['add']) abort(403);
                    } elseif ($request_path_array[1] == 'order') { // Order page
                        if (!$admin_page_permission['edit']) abort(403);
                    } elseif (isset($request_path_array[2]) && $request_path_array[2] == 'edit') { // Edit page
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
