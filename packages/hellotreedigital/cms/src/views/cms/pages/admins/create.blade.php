@extends('cms::cms/layouts/dashboard')

@section('breadcrumb')
	<ul class="breadcrumbs list-inline font-weight-bold text-uppercase m-0">
		<li><a href="{{ url(env('CMS_PREFIX', 'admin') . '/admins') }}">admins</a></li>
		<li>Create</li>
	</ul>
@endsection

@section('dashboard-content')

	<form method="post" action="{{ url(env('CMS_PREFIX', 'admin') . '/admins') }}" enctype="multipart/form-data">

		<div class="card p-4 mx-2 mx-sm-5">

			<p class="font-weight-bold text-uppercase mb-4">Add admin</p>
			
			@if ($errors->any())
				<div class="alert alert-danger">
					@foreach ($errors->all() as $error)
						<p class="m-0">{{ $error }}</p>
					@endforeach
				</div>
			@endif
			
			@csrf

			@include('cms::cms/components/form-fields/input', ['label' => 'Name', 'name' => 'name', 'type' => 'text', 'value' => old('name') ? old('name') : '' ])
			@include('cms::cms/components/form-fields/image', ['label' => 'Image', 'name' => 'image' ])
			@include('cms::cms/components/form-fields/input', ['label' => 'Email', 'name' => 'email', 'type' => 'text', 'value' => old('email') ? old('email') : '' ])
			@include('cms::cms/components/form-fields/password-with-confirmation', ['label' => 'Password', 'name' => 'password' ])
			@include('cms::cms/components/form-fields/select', ['label' => 'Role', 'name' => 'role_id', 'options' => $admin_roles, 'store_column' => 'id', 'display_column' => 'title' ])
			
			<div class="text-right">
				<button type="submit" class="btn btn-sm btn-primary py-2 px-4">Submit</button>
			</div>

		</div>

	</form>

@endsection