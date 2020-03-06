@php
$lat = '33.89';
$lng = '35.55';
if ($value) {
    $lat = explode(',', $value)[0];
    $lng = explode(',', $value)[1];
}
@endphp

<div class="mb-4">
	<label class="font-weight-bold">{{ $label }}</label>
    <div class="pl-3">
       <div class="map" id="map_{{ $name }}" style="height: 500px;"></div>
   </div>
	<hr>
</div>

@section('scripts')

<script>
    var map_{{ $name }};
    var marker_{{ $name }};

    if (!$('script[src*="maps.googleapis.com/maps/api/js"]').length) {
        var google_map_script = document.createElement('script');
        google_map_script.setAttribute('src','https://maps.googleapis.com/maps/api/js');
        document.head.appendChild(google_map_script);
    }

    $(window).on('load', function(){

        var latlng = { lat: {{ isset($value) ? $lat : '33.89' }}, lng: {{ isset($value) ? $lng : '35.55' }} };
        map_{{ $name }} = new google.maps.Map(document.getElementById('map_{{ $name }}'), {
            center: latlng,
            zoom: 8
        });
        var marker_{{ $name }} = new google.maps.Marker({
            position: latlng,
            map: map_{{ $name }},
            @if (!isset($value))
            	visible: false
            @endif
        });

    });
</script>

@endsection