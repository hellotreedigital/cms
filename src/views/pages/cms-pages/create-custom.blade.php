@extends('cms::layouts/dashboard')

@section('breadcrumb')
	<ul class="breadcrumbs list-inline font-weight-bold text-uppercase m-0">
		<li><a href="{{ url(config('hellotree.cms_route_prefix') . '/cms-pages') }}">CMS Pages</a></li>
		@if (isset($cms_page))
			<li>{{ $cms_page['id'] }}</li>
			<li>Edit Custom</li>
		@else
			<li>Create Custom</li>
		@endif
	</ul>
@endsection

@section('dashboard-content')

	<form method="post" action="{{ isset($cms_page) ? url(config('hellotree.cms_route_prefix') . '/cms-pages/custom/' . $cms_page['id']) : url(config('hellotree.cms_route_prefix') . '/cms-pages/custom') }}">

		<div class="card p-4 mx-2 mx-sm-5">
			<p class="font-weight-bold text-uppercase mb-4">{{ isset($cms_page) ? 'Edit Custom CMS page #' . $cms_page['id'] : 'Add Custom CMS page' }}</p>

			@if (isset($cms_page))
				@method('put')
			@endif

			@if ($errors->any())
				<div class="alert alert-danger">
					@foreach ($errors->all() as $error)
						<p class="m-0">{{ $error }}</p>
					@endforeach
				</div>
			@endif

			@include('cms::components/form-fields/input', [
				'label' => 'Display name plural',
				'name' => 'display_name_plural',
				'type' => 'text',
				'value' => old('display_name_plural') ? old('display_name_plural') : (isset($cms_page) ? $cms_page['display_name_plural'] : '')
			])
			@include('cms::components/form-fields/input', [
				'label' => 'Route',
				'name' => 'route',
				'type' => 'text',
				'slug_origin' => 'display_name_plural',
				'value' => old('route') ? old('route') : (isset($cms_page) ? $cms_page['route'] : '')
			])
			@include('cms::components/form-fields/input', [
				'label' => 'Icon <a href="' . url(config('hellotree.cms_route_prefix') . '/cms-pages/icons') . '"><i class="fa fa-font-awesome ml-1" aria-hidden="true"></i></a>',
				'name' => 'icon',
				'type' => 'text',
				'value' => old('icon') ? old('icon') : (isset($cms_page) ? $cms_page['icon'] : '')
			])

			<div class="text-right">
				@csrf
				<button type="submit" class="btn btn-sm btn-primary">Submit</button>
			</div>

		</div>

	</form>

@endsection