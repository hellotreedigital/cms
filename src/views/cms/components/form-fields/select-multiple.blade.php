<div class="form-group">
	<label class="d-block">{{ $label }}</label>
	<select class="form-control" name="{{ $name }}" multiple="">
		@foreach($options as $option)
			<option value="{{ $option[$store_column] }}" {{ isset($value) && is_array($value) && in_array($option[$store_column], $value) ? 'selected' : '' }}>
				{{ $option[$display_column] }}
			</option>
		@endforeach
	</select>
</div>
