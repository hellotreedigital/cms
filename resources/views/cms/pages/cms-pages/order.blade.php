@extends('cms/layouts/dashboard')

@section('breadcrumb')
	<ul class="breadcrumbs list-inline font-weight-bold text-uppercase m-0">
		<li><a href="{{ url(env('CMS_PREFIX', 'admin') . '/cms-pages') }}">cms pages</a></li>
		<li>order</li>
	</ul>
@endsection

@section('dashboard-content')

	<div class="card p-4 mx-2 mx-sm-5">
		@if (count($rows))
			<form method="post">
				@csrf
				<ul class="sortable list-inline m-0">
					@foreach($rows as $row)
						<li class="sortable-row d-block bg-white border px-3 py-2 mb-2">
							<input type="hidden" name="id[]" value="{{ $row['id'] }}">
							<input type="hidden" name="ht_pos[]" value="">
							<i class="text-center mr-2 fa {{ $row->icon }}" aria-hidden="true"></i> {{ $row->display_name_plural }}
						</li>
					@endforeach
				</ul>
				<div class="text-right">
					<button class="btn btn-sm btn-primary">Submit</button>
				</div>
			</form>
		@else
			<h5 class="text-center m-0 py-4">No record found for sorting</h5>
		@endif
	</div>

@endsection