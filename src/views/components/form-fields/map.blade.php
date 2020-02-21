@php
    if ($errors->any()) {
        $value = old($name);
        if ($locale) {
            $value = old($locale)[$name];
        }
    }
@endphp
@php
$lat = 33.8892527;
$lng = 35.4867727;

if ($errors->any()) {
    $array = explode(',', old($name));
    $lat = $array[0];
    $lng = $array[1];
} elseif ($value) {
    $array = explode(',', $value);
    $lat = $array[0];
    $lng = $array[1];
}

    $input_name = $name;
    if ($locale) {
        $input_name = $locale . '[' . $name . ']';
        $input_id = $locale . '_' . $name;
    }
@endphp

<div class="form-group">
	<label class="d-block">{{ $label }}</label>
    <div class="map" id="map_{{ $input_id }}" style="height: 500px;"></div>
	<input type="hidden" name="{{ $input_name }}" value="{{ $value }}">
</div>

@section('scripts')

<script>
    var map_{{ $input_id }};
    var marker_{{ $input_id }};

    $(window).on('load', function(){

        var latlng = { lat: {{ $lat }}, lng: {{ $lng }} };
        map_{{ $input_id }} = new google.maps.Map(document.getElementById('map_{{ $input_id }}'), {
            center: latlng,
            zoom: 8
        });
        var marker_{{ $input_id }} = new google.maps.Marker({
            position: latlng,
            map: map_{{ $input_id }},
            @if (!$value)
            	visible: false
            @endif
        });
        map_{{ $input_id }}.addListener('click', function(e) {
        	$('#map_{{ $input_id }}').next('input').val(e.latLng.lat() + ',' + e.latLng.lng());
        	marker_{{ $input_id }}.setVisible(true);
            marker_{{ $input_id }}.setPosition(new google.maps.LatLng(e.latLng.lat(), e.latLng.lng()));
        });

    });
</script>

@endsection