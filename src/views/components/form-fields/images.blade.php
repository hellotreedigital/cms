@php
if (!isset($value) || !$value) $value = [];
else $value = json_decode($value);
@endphp

<div class="form-group multiple-images-wrapper {{ isset($row) ? 'edit' : '' }}">
    <label class="d-block">{{ $label }}</label>
    <label class="file-wrapper placeholder" data-text="Upload images">
        <input type="file" class="form-control" name="{{ $name }}[]" multiple="">
    </label>
    <div class="images-preview px-3 py-3" {!! count($value) ? '' : 'style="display: none;"' !!}>
        <div class="row images-sortable">
            @foreach($value as $image)
            <div class="col-auto saved" path="{{ $image }}">
                <img class="img-thumbnail" src="{{ asset($image) }}">
                <div class="bg-danger text-white">
                    <i class="fa fa-times" aria-hidden="true"></i>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>