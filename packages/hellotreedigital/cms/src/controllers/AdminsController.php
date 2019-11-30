<?php

namespace Hellotreedigital\Cms\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Admin;
use App\AdminRole;
use Hash;


class AdminsController extends Controller
{
    public function index()
    {
        $rows = Admin::whereNotNull('role_id')->get();
        $admin_roles = [];
        $admin_roles_db = AdminRole::get()->toArray();
        foreach ($admin_roles_db as $single_admin_roles_db) $admin_roles[$single_admin_roles_db['id']] = $single_admin_roles_db;

        return view('cms::cms/pages/admins/index', compact('rows', 'admin_roles'));
    }

    public function create()
    {
        $admin_roles = [];
        $admin_roles_db = AdminRole::get()->toArray();
        foreach ($admin_roles_db as $single_admin_roles_db) $admin_roles[$single_admin_roles_db['id']] = $single_admin_roles_db;

        return view('cms::cms/pages/admins/create', compact('admin_roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'image' => 'image',
            'email' => 'required|unique:admins',
            'password' => 'required|confirmed',
            'role_id' => 'required',
        ]);

        $row = new Admin;
        
        $row->name = $request->name;
        if ($request->image) {
            $image = time() . '_' . md5(rand()) . '.' . request()->image->getClientOriginalExtension();
            $request->image->move(storage_path('app/public/admins'), $image);
            $row->image = 'storage/admins/' . $image;
        }
        $row->email = $request->email;
        $row->password = Hash::make($request->password);
        $row->role_id = $request->role_id;
        
        $row->save();

        return redirect(env('CMS_PREFIX', 'admin') . '/admins')->with('success', 'Record added successfully');
    }

    public function show($id)
    {
        $row = Admin::findOrFail($id);
        $admin_roles = [];
        $admin_roles_db = AdminRole::get()->toArray();
        foreach ($admin_roles_db as $single_admin_roles_db) $admin_roles[$single_admin_roles_db['id']] = $single_admin_roles_db;

        return view('cms::cms/pages/admins/show', compact('row', 'admin_roles'));
    }

    public function edit($id)
    {
        $row = Admin::findOrFail($id);
        $admin_roles = [];
        $admin_roles_db = AdminRole::get()->toArray();
        foreach ($admin_roles_db as $single_admin_roles_db) $admin_roles[$single_admin_roles_db['id']] = $single_admin_roles_db;

        return view('cms::cms/pages/admins/edit', compact('row', 'admin_roles'));
    }

    public function update(Request $request, $id)
    {
        $row = Admin::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'image' => 'image',
            'email' => 'required|unique:admins,email,' . $row->id,
            'password' => 'confirmed',
            'role_id' => 'required',
        ]);

        $row->name = $request->name;
        if ($request->remove_file_image) {
            $row->image = '';
        } elseif ($request->image) {
            $image = time() . '_' . md5(rand()) . '.' . request()->image->getClientOriginalExtension();
            $request->image->move(storage_path('app/public/admins'), $image);
            $row->image = 'storage/admins/' . $image;
        }
        $row->email = $request->email;
        if ($request->password) $row->password = Hash::make($request->password);
        $row->role_id = $request->role_id;
        
        $row->save();

        return redirect(env('CMS_PREFIX', 'admin') . '/admins')->with('success', 'Record edited successfully');
    }

    public function destroy($id)
    {
        $array = explode(',', $id);
        foreach ($array as $id) Admin::destroy($i);
        return redirect(env('CMS_PREFIX', 'admin') . '/admins')->with('success', 'Record deleted successfully');
    }
}