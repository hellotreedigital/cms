<!DOCTYPE html>
<html>
<head>
	<title>HELLOTREE | CMS</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- Favicon -->
	<link rel="apple-touch-icon" sizes="57x57" href="{{ asset('cms/favicon//apple-icon-57x57.png') }}">
	<link rel="apple-touch-icon" sizes="60x60" href="{{ asset('cms/favicon//apple-icon-60x60.png') }}">
	<link rel="apple-touch-icon" sizes="72x72" href="{{ asset('cms/favicon//apple-icon-72x72.png') }}">
	<link rel="apple-touch-icon" sizes="76x76" href="{{ asset('cms/favicon//apple-icon-76x76.png') }}">
	<link rel="apple-touch-icon" sizes="114x114" href="{{ asset('cms/favicon//apple-icon-114x114.png') }}">
	<link rel="apple-touch-icon" sizes="120x120" href="{{ asset('cms/favicon//apple-icon-120x120.png') }}">
	<link rel="apple-touch-icon" sizes="144x144" href="{{ asset('cms/favicon//apple-icon-144x144.png') }}">
	<link rel="apple-touch-icon" sizes="152x152" href="{{ asset('cms/favicon//apple-icon-152x152.png') }}">
	<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('cms/favicon//apple-icon-180x180.png') }}">
	<link rel="icon" type="image/png" sizes="192x192"  href="{{ asset('cms/favicon//android-icon-192x192.png') }}">
	<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('cms/favicon//favicon-32x32.png') }}">
	<link rel="icon" type="image/png" sizes="96x96" href="{{ asset('cms/favicon//favicon-96x96.png') }}">
	<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('cms/favicon//favicon-16x16.png') }}">
	<link rel="manifest" href="{{ asset('cms/favicon//manifest.json') }}">
	<meta name="msapplication-TileColor" content="#ffffff">
	<meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
	<meta name="theme-color" content="#ffffff">

	<!-- Styles -->
	<link rel="stylesheet" type="text/css" href="{{ asset('cms/css/font-awesome/css/font-awesome.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('cms/css/bootstrap.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('cms/css/dataTables.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('cms/css/select2.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('cms/css/quill.snow.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('cms/css/main.min.css') }}">

	@yield('styles')

</head>
<body class="m-0">

	<div id="loader">
		<img src="{{ asset('cms/images/triangle.png') }}">
	</div>

	@yield('main-content')

	<script type="text/javascript" src="{{ asset('cms/js/jquery.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('cms/js/jquery-ui.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('cms/js/dataTables.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('cms/js/dataTables.buttons.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('cms/js/jszip.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('cms/js/pdfmake.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('cms/js/vfs_fonts.js') }}"></script>
	<script type="text/javascript" src="{{ asset('cms/js/buttons.html5.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('cms/js/select2.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('cms/js/quill.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('cms/js/quill-textarea.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('cms/js/main.js') }}"></script>

	@yield('scripts')

</body>
</html>