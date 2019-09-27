<div class="mb-4">
	<label class="font-weight-bold mb-3">{{ $label }}</label>
	<div class="pl-3">
		@if ($image)
			<img class="img-thumbnail" src="{{ asset($image) }}">
		@else
			<p class="m-0">No image</p>
		@endif
	</div>
	<hr>
</div>