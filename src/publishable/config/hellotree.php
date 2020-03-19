<?php

return [

    /*
    |--------------------------------------------------------------------------
    | CMS route prefix
    |--------------------------------------------------------------------------
    */

    'cms_route_prefix' => 'admin',


    /*
    |--------------------------------------------------------------------------
    | API route prefix
    |--------------------------------------------------------------------------
    */

    'api_route_prefix' => 'api',


    /*
    |--------------------------------------------------------------------------
    | CMS assets
    |--------------------------------------------------------------------------
    */

    'cms_assets' => [

    	/*
	    |--------------------------------------------------------------------------
	    | Used styles. You can add or remove any
	    |--------------------------------------------------------------------------
	    */

	    'styles' => [
	    	'asset?path=css/font-awesome/css/font-awesome.min.css',
	    	'asset?path=css/bootstrap.min.css',
	    	'asset?path=css/dataTables.min.css',
	    	'asset?path=css/select2.min.css',
	    	'asset?path=css/quill.snow.min.css',
	    	'asset?path=css/main.min.css',
	    ],


    	/*
	    |--------------------------------------------------------------------------
	    | Used scripts. You can add or remove any
	    |--------------------------------------------------------------------------
	    */

	    'scripts' => [
	    	'asset?path=js/jquery.min.js',
	    	'asset?path=js/jquery-ui.min.js',
	    	'asset?path=js/dataTables.min.js',
	    	'asset?path=js/dataTables.buttons.min.js',
	    	'asset?path=js/jszip.min.js',
	    	'asset?path=js/pdfmake.min.js',
	    	'asset?path=js/vfs_fonts.js',
	    	'asset?path=js/buttons.html5.min.js',
	    	'asset?path=js/select2.min.js',
	    	'asset?path=js/quill.min.js',
	    	'asset?path=js/quill-textarea.min.js',
	    	'asset?path=js/main.js',
	    ],
    ],
];