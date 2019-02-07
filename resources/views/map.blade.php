@extends('layouts.app')
@include ('footer') 

@section('content')

<head>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.4/dist/leaflet.css"
          integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA=="
          crossorigin=""/>
    <!-- Make sure you put this AFTER Leaflet's CSS -->
    <script src="https://unpkg.com/leaflet@1.3.4/dist/leaflet.js"
            integrity="sha512-nMMmRyTVoLYqjP9hrbed9S+FzjZHW5gY1TWCHA5ckwXZBadntCNs8kEqAWdrb9O7rxbCaA4lKTIWjDXZxflOcA=="
    crossorigin=""></script>

    <style> #mapid { display:flex; height: 600px; } </style>

</head>

<body>

    <div class="relative">
        <div class="absolute"> 
            <button type="button" class="btn btn-primary" onclick="returnview()">Return to initial view</button>
            <br>
        </div>
        <br>
        <div class="absolute"> 
            <div id="mapid">
            </div>
        </div>
    </div>
</body>


<script defer>

    var mymap = L.map('mapid').setView([48.8566, 2.3522], 11);
    L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
        maxZoom: 18,
        id: 'mapbox.streets',
        accessToken: 'pk.eyJ1IjoiZ3VpbGxhdW1lLS0zNSIsImEiOiJjam95ZGl0Nm8yYWdnM2trZjh6eDB6cTN0In0.NvfE5abcZLDjnhBh5ChRzA'
    }).addTo(mymap);

    var nom2 = nomCommBizAdj;
    nbcommunesadj = polygonBizAdj.length;
    for (i = 0; i < nbcommunesadj; i++) {
        data = JSON.parse(polygonBizAdj[i]);
        L.geoJSON(data, {color: 'green'}).bindPopup(nom2[i]).addTo(mymap);
    }

    var nom = nomCommBiz;

    nbcommunes = polygonBiz.length;
    for (i = 0; i < nbcommunes; i++) {
        data = JSON.parse(polygonBiz[i]);
        L.geoJSON(data, {color: 'red'}).bindPopup(nom[i]).addTo(mymap);
    }

    var nom = nomCommOpport;

    nbcommunes = polygonOpport.length;
    for (i = 0; i < nbcommunes; i++) {
        data = JSON.parse(polygonOpport[i]);
        L.geoJSON(data, {color: 'blue'}).bindPopup(nom[i]).addTo(mymap);
    }

    function returnview() {
        mymap.setView([48.8566, 2.3522], 11);
    }


</script>

@stop




