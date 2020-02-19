@extends('cms::cms/layouts/dashboard')

@section('breadcrumb')
	<ul class="breadcrumbs list-inline font-weight-bold text-uppercase m-0">
		<li><a href="{{ url(config('hellotree.cms_route_prefix') . '/admins') }}">admins</a></li>
		<li><a href="{{ url(config('hellotree.cms_route_prefix') . '/admins/' . $row['id']) }}">{{ $row['id'] }}</a></li>
		<li>Edit</li>
	</ul>
@endsection

@section('dashboard-content')

	<form method="post" action="{{ url(config('hellotree.cms_route_prefix') . '/admins/' . $row['id']) }}" enctype="multipart/form-data">

		<div class="card p-4 mx-2 mx-sm-5">

			<p class="font-weight-bold text-uppercase mb-4">Edit admin #{{ $row['id'] }}</p>

			@if ($errors->any())
				<div class="alert alert-danger">
					@foreach ($errors->all() as $error)
						<p class="m-0">{{ $error }}</p>
					@endforeach
				</div>
			@endif

			@csrf

			@method('PUT')

			@include('cms::cms/components/form-fields/input', ['label' => 'Name', 'name' => 'name', 'type' => 'text', 'value' => old('name') ? old('name') : $row->name ])
			@include('cms::cms/components/form-fields/file', ['label' => 'Image', 'name' => 'image', 'value' => $row->image ])
			@include('cms::cms/components/form-fields/input', ['label' => 'Email', 'name' => 'email', 'type' => 'text', 'value' => old('email') ? old('email') : $row->email ])
			@include('cms::cms/components/form-fields/password-with-confirmation', ['label' => 'Password', 'name' => 'password' ])
			@include('cms::cms/components/form-fields/select', ['label' => 'Role', 'name' => 'admin_role_id', 'options' => $admin_roles, 'store_column' => 'id', 'display_column' => 'title', 'value' => old('role_id') ? old('role_id') : $row->admin_role_id ])

			<div class="text-right">
				<button type="submit" class="btn btn-sm btn-primary py-2 px-4">Submit</button>
			</div>

		</div>

	</form>

@endsection