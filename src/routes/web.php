<?php

/*
|--------------------------------------------------------------------------
| CMS GET routes for non signed in admins
|--------------------------------------------------------------------------
*/

Route::prefix(config('hellotree.cms_route_prefix'))->middleware(['web', 'admin'])->group(function () {
    Route::get('/', 'Hellotreedigital\Cms\Controllers\CmsController@redirectToLoginForm');
    Route::get('login', 'Hellotreedigital\Cms\Controllers\CmsController@showLoginForm')->name('admin-login');
});

// Asset
Route::get('/asset', 'Hellotreedigital\Cms\Controllers\CmsController@asset');

/*
|--------------------------------------------------------------------------
| CMS POST routes for non signed in admins
|--------------------------------------------------------------------------
*/

Route::prefix(config('hellotree.cms_route_prefix'))->middleware(['web'])->group(function () {
    Route::post('login', 'Hellotreedigital\Cms\Controllers\CmsController@login');
});


/*
|--------------------------------------------------------------------------
| CMS basic routes for signed in admins
|--------------------------------------------------------------------------
*/

Route::prefix(config('hellotree.cms_route_prefix'))->middleware(['web', 'admin'])->group(function () {
    
    Route::get('cms-media', 'Hellotreedigital\Cms\Controllers\CmsMediaController@showMedia')->name('cms-media');
    Route::get('vendor-image/{filename}', function ($filename) {
        $path = base_path("vendor/hellotreedigital/cms/src/assets/images/$filename");
    
        if (!file_exists($path)) {
            abort(404);
        }
    
        $mimeType = mime_content_type($path);
        return response()->file($path, [
            'Content-Type' => $mimeType
        ]);
    });

    Route::delete('cms-media/destroy', 'Hellotreedigital\Cms\Controllers\CmsMediaController@destroy');
    Route::post('/cms-media/upload', 'Hellotreedigital\Cms\Controllers\CmsMediaController@uploadFile');
    
    Route::get('logout', 'Hellotreedigital\Cms\Controllers\CmsController@logout')->name('admin-logout');
    Route::get('home', 'Hellotreedigital\Cms\Controllers\CmsController@showHome')->name('admin-home');
    Route::get('profile', 'Hellotreedigital\Cms\Controllers\CmsController@showProfile')->name('admin-profile');
    Route::get('profile/edit', 'Hellotreedigital\Cms\Controllers\CmsController@showEditProfile')->name('admin-profile-edit');
    Route::post('profile/edit', 'Hellotreedigital\Cms\Controllers\CmsController@editProfile');
    Route::resource('/admins', 'Hellotreedigital\Cms\Controllers\AdminsController');
    Route::resource('/admin-roles', 'Hellotreedigital\Cms\Controllers\AdminRolesController');
    Route::resource('/languages', 'Hellotreedigital\Cms\Controllers\LanguaguesController');

    // Cms Pages managment routes
    Route::get('/cms-pages/icons', 'Hellotreedigital\Cms\Controllers\CmsPagesController@icons');
    Route::get('/cms-pages/order', 'Hellotreedigital\Cms\Controllers\CmsPagesController@orderIndex');
    Route::get('/cms-pages', 'Hellotreedigital\Cms\Controllers\CmsPagesController@index');
    Route::get('/cms-pages/order', 'Hellotreedigital\Cms\Controllers\CmsPagesController@order');
    Route::get('/cms-pages/create', 'Hellotreedigital\Cms\Controllers\CmsPagesController@create');
    Route::get('/cms-pages/create/custom', 'Hellotreedigital\Cms\Controllers\CmsPagesController@createCustom');
    Route::get('/cms-pages/{id}/edit', 'Hellotreedigital\Cms\Controllers\CmsPagesController@edit');
    Route::get('/cms-pages/custom/{id}/edit', 'Hellotreedigital\Cms\Controllers\CmsPagesController@editCustom');
    Route::post('/cms-pages/order', 'Hellotreedigital\Cms\Controllers\CmsPagesController@orderSubmit');
    Route::post('/cms-pages', 'Hellotreedigital\Cms\Controllers\CmsPagesController@store');
    Route::post('/cms-pages/custom', 'Hellotreedigital\Cms\Controllers\CmsPagesController@storeCustom');
    Route::post('/cms-pages/order', 'Hellotreedigital\Cms\Controllers\CmsPagesController@changeOrder');
    Route::put('/cms-pages/{id}', 'Hellotreedigital\Cms\Controllers\CmsPagesController@update');
    Route::put('/cms-pages/custom/{id}', 'Hellotreedigital\Cms\Controllers\CmsPagesController@updateCustom');
    Route::delete('/cms-pages/{id}', 'Hellotreedigital\Cms\Controllers\CmsPagesController@destroy');
    Route::post('/ckeditor/images', 'Hellotreedigital\Cms\Controllers\CmsPageController@uploadCkeditorImages')->name('ckeditor-images');

    //Logs
    Route::get('/logs', 'Hellotreedigital\Cms\Controllers\CmsLogsController@index');

    // Cms Pages routes
    foreach (\Hellotreedigital\Cms\Models\CmsPage::where('custom_page', 0)->get() as $cms_page) {
        Route::get('/' . $cms_page->route, 'Hellotreedigital\Cms\Controllers\CmsPageController@index')->defaults('route', $cms_page->route);
        Route::get('/' . $cms_page->route . '/order', 'Hellotreedigital\Cms\Controllers\CmsPageController@order')->defaults('route', $cms_page->route);
        Route::get('/' . $cms_page->route . '/create', 'Hellotreedigital\Cms\Controllers\CmsPageController@create')->defaults('route', $cms_page->route);
        Route::get('/' . $cms_page->route . '/{id}', 'Hellotreedigital\Cms\Controllers\CmsPageController@show')->defaults('route', $cms_page->route);
        Route::get('/' . $cms_page->route . '/{id}/edit', 'Hellotreedigital\Cms\Controllers\CmsPageController@edit')->defaults('route', $cms_page->route);

        Route::post('/' . $cms_page->route, 'Hellotreedigital\Cms\Controllers\CmsPageController@store')->defaults('route', $cms_page->route);
        Route::put('/' . $cms_page->route . '/order', 'Hellotreedigital\Cms\Controllers\CmsPageController@changeOrder')->defaults('route', $cms_page->route);
        Route::put('/' . $cms_page->route . '/{id}', 'Hellotreedigital\Cms\Controllers\CmsPageController@update')->defaults('route', $cms_page->route);
        // Both routes are the same but have different method for roles purposes
        Route::post('/' . $cms_page->route . '/edit/images', 'Hellotreedigital\Cms\Controllers\CmsPageController@uploadImages')->defaults('route', $cms_page->route);
        Route::put('/' . $cms_page->route . '/edit/images', 'Hellotreedigital\Cms\Controllers\CmsPageController@uploadImages')->defaults('route', $cms_page->route);
        Route::delete('/' . $cms_page->route . '/{id}', 'Hellotreedigital\Cms\Controllers\CmsPageController@destroy')->defaults('route', $cms_page->route);
    }
});

/*
|--------------------------------------------------------------------------
| APIs
|--------------------------------------------------------------------------
*/

Route::prefix(config('hellotree.api_route_prefix'))->middleware(['api'])->group(function () {
    foreach (\Hellotreedigital\Cms\Models\CmsPage::where('custom_page', 0)->where('apis', 1)->get() as $cms_page) {
        Route::post('/' . $cms_page->route, 'Hellotreedigital\Cms\Controllers\ApisController@index')->defaults('route', $cms_page->route);
        Route::post('/' . $cms_page->route . '/{id}', 'Hellotreedigital\Cms\Controllers\ApisController@single')->defaults('route', $cms_page->route);
    }
});