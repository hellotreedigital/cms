<!DOCTYPE html>
<html>
<head>
	<title>HELLOTREE | CMS</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- Favicon -->
	<link rel="apple-touch-icon" sizes="57x57" href="{{ url('asset?path=favicon//apple-icon-57x57.png') }}">
	<link rel="apple-touch-icon" sizes="60x60" href="{{ url('asset?path=favicon//apple-icon-60x60.png') }}">
	<link rel="apple-touch-icon" sizes="72x72" href="{{ url('asset?path=favicon//apple-icon-72x72.png') }}">
	<link rel="apple-touch-icon" sizes="76x76" href="{{ url('asset?path=favicon//apple-icon-76x76.png') }}">
	<link rel="apple-touch-icon" sizes="114x114" href="{{ url('asset?path=favicon//apple-icon-114x114.png') }}">
	<link rel="apple-touch-icon" sizes="120x120" href="{{ url('asset?path=favicon//apple-icon-120x120.png') }}">
	<link rel="apple-touch-icon" sizes="144x144" href="{{ url('asset?path=favicon//apple-icon-144x144.png') }}">
	<link rel="apple-touch-icon" sizes="152x152" href="{{ url('asset?path=favicon//apple-icon-152x152.png') }}">
	<link rel="apple-touch-icon" sizes="180x180" href="{{ url('asset?path=favicon//apple-icon-180x180.png') }}">
	<link rel="icon" type="image/png" sizes="192x192"  href="{{ url('asset?path=favicon//android-icon-192x192.png') }}">
	<link rel="icon" type="image/png" sizes="32x32" href="{{ url('asset?path=favicon//favicon-32x32.png') }}">
	<link rel="icon" type="image/png" sizes="96x96" href="{{ url('asset?path=favicon//favicon-96x96.png') }}">
	<link rel="icon" type="image/png" sizes="16x16" href="{{ url('asset?path=favicon//favicon-16x16.png') }}">
	<link rel="manifest" href="{{ url('asset?path=favicon//manifest.json') }}">
	<meta name="msapplication-TileColor" content="#ffffff">
	<meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
	<meta name="theme-color" content="#ffffff">

	<!-- Styles -->
	@foreach(config('hellotree.cms_assets.styles') as $path)
		<link rel="stylesheet" type="text/css" href="{{ url($path) }}">
	@endforeach

	@yield('styles')

</head>
<body class="m-0">

	<div id="loader">
		<img src="{{ url('asset?path=images/triangle.png') }}">
	</div>

	@yield('main-content')

	@foreach(config('hellotree.cms_assets.scripts') as $path)
		<script type="text/javascript" src="{{ url($path) }}"></script>
	@endforeach

	@yield('scripts')

</body>
</html>