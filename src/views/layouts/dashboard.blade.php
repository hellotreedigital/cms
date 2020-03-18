@extends('cms::layouts/main')

@section('main-content')

<nav class="main py-3">
	<div class="header">
		<img class="px-3" src="{{ url('asset?path=images/logo.png') }}">
		<ul class="my-5 p-0">
			<li class="position-relative {{ Route::currentRouteName() == 'admin-home' ? 'active' : '' }}">
				<a class="d-block p-3" href="{{ route('admin-home') }}" title="Home">
					<i class="text-center mr-2 fa fa-home" aria-hidden="true"></i>
					Home
				</a>
			</li>
			@foreach(request()->get('admin')['cms_pages_grouped'] as $group)
				@if (!$group['icon'] && !$group['title'])
					@foreach($group['pages'] as $page)
						@if (!$page['display_name_plural']) @continue @endif
						<li class="position-relative {{ request()->is(config('hellotree.cms_route_prefix') . '/' . $page['route']) || request()->is(config('hellotree.cms_route_prefix') . '/' . $page['route'] . '/*') ? 'active' : '' }}">
							<a class="d-block p-3" href="{{ url(config('hellotree.cms_route_prefix') . '/' . $page['route']) }}" title="{{ $page['display_name_plural'] }}">
								<i class="text-center mr-2 fa {{ $page['icon'] }}" aria-hidden="true"></i>
								{{ $page['display_name_plural'] }}
							</a>
						</li>
					@endforeach
				@else
					<li class="position-relative menu-dropdown-wrapper @foreach($group['pages'] as $page){{ request()->is(config('hellotree.cms_route_prefix') . '/' . $page['route'] . '*') ? 'active' : '' }}@endforeach">
						<a class="d-block p-3" title="{{ $group['title'] }}">
							<i class="text-center mr-2 fa {{ $group['icon'] }}" aria-hidden="true"></i>
							{{ $group['title'] }}
							<i class="fa  fa-caret-down" aria-hidden="true"></i>
						</a>
						<div class="menu-dropdown pl-5">
							@foreach($group['pages'] as $page)
								@if (!$page['display_name_plural']) @continue @endif
								<a class="px-3 py-1" href="{{ url(config('hellotree.cms_route_prefix') . '/' . $page['route']) }}" title="{{ $page['display_name_plural'] }}">{{ $page['display_name_plural'] }}</a>
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
					<span class="font-weight-bold mr-3">{{ request()->get('admin')['name'] }}</span>
					<img src="{{ request()->get('admin')['image'] ? asset(request()->get('admin')['image']) : url('asset?path=images/default.png') }}">
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
		@if (Session::has('success') || Session::has('error'))
			<div class="toast px-5 py-3">
				{{ Session::get('success') }}
				{{ Session::get('error') }}
			</div>
		@endif
	</header>

	<div id="dashboard-content">
		@yield('dashboard-content')
	</div>

	<div id="content-overlay"></div>

	@include('cms::components/footer')

</div>

@endsection