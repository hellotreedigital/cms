<?php

namespace Hellotreedigital\Cms\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Hellotreedigital\Cms\Models\AdminRolePermission;
use Hellotreedigital\Cms\Models\AdminRole;
use Hellotreedigital\Cms\Models\CmsPage;


class AdminRolesController extends Controller
{
    public function index()
    {
        $rows = AdminRole::get();
        return view('cms::pages/admin-roles/index', compact('rows'));
    }

    public function create()
    {
        $cms_pages = CmsPage::get()->toArray();
        return view('cms::pages/admin-roles/create', compact(
            'cms_pages'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
        ]);

        $admin_role = new AdminRole;
		$admin_role->title = $request->title;
        $admin_role->save();

        $cms_pages = CmsPage::get();
        foreach ($cms_pages as $key => $cms_page) {
            if ($cms_page->id == 1) continue;

            if (
                isset($request['browse_' . $cms_page->id]) ||
                isset($request['read_' . $cms_page->id]) ||
                isset($request['edit_' . $cms_page->id]) ||
                isset($request['add_' . $cms_page->id]) ||
                isset($request['delete_' . $cms_page->id])
            ) {
                $admin_role_permission = new AdminRolePermission;
                $admin_role_permission->admin_role_id = $admin_role->id;
                $admin_role_permission->cms_page_id = $cms_page->id;
                $admin_role_permission->browse = isset($request['browse_' . $cms_page->id]) ? 1 : 0;
                $admin_role_permission->read = isset($request['read_' . $cms_page->id]) ? 1 : 0;
                $admin_role_permission->edit = isset($request['edit_' . $cms_page->id]) ? 1 : 0;
                $admin_role_permission->add = isset($request['add_' . $cms_page->id]) ? 1 : 0;
                $admin_role_permission->delete = isset($request['delete_' . $cms_page->id]) ? 1 : 0;
                $admin_role_permission->save();
            }
        }

        return redirect(config('hellotree.cms_route_prefix') . '/admin-roles')->with('success', 'Record added successfully');
    }

    public function show($id)
    {
        $row = AdminRole::findOrFail($id);
        $admin_role_permissions = AdminRolePermission::where('admin_role_id', $id)->get();
        return view('cms::pages/admin-roles/show', compact('row', 'admin_role_permissions'));
    }

    public function edit($id)
    {
        $row = AdminRole::findOrFail($id);
        $cms_pages = CmsPage::get()->toArray();
        $admin_role_permissions = AdminRolePermission::where('admin_role_id', $id)->get();
        $cms_pages_permissions = $this->cmsPagesWithPermissions($cms_pages, $admin_role_permissions);
        return view('cms::pages/admin-roles/edit', compact(
            'row',
            'cms_pages_permissions'
        ));
    }

    public function update(Request $request, $id)
    {
        $row = AdminRole::findOrFail($id);

        $request->validate([
            'title' => 'required',
        ]);

        $row->title = $request->title;
        $row->save();

        $cms_pages = CmsPage::get();
        foreach ($cms_pages as $key => $cms_page) {
            if ($cms_page->id == 1) continue;

            // Check if old permission exists
            $admin_role_permission = AdminRolePermission::where('admin_role_id', $id)->where('cms_page_id', $cms_page->id)->first();
            if ($admin_role_permission) {
                // If browse permission is not granted then user cannot see the page so delete it
                if (
                    !isset($request['browse_' . $cms_page->id]) &&
                    !isset($request['read_' . $cms_page->id]) &&
                    !isset($request['edit_' . $cms_page->id]) &&
                    !isset($request['add_' . $cms_page->id]) &&
                    !isset($request['delete_' . $cms_page->id])
                ) {
                    AdminRolePermission::destroy($admin_role_permission->id);
                    continue;
                }
            } else {
                // If browse permission is granted create new permission
                if (
                    isset($request['browse_' . $cms_page->id]) ||
                    isset($request['read_' . $cms_page->id]) ||
                    isset($request['edit_' . $cms_page->id]) ||
                    isset($request['add_' . $cms_page->id]) ||
                    isset($request['delete_' . $cms_page->id])
                ) {
                    $admin_role_permission = new AdminRolePermission;
                    $admin_role_permission->admin_role_id = $id;
                    $admin_role_permission->cms_page_id = $cms_page->id;
                } else {
                    continue;
                }
            }

            $admin_role_permission->browse = isset($request['browse_' . $cms_page->id]) ? 1 : 0;
            $admin_role_permission->read = isset($request['read_' . $cms_page->id]) ? 1 : 0;
            $admin_role_permission->edit = isset($request['edit_' . $cms_page->id]) ? 1 : 0;
            $admin_role_permission->add = isset($request['add_' . $cms_page->id]) ? 1 : 0;
            $admin_role_permission->delete = isset($request['delete_' . $cms_page->id]) ? 1 : 0;
            $admin_role_permission->save();
        }

        return redirect(config('hellotree.cms_route_prefix') . '/admin-roles')->with('success', 'Record edited successfully');
    }

    public function destroy($id)
    {
        $array = explode(',', $id);
        foreach ($array as $id) AdminRole::destroy($id);
        return redirect(config('hellotree.cms_route_prefix') . '/admin-roles')->with('success', 'Record deleted successfully');
    }

    public function cmsPagesWithPermissions($cms_pages, $permissions)
    {
        foreach ($cms_pages as $key => $cms_page) {
            $cms_pages[$key]['permissions'] = [
                'browse' => 0,
                'read' => 0,
                'edit' => 0,
                'add' => 0,
                'delete' => 0,
            ];
            foreach ($permissions as $permission) {
                if ($permission['cms_page_id'] == $cms_page['id']) {
                    $cms_pages[$key]['permissions'] = [
                        'browse' => $permission['browse'],
                        'read' => $permission['read'],
                        'edit' => $permission['edit'],
                        'add' => $permission['add'],
                        'delete' => $permission['delete'],
                    ];
                }
            }
        }
        return $cms_pages;
    }
}