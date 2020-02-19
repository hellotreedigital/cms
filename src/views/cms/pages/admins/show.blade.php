@extends('cms::cms/layouts/dashboard')

@section('breadcrumb')
	<ul class="breadcrumbs list-inline font-weight-bold text-uppercase m-0">
		<li><a href="{{ url(config('hellotree.cms_route_prefix') . '/admins') }}">admins</a></li>
		<li><a href="{{ url(config('hellotree.cms_route_prefix') . '/admins/' . $row['id']) }}">{{ $row['id'] }}</a></li>
		<li>Show</li>
	</ul>
@endsection

@section('dashboard-content')

	<div class="card p-4 mx-2 mx-sm-5">

		<div class="row">
			<div class="col-lg-6">
				<p class="font-weight-bold text-uppercase mb-4">Show admin #{{ $row['id'] }}</p>
			</div>
			<div class="col-lg-6 text-right">
				<div class="actions p-0">
					@if (request()->get('admin')['cms_pages']['admins']['permissions']['edit'])
						<a href="{{ url(config('hellotree.cms_route_prefix') . '/admins/' . $row['id'] . '/edit') }}" class="btn btn-sm btn-primary">Edit</a>
					@endif
					@if (request()->get('admin')['cms_pages']['admins']['permissions']['delete'])
						<form class="d-inline" onsubmit="return confirm('Are you sure?')" method="post" action="{{ url(config('hellotree.cms_route_prefix') . '/admins/' . $row['id']) }}">
							@csrf
							<input type="hidden" name="_method" value="DELETE">
							<button class="btn btn-danger btn-sm">Delete</button>
						</form>
					@endif
				</div>
			</div>
		</div>

		@include('cms::cms/components/show-fields/text', ['label' => 'Name', 'text' => $row['name']])
		@include('cms::cms/components/show-fields/image', ['label' => 'Image', 'image' => $row['image']])
		@include('cms::cms/components/show-fields/text', ['label' => 'Email', 'text' => $row['email']])
		@include('cms::cms/components/show-fields/text', ['label' => 'Role', 'text' => $row->role->title])

	</div>

@endsection