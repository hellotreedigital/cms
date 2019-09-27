<div class="form-group">
	@if (!isset($inline_label))
		<label class="d-block">{{ $label }}</label>
	@endif
	<label class="checkbox-wrapper">
		<input type="checkbox" class="form-control" name="{{ $name }}" {!! old($name) ? 'checked=""' : isset($checked) && $checked ? 'checked=""' : '' !!}>
		<div></div>
		@if (isset($inline_label))
			<span class="d-inline-block align-middle mb-0 ml-1">{{ $label }}</span>
		@endif
	</label>
</div>