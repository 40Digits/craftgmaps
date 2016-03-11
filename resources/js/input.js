function googleMapify(textId, mapId) {
  var map = new google.maps.Map(document.getElementById(mapId), {
    center: { lat: -34.397, lng: 150.644 },
    scrollwheel: false,
    zoom: 8
  });

  var marker = new google.maps.Marker({
    map: map,
  });

  var geocodeSearch = function(address) {
    new google.maps.Geocoder().geocode({'address': address}, function(results, status) {
      if (status === google.maps.GeocoderStatus.OK) {
        map.fitBounds(results[0].geometry.viewport);
        map.setCenter(results[0].geometry.location);
        marker.setPosition(results[0].geometry.location);
        textEl.value = results[0].formatted_address;
      } else {
        alert('Geocode was not successful for the following reason: ' + status);
      }
    });
  };

  var textEl = document.getElementById(textId);

  textEl.addEventListener('change', function() {
    geocodeSearch(this.value);
  });

  geocodeSearch(textEl.value);
};
