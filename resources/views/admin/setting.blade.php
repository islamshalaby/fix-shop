@extends('admin.app')

@section('title' , 'Admin Panel AboutApp')
@push('styles')
<script src="https://unpkg.com/@turf/turf@6/turf.min.js"></script>
<script src="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-draw/v1.2.2/mapbox-gl-draw.js"></script>
<link rel="stylesheet" href="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-draw/v1.2.2/mapbox-gl-draw.css" type="text/css">
@endpush
@section('content')
<div class="col-lg-12 col-12 layout-spacing">
    <div class="statbox widget box box-shadow">
        <div class="widget-header">
            <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                    <h4>{{ __('messages.setting') }}</h4>
             </div>
    </div>
    <form method="post" action="" enctype="multipart/form-data" >
        @csrf
         <div class="form-group mb-4">
            <label>{{ __('messages.current_logo') }}</label>
            <br>
            <img src="https://res.cloudinary.com/{{ cloudinary_app_name() }}/image/upload/w_100,q_100/v1581928924/{{$data['setting']['logo']}}" >
        </div>

         <div class="form-group mb-4">
            <label for="logo">{{ __('messages.logo') }}</label>
            <input  type="file" name="logo" class="form-control" id="logo" placeholder="{{ __('messages.logo') }}" value="" >
        </div>

         <div class="form-group mb-4">
            <label for="app_name_en">{{ __('messages.app_name_en') }}</label>
            <input required type="text" name="app_name_en" class="form-control" id="app_name_en" placeholder="{{ __('messages.app_name_en') }}" value="{{$data['setting']['app_name_en']}}" >
        </div>
         <div class="form-group mb-4">
            <label for="app_name_ar">{{ __('messages.app_name_ar') }}</label>
            <input required type="text" name="app_name_ar" class="form-control" id="app_name_ar" placeholder="{{ __('messages.app_name_ar') }}" value="{{$data['setting']['app_name_ar']}}" >
        </div>
         <div class="form-group mb-4">
            <label for="email">{{ __('messages.email') }}</label>
            <input required type="email" name="email" class="form-control" id="email" placeholder="{{ __('messages.email') }}" value="{{$data['setting']['email']}}" >
        </div>
        <div class="form-group mb-4">
            <label for="phone">{{ __('messages.phone') }}</label>
            <input required type="phone" name="phone" class="form-control" id="phone" placeholder="{{ __('messages.phone') }}" value="{{$data['setting']['phone']}}" >
        </div>
        <div class="form-group mb-4">
            <label for="app_phone">{{ __('messages.support_phone') }}</label>
            <input required type="phone" name="app_phone" class="form-control" id="app_phone" placeholder="{{ __('messages.support_phone') }}" value="{{$data['setting']['app_phone']}}" >
        </div>
        <div class="form-group mb-4">
            <label for="address_en">{{ __('messages.address_en') }}</label>
            <input  type="text" name="address_en" class="form-control" id="address_en" placeholder="{{ __('messages.address_en') }}" value="{{$data['setting']['address_en']}}" >
        </div>
        <div class="form-group mb-4">
            <label for="address_ar">{{ __('messages.address_ar') }}</label>
            <input  type="text" name="address_ar" class="form-control" id="address_ar" placeholder="{{ __('messages.address_ar') }}" value="{{$data['setting']['address_ar']}}" >
        </div>
        <div class="form-group mb-4">
            <label for="facebook">{{ __('messages.facebook') }}</label>
            <input  type="text" name="facebook" class="form-control" id="facebook" placeholder="{{ __('messages.facebook') }}" value="{{$data['setting']['facebook']}}" >
        </div>
        <div class="form-group mb-4">
            <label for="youtube">{{ __('messages.youtube') }}</label>
            <input  type="text" name="youtube" class="form-control" id="youtube" placeholder="{{ __('messages.youtube') }}" value="{{$data['setting']['youtube']}}" >
        </div>
        <div class="form-group mb-4">
            <label for="twitter">{{ __('messages.twitter') }}</label>
            <input  type="text" name="twitter" class="form-control" id="twitter" placeholder="{{ __('messages.twitter') }}" value="{{$data['setting']['twitter']}}" >
        </div>
        <div class="form-group mb-4">
            <label for="instegram">{{ __('messages.instegram') }}</label>
            <input  type="text" name="instegram" class="form-control" id="instegram" placeholder="{{ __('messages.instegram') }}" value="{{$data['setting']['instegram']}}" >
        </div>
       {{--  <div class="form-group mb-4">
            <label for="snap_chat">{{ __('messages.snap_chat') }}</label>
            <input  type="text" name="snap_chat" class="form-control" id="snap_chat" placeholder="{{ __('messages.snap_chat') }}" value="{{$data['setting']['snap_chat']}}" >
        </div> --}}
        <div class="form-group mb-4">
            <label for="map_url">{{ __('messages.map_url') }}</label>
            <input  type="text" name="map_url" class="form-control" id="map_url" placeholder="{{ __('messages.map_url') }}" value="{{$data['setting']['map_url']}}" >
        </div>
        <div class="form-group mb-4">
            <input  type="hidden" name="latitude" class="form-control"  value="{{$data['setting']['latitude']}}" >
        </div>
        <div class="form-group mb-4">
            <input  type="hidden" name="longitude" class="form-control" value="{{$data['setting']['longitude']}}" >
        </div>
        

        <h4>{{ __('messages.about_app') }}</h4>
        <div class="form-group mb-4">
            <label>{{ __('messages.current_image') }}</label>
            <br>
            <img src="https://res.cloudinary.com/{{ cloudinary_app_name() }}/image/upload/w_100,q_100/v1581928924/{{$data['setting']['about_image']}}" >
        </div>

        <div class="form-group mb-4">
            <label for="logo">{{ __('messages.image') }}</label>
            <input  type="file" name="about_image" class="form-control" id="about_image" placeholder="{{ __('messages.about_image') }}" value="" >
        </div>
        <div class="form-group mb-4">
            <label for="address_en">{{ __('messages.about_title') }}</label>
            <input  type="text" name="about_title" class="form-control" id="about_title" placeholder="{{ __('messages.about_title') }}" value="{{$data['setting']['about_title']}}" >
        </div>
        <div class="form-group mb-4">
            <label for="address_ar">{{ __('messages.about_desc') }}</label>
            <input  type="text" name="about_desc" class="form-control" id="about_desc" placeholder="{{ __('messages.about_desc') }}" value="{{$data['setting']['about_desc']}}" >
        </div>
        <div class="row">
            <div id='map' style='width: 100%; height: 300px;'></div>
            <div class="calculation-box">
                <p>حدد النقاط على الخريطة</p>
                <div id="calculated-area"></div>
                </div>
                <div id="latlngbox"></div>
            </div>
        </div>
        
        
        <div class="form-group mb-4">
            <label for="address_ar">{{ __('messages.about_footer') }}</label>
            <input  type="text" name="about_footer" class="form-control" id="about_footer" placeholder="{{ __('messages.about_footer') }}" value="{{$data['setting']['about_footer']}}" >
        </div>
            <input type="submit" value="{{ __('messages.submit') }}" class="btn btn-primary">

    </form>
