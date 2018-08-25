(function () {
    var reg = new RegExp("^-?([1-8]?[1-9]|[1-9]0)\.{1}\d{1,6}");
    var mapId = mapData.mapId;
    var getLat = mapData.lat;
    var getLng = mapData.lng;
    var theZoom = mapData.zoom;
    var getDesc = mapData.desc;
    var getIcon = mapData.icon;
    var icon = getIcon.constructor === Array ? Array.prototype.concat.apply([], mapData.icon) : mapData.icon;
    var lat = getLat.constructor === Array ? getLat : Array(getLat);
    var lng = getLng.constructor === Array ? getLng : Array(getLng);
    var colors = mapData.colors;
    var locations = [];
    for (var i = 0; i < lat.length; i++) {
        if (inrange(-90, lat[i][0], 90) && inrange(-180, lng[i][0], 180)) { // Validate lat and lng first
            locations.push({
                lat: parseFloat(lat[i][0]),
                lng: parseFloat(lng[i][0]),
                info: getDesc[i][0],
            });
        }
    }

    // Test if value is in proper range
    function inrange(min, number, max) {
        return (!isNaN(number) && (number >= min) && (number <= max));
    }

    function initMap() {

        var map = new google.maps.Map(document.getElementById(mapId), {
            zoom: 1,
            center: {
                lat: 37.9838096,
                lng: 23.727538800000048
            }
        });

        var bounds = new google.maps.LatLngBounds();


        // Custom infobox
        var boxText = document.createElement('div');
        var myOptions = {
            content: boxText,
            alignBottom: true,
            pixelOffset: new google.maps.Size(-100, -50),
        };
        var ib = new InfoBox(myOptions);

        // Add some markers to the map.
        // Note: The code uses the JavaScript Array.prototype.map() method to
        // create an array of markers based on a given "locations" array.
        // The map() method here has nothing to do with the Google Maps API.
        var markers = locations.map(function (location, i) {
            bounds.extend(location);
            map.fitBounds(bounds);
            var marker = new google.maps.Marker({
                position: location,
                icon: mapData.themeUrl + '/images/marker.png'
            });

            google.maps.event.addListener(marker, 'click', function (evt) {
                boxText.innerHTML = location.info;
                ib.open(map, marker);
            });

            return marker;
        });

        // markerCluster.setMarkers(markers);
        // Add a marker clusterer to manage the markers.
        var markerCluster = new MarkerClusterer(map, markers, {
            styles: [
                {
                    height: 39,
                    width: 39,
                    url: mapData.themeUrl + '/images/cluster.png'
                }
            ]
        });
    }

    google.maps.event.addDomListener(window, 'load', initMap);
})();
