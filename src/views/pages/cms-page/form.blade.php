@extends('cms::layouts/dashboard')

@section('breadcrumb')
	<ul class="breadcrumbs list-inline font-weight-bold text-uppercase m-0">
		<li><a href="{{ url(config('hellotree.cms_route_prefix') . '/' . $page['route'] . '') }}">{{ $page['display_name_plural'] }}</a></li>
		@if (isset($row))
			<li>{{ $row['id'] }}</li>
			<li>Edit</li>
		@else
			<li>Create</li>
		@endif
	</ul>
@endsection

@section('dashboard-content')

	<form method="post" enctype="multipart/form-data" action="{{ isset($row) ? url(config('hellotree.cms_route_prefix') . '/' . $page['route'] . '/' . $row['id']) : url(config('hellotree.cms_route_prefix') . '/' . $page['route'] . '') }}">

		<div class="card p-4 mx-2 mx-sm-5">
			<p class="font-weight-bold text-uppercase mb-4">{{ isset($row) ? 'Edit ' . $page['display_name'] . ' #' . $row['id'] : 'Add ' . $page['display_name'] }}</p>

			@if (isset($row))
				@method('put')
			@endif

			@if ($errors->any())
				<div class="alert alert-danger">
					@foreach ($errors->all() as $error)
						<p class="m-0">{{ $error }}</p>
					@endforeach
				</div>
			@endif

			@foreach($page_fields as $field)
				@include('cms::pages/cms-page/form-fields', ['locale' => null])
			@endforeach
			@if (count($page_translatable_fields))
				@foreach (config('translatable.locales') as $locale)
					@if (is_array($locale)) @continue @endif
					<div class="form-group">
						<label>{{ ucfirst($locale) }}</label>
						<div class="pl-3">
							@foreach($page_translatable_fields as $field)
								@include('cms::pages/cms-page/form-fields', compact('locale'))
							@endforeach
						</div>
					</div>
				@endforeach
			@endif

			<div class="text-right">
				@csrf
				<button type="submit" class="btn btn-sm btn-primary">Submit</button>
			</div>
		</div>

	</form>

@endsection