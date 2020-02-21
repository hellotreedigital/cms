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
	<label>{{ $label }}</label>
	<textarea name="{{ $input_name }}" class="quill">{{ $value }}</textarea>
</div>