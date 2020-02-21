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
	<label class="d-block">{!! $label !!}</label>
	<input class="form-control" name="{{ $input_name }}" type="{{ $type }}" value="{{ $value }}" {!! isset($slug_origin) ? 'data-slug-origin="' . $slug_origin . '"' : '' !!}>
</div>