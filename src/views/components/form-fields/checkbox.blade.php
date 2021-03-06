@php
	$input_name = $name;
	if ($locale) {
		$input_name = $locale . '[' . $name . ']';
	}
@endphp
<div class="form-group">
	@if (!isset($inline_label))
        @include('cms::components/form-fields/label')
	@endif
	<label class="checkbox-wrapper">
		<input type="checkbox" class="form-control" name="{{ $input_name }}" {!! $checked ? 'checked=""' : '' !!}>
		<div></div>
		@if (isset($inline_label))
			<span class="d-inline-block align-middle mb-0 ml-1">{{ $label }}</span>
		@endif
	</label>
</div>