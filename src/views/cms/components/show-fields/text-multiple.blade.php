<div class="mb-4">
	<label class="font-weight-bold">{{ $label }}</label>
	<div class="pl-3">
		@foreach($texts as $text)
			<p class="m-0 pre-wrap">{{ strip_tags($text[$display_column]) }}</p>
		@endforeach
	</div>
	<hr>
</div>