@if ($field['form_field'] == 'textarea')
	@include('cms::/components/form-fields/textarea', [
		'label' => ucwords(str_replace('_', ' ', $field['name'])),
		'name' => $field['name'],
		'value' => isset($row) ? ($locale ? $row->translate($locale)[$field['name']] : $row[$field['name']]) : '',
		'locale' => $locale,
	])
@elseif ($field['form_field'] == 'rich-textbox')
	@include('cms::/components/form-fields/rich-textbox', [
		'label' => ucwords(str_replace('_', ' ', $field['name'])),
		'name' => $field['name'],
		'value' => isset($row) ? ($locale ? $row->translate($locale)[$field['name']] : $row[$field['name']]) : '',
		'locale' => $locale,
	])
@elseif ($field['form_field'] == 'select')
	@include('cms::/components/form-fields/select', [
		'label' => ucwords(str_replace(['_id', '_'], ['', ' '], $field['name'])),
		'name' => $field['name'],
		'options' => $extra_variables[$field['form_field_additionals_1']],
		'store_column' => 'id',
		'display_column' => $field['form_field_additionals_2'],
		'value' => isset($row) ? ($locale ? $row->translate($locale)[$field['name']] : $row[$field['name']]) : '',
		'locale' => $locale,
	])
@elseif ($field['form_field'] == 'select multiple')
	@include('cms::/components/form-fields/select-multiple', [
		'label' => ucwords(str_replace(['_id', '_'], ['', ' '], $field['name'])),
		'name' => $row[ucwords(str_replace(['_id', '_'], ['', ' '], $field['name']))],
		'options' => $extra_variables[$field['form_field_additionals_1']],
		'store_column' => 'id',
		'display_column' => $field['form_field_additionals_2'],
		'value' => isset($row) ? ($locale ? $row->translate($locale)[$field['name']] : $row[$field['name']]) : '',
		'locale' => $locale,
	])
@elseif ($field['form_field'] == 'file')
	@include('cms::/components/form-fields/file', [
		'label' => ucwords(str_replace('_', ' ', $field['name'])),
		'name' => $field['name'],
		'value' => isset($row) ? ($locale ? $row->translate($locale)[$field['name']] : $row[$field['name']]) : '',
		'locale' => $locale,
	])
@elseif ($field['form_field'] == 'image')
	@include('cms::/components/form-fields/image', [
		'label' => ucwords(str_replace('_', ' ', $field['name'])),
		'name' => $field['name'],
		'value' => isset($row) ? ($locale ? $row->translate($locale)[$field['name']] : $row[$field['name']]) : '',
		'locale' => $locale,
	])
@elseif ($field['form_field'] == 'slug')
    @if (!isset($row) || (isset($row) && $field['form_field_additionals_2']))
    	@include('cms::/components/form-fields/input', [
    		'label' => ucwords(str_replace('_', ' ', $field['name'])),
    		'name' => $field['name'],
    		'slug_origin' => $field['form_field_additionals_1'],
    		'type' => 'text',
    		'value' => isset($row) ? ($locale ? $row->translate($locale)[$field['name']] : $row[$field['name']]) : '',
    		'locale' => $locale,
    	])
	@endif
@elseif ($field['form_field'] == 'date')
	@include('cms::/components/form-fields/date', [
		'label' => ucwords(str_replace('_', ' ', $field['name'])),
		'name' => $field['name'],
		'value' => isset($row) ? ($locale ? $row->translate($locale)[$field['name']] : $row[$field['name']]) : '',
		'locale' => $locale,
	])
@elseif ($field['form_field'] == 'time')
	@include('cms::/components/form-fields/time', [
		'label' => ucwords(str_replace('_', ' ', $field['name'])),
		'name' => $field['name'],
		'value' => isset($row) ? ($locale ? $row->translate($locale)[$field['name']] : $row[$field['name']]) : '',
		'locale' => $locale,
	])
@elseif ($field['form_field'] == 'password')
	@include('cms::/components/form-fields/input', [
		'label' => ucwords(str_replace('_', ' ', $field['name'])),
		'name' => $field['name'],
		'type' => 'password',
		'value' => '',
		'locale' => $locale,
	])
@elseif ($field['form_field'] == 'password with confirmation')
	@include('cms::/components/form-fields/password-with-confirmation', [
		'label' => ucwords(str_replace('_', ' ', $field['name'])),
		'name' => $field['name'],
		'value' => '',
		'locale' => $locale,
	])
@elseif ($field['form_field'] == 'checkbox')
	@include('cms::/components/form-fields/checkbox', [
		'label' => ucwords(str_replace('_', ' ', $field['name'])),
		'name' => $field['name'],
		'checked' => isset($row) ? ($locale ? $row->translate($locale)[$field['name']] : $row[$field['name']]) : '',
		'locale' => $locale,
	])
@elseif ($field['form_field'] == 'map coordinates')
	@include('cms::/components/form-fields/map', [
		'label' => ucwords(str_replace('_', ' ', $field['name'])),
		'name' => $field['name'],
		'value' => isset($row) ? ($locale ? $row->translate($locale)[$field['name']] : $row[$field['name']]) : '',
		'locale' => $locale,
	])
@else
	@include('cms::/components/form-fields/input', [
		'label' => ucwords(str_replace('_', ' ', $field['name'])),
		'name' => $field['name'],
		'type' => 'text',
		'value' => isset($row) ? ($locale ? $row->translate($locale)[$field['name']] : $row[$field['name']]) : '',
		'locale' => $locale,
	])
@endif
