<?php

namespace App\Http\Controllers\Cms;

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
        return view('cms/pages/admins/index', compact('rows'));
    }

    public function create()
    {
        $admin_roles = AdminRole::get()->toArray();

        return view('cms/pages/admins/create', compact('admin_roles'));
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
        return view('cms/pages/admins/show', compact('row'));
    }

    public function edit($id)
    {
        $row = Admin::findOrFail($id);
        return view('cms/pages/admins/edit', compact('row'));
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