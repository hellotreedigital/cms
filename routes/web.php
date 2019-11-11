<?php

/*
|--------------------------------------------------------------------------
| CMS routes for non signed in admins
|--------------------------------------------------------------------------
*/

Route::prefix(env('CMS_PREFIX', 'admin'))->middleware(['guest:admin'])->group(function () {
    Route::get('/', 'Cms\CmsController@redirectToLoginForm');
    Route::get('login', 'Cms\CmsController@showLoginForm')->name('admin-login');
    Route::post('login', 'Cms\CmsController@login');
});


/*
|--------------------------------------------------------------------------
| CMS basic routes for signed in admins
|--------------------------------------------------------------------------
*/

Route::prefix(env('CMS_PREFIX', 'admin'))->middleware(['auth:admin', 'admin'])->group(function () {
    Route::get('logout', 'Cms\CmsController@logout')->name('admin-logout');
    Route::get('home', 'Cms\CmsController@showHome')->name('admin-home');
    Route::get('profile', 'Cms\CmsController@showProfile')->name('admin-profile');
    Route::get('profile/edit', 'Cms\CmsController@showEditProfile')->name('admin-profile-edit');
    Route::post('profile/edit', 'Cms\CmsController@editProfile');
    Route::get('/cms-pages/icons', 'Cms\CmsPagesController@icons');
    Route::get('/cms-pages/order', 'Cms\CmsPagesController@orderIndex');
    Route::post('/cms-pages/order', 'Cms\CmsPagesController@orderSubmit');
    Route::resource('cms-pages', 'Cms\CmsPagesController');
    Route::resource('/admins', 'Cms\AdminsController');
    Route::resource('/admin-roles', 'Cms\AdminRolesController');
});


/*
|--------------------------------------------------------------------------
| CMS generated routes for signed in admins
|--------------------------------------------------------------------------
*/

Route::prefix(env('CMS_PREFIX', 'admin'))->middleware(['auth:admin', 'admin'])->group(function () {
    /* Start admin route group */
    /* End admin route group */
});

/*
|--------------------------------------------------------------------------
| Website get routes
|--------------------------------------------------------------------------
*/

Route::get('/', function(){
	return redirect('/admin');
});

Route::middleware(['locale', 'website'])->prefix('{locale}')->group(function () {
});

/*
|--------------------------------------------------------------------------
| Website post routes
|--------------------------------------------------------------------------
*/

Route::middleware(['locale'])->prefix('{locale}')->group(function () {
});