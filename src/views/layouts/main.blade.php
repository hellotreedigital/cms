<!DOCTYPE html>
<html>
<head>
	<title>{{ config('hellotree.tab_title') }}</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Favicon -->
	<link rel="apple-touch-icon" sizes="57x57" href="{{ url(config('hellotree.favicon.apple-icon-57x57')) }}">
	<link rel="apple-touch-icon" sizes="60x60" href="{{ url(config('hellotree.favicon.apple-icon-60x60')) }}">
	<link rel="apple-touch-icon" sizes="72x72" href="{{ url(config('hellotree.favicon.apple-icon-72x72')) }}">
	<link rel="apple-touch-icon" sizes="76x76" href="{{ url(config('hellotree.favicon.apple-icon-76x76')) }}">
	<link rel="apple-touch-icon" sizes="114x114" href="{{ url(config('hellotree.favicon.apple-icon-114x114')) }}">
	<link rel="apple-touch-icon" sizes="120x120" href="{{ url(config('hellotree.favicon.apple-icon-120x120')) }}">
	<link rel="apple-touch-icon" sizes="144x144" href="{{ url(config('hellotree.favicon.apple-icon-144x144')) }}">
	<link rel="apple-touch-icon" sizes="152x152" href="{{ url(config('hellotree.favicon.apple-icon-152x152')) }}">
	<link rel="apple-touch-icon" sizes="180x180" href="{{ url(config('hellotree.favicon.apple-icon-180x180')) }}">
	<link rel="icon" type="image/png" sizes="192x192"  href="{{ url(config('hellotree.favicon.android-icon-192x192')) }}">
	<link rel="icon" type="image/png" sizes="32x32" href="{{ url(config('hellotree.favicon.favicon-32x32')) }}">
	<link rel="icon" type="image/png" sizes="96x96" href="{{ url(config('hellotree.favicon.favicon-96x96')) }}">
	<link rel="icon" type="image/png" sizes="16x16" href="{{ url(config('hellotree.favicon.favicon-16x16')) }}">
	<link rel="manifest" href="{{ url(config('hellotree.favicon.manifest')) }}">
    <meta name="msapplication-TileColor" content="{{ config('hellotree.favicon.msapplication-TileColor') }}">
	<meta name="msapplication-TileImage" content="{{ url(config('hellotree.favicon.msapplication-TileImage')) }}">
    <meta name="theme-color" content="{{ config('hellotree.favicon.theme-color') }}">

	<!-- Styles -->
	@foreach(config('hellotree.cms_assets.styles') as $path)
		<link rel="stylesheet" type="text/css" href="{{ url($path) }}">
	@endforeach

	@yield('styles')

</head>
<body class="m-0">

	<div id="loader">
        @if (config('hellotree.loading'))
            <img src="{{ url(config('hellotree.loading')) }}">
        @endif
	</div>

	@yield('main-content')

	@foreach(config('hellotree.cms_assets.scripts') as $path)
		<script type="text/javascript" src="{{ url($path) }}"></script>
	@endforeach

	@yield('scripts')

</body>
</html>