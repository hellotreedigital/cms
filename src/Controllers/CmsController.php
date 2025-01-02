<?php

namespace Hellotreedigital\Cms\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use App\Http\Controllers\Controller;
use League\Flysystem\Util;
use Hash;
use File;
use Str;
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
        return view('cms::pages/login/index');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        if (Auth::guard('admin')->attempt(['email' => $request['email'], 'password' => $request['password']])) {
            $cookieValue = now(); // or \Carbon\Carbon::now() if you prefer Carbon
            $cookie = Cookie::make('hellotree_cms_login_date', $cookieValue, 120); // 120 minutes expiration time

            return redirect()->intended(route('admin-home'))->withCookie($cookie);
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
       return response()->view('cms::pages/profile/show')->withCookie(cookie('hellotree_cms_login_date', now(), 120));
    }


    public function showEditProfile()
    {
        return view('cms::pages/profile/edit');
    }

    public function editProfile(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'password' => 'confirmed',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg',
        ]);

        $admin = Auth::guard('admin')->user();
        $admin->name = $request->name;

        if ($request->password){
            $admin->password = Hash::make($request->password);
            $admin->reset_password_date = now();
        }
        if ($request->remove_file_image) {
            $admin->image = '';
        } elseif ($request->image) {
            $admin->image = $request->file('image')->store('admins');
        }

        $admin->save();

        $request->session()->flash('success', 'Profile updated successfully');
        return route('admin-profile');
    }

    /*
	 * Start: Home methods
	 */

    public function showHome()
    {
        return view('cms::pages/home/index');
    }

    /*
     * Start: Assets methods
     */

    public function asset(Request $request)
    {
        if (class_exists(\League\Flysystem\Util::class)) {
            // Flysystem 1.x
            $path = __DIR__ . '/../assets/' . \League\Flysystem\Util::normalizeRelativePath(urldecode($request['path']));
        } elseif (class_exists(\League\Flysystem\WhitespacePathNormalizer::class)) {
            // Flysystem >= 2.x
            $normalizer = new \League\Flysystem\WhitespacePathNormalizer();
            $path = __DIR__ . '/../assets/' . $normalizer->normalizePath(urldecode($request['path']));
        }

        $file = File::get($path);
        if (Str::endsWith($path, '.js')) $mime = 'application/javascript';
        elseif (Str::endsWith($path, '.css')) $mime = 'text/css';
        else $mime = File::mimeType($path);
        return response($file, 200, ['Content-Type' => $mime])->setSharedMaxAge(31536000)->setMaxAge(31536000)->setExpires(new \DateTime('+1 year'));
    }
}
