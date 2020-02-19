@php
	if ($errors->any()) $value = old($name);
@endphp
<div class="form-group">
	<label>{{ $label }}</label>
	<textarea class="form-control" name="{{ $name }}" rows="5">{{ $value }}</textarea>
</div>