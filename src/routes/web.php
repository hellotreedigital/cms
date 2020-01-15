<?php

/*
|--------------------------------------------------------------------------
| CMS GET routes for non signed in admins
|--------------------------------------------------------------------------
*/

Route::prefix(config('hellotree.cms_route_prefix'))->middleware(['web', 'admin'])->group(function () {
    Route::get('/', 'hellotreedigital\cms\controllers\CmsController@redirectToLoginForm');
    Route::get('login', 'hellotreedigital\cms\controllers\CmsController@showLoginForm')->name('admin-login');
});

/*
|--------------------------------------------------------------------------
| CMS POST routes for non signed in admins
|--------------------------------------------------------------------------
*/

Route::prefix(config('hellotree.cms_route_prefix'))->middleware(['web'])->group(function () {
    Route::post('login', 'hellotreedigital\cms\controllers\CmsController@login');
});


/*
|--------------------------------------------------------------------------
| CMS basic routes for signed in admins
|--------------------------------------------------------------------------
*/

Route::prefix(config('hellotree.cms_route_prefix'))->middleware(['web', 'admin'])->group(function () {
    Route::get('logout', 'hellotreedigital\cms\controllers\CmsController@logout')->name('admin-logout');
    Route::get('home', 'hellotreedigital\cms\controllers\CmsController@showHome')->name('admin-home');
    Route::get('profile', 'hellotreedigital\cms\controllers\CmsController@showProfile')->name('admin-profile');
    Route::get('profile/edit', 'hellotreedigital\cms\controllers\CmsController@showEditProfile')->name('admin-profile-edit');
    Route::post('profile/edit', 'hellotreedigital\cms\controllers\CmsController@editProfile');
    Route::get('/cms-pages/icons', 'hellotreedigital\cms\controllers\CmsPagesController@icons');
    Route::get('/cms-pages/order', 'hellotreedigital\cms\controllers\CmsPagesController@orderIndex');
    Route::post('/cms-pages/order', 'hellotreedigital\cms\controllers\CmsPagesController@orderSubmit');
    Route::resource('cms-pages', 'hellotreedigital\cms\controllers\CmsPagesController');
    Route::resource('/admins', 'hellotreedigital\cms\controllers\AdminsController');
    Route::resource('/admin-roles', 'hellotreedigital\cms\controllers\AdminRolesController');
});