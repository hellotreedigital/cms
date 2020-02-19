<div class="mb-4">
	<label class="font-weight-bold mb-3">{{ $label }}</label>
	<div class="pl-3">
		@if ($files)
			@foreach(json_decode($files) as $file)
				<a href="{{ asset($file) }}" target="_blank" class="mr-2"><i class="fa fa-file" aria-hidden="true"></i><span class="btn-sm">Click to see file</span></a>
			@endforeach
		@else
			<p class="m-0">No files</p>
		@endif
	</div>
	<hr>
</div>