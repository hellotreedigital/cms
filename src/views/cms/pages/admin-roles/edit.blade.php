@extends('cms::cms/layouts/dashboard')

@section('breadcrumb')
	<ul class="breadcrumbs list-inline font-weight-bold text-uppercase m-0">
		<li><a href="{{ url(config('hellotree.cms_route_prefix') . '/admin-roles') }}">admin roles</a></li>
		<li><a href="{{ url(config('hellotree.cms_route_prefix') . '/admin-roles/' . $row['id']) }}">{{ $row['id'] }}</a></li>
		<li>Edit</li>
	</ul>
@endsection

@section('dashboard-content')

	<form method="post" action="{{ url(config('hellotree.cms_route_prefix') . '/admin-roles/' . $row['id']) }}" enctype="multipart/form-data">

		<div class="card p-4 mx-2 mx-sm-5">

			<p class="font-weight-bold text-uppercase mb-4">Edit admin role #{{ $row['id'] }}</p>
			
			@if ($errors->any())
				<div class="alert alert-danger">
					@foreach ($errors->all() as $error)
						<p class="m-0">{{ $error }}</p>
					@endforeach
				</div>
			@endif
			
			@csrf

			@method('PUT')

			@include('cms::cms/components/form-fields/input', ['label' => 'Title', 'name' => 'title', 'type' => 'text', 'value' => old('title') ? old('title') : $row->title ])

			@foreach($cms_pages_permissions as $cms_page)
				@if ($cms_page['route'] == 'cms-pages') @continue @endif

				<div class="form-group">
					<label class="admin-role-main-label">{{ $cms_page['display_name_plural'] }}</label><br>
					@include('cms::cms/components/form-fields/checkbox', [ 'label' => 'Browse', 'inline_label' => true, 'name' => 'browse_' . $cms_page['id'], 'checked' => $cms_page['permissions']['browse'] ])
					@include('cms::cms/components/form-fields/checkbox', [ 'label' => 'Read', 'inline_label' => true, 'name' => 'read_' . $cms_page['id'], 'checked' => $cms_page['permissions']['read'] ])
					@include('cms::cms/components/form-fields/checkbox', [ 'label' => 'Edit', 'inline_label' => true, 'name' => 'edit_' . $cms_page['id'], 'checked' => $cms_page['permissions']['edit'] ])
					@include('cms::cms/components/form-fields/checkbox', [ 'label' => 'Add', 'inline_label' => true, 'name' => 'add_' . $cms_page['id'], 'checked' => $cms_page['permissions']['add'] ])
					@include('cms::cms/components/form-fields/checkbox', [ 'label' => 'Delete', 'inline_label' => true, 'name' => 'delete_' . $cms_page['id'], 'checked' => $cms_page['permissions']['delete'] ])
				</div>

			@endforeach
			
			<div class="text-right">
				<button type="submit" class="btn btn-sm btn-primary py-2 px-4">Submit</button>
			</div>

		</div>

	</form>

@endsection
