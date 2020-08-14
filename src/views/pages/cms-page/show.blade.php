@extends('cms::layouts/dashboard')

@section('breadcrumb')
	<ul class="breadcrumbs list-inline font-weight-bold text-uppercase m-0">
		<li><a href="{{ url(config('hellotree.cms_route_prefix') . '/' . $page['route']) }}">{{ $page['display_name_plural'] }}</a></li>
		<li><a href="{{ url(config('hellotree.cms_route_prefix') . '/' . $page['route'] . '/' . $row['id']) }}">{{ $row['id'] }}</a></li>
		<li>Show</li>
	</ul>
@endsection

@section('dashboard-content')

	<div class="card p-4 mx-2 mx-sm-5">

		<div class="row">
			<div class="col-lg-6">
				<p class="font-weight-bold text-uppercase mb-4">Show {{ $page['display_name'] }} #{{ $row['id'] }}</p>
			</div>
			<div class="col-lg-6 text-right">
				<div class="actions p-0">
					@if ($page['edit'])
						@if (request()->get('admin')['cms_pages'][$page['route']]['permissions']['edit'])
							<a href="{{ url(config('hellotree.cms_route_prefix') . '/' . $page['route'] . '/' . $row['id'] . '/edit') }}" class="btn btn-sm btn-primary">Edit</a>
						@endif
					@endif
					@if ($page['delete'])
						@if (request()->get('admin')['cms_pages'][$page['route']]['permissions']['delete'])
							<form class="d-inline" onsubmit="return confirm('Are you sure?')" method="post" action="{{ url(config('hellotree.cms_route_prefix') . '/' . $page['route'] . '/' . $row['id']) }}">
								@csrf
								<input type="hidden" name="_method" value="DELETE">
								<button class="btn btn-danger btn-sm">Delete</button>
							</form>
						@endif
					@endif
				</div>
			</div>
		</div>

		@foreach ($page_fields as $field)
			@if ($field['form_field'] == 'password' || $field['form_field'] == 'password with confirmation') @continue

			@elseif ($field['form_field'] == 'image')
				@include('cms::/components/show-fields/image', ['label' => ucwords(str_replace('_', ' ', $field['name'])), 'image' => $row[$field['name']] ])
			@elseif ($field['form_field'] == 'file')
				@include('cms::/components/show-fields/file', ['label' => ucwords(str_replace('_', ' ', $field['name'])), 'file' => $row[$field['name']] ])
			@elseif ($field['form_field'] == 'files')
				@include('cms::/components/show-fields/files', ['label' => ucwords(str_replace('_', ' ', $field['name'])), 'files' => $row[$field['name']] ])
			@elseif ($field['form_field'] == 'select')
				@include('cms::/components/show-fields/text', ['label' => ucwords(str_replace(['_id', '_'], ['', ' '], $field['name'])), 'text' => $row[str_replace('_id', '', $field['name'])][$field['form_field_additionals_2']] ])
			@elseif ($field['form_field'] == 'select multiple')
				@include('cms::/components/show-fields/text-multiple', ['label' => ucwords(str_replace(['_id', '_'], ['', ' '], $field['name'])), 'texts' => $row[$field['name']], 'display_column' => $field['form_field_additionals_2'] ])
			@elseif ($field['form_field'] == 'checkbox')
				@include('cms::/components/show-fields/boolean', ['label' => ucwords(str_replace('_', ' ', $field['name'])), 'value' => $row[$field['name']] ])
			@elseif ($field['form_field'] == 'map coordinates')
				@include('cms::/components/show-fields/map', ['label' => ucwords(str_replace('_', ' ', $field['name'])), 'name' => $field['name'], 'value' => $row[$field['name']] ])
			@else
				@include('cms::/components/show-fields/text', ['label' => ucwords(str_replace('_', ' ', $field['name'])), 'text' => $row[$field['name']] ])
			@endif
		@endforeach

		@foreach ($translatable_fields as $field)

		@endforeach

	</div>

@endsection