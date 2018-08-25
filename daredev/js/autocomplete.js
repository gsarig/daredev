function initAutocomplete() {
    const input = document.getElementById('dd-places-autocomplete');
    const searchBox = new google.maps.places.SearchBox(input);
    const link = document.getElementById('ddDirectionsLink');
    const origin = link.getAttribute('data-origin');
    searchBox.addListener('places_changed', function () {
        const places = searchBox.getPlaces();
        if (places.length === 0) {
            return;
        }
        places.forEach(function (place) {
            if (place.formatted_address) {
                link.setAttribute('href', encodeURI('https://www.google.com/maps/dir/?api=1&origin=' + place.formatted_address + '&destination=' + origin).replace(/%20/g, '+'));
                link.classList.add('active');
            } else {
                link.classList.remove('active');
            }
        });
    });
}

initAutocomplete();