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
	    	'https://cdn.ckeditor.com/4.6.2/standard-all/ckeditor.js',
	    	'asset?path=js/main.js',
	    ],
    ],


    /*
    |--------------------------------------------------------------------------
    | CMS branding
    |--------------------------------------------------------------------------
    */

    'favicon' => [
        'apple-icon-57x57' => 'asset?path=favicon/apple-icon-57x57.png',
        'apple-icon-60x60' => 'asset?path=favicon/apple-icon-60x60.png',
        'apple-icon-72x72' => 'asset?path=favicon/apple-icon-72x72.png',
        'apple-icon-76x76' => 'asset?path=favicon/apple-icon-76x76.png',
        'apple-icon-114x114' => 'asset?path=favicon/apple-icon-114x114.png',
        'apple-icon-120x120' => 'asset?path=favicon/apple-icon-120x120.png',
        'apple-icon-144x144' => 'asset?path=favicon/apple-icon-144x144.png',
        'apple-icon-152x152' => 'asset?path=favicon/apple-icon-152x152.png',
        'apple-icon-180x180' => 'asset?path=favicon/apple-icon-180x180.png',
        'android-icon-192x192' => 'asset?path=favicon/android-icon-192x192.png',
        'favicon-32x32' => 'asset?path=favicon/favicon-32x32.png',
        'favicon-96x96' => 'asset?path=favicon/favicon-96x96.png',
        'favicon-16x16' => 'asset?path=favicon/favicon-16x16.png',
        'manifest' => 'asset?path=favicon/manifest.json',
        'msapplication-TileColor' => '#ffffff',
        'msapplication-TileImage' => 'asset?path=favicon/ms-icon-144x144.png',
        'theme-color' => '#ffffff',
    ],
    'logo' => 'asset?path=images/logo.png',
    'loading' => 'asset?path=images/loading.png',
    'footer_slogan' => 'The Tree is the code',
    'footer_copyright' => 'V0.1 All Right Reserved Copyright &reg; <a href="http://hellotree.co/" target="_blank" class="font-weight-bold">HELLOTREE</a>',
    'tab_title' => 'HELLOTREE | CMS',
    'home_title' => 'HELLOTREE CMS',
    'home_content' => '',


    /*
    |--------------------------------------------------------------------------
    | CKEditor
    |--------------------------------------------------------------------------
    */

    'ckeditor' => [
        'colors' => [],
    ],


    /*
    |--------------------------------------------------------------------------
    | Storage
    |--------------------------------------------------------------------------
    */

    'use_original_name' => false,


    /*
    |--------------------------------------------------------------------------
    | Tinify
    |--------------------------------------------------------------------------
    */

    'tinify' => [
        'key' => null
    ],

];