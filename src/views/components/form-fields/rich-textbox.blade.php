@php
	$input_name = $name;
	if ($locale) {
		$input_name = $locale . '[' . $name . ']';
	}
@endphp
<div class="form-group">
	@include('cms::components/form-fields/label')
	<textarea name="{{ $input_name }}" id="ckeditor_{{ $input_name }}" upload-url="{{ route('ckeditor-images', ['_token' => csrf_token()]) }}">{{ $value }}</textarea>
</div>