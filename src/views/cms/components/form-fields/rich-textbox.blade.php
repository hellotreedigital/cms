@php
	if ($errors->any()) $value = old($name);
@endphp
<div class="form-group">
	<label>{{ $label }}</label>
	<textarea name="{{ $name }}" class="quill">{{ $value }}</textarea>
</div>