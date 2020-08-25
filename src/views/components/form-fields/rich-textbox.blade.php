@php
	$input_name = $name;
	if ($locale) {
		$input_name = $locale . '[' . $name . ']';
	}
@endphp
<div class="form-group">
	@include('cms::components/form-fields/label')
	<textarea name="{{ $input_name }}" class="quill">{{ $value }}</textarea>
</div>