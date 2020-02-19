@extends('cms::layouts/dashboard')

@section('dashboard-content')

	<div class="card p-4 mx-2 mx-sm-5">
		<p class="font-weight-bold text-uppercase mb-4">Edit profile</p>

		@if ($errors->any())
			<div class="alert alert-danger">
				@foreach ($errors->all() as $error)
					<p class="m-0">{{ $error }}</p>
				@endforeach
			</div>
		@endif

		<form method="post" enctype="multipart/form-data">

			@csrf

			@include('cms::components/form-fields/input', [
				'label' => 'Name',
				'name' => 'name',
				'type' => 'text',
				'value' => old('name') ? old('name') : request()->get('admin')['name']
			])
			@include('cms::components/form-fields/input', [
				'label' => 'Password',
				'name' => 'password',
				'type' => 'password',
				'value' => ''
			])
			@include('cms::components/form-fields/input', [
				'label' => 'Confirm Password',
				'name' => 'password_confirmation',
				'type' => 'password',
				'value' => ''
			])
			@include('cms::components/form-fields/image', [
				'label' => 'Image',
				'name' => 'image',
				'value' => request()->get('admin')['image']
			])

			<div class="text-right">
				<button type="submit" class="btn btn-sm btn-primary py-2 px-4">Submit</button>
			</div>
		</form>
	</div>

@endsection