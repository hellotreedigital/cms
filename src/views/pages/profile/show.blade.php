@extends('cms::layouts/dashboard')

@section('dashboard-content')

	<div class="card p-4 mx-2 mx-sm-5">

		<div class="row">
			<div class="col-lg-6">
				<p class="font-weight-bold text-uppercase mb-4">profile</p>
			</div>
			<div class="col-lg-6 text-right">
				<div class="actions p-0">
					<a href="{{ route('admin-profile-edit') }}" class="btn btn-sm btn-primary">Edit</a>
				</div>
			</div>
		</div>

		@include('cms::components/show-fields/text', ['label' => 'Name', 'text' => request()->get('admin')['name']])

		@include('cms::components/show-fields/image', ['label' => 'Image', 'image' => request()->get('admin')['image'] ? Storage::url(request()->get('admin')['image']) : url('asset?path=images/default.png')])

	</div>

@endsection