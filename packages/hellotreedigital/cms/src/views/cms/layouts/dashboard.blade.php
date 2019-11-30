@extends('cms::cms/layouts/main')

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
			@foreach(session('admin')['cms_pages_grouped'] as $group)
				@if (!$group['icon'] && !$group['title'])
					@foreach($group['pages'] as$page)
						<li class="position-relative {{ request()->is('admin/' . $page['route']) || request()->is('admin/' . $page['route'] . '/*') ? 'active' : '' }}">
							<a class="d-block p-3" href="{{ url('admin/' . $page['route']) }}" title="{{ $page['display_name_plural'] }}">
								<i class="text-center mr-2 fa {{ $page['icon'] }}" aria-hidden="true"></i>
								{{ $page['display_name_plural'] }}
							</a>
						</li>
					@endforeach
				@else
					<li class="position-relative menu-dropdown-wrapper @foreach($group['pages'] as $page){{ request()->is('admin/' . $page['route'] . '*') ? 'active' : '' }}@endforeach">
						<a class="d-block p-3" title="{{ $group['title'] }}">
							<i class="text-center mr-2 fa {{ $group['icon'] }}" aria-hidden="true"></i>
							{{ $group['title'] }}
							<i class="fa  fa-caret-down" aria-hidden="true"></i>
						</a>
						<div class="menu-dropdown pl-5">
							@foreach($group['pages'] as $page)
								<a class="px-3 py-1" href="{{ url('admin/' . $page['route']) }}" title="{{ $page['display_name_plural'] }}">{{ $page['display_name_plural'] }}</a>
							@endforeach
						</div>
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

	@include('cms::cms/components/footer')

</div>

@endsection