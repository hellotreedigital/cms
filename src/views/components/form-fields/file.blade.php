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
				<a href="{{ asset($value) }}" target="_blank"><i class="fa fa-file" aria-hidden="true"></i><span class="btn-sm">Click to see file</span></a>
			</div>
			<div class="col-6 text-right">
				<label class="remove-current-file">
					<a class="btn btn-sm btn-danger py-1">Remove current file</a>
					<input type="checkbox" name="{{ $remove_input_name }}">
				</label>
			</div>
		</div>
	@endif
	<label class="file-wrapper" data-placeholder="Upload file" data-file="">
		<input type="file" class="form-control" name="{{ $input_name }}">
	</label>
</div>