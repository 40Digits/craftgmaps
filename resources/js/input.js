function googleMapify(formattedAddressId, mapId, latId, lngId) {
  var formattedAddressEl = document.getElementById(formattedAddressId);
  var mapEl = document.getElementById(mapId);
  var latEl = document.getElementById(latId);
  var lngEl = document.getElementById(lngId);

  var mapCenter = { 
    lat: parseFloat(latEl.value), 
    lng: parseFloat(lngEl.value) 
  };

  var map = new google.maps.Map(mapEl, {
    center: mapCenter,
    zoom: 12
  });
  
  var marker = new google.maps.Marker({
    map: map,
    position: mapCenter,
  });

  var geocodeSearch = function(address) {
    new google.maps.Geocoder().geocode({ 'address': address }, function(results, status) {
      if (status === google.maps.GeocoderStatus.OK && !!results[0]) {
        map.fitBounds(results[0].geometry.viewport);
        map.setCenter(results[0].geometry.location);
        marker.setPosition(results[0].geometry.location);
        formattedAddressEl.value = results[0].formatted_address;
        latEl.value = results[0].geometry.location.lat();
        lngEl.value = results[0].geometry.location.lng();
      } else {
        alert('Geocode was not successful for the following reason: ' + status);
      }
    });
  };

  formattedAddressEl.addEventListener('change', function() {
    geocodeSearch(this.value);
j });
};
