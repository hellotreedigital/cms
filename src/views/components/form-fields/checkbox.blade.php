@php
	if ($errors->any()) {
		$checked = old($name);
		if ($locale) {
			$checked = old($locale)[$name];
		}
	}
	$input_name = $name;
	if ($locale) {
		$input_name = $locale . '[' . $name . ']';
	}
@endphp
<div class="form-group">
	@if (!isset($inline_label))
		<label class="d-block">{{ $label }}</label>
	@endif
	<label class="checkbox-wrapper">
		<input type="checkbox" class="form-control" name="{{ $input_name }}" {!! $checked ? 'checked=""' : '' !!}>
		<div></div>
		@if (isset($inline_label))
			<span class="d-inline-block align-middle mb-0 ml-1">{{ $label }}</span>
		@endif
	</label>
</div>