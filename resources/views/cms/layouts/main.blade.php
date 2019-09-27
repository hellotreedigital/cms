<!DOCTYPE html>
<html>
<head>
	<title>HELLOTREE | CMS</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="{{ asset('font-awesome/css/font-awesome.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('cms/css/bootstrap.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('datatables/dataTables.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('select2/select2.min.css') }}">
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
	<script type="text/javascript" src="{{ asset('datatables/dataTables.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('select2/select2.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('cms/js/quill.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('cms/js/quill-textarea.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('cms/js/main.js') }}"></script>

	@yield('scripts')

</body>
</html>