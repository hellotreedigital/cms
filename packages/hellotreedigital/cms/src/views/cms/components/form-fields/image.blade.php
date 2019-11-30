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
						<input type="checkbox" name="remove_file_{{ $name }}">
					</label>
				</div>
			</div>
		</div>
	@endif
	<label class="file-wrapper" data-file="">
		<input type="file" class="form-control" name="{{ $name }}">
	</label>
</div>