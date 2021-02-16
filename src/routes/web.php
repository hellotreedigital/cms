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

// Asset
Route::get('/asset', 'hellotreedigital\cms\controllers\CmsController@asset');

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
    Route::resource('/admins', 'hellotreedigital\cms\controllers\AdminsController');
    Route::resource('/admin-roles', 'hellotreedigital\cms\controllers\AdminRolesController');

    // Cms Pages managment routes
    Route::get('/cms-pages/icons', 'hellotreedigital\cms\controllers\CmsPagesController@icons');
    Route::get('/cms-pages/order', 'hellotreedigital\cms\controllers\CmsPagesController@orderIndex');
    Route::get('/cms-pages', 'hellotreedigital\cms\controllers\CmsPagesController@index');
    Route::get('/cms-pages/order', 'hellotreedigital\cms\controllers\CmsPagesController@order');
    Route::get('/cms-pages/create', 'hellotreedigital\cms\controllers\CmsPagesController@create');
    Route::get('/cms-pages/create/custom', 'hellotreedigital\cms\controllers\CmsPagesController@createCustom');
    Route::get('/cms-pages/{id}/edit', 'hellotreedigital\cms\controllers\CmsPagesController@edit');
    Route::get('/cms-pages/custom/{id}/edit', 'hellotreedigital\cms\controllers\CmsPagesController@editCustom');
    Route::post('/cms-pages/order', 'hellotreedigital\cms\controllers\CmsPagesController@orderSubmit');
    Route::post('/cms-pages', 'hellotreedigital\cms\controllers\CmsPagesController@store');
    Route::post('/cms-pages/custom', 'hellotreedigital\cms\controllers\CmsPagesController@storeCustom');
    Route::post('/cms-pages/order', 'hellotreedigital\cms\controllers\CmsPagesController@changeOrder');
    Route::put('/cms-pages/{id}', 'hellotreedigital\cms\controllers\CmsPagesController@update');
    Route::put('/cms-pages/custom/{id}', 'hellotreedigital\cms\controllers\CmsPagesController@updateCustom');
    Route::delete('/cms-pages/{id}', 'hellotreedigital\cms\controllers\CmsPagesController@destroy');
    Route::post('/ckeditor/images', 'hellotreedigital\cms\controllers\CmsPagesController@uploadCkeditorImages')->name('ckeditor-images');

    //Logs
    Route::get('/logs', 'hellotreedigital\cms\controllers\CmsLogsController@index');

    // Cms Pages routes
    foreach (\Hellotreedigital\Cms\Models\CmsPage::where('custom_page', 0)->get() as $cms_page) {
        Route::get('/' . $cms_page->route, 'hellotreedigital\cms\controllers\CmsPageController@index')->defaults('route', $cms_page->route);
        Route::get('/' . $cms_page->route . '/order', 'hellotreedigital\cms\controllers\CmsPageController@order')->defaults('route', $cms_page->route);
        Route::get('/' . $cms_page->route . '/create', 'hellotreedigital\cms\controllers\CmsPageController@create')->defaults('route', $cms_page->route);
        Route::get('/' . $cms_page->route . '/{id}', 'hellotreedigital\cms\controllers\CmsPageController@show')->defaults('route', $cms_page->route);
        Route::get('/' . $cms_page->route . '/{id}/edit', 'hellotreedigital\cms\controllers\CmsPageController@edit')->defaults('route', $cms_page->route);

        Route::post('/' . $cms_page->route, 'hellotreedigital\cms\controllers\CmsPageController@store')->defaults('route', $cms_page->route);
        Route::put('/' . $cms_page->route . '/order', 'hellotreedigital\cms\controllers\CmsPageController@changeOrder')->defaults('route', $cms_page->route);
        Route::put('/' . $cms_page->route . '/{id}', 'hellotreedigital\cms\controllers\CmsPageController@update')->defaults('route', $cms_page->route);
        // Both routes are the same but have different method for roles purposes
        Route::post('/' . $cms_page->route . '/edit/images', 'hellotreedigital\cms\controllers\CmsPageController@uploadImages')->defaults('route', $cms_page->route);
        Route::put('/' . $cms_page->route . '/edit/images', 'hellotreedigital\cms\controllers\CmsPageController@uploadImages')->defaults('route', $cms_page->route);
        Route::delete('/' . $cms_page->route . '/{id}', 'hellotreedigital\cms\controllers\CmsPageController@destroy')->defaults('route', $cms_page->route);
    }
});

/*
|--------------------------------------------------------------------------
| APIs
|--------------------------------------------------------------------------
*/

Route::prefix(config('hellotree.api_route_prefix'))->middleware(['api'])->group(function () {
    foreach (\Hellotreedigital\Cms\Models\CmsPage::where('custom_page', 0)->where('apis', 1)->get() as $cms_page) {
        Route::post('/' . $cms_page->route, 'hellotreedigital\cms\controllers\ApisController@index')->defaults('route', $cms_page->route);
        Route::post('/' . $cms_page->route . '/{id}', 'hellotreedigital\cms\controllers\ApisController@single')->defaults('route', $cms_page->route);
    }
});