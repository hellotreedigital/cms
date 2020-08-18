@extends('cms::layouts/dashboard')

@section('breadcrumb')
	<ul class="breadcrumbs list-inline font-weight-bold text-uppercase m-0">
		<li>logs</li>
	</ul>
@endsection

@section('dashboard-content')

	<div class="card py-4 mx-2 mx-lg-5">
		<div class="datatable-wrapper">
			<table class="datatable">
				<thead>
					<tr>
						<th>#</th>
						<th>Admin</th>
						<th>CMS Page</th>
						<th>Record #</th>
						<th>Action</th>
						<th>Date</th>
					</tr>
				</thead>
				<tbody>
					@foreach($rows as $row)
						<tr>
							<td>{{ $row->id }}</td>
							<td>{{ $row->admin->email }}</td>
							<td>{{ $row->page->route }}</td>
							<td>{{ $row->record_id }}</td>
							<td>{{ $row->action }}</td>
							<td>{{ $row->created_at }}</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>

@endsection