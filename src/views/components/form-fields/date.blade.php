@php
	if ($errors->any()) {
		$value = old($name);
		if ($locale) {
			$value = old($locale)[$name];
		}
	}
	$input_name = $name;
	if ($locale) {
		$input_name = $locale . '[' . $name . ']';
	}
@endphp
<div class="form-group">
	@include('cms::components/form-fields/label')
	<input class="form-control datepicker" name="{{ $input_name }}" value="{{ $value }}">
</div>