<div class="form-group">
	<label class="d-block">{{ $label }}</label>
	<select class="form-control" name="{{ $name }}" multiple="">
		@foreach($options as $option)
			<option value="{{ $option['value'] }}">{{ $option['text'] }}</option>
		@endforeach
	</select>
</div>