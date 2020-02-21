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
			<p class="font-weight-bold text-uppercase mb-4">Instructions</p>
			<div class="form-group m-0">
				<label class="m-0">
					<ul>
						<li>Display name plural is the name you see on the menu and browse pages.</li>
						<li>The route is used to redirect the user to its custom route from the menu</li>
						<li>The icon is the class of a font-awesome icon, you will see the icon in the menu <a href="{{ url(config('hellotree.cms_route_prefix') . '/cms-pages/icons') }}" target="_blank" class="text-secondary">click here to see all the icons</a>.</li>
						<li>The CMS will create only a menu item. The developer will have to create the custom route in the cms.php file, controller, model and view.</li>
						<li>Every custom page should be added here, even if it's hidden from the menu. If the page is not added here, it won't be accessible by admins because it cannot be added to the permissions</li>
						<li>If you wish to create a page without showing it on the menu, leave the route field empty.</li>
						<li>
							Follow the below steps for the page permissions:
							<ul>
								<li>Method post: create</li>
								<li>Method delete: delete</li>
								<li>Method put: edit</li>
								<li>
									Method get:
									<ul>
										<li>/route_name: browse page</li>
										<li>/route_name/create: create page</li>
										<li>/route_name/order: order page</li>
										<li>/route_name/{var}/edit: edit page</li>
										<li>Everything else starting with `/route_name`: read page</li>
									</ul>
								</li>
							</ul>
						</li>
					</ul>
				</label>
			</div>
		</div>

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
				'value' => old('display_name_plural') ? old('display_name_plural') : (isset($cms_page) ? $cms_page['display_name_plural'] : ''),
				'locale' => null
			])
			@include('cms::components/form-fields/input', [
				'label' => 'Route',
				'name' => 'route',
				'type' => 'text',
				'slug_origin' => 'display_name_plural',
				'value' => old('route') ? old('route') : (isset($cms_page) ? $cms_page['route'] : ''),
				'locale' => null
			])
			@include('cms::components/form-fields/input', [
				'label' => 'Icon',
				'name' => 'icon',
				'type' => 'text',
				'value' => old('icon') ? old('icon') : (isset($cms_page) ? $cms_page['icon'] : ''),
				'locale' => null
			])

			<div class="text-right">
				@csrf
				<button type="submit" class="btn btn-sm btn-primary">Submit</button>
			</div>

		</div>

	</form>

@endsection