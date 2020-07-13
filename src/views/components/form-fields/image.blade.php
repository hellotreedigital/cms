@php
	$input_name = $name;
	$remove_input_name = 'remove_file_' . $name;
	if ($locale) {
		$input_name = $locale . '[' . $name . ']';
		$remove_input_name = $locale . '[' . 'remove_file_' . $name . ']';
	}
@endphp
<div class="form-group">
	<label class="d-block">{{ $label }}</label>
	@if (isset($value) && $value)
		<div class="row">
			<div class="col-6 mb-2">
				<div class="img-wrapper">
					<img src="{{ asset($value) }}" class="img-thumbnail">
				</div>
			</div>
			<div class="col-6 mb-2">
				<div class="text-right">
					<div class="d-inline-block remove-current-image">
						<a class="btn btn-sm btn-danger py-1">Remove</a>
						<input type="checkbox" name="{{ $remove_input_name }}" value="0">
					</div>
				</div>
			</div>
		</div>
	@endif
	<label class="file-wrapper placeholder" data-placeholder="Upload image" data-text="Upload image">
		<input type="file" class="form-control" name="{{ $input_name }}">
	</label>
</div>
