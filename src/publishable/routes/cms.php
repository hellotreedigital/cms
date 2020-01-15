<?php

/*
|--------------------------------------------------------------------------
| CMS generated routes for signed in admins
|--------------------------------------------------------------------------
*/

Route::prefix(config('hellotree.cms_route_prefix'))->middleware(['auth:admin', 'admin'])->group(function () {
    /* Start admin route group */
	/* End admin route group */
});