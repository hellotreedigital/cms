@php
	$input_name = $name;
	$remove_input_name = 'remove_file_' . $name;
	if ($locale) {
		$input_name = $locale . '[' . $name . ']';
		$remove_input_name = $locale . '[' . 'remove_file_' . $name . ']';
	}
@endphp
<div class="form-group">
	@include('cms::components/form-fields/label')
	@if (isset($value) && $value)
		<div class="row">
			<div class="col-6">
                <div class="file-link-wrapper">
                    <a href="{{ Storage::url($value) }}" target="_blank"><i class="fa fa-file" aria-hidden="true"></i><span class="btn-sm">Click to see file</span></a>
                </div>
			</div>
			<div class="col-6 mb-2 text-right">
                <div class="d-inline-block remove-current-file">
                    <a class="btn btn-sm btn-danger py-1">Remove</a>
                    <input name="{{ $remove_input_name }}" value="">
                </div>
			</div>
		</div>
	@endif
	<label class="file-wrapper placeholder" data-placeholder="Upload file" data-text="Upload file">
		<input type="file" class="form-control" name="{{ $input_name }}">
	</label>
</div>
