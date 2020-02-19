@extends('cms::cms/layouts/dashboard')

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
				@if ($field['form_field'] == 'textarea')
					@include('cms::cms//components/form-fields/textarea', [
						'label' => ucwords(str_replace('_', ' ', $field['name'])),
						'name' => $field['name'],
						'value' => isset($row[$field['name']]) ? $row[$field['name']] : '',
					])
				@elseif ($field['form_field'] == 'rich-textbox')
					@include('cms::cms//components/form-fields/rich-textbox', [
						'label' => ucwords(str_replace('_', ' ', $field['name'])),
						'name' => $field['name'],
						'value' => isset($row[$field['name']]) ? $row[$field['name']] : '',
					])
				@elseif ($field['form_field'] == 'select')
					@include('cms::cms//components/form-fields/select', [
						'label' => ucwords(str_replace(['_id', '_'], ['', ' '], $field['name'])),
						'name' => $field['name'],
						'options' => $extra_variables[$field['form_field_additionals_1']],
						'store_column' => 'id',
						'display_column' => $field['form_field_additionals_2'],
						'value' => isset($row[$field['name']]) ? $row[$field['name']] : '',
					])
				@elseif ($field['form_field'] == 'select multiple')
					@include('cms::cms//components/form-fields/select-multiple', [
						'label' => ucwords(str_replace(['_id', '_'], ['', ' '], $field['name'])),
						'name' => $field['name'],
						'options' => $extra_variables[$field['form_field_additionals_1']],
						'store_column' => 'id',
						'display_column' => $field['form_field_additionals_2'],
						'value' => isset($row[$field['name']]) ? $row[$field['name']] : '',
					])
				@elseif ($field['form_field'] == 'file')
					@include('cms::cms//components/form-fields/file', [
						'label' => ucwords(str_replace('_', ' ', $field['name'])),
						'name' => $field['name'],
						'value' => isset($row[$field['name']]) ? $row[$field['name']] : '',
					])
				@elseif ($field['form_field'] == 'image')
					@include('cms::cms//components/form-fields/image', [
						'label' => ucwords(str_replace('_', ' ', $field['name'])),
						'name' => $field['name'],
						'value' => isset($row[$field['name']]) ? $row[$field['name']] : '',
					])
				@elseif ($field['form_field'] == 'slug')
					@include('cms::cms//components/form-fields/input', [
						'label' => ucwords(str_replace('_', ' ', $field['name'])),
						'name' => $field['name'],
						'slug_origin' => $field['form_field_additionals_1'],
						'type' => 'text',
						'value' => isset($row[$field['name']]) ? $row[$field['name']] : '',
					])
				@elseif ($field['form_field'] == 'date')
					@include('cms::cms//components/form-fields/date', [
						'label' => ucwords(str_replace('_', ' ', $field['name'])),
						'name' => $field['name'],
						'value' => isset($row[$field['name']]) ? $row[$field['name']] : '',
					])
				@elseif ($field['form_field'] == 'time')
					@include('cms::cms//components/form-fields/time', [
						'label' => ucwords(str_replace('_', ' ', $field['name'])),
						'name' => $field['name'],
						'value' => isset($row[$field['name']]) ? $row[$field['name']] : '',
					])
				@elseif ($field['form_field'] == 'password')
					@include('cms::cms//components/form-fields/input', [
						'label' => ucwords(str_replace('_', ' ', $field['name'])),
						'name' => $field['name'],
						'type' => 'password',
						'value' => '',
					])
				@elseif ($field['form_field'] == 'password with confirmation')
					@include('cms::cms//components/form-fields/password-with-confirmation', [
						'label' => ucwords(str_replace('_', ' ', $field['name'])),
						'name' => $field['name'],
						'value' => '',
					])
				@elseif ($field['form_field'] == 'checkbox')
					@include('cms::cms//components/form-fields/checkbox', [
						'label' => ucwords(str_replace('_', ' ', $field['name'])),
						'name' => $field['name'],
						'checked' => isset($row[$field['name']]) ? $row[$field['name']] : '',
					])
				@elseif ($field['form_field'] == 'map coordinates')
					@include('cms::cms//components/form-fields/map', [
						'label' => ucwords(str_replace('_', ' ', $field['name'])),
						'name' => $field['name'],
						'value' => isset($row[$field['name']]) ? $row[$field['name']] : '',
					])
				@else
					@include('cms::cms//components/form-fields/input', [
						'label' => ucwords(str_replace('_', ' ', $field['name'])),
						'name' => $field['name'],
						'type' => 'text',
						'value' => isset($row[$field['name']]) ? $row[$field['name']] : '',
					])
				@endif
			@endforeach

			<div class="text-right">
				@csrf
				<button type="submit" class="btn btn-sm btn-primary">Submit</button>
			</div>
		</div>

	</form>

@endsection