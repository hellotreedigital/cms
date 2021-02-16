<?php

namespace Hellotreedigital\Cms\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Hellotreedigital\Cms\Models\Admin;
use Hellotreedigital\Cms\Models\AdminRole;
use Hash;


class AdminsController extends Controller
{
    public function index()
    {
        $rows = Admin::whereNotNull('admin_role_id')->get();
        return view('cms::pages/admins/index', compact('rows'));
    }

    public function create()
    {
        $admin_roles = [];
        $admin_roles = AdminRole::get();
        return view('cms::pages/admins/create', compact('admin_roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'image' => 'image',
            'email' => 'required|unique:admins',
            'password' => 'required|confirmed',
            'admin_role_id' => 'required',
        ]);

        $row = new Admin;
        $row->name = $request->name;
        if ($request->image) {
            $row->image = request()->file('image')->store('admins');
        }
        $row->email = $request->email;
        $row->password = Hash::make($request->password);
        $row->admin_role_id = $request->admin_role_id;
        $row->save();

        $request->session()->flash('success', 'Record added successfully');
        return url(config('hellotree.cms_route_prefix') . '/admins');
    }

    public function show($id)
    {
        $row = Admin::findOrFail($id);
        return view('cms::pages/admins/show', compact('row'));
    }

    public function edit($id)
    {
        $row = Admin::findOrFail($id);
        $admin_roles = [];
        $admin_roles_db = AdminRole::get()->toArray();
        foreach ($admin_roles_db as $single_admin_roles_db) $admin_roles[$single_admin_roles_db['id']] = $single_admin_roles_db;

        return view('cms::pages/admins/edit', compact('row', 'admin_roles'));
    }

    public function update(Request $request, $id)
    {
        $row = Admin::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'image' => 'image',
            'email' => 'required|unique:admins,email,' . $row->id,
            'password' => 'confirmed',
            'admin_role_id' => 'required',
        ]);

        $row->name = $request->name;
        if ($request->remove_file_image) {
            $row->image = '';
        } elseif ($request->image) {
            $row->image = request()->file('image')->store('admins');
        }
        $row->email = $request->email;
        if ($request->password) $row->password = Hash::make($request->password);
        $row->admin_role_id = $request->admin_role_id;
        $row->save();

        $request->session()->flash('success', 'Record edited successfully');
        return url(config('hellotree.cms_route_prefix') . '/admins');
    }

    public function destroy($id)
    {
        $array = explode(',', $id);
        foreach ($array as $id) Admin::destroy($id);
        return redirect(config('hellotree.cms_route_prefix') . '/admins')->with('success', 'Record deleted successfully');
    }
}
