<div class="mb-4">
	<label class="font-weight-bold">{{ $label }}</label>
	<div class="pl-3">
        <div class="d-flex align-items-center">
            <div class="rounded mr-1" style="width: 22px; height: 22px; background-color: {{ $row[$field['name']] }}"></div>
            <span>{{ $row[$field['name']] }}</span>
        </div>
	</div>
	<hr>
</div>
