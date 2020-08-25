@php
	$input_name = $name;
	if ($locale) {
		$input_name = $locale . '[' . $name . ']';
	}
@endphp
<div class="form-group">
	@include('cms::components/form-fields/label')
	<textarea class="form-control" name="{{ $input_name }}" rows="5">{{ $value }}</textarea>
</div>
