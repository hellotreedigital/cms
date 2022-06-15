@php
if (!isset($value) || !$value) {
    $value = [];
}
@endphp

<div class="form-group">
    @include('cms::components/form-fields/label')
    <div class="select-multiple-custom-wrapper">
        <select class="form-control select-multiple-custom" data-name="{{ $name }}" multiple>
            @foreach ($options as $option)
                <option value="{{ $option[$store_column] }}" {!! in_array($option[$store_column], $value) ? 'selected' : '' !!}>
                    {{ $option[$display_column] }}
                </option>
            @endforeach
        </select>
        <div class="selected-options sortable">
            @foreach ($value as $selected_id)
                @foreach ($options as $option)
                    @if ($option->id == $selected_id)
                        <div class="selected-option py-1 d-flex align-items-center border-bottom sortable-row">
                            <p class="flex-grow-1 mb-0">{{ $option[$display_column] }}</p>
                            <i class="fa fa-remove text-danger"></i>
                            <input type="hidden" name="{{ $name }}[]" value="{{ $selected_id }}" class="selected-option-id">
                            <input type="hidden" name="ht_pos[{{ $name }}][{{ $selected_id }}]" value="">
                        </div>
                    @endif
                @endforeach
            @endforeach
        </div>
    </div>
</div>
