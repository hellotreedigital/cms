@php
	if ($field_type) {
		if (isset($field_key) && isset(old('translatable_name')[$field_key])) {
			$row_field_old_name = isset(old('translatable_old_name')[$field_key]) && old('translatable_old_name')[$field_key] ? old('translatable_old_name')[$field_key] : '';
			$row_field_name = isset(old('translatable_name')[$field_key]) && old('translatable_name')[$field_key] ? old('translatable_name')[$field_key] : '';
			$row_field_migration_type = isset(old('translatable_migration_type')[$field_key]) && old('translatable_migration_type')[$field_key] ? old('translatable_migration_type')[$field_key] : '';
			$row_field_form_field = isset(old('translatable_form_field')[$field_key]) && old('translatable_form_field')[$field_key] ? old('translatable_form_field')[$field_key] : '';
			$row_field_old_form_field_additionals_1 = isset(old('translatable_old_form_field_additionals_1')[$field_key]) && old('translatable_old_form_field_additionals_1')[$field_key] ? old('translatable_old_form_field_additionals_1')[$field_key] : '';
			$row_field_form_field_additionals_1 = isset(old('translatable_form_field_additionals_1')[$field_key]) && old('translatable_form_field_additionals_1')[$field_key] ? old('translatable_form_field_additionals_1')[$field_key] : '';
			$row_field_form_field_additionals_2 = isset(old('translatable_form_field_additionals_2')[$field_key]) && old('translatable_form_field_additionals_2')[$field_key] ? old('translatable_form_field_additionals_2')[$field_key] : '';
			$row_field_nullable = isset(old('translatable_nullable')[$field_key]) && old('translatable_nullable')[$field_key] ? old('translatable_nullable')[$field_key] : 0;
			$row_field_unique = isset(old('translatable_unique')[$field_key]) && old('translatable_unique')[$field_key] ? old('translatable_unique')[$field_key] : 0;
		} elseif (isset($cms_page)) {
			$row_field_old_name = $field['name'];
			$row_field_name = $field['name'];
			$row_field_migration_type = $field['migration_type'];
			$row_field_form_field = $field['form_field'];
			$row_field_old_form_field_additionals_1 = null;
			$row_field_form_field_additionals_1 = null;
			$row_field_form_field_additionals_2 = null;
			$row_field_nullable = $field['nullable'] ? 1 : 0;
			$row_field_unique = null;
		} else {
			$row_field_old_name = '';
			$row_field_name = '';
			$row_field_migration_type = '';
			$row_field_form_field = '';
			$row_field_old_form_field_additionals_1 = '';
			$row_field_form_field_additionals_1 = '';
			$row_field_form_field_additionals_2 = null;
			$row_field_nullable = 0;
			$row_field_unique = 0;
		}
	} else {
		if (isset($field_key) && isset(old('name')[$field_key])) {
			$row_field_old_name = isset(old('old_name')[$field_key]) && old('old_name')[$field_key] ? old('old_name')[$field_key] : '';
			$row_field_name = isset(old('name')[$field_key]) && old('name')[$field_key] ? old('name')[$field_key] : '';
			$row_field_migration_type = isset(old('migration_type')[$field_key]) && old('migration_type')[$field_key] ? old('migration_type')[$field_key] : '';
			$row_field_form_field = isset(old('form_field')[$field_key]) && old('form_field')[$field_key] ? old('form_field')[$field_key] : '';
			$row_field_old_form_field_additionals_1 = isset(old('form_old_field_additionals_1')[$field_key]) && old('form_old_field_additionals_1')[$field_key] ? old('form_old_field_additionals_1')[$field_key] : '';
			$row_field_form_field_additionals_1 = isset(old('form_field_additionals_1')[$field_key]) && old('form_field_additionals_1')[$field_key] ? old('form_field_additionals_1')[$field_key] : '';
			$row_field_form_field_additionals_2 = isset(old('form_field_additionals_2')[$field_key]) && old('form_field_additionals_2')[$field_key] ? old('form_field_additionals_2')[$field_key] : '';
			$row_field_nullable = isset(old('nullable')[$field_key]) && old('nullable')[$field_key] ? old('nullable')[$field_key] : 0;
			$row_field_unique = isset(old('unique')[$field_key]) && old('unique')[$field_key] ? old('unique')[$field_key] : 0;
		} elseif (isset($cms_page)) {
			$row_field_old_name = $field['name'];
			$row_field_name = $field['name'];
			$row_field_migration_type = $field['migration_type'];
			$row_field_form_field = $field['form_field'];
			$row_field_old_form_field_additionals_1 = $field['form_field_additionals_1'];
			$row_field_form_field_additionals_1 = $field['form_field_additionals_1'];
			$row_field_form_field_additionals_2 = $field['form_field_additionals_2'];
			$row_field_nullable = $field['nullable'] ? 1 : 0;
			$row_field_unique = $field['unique'] ? 1 : 0;
		} else {
			$row_field_old_name = '';
			$row_field_name = '';
			$row_field_migration_type = '';
			$row_field_form_field = '';
			$row_field_old_form_field_additionals_1 = '';
			$row_field_form_field_additionals_1 = '';
			$row_field_form_field_additionals_2 = null;
			$row_field_nullable = 0;
			$row_field_unique = 0;
		}
	}
