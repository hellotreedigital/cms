<?php

/*
|--------------------------------------------------------------------------
| CMS generated routes for signed in admins
|--------------------------------------------------------------------------
*/

Route::prefix(env('CMS_PREFIX', 'admin'))->middleware(['auth:admin', 'admin'])->group(function () {
    /* Start admin route group */
	/* End admin route group */
});