<div class="form-group">
	<label class="d-block">{!! $label !!}</label>
	<input class="form-control" name="{{ $name }}" type="{{ $type }}" value="{{ $value }}" {!! isset($slug_origin) ? 'data-slug-origin="' . $slug_origin . '"' : '' !!}>
</div>