@endphp

<tr class="sortable-row position-relative field">
	<td class="text-center">
		<input type="hidden" name="{{ $field_type ? ($field_type . '_') : '' }}old_name[]" value="{{ $row_field_old_name }}">
		<input class="form-control" name="{{ $field_type ? ($field_type . '_') : '' }}name[]" value="{{ $row_field_name }}">
	</td>
	<td class="text-center">
		<select class="form-control regular-select" name="{{ $field_type ? ($field_type . '_') : '' }}migration_type[]">
			<option></option>
			@foreach ($migration_types as $type)
				<option value="{{ $type }}" {!! $row_field_migration_type == $type ? 'selected=""' : '' !!}>{{ $type }}</option>
			@endforeach
		</select>
	</td>
	<td class="text-center">
		<select class="form-control regular-select" name="{{ $field_type ? ($field_type . '_') : '' }}form_field[]">
			<option></option>
			@foreach ($form_fields as $form_field)
				<option value="{{ $form_field }}" {!! $row_field_form_field == $form_field ? 'selected=""' : '' !!}>{{ $form_field }}</option>
			@endforeach
		</select>
		<div class="form-field-additionals" {!! $row_field_form_field_additionals_1 ? '' : 'style="display: none;"' !!}>
			<input type="hidden" name="{{ $field_type ? ($field_type . '_') : '' }}old_form_field_additionals_1[]" value="{{ $row_field_old_form_field_additionals_1 }}">
			<input class="form-control mt-2" name="{{ $field_type ? ($field_type . '_') : '' }}form_field_additionals_1[]" value="{{ $row_field_form_field_additionals_1 }}">
			<input class="form-control mt-2" name="{{ $field_type ? ($field_type . '_') : '' }}form_field_additionals_2[]" value="{{ $row_field_form_field_additionals_2 }}" {!! $row_field_form_field == 'slug' ? 'type="number"' : '' !!} {!! is_null($row_field_form_field_additionals_2) ? 'style="display:none;"' : '' !!}>
		</div>
	</td>
	<td class="text-center">
		<input class="form-control" type="number" name="{{ $field_type ? ($field_type . '_') : '' }}nullable[]" min="0" max="1" value="{{ $row_field_nullable }}">
	</td>
	@if (!$field_type)
		<td class="text-center">
			<input class="form-control" type="number" name="{{ $field_type ? ($field_type . '_') : '' }}unique[]" min="0" max="1" value="{{ $row_field_unique }}">
		</td>
	@endif
	<td class="text-center">
		<button class="remove btn btn-sm btn-danger" type="button" onclick="removeField(this)">
			Remove
		</button>
	</td>
</tr>