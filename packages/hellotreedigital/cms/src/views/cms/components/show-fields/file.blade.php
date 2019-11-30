<div class="mb-4">
	<label class="font-weight-bold mb-3">{{ $label }}</label>
	<div class="pl-3">
		@if ($file)
			<a href="{{ asset($file) }}" target="_blank"><i class="fa fa-file" aria-hidden="true"></i><span class="btn-sm">Click to see file</span></a>
		@else
			<p class="m-0">No file</p>
		@endif
	</div>
	<hr>
</div>