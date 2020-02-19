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
@endphp

<div class="form-group">
	<label class="d-block">{{ $label }}</label>
    <div class="map" id="map_{{ $name }}" style="height: 500px;"></div>
	<input type="hidden" name="{{ $name }}" value="{{ $value }}">
</div>

@section('scripts')

<script>
    var map_{{ $name }};
    var marker_{{ $name }};

    $(window).on('load', function(){

        var latlng = { lat: {{ $lat }}, lng: {{ $lng }} };
        map_{{ $name }} = new google.maps.Map(document.getElementById('map_{{ $name }}'), {
            center: latlng,
            zoom: 8
        });
        var marker_{{ $name }} = new google.maps.Marker({
            position: latlng,
            map: map_{{ $name }},
            @if (!$value)
            	visible: false
            @endif
        });
        map_{{ $name }}.addListener('click', function(e) {
        	$('#map_{{ $name }}').next('input').val(e.latLng.lat() + ',' + e.latLng.lng());
        	marker_{{ $name }}.setVisible(true);
            marker_{{ $name }}.setPosition(new google.maps.LatLng(e.latLng.lat(), e.latLng.lng()));
        });

    });
</script>

@endsection