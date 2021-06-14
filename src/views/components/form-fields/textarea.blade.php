@php
	$input_name = $name;
	if ($locale) {
		$input_name = $locale . '[' . $name . ']';
	}
@endphp
<div class="form-group">
	@include('cms::components/form-fields/label')
    <div class="word-count-wrapper">
	    <textarea class="form-control" name="{{ $input_name }}" rows="5" onkeyup="wordCount(this)">{{ $value }}</textarea>
        <small class="float-right"><span class="word-count-number"></span> words</small>
    </div>
</div>
