@php
$input_name = $name;
if ($locale) {
    $input_name = $locale . '[' . $name . ']';
}
@endphp

<div class="form-group">
    @include('cms::components/form-fields/label')
    <div class="word-count-wrapper">
        <input class="form-control" name="{{ $input_name }}" type="{{ $type }}" value="{{ $value }}" {!! isset($slug_origin) ? 'data-slug-origin="' . $slug_origin . '"' : '' !!} onkeyup="wordCount(this)">
        @if (!isset($no_word_count) || !$no_word_count)
            <small class="float-right"><span class="word-count-number"></span> words</small>
        @endif
    </div>
</div>
