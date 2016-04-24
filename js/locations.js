// Define your locations: HTML content for the info window, latitude, longitude
var mapId = mapData.mapId,
    getLat = mapData.lat,
    getLng = mapData.lng,
    theZoom = mapData.zoom,
    title = mapData.title,
    getDesc = mapData.desc,
    icon = mapData.icon,
    getImg = mapData.img,
    getMore = mapData.more,
    lat = getLat.constructor === Array ? getLat : Array(getLat),
    lng = getLng.constructor === Array ? getLng : Array(getLng),
    colors = mapData.colors;

var isDraggable = !('ontouchstart' in document.documentElement); // If on touch device, disable drag map
var map = new google.maps.Map(document.getElementById(mapId), {
    zoom: 14,
    center: new google.maps.LatLng(-37.92, 151.25),
    mapTypeId: google.maps.MapTypeId.ROADMAP,
    mapTypeControl: false,
    draggable: isDraggable,
    scrollwheel: false,
    streetViewControl: false,
    panControl: false,
    zoomControlOptions: {
        position: google.maps.ControlPosition.LEFT_BOTTOM
    }
});

// Style Map
var styles = (colors) ? [
    {
        "featureType": "administrative",
        "elementType": "all",
        "stylers": [{
            "visibility": "simplified"
        }]
    }, {
        "featureType": "landscape",
        "elementType": "geometry",
        "stylers": [{
            "visibility": "simplified"
        }, {
            "color": '#' + colors[1]
        }]
    }, {
        "featureType": "poi",
        "elementType": "geometry",
        "stylers": [{
            "visibility": "simplified"
        }, {
            "color": '#' + colors[1]
        }]
    }, {
        "featureType": "road.highway",
        "elementType": "geometry",
        "stylers": [{
            "visibility": "simplified"
        }, {
            "color": '#' + colors[2]
        }]
    }, {
        "featureType": "road.arterial",
        "elementType": "geometry",
        "stylers": [{
            "visibility": "simplified"
        }, {
            "color": '#' + colors[2]
        }]
    }, {
        "featureType": "road.local",
        "elementType": "geometry",
        "stylers": [{
            "visibility": "simplified"
        }, {
            "color": '#' + colors[2]
        }]
    }, {
        "featureType": "water",
        "elementType": "geometry",
        "stylers": [{
            "visibility": "simplified"
        }, {
            "color": '#' + colors[0]
        }]
    }
] : '';

map.setOptions({
    styles: styles
});

var infoWindow = new google.maps.InfoWindow({
    maxWidth: 260
});

var markers = [];

// Add the markers and infoWindows to the map
for (var i = 0; i < lat.length; i++) {
    var marker = new google.maps.Marker({
        position: new google.maps.LatLng(lat[i], lng[i]),
        map: map,
        icon: icon
    });

    markers.push(marker);

    google.maps.event.addListener(marker, 'click', (function (marker, i) {
        return function () {
            var img = getImg[i] !== undefined ? getImg[i] : '',
                desc = getDesc[i] !== undefined ? getDesc[i] : '',
                more = getMore[i] !== undefined ? getMore[i] : '';
            infoWindow.setContent(img + title[i] + desc + more);
            infoWindow.open(map, marker);
        };
    })(marker, i));
}

function autoCenter() {
    //  Create a new viewpoint bound
    var bounds = new google.maps.LatLngBounds();
    //  Go through each...
    for (var i = 0; i < markers.length; i++) {
        bounds.extend(markers[i].position);
    }
    //  If there are more than one markers, fit these bounds to the map
    if (markers.length > 1) {
        map.fitBounds(bounds);
    } else { // otherwise set a default zoom level
        map.setCenter(bounds.getCenter());
        map.setZoom(parseFloat(theZoom));
    }
}
autoCenter();