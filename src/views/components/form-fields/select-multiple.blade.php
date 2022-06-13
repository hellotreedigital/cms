@php
if (!isset($value) || !$value) {
    $value = [];
}
$selected_ids = [];
foreach($value as $single_value) {
    $selected_ids[] = $single_value->id;
}
@endphp

<div class="form-group">
    @include('cms::components/form-fields/label')
    <div class="select-multiple-custom-wrapper">
        <select class="form-control select-multiple-custom" data-name="{{ $name }}" multiple>
            @foreach ($options as $option)
                <option value="{{ $option[$store_column] }}" {!! in_array($option[$store_column], $selected_ids) ? 'selected' : '' !!}>
                    {{ $option[$display_column] }}
                </option>
            @endforeach
        </select>
        <div class="selected-options sortable">
            @foreach ($value as $single_value)
                <div class="selected-option py-1 d-flex align-items-center border-bottom sortable-row">
                    <p class="flex-grow-1 mb-0">{{ $single_value[$display_column] }}</p>
                    <i class="fa fa-remove text-danger"></i>
                    <input type="hidden" name="{{ $name }}[]" value="{{ $single_value[$store_column] }}">
                    <input type="hidden" name="ht_pos[{{ $name }}][{{ $single_value[$store_column] }}]" value="">
                </div>
            @endforeach
        </div>
    </div>
</div>
