<div class="form-group">
	<label class="d-block">{{ $label }}</label>
	<select class="form-control" name="{{ $name }}">
		<option selected="" disabled=""></option>
		@foreach($options as $option)
			<option value="{{ $option[$store_column] }}" {{ old($name) ? old($name) : isset($value) && $value == $option[$store_column] ? 'selected' : '' }}>{{ $option[$display_column] }}</option>
		@endforeach
	</select>
</div>