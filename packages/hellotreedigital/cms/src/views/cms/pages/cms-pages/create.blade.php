@extends('cms::cms/layouts/dashboard')

@section('breadcrumb')
	<ul class="breadcrumbs list-inline font-weight-bold text-uppercase m-0">
		<li><a href="{{ url(env('CMS_PREFIX', 'admin') . '/cms-pages') }}">CMS Pages</a></li>
		@if (isset($cms_page))
			<li>{{ $cms_page['id'] }}</li>
			<li>Edit</li>
		@else
			<li>Create</li>
		@endif
	</ul>
@endsection

@section('dashboard-content')

	<form method="post" action="{{ isset($cms_page) ? url(env('CMS_PREFIX', 'admin') . '/cms-pages/' . $cms_page['id']) : url(env('CMS_PREFIX', 'admin') . '/cms-pages') }}">

		<div class="card p-4 mx-2 mx-sm-5">
			<p class="font-weight-bold text-uppercase mb-4">{{ isset($cms_page) ? 'Edit CMS page #' . $cms_page['id'] : 'Add CMS page' }}</p>
			
			@if (isset($cms_page))
				@method('put')
			@endif

			@if ($errors->any())
				<div class="alert alert-danger">
					@foreach ($errors->all() as $error)
						<p class="m-0">{{ $error }}</p>
					@endforeach
				</div>
			@endif

			@include('cms::cms/components/form-fields/input', [
				'label' => 'Database table',
				'name' => 'database_table',
				'type' => 'text',
				'value' => old('database_table') ? old('database_table') : (isset($cms_page) ? $cms_page['database_table'] : '')
			])
			@include('cms::cms/components/form-fields/input', [
				'label' => 'Display name',
				'name' => 'display_name',
				'type' => 'text',
				'value' => old('display_name') ? old('display_name') : (isset($cms_page) ? $cms_page['display_name'] : '')
			])
			@include('cms::cms/components/form-fields/input', [
				'label' => 'Display name plural',
				'name' => 'display_name_plural',
				'type' => 'text',
				'value' => old('display_name_plural') ? old('display_name_plural') : (isset($cms_page) ? $cms_page['display_name_plural'] : '')
			])
			@include('cms::cms/components/form-fields/input', [
				'label' => 'Model',
				'name' => 'model_name',
				'type' => 'text',
				'value' => old('model_name') ? old('model_name') : (isset($cms_page) ? $cms_page['model_name'] : '')
			])
			@include('cms::cms/components/form-fields/input', [
				'label' => 'Controller',
				'name' => 'controller_name',
				'type' => 'text',
				'value' => old('controller_name') ? old('controller_name') : (isset($cms_page) ? $cms_page['controller_name'] : '')
			])
			@include('cms::cms/components/form-fields/input', [
				'label' => 'Icon <a href="' . url(env('CMS_PREFIX', 'admin') . '/cms-pages/icons') . '"><i class="fa fa-font-awesome ml-1" aria-hidden="true"></i></a>',
				'name' => 'icon',
				'type' => 'text',
				'value' => old('icon') ? old('icon') : (isset($cms_page) ? $cms_page['icon'] : '')
			])
			@include('cms::cms/components/form-fields/select', [
				'label' => 'Page type',
				'name' => 'page_type',
				'value' => old('page_type') ? old('page_type') : (isset($cms_page) ? $cms_page['page_type'] : 'regular'),
				'store_column' => 'store',
				'display_column' => 'display',
				'options' => [
					[
						'display' => 'Regular page',
						'store' => 'regular',
					],
					[
						'display' => 'Show page',
						'store' => 'show',
					],
					[
						'display' => 'Fixed records page',
						'store' => 'fixed',
					],
					[
						'display' => 'Single record page',
						'store' => 'single',
					],
				],
			])
			@include('cms::cms/components/form-fields/input', [
				'label' => 'Order display column (Should be added in the below table)',
				'name' => 'order_display',
				'type' => 'text',
				'value' => old('order_display') ? old('order_display') : (isset($cms_page) ? $cms_page['order_display'] : '')
			])
			@include('cms::cms/components/form-fields/textarea', [
				'label' => 'Notes',
				'name' => 'notes',
				'value' => old('notes') ? old('notes') : (isset($cms_page) ? $cms_page['notes'] : '')
			])

		</div>

		<div class="card py-4 mx-2 mx-sm-5">
			<div class="px-4 pb-4">
				<button class="btn btn-sm btn-primary px-5" id="add" type="button">Add</button>
			</div>
			
			<table class="fields table">
				<thead>
					<tr>
						<th class="text-center">NAME</th>
						<th class="text-center">Migration Type</th>
						<th class="text-center">Form Field</th>
						<th class="text-center">Nullable</th>
						<th class="text-center">Unique</th>
						<th class="text-center">Remove</th>
					</tr>
				</thead>
				<tbody class="sortable">
					@if (old('name'))
						@foreach(old('name') as $field_key => $field)
							@include('cms::cms/pages/cms-pages/table-field')
						@endforeach
					@elseif (isset($cms_page))
						@php
						$fields = json_decode($cms_page['fields'], TRUE);
						@endphp
						@foreach($fields as $field_key => $field)
							@include('cms::cms/pages/cms-pages/table-field')
						@endforeach
					@else
						@include('cms::cms/pages/cms-pages/table-field')
					@endif
				</tbody>
			</table>

			@if (isset($cms_page))
				<hr>
				<div class="px-4">
					<p class="font-weight-bold">Edit:</p>
					@include('cms::cms/components/form-fields/checkbox', [
						'label' => 'Migration (Database rows will be removed)',
						'name' => 'edit_migration',
						'value' => old('edit_migration') ? old('edit_migration') : '',
						'inline_label' => true,
					])
					@include('cms::cms/components/form-fields/checkbox', [
						'label' => 'Model',
						'name' => 'edit_model',
						'value' => old('edit_model') ? old('edit_model') : '',
						'inline_label' => true,
					])
					@include('cms::cms/components/form-fields/checkbox', [
						'label' => 'Controller',
						'name' => 'edit_controller',
						'value' => old('edit_controller') ? old('edit_controller') : '',
						'checked' => true,
						'inline_label' => true,
					])
					@include('cms::cms/components/form-fields/checkbox', [
						'label' => 'Browse view',
						'name' => 'edit_index_view',
						'value' => old('edit_index_view') ? old('edit_index_view') : '',
						'checked' => true,
						'inline_label' => true,
					])
					@include('cms::cms/components/form-fields/checkbox', [
						'label' => 'Create view',
						'name' => 'edit_create_view',
						'value' => old('edit_create_view') ? old('edit_create_view') : '',
						'checked' => true,
						'inline_label' => true,
					])
					@include('cms::cms/components/form-fields/checkbox', [
						'label' => 'Edit view',
						'name' => 'edit_edit_view',
						'value' => old('edit_edit_view') ? old('edit_edit_view') : '',
						'checked' => true,
						'inline_label' => true,
					])
					@include('cms::cms/components/form-fields/checkbox', [
						'label' => 'Show view',
						'name' => 'edit_show_view',
						'value' => old('edit_show_view') ? old('edit_show_view') : '',
						'checked' => true,
						'inline_label' => true,
					])
					@include('cms::cms/components/form-fields/checkbox', [
						'label' => 'Order view',
						'name' => 'edit_order_view',
						'value' => old('edit_order_view') ? old('edit_order_view') : '',
						'checked' => true,
						'inline_label' => true,
					])
					@include('cms::cms/components/form-fields/checkbox', [
						'label' => 'Route',
						'name' => 'edit_route',
						'value' => old('edit_route') ? old('edit_route') : '',
						'checked' => false,
						'inline_label' => true,
					])
				</div>
			@endif

			<div class="px-4 text-right">
				@csrf
				<button type="submit" class="btn btn-sm btn-primary">Submit</button>
			</div>
		</div>

	</form>

