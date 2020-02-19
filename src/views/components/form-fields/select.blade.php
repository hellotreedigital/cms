@php
	if ($errors->any()) $value = old($name);
@endphp
<div class="form-group">
	<label class="d-block">{{ $label }}</label>
	<select class="form-control" name="{{ $name }}">
		<option selected=""></option>
		@foreach($options as $option)
			<option value="{{ $option[$store_column] }}" {{ $value == $option[$store_column] ? 'selected' : '' }}>{{ $option[$display_column] }}</option>
		@endforeach
	</select>
</div>