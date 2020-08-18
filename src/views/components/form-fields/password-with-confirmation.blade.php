@php
	$input_name = $name;
	$input_confirmation_name = $name . '_confirmation';
	if ($locale) {
		$input_name = $locale . '[' . $name . ']';
		$input_confirmation_name = $locale . '[' . $name . '_confirmation]';
	}
@endphp
<div class="form-group">
	@include('cms::components/form-fields/label')
	<input class="form-control" name="{{ $input_name }}" type="password">
</div>
<div class="form-group">
    @include('cms::components/form-fields/label', ['label' => 'Confirm ' . $label])
	<input class="form-control" name="{{ $input_confirmation_name }}" type="password">
</div>