@endsection

@section('scripts')
	<script>
		var field_html = $('.field').last().html();

		$('input[name="database_table"]').on("keyup", function () {
			var v = $(this).val();
			$('input[name="display_name"]').val(displayName(v));
			$('input[name="display_name_plural"]').val(displayNamePlural(v));
			$('input[name="model_name"]').val(modelName(v));
			$('input[name="controller_name"]').val(controllerName(v));
		});

		$(document).on('change', '[name="form_field[]"]', function(){
			var select = $(this);
			var additional_field = select.closest('td').find('.form-field-additionals');
			var additional_input_1 = additional_field.find('input[name="form_field_additionals_1[]"');
			var additional_input_2 = additional_field.find('input[name="form_field_additionals_2[]"');

			additional_input_1.prop('required', false);
			additional_input_2.prop('required', false);


			additional_field.slideUp(function(){
				additional_input_1.hide();
				additional_input_2.hide();

				if (select.val() == 'slug') {
					additional_input_1.prop('required', true);
					additional_input_1.attr('placeholder', 'Slug origin name');

					additional_input_2.prop('required', true);
					additional_input_2.attr('placeholder', 'Editable');
					additional_input_2.attr('min', '0');
					additional_input_2.attr('max', '1');
					additional_input_2.attr('type', 'number');

					additional_input_1.val('');
					additional_input_2.val('');

					additional_input_1.show();
					additional_input_2.show();

					additional_field.slideDown();
				} else if (select.val() == 'select') {
					additional_input_1.prop('required', true);
					additional_input_1.attr('placeholder', 'Database table');

					additional_input_2.prop('required', true);
					additional_input_2.attr('placeholder', 'Display column');
					additional_input_2.attr('type', 'text');

					additional_input_1.val('');
					additional_input_2.val('');

					additional_input_1.show();
					additional_input_2.show();

					additional_field.slideDown();
				}
			});
		});

		$('#add').on('click', function () {
			$('.fields').append('<tr class="field">' + field_html + '</tr>');
			$('.field:last').find('[name="name[]"]').val('');
			$('.field:last').find('select').val('');
			$('.field:last').find('[name="form_field[]"]').next('.form-field-additionals').hide();
			$('.field:last').find('[type="number"]').val(0);
		});


		function ucwords(str){
			return str.replace(/(\b)([a-zA-Z])/g,
				function (firstLetter){
					return firstLetter.toUpperCase();
				});
		}

		function displayNamePlural(v) {
			v = v.replace(/_/g, ' ');
			v = ucwords(v);
			return v;
		}

		function displayName(v) {
			v = displayNamePlural(v);
			if (v.substr(-3) == 'ies') v = v.slice(0, -3) + 'y';
			else 
				if (v.substr(-1) == 's') v = v.slice(0, -1);
			return v;
		}

		function modelNamePlural(v) {
			v = v.replace(/_/g, ' ');
			v = ucwords(v);
			v = v.replace(/ /g, '');
			return v;
		}

		function modelName(v) {
			v = modelNamePlural(v);
			if (v.substr(-3) == 'ies') v = v.slice(0, -3) + 'y';
			else 
				if (v.substr(-1) == 's') v = v.slice(0, -1);
			return v;
		}

		function controllerName(v) {
			v = (v != '') ? modelNamePlural(v) + 'Controller' : '';
			return v;
		}

		function removeField(btn) {
			$(btn).closest('.field').remove();
		}
	</script>
@endsection