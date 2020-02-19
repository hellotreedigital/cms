@php
	if ($errors->any()) $value = old($name);
@endphp
<div class="form-group">
	<label class="d-block">{{ $label }}</label>
	<input class="form-control datepicker" name="{{ $name }}" value="{{ $value }}">
</div>