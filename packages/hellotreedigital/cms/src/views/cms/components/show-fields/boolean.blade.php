<div class="mb-4">
	<label class="font-weight-bold">{{ $label }}</label>
	<div class="pl-3">
		@if ($value)
			<i class="fa fa-check" aria-hidden="true"></i>
		@else
			<i class="fa fa-times" aria-hidden="true"></i>
		@endif
	</div>
	<hr>
</div>