</div>
@endsection
@push('scripts') 
    <script>
        mapboxgl.accessToken = 'pk.eyJ1IjoiZml4LXNob3AiLCJhIjoiY2t1M3c5Z3VvNGttNTJvbXA2cmRoemhvbiJ9.ZnPPvqfazzjy4YmjcxiuJQ';
        const map = new mapboxgl.Map({
            container: 'map', // container ID
            style: 'mapbox://styles/mapbox/streets-v9',
            center: [47.5133068 , 25.6017925],
            zoom: 4 // starting zoom
            });
            
            const draw = new MapboxDraw({
            displayControlsDefault: false,
            // Select which mapbox-gl-draw control buttons to add to the map.
            controls: {
            polygon: true,
            trash: true
            },
            // Set mapbox-gl-draw to draw by default.
            // The user does not have to click the polygon control button first.
            defaultMode: 'draw_polygon'
        });
        //Add the control to the map.
        map.addControl(
        new MapboxGeocoder({
        accessToken: mapboxgl.accessToken,
        mapboxgl: mapboxgl
        })
        );
        
        map.addControl(draw);
        map.addControl(new mapboxgl.FullscreenControl());
        map.on('draw.create', updateArea);
        map.on('draw.delete', updateArea);
        map.on('draw.update', updateArea);
        
        function updateArea(e) {
            const data = draw.getAll()
            const answer = document.getElementById('calculated-area');
            if (data.features.length > 0) {
                if (data.features[0].geometry.coordinates.length > 0) {
                    data.features[0].geometry.coordinates[0].map(function (row) {
                        $("#latlngbox").append(`
                        <input class="latitude" type="hidden" name="lng[]" value="${row[0]}" />
                        <input class="longitude" type="hidden" name="lat[]" value="${row[1]}" />
                        `)
                    })
                    
                }
                const area = turf.area(data);
                // Restrict the area to 2 decimal points.
                const rounded_area = Math.round(area * 100) / 100;
                answer.innerHTML = `<p><strong>${rounded_area}</strong></p><p>square meters</p>`;
            } else {
                answer.innerHTML = '';
                if (e.type !== 'draw.delete')
                alert('Click the map to draw a polygon.');
            }
        }
        var str = "{{ json_encode($data['polygon']) }}",
            poly = JSON.parse(str.replace(/&quot;/g,'"')),
            polys = []

        for (var i = 0; i < poly.length; i ++) {
            var arra = [],
                obj = {}
            
            for (var n = 0; n < poly[i].length; n ++) {
                var cord = [poly[i][n].lng, poly[i][n].lat]
                arra.push(cord)
            }
            obj = {
                'type': 'Feature',
                'geometry': {
                'type': 'Polygon',
                'coordinates': [arra]
                }
            }
            polys.push(obj)
        }
        
        map.on('load', () => {
            // Add a data source containing GeoJSON data.
            map.addSource('maine', {
            'type': 'geojson',
            'data': {
            'type': 'FeatureCollection',
            'features': polys
            }
            });
             
            // Add a new layer to visualize the polygon.
            map.addLayer({
            'id': 'maine',
            'type': 'fill',
            'source': 'maine', // reference the data source
            'layout': {},
            'paint': {
            'fill-color': '#ff0039', // blue color fill
            'fill-opacity': 0.5
            }
            });
            
            });
    </script>
@endpush
