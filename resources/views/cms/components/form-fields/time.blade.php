<div class="form-group">
	<label class="d-block">{{ $label }}</label>
	<div class="timepicker no-selection">
		<div class="buttons-wrapper upper">
			<span></span>
			<span></span>
			<span></span>
		</div>
		<div class="inputs-wrapper">
			<input class="hour" readonly="" value="{{ date('H', strtotime($value)) }}">
			<input class="minutes" readonly="" value="{{ date('i', strtotime($value)) }}">
			<input class="period" readonly="" value="{{ date('A', strtotime($value)) }}">
		</div>
		<div class="buttons-wrapper lower">
			<span></span>
			<span></span>
			<span></span>
		</div>
	</div>
</div>