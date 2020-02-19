@php
    if ($errors->any()) $value = old($name);
@endphp
<div class="form-group">
    <label class="d-block">{{ $label }}</label>
    <div class="timepicker no-selection">
        <div class="buttons-wrapper upper">
            <span></span>
            <span></span>
            <span></span>
        </div>
        <div class="inputs-wrapper">
            <input name="{{ $name }}_hour" class="hour" readonly="" value="{{ date('h', strtotime($value)) }}">
            <input name="{{ $name }}_minutes" class="minutes" readonly="" value="{{ date('i', strtotime($value)) }}">
            <input name="{{ $name }}_period" class="period" readonly="" value="{{ date('A', strtotime($value)) }}">
            <input type="hidden" name="{{ $name }}" value="{{ $value ? date('h:i A', strtotime($value)) : '' }}">
        </div>
        <div class="buttons-wrapper lower">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
</div>