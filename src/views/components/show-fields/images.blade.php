@php
if ($images) {
	$images = json_decode($images);
	if (!$images) $images = [];
} else {
	$images = [];
}
@endphp
<div class="mb-4">
	<label class="font-weight-bold mb-3">{{ $label }}</label>
	<div class="pl-3">
		@if ($images)
			@foreach($images as $image)
				<img class="img-thumbnail" src="{{ Storage::url($image) }}" style="height: 100px;">
			@endforeach
		@else
			<p class="m-0">No image</p>
		@endif
	</div>
	<hr>
</div>