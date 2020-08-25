@php
    $input_name = $name;
    if ($locale) {
        $input_name = $locale . '[' . $name . ']';
    }
@endphp
<div class="form-group">
    @include('cms::components/form-fields/label')
    <div class="timepicker no-selection">
        <div class="buttons-wrapper upper">
            <span></span>
            <span></span>
            <span></span>
        </div>
        <div class="inputs-wrapper">
            <input class="hour" readonly="" value="{{ date('h', strtotime($value)) }}">
            <input class="minutes" readonly="" value="{{ date('i', strtotime($value)) }}">
            <input class="period" readonly="" value="{{ date('A', strtotime($value)) }}">
            <input type="hidden" name="{{ $input_name }}" value="{{ $value ? date('h:i A', strtotime($value)) : '12:00 AM' }}">
        </div>
        <div class="buttons-wrapper lower">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
</div>