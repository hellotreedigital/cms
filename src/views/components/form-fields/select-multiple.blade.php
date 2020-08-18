@php
if ($errors->any()) {
	$selected = old($name) ? old($name) : [];
} else {
	if (!isset($value) || !$value) $value = [];
	$selected = [];
	foreach ($value as $obj) {
		$selected[] = $obj[$store_column];
	}
}
@endphp

<div class="form-group">
	@include('cms::components/form-fields/label')
	<select class="form-control" name="{{ $name }}[]" multiple="">
		@foreach($options as $option)
			<option value="{{ $option[$store_column] }}" {{ in_array($option[$store_column], $selected) ? 'selected' : '' }}>
				{{ $option[$display_column] }}
			</option>
		@endforeach
	</select>
</div>
