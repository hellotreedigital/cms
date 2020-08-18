<label class="d-block form-field-description-wrapper">
    @if ($description ?? null)
    <i class="fa fa-info-circle" aria-hidden="true"></i>
    <div class="form-field-description">
        {!! $description !!}
    </div>
    @endif
    <span>{!! $label !!}</span>
    {!! ($required ?? '') ? '<span class="text-danger">*</span>' : '' !!}
</label>