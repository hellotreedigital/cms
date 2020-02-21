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
			<div class="col-6">
				<div class="img-wrapper">
					<img src="{{ asset($value) }}" class="img-thumbnail mb-2">
				</div>
			</div>
			<div class="col-6">
				<div class="text-right">
					<label class="remove-current-file">
						<a class="btn btn-sm btn-danger py-1">Remove current file</a>
						<input type="checkbox" name="{{ $remove_input_name }}">
					</label>
				</div>
			</div>
		</div>
	@endif
	<label class="file-wrapper" data-placeholder="Upload image" data-text="">
		<input type="file" class="form-control" name="{{ $input_name }}">
	</label>
</div>