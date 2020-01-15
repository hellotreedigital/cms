@extends('cms::cms/layouts/dashboard')

@section('breadcrumb')
	<ul class="breadcrumbs list-inline font-weight-bold text-uppercase m-0">
		<li><a href="{{ url(config('hellotree.cms_route_prefix') . '/admin-roles') }}">admin roles</a></li>
		<li><a href="{{ url(config('hellotree.cms_route_prefix') . '/admin-roles/' . $row['id']) }}">{{ $row['id'] }}</a></li>
		<li>Show</li>
	</ul>
@endsection

@section('dashboard-content')

	<div class="card p-4 mx-2 mx-sm-5">

		<div class="row">
			<div class="col-lg-6">
				<p class="font-weight-bold text-uppercase mb-4">Show admin role #{{ $row['id'] }}</p>
			</div>
			<div class="col-lg-6 text-right">
				<div class="actions p-0">
					@if (request()->get('admin')['cms_pages']['admin-roles']['permissions']['edit'])
						<a href="{{ url(config('hellotree.cms_route_prefix') . '/admin-roles/' . $row['id'] . '/edit') }}" class="btn btn-sm btn-primary">Edit</a>
					@endif
					@if (request()->get('admin')['cms_pages']['admin-roles']['permissions']['delete'])
						<form class="d-inline" onsubmit="return confirm('Are you sure?')" method="post" action="{{ url(config('hellotree.cms_route_prefix') . '/admin-roles/' . $row['id']) }}">
							@csrf
							<input type="hidden" name="_method" value="DELETE">
							<button class="btn btn-danger btn-sm">Delete</button>
						</form>
					@endif
				</div>
			</div>
		</div>

		@include('cms::cms/components/show-fields/text', ['label' => 'Title', 'text' => $row['title']])
		
		<table>
			<thead>
				<tr>
					<th class="text-center">Page</th>
					<th class="text-center">Can Browse</th>
					<th class="text-center">Can Read</th>
					<th class="text-center">Can Edit</th>
					<th class="text-center">Can Add</th>
					<th class="text-center">Can Delete</th>
				</tr>
			</thead>
			<tbody>
				@foreach(request()->get('admin')_role_permissions as $permission)
					<tr>
						<td class="text-center">{{ $permission->cms_page->display_name }}</td>
						<td class="text-center">{{ $permission->browse ? 'true' : 'false' }}</td>
						<td class="text-center">{{ $permission->read ? 'true' : 'false' }}</td>
						<td class="text-center">{{ $permission->edit ? 'true' : 'false' }}</td>
						<td class="text-center">{{ $permission->add ? 'true' : 'false' }}</td>
						<td class="text-center">{{ $permission->delete ? 'true' : 'false' }}</td>
					</tr>
				@endforeach
			</tbody>
		</table>

	</div>

@endsection