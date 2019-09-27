<?php

namespace App\Http\Controllers\Cms;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Hash;
use Auth;

class CmsController extends Controller
{
	/*
	 * Start: Auth methods
	 */

	public function redirectToLoginForm()
    {
    	return redirect(route('admin-login'));
    }

	public function showLoginForm()
    {
    	return view('cms/pages/login/show');
    }

    public function login(Request $request)
    {
    	$this->validate($request, [
    		'email' => 'required',
    		'password' => 'required'
    	]);

    	if (Auth::guard('admin')->attempt(['email' => $request['email'], 'password' => $request['password']])) {
    		return redirect()->intended(route('admin-home'));
    	}

    	return redirect()->back()->withInput($request->only('email'))->with('error', 'Wrong credentials');;
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect(route('admin-login'));
    }

	/*
	 * Start: Profile methods
	 */

	public function showProfile()
	{
		return view('cms/pages/profile/show');
	}

    public function showEditProfile()
    {
        return view('cms/pages/profile/edit');
    }

    public function editProfile(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'password' => 'confirmed',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg',
        ]);

        $admin = Auth::guard('admin')->user();
        $admin->name = $request->name;

        if ($request->password) $admin->password = Hash::make($request->password);
        if ($request->remove_file_image) {
            $admin->image = '';
        } elseif ($request->image) {
            $image = time() . '_' . md5(rand()) . '.' . request()->image->getClientOriginalExtension();
            $request->image->move(storage_path('app/public/admins'), $image);
            $admin->image = 'storage/admins/' . $image;
        }

        $admin->save();

        return redirect(route('admin-profile'));
    }

	/*
	 * Start: Home methods
	 */

	public function showHome()
	{
		return view('cms/pages/home/show');
	}
}
