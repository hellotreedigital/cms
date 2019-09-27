@extends('cms/layouts/main')

@section('main-content')
	
<nav class="py-3">
	<div class="header">
		<img class="px-3" src="{{ asset('cms/images/logo.png') }}">
		<ul class="my-5 p-0">
			<li class="position-relative {{ Route::currentRouteName() == 'admin-home' ? 'active' : '' }}">
				<a class="d-block p-3" href="{{ route('admin-home') }}" title="Home">
					<i class="text-center mr-2 fa fa-home" aria-hidden="true"></i>
					Home
				</a>
			</li>
			@foreach(session('admin')['cms_pages'] as $page)
				@if ($page['permissions']['browse'])
					<li class="position-relative {{ request()->is('admin/' . $page['route'] . '*') ? 'active' : '' }}">
						<a class="d-block p-3" href="{{ url('admin/' . $page['route']) }}" title="{{ $page['display_name_plural'] }}">
							<i class="text-center mr-2 fa {{ $page['icon'] }}" aria-hidden="true"></i>
							{{ $page['display_name_plural'] }}
						</a>
					</li>
				@endif
			@endforeach
		</ul>
	</div>
</nav>

<div id="content">

	<header class="position-relative py-5 px-3 px-sm-5">
		<div class="row align-items-center">
			<div class="col-10 col-lg-6">
				@yield('breadcrumb')
			</div>
			<div class="col-2 col-lg-6 text-right">
				<div class="user-info d-none d-lg-block">
					<span class="font-weight-bold mr-3">{{ session('admin')['name'] }}</span>
					<img src="{{ session('admin')['image'] ? asset(session('admin')['image']) : asset('cms/images/default.png') }}">
					<ul class="list-group text-left">
						<li class="list-group-item py-2 px-4">
							<a href="{{ route('admin-profile') }}">
								<i class="fa fa-user mr-2" aria-hidden="true"></i>
								My Profile
							</a>
						</li>
						<li class="list-group-item py-2 px-4">
							<a href="{{ route('admin-logout') }}">
								<i class="fa fa-sign-out mr-2" aria-hidden="true"></i>
								Logout
							</a>
						</li>
					</ul>
				</div>
				<div id="burger" class="d-inline-block d-lg-none">
					<label></label>
					<label></label>
					<label></label>
				</div>
			</div>
		</div>
		<div class="toast {{ Session::has('success') || Session::has('error') ? 'active' : '' }} px-5 py-3">
			{{ Session::get('success') }}
			{{ Session::get('error') }}
		</div>
	</header>

	<div id="dashboard-content">
		@yield('dashboard-content')
	</div>

	<div id="content-overlay"></div>

	<footer>
		<div class="py-4 px-3 px-sm-5">
			<div class="row">
				<div class="col-md-6 mb-1 mb-md-0 text-center text-md-left">
					Thank You for Choosing Hellotree <3
				</div>
				<div class="col-md-6 text-center text-md-right">
					Copyright &copy; {{ date('Y') }} <a href="http://hellotree.co/" target="_blank" class="font-weight-bold">HELLOTREE</a>
				</div>
			</div>
		</div>
	</footer>

</div>

@endsection