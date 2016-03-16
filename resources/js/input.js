function googleMapify(formattedAddressId, mapId, latId, lngId, defaultLat, defaultLng) {
  
  //Dom nodes
  var formattedAddressEl = document.getElementById(formattedAddressId),
  mapEl = document.getElementById(mapId),
  latEl = document.getElementById(latId),
  lngEl = document.getElementById(lngId),
  geocoder = new google.maps.Geocoder(),
  searchTimeout,
  mapCenter,
  zoom;


  if (!!latEl.value && !!lngEl.value) {
    mapCenter = { 
      lat: parseFloat(latEl.value),
      lng: parseFloat(lngEl.value)
    };
    
    zoom = 12;
  } else {
    mapCenter = { 
      lat: parseFloat(defaultLat),
      lng: parseFloat(defaultLng)
    };
    
    zoom = 4;
  }

  var map = new google.maps.Map(mapEl, {
    center: mapCenter,
    zoom: zoom
  });
  
  var marker = new google.maps.Marker({
    map: map,
    draggable: true,
    position: mapCenter,
  });

  var geocodeResults = function geocodeResults(results, status, givenLocation) {
    if (status === google.maps.GeocoderStatus.OK && !!results[0]) {
      var result = results[0];
      var location = !!givenLocation ? givenLocation : result.geometry.location;

      map.fitBounds(result.geometry.viewport);
      map.setCenter(location);
      marker.setPosition(location);

      updateValues(
          location.lat(), 
          location.lng(), 
          result.formatted_address
      );
    } else {
      alert('Geocode was not successful for the following reason: ' + status);
    }
  };

  var geocodeSearch = function geocodeSearch(address) {
    geocoder.geocode({ 'address': address }, geocodeResults);
  };

  var updateValues = function updateValues(lat, lng, address) {
    formattedAddressEl.value = address;
    latEl.value = lat;
    lngEl.value = lng;
  };
  
  formattedAddressEl.addEventListener('keyup', function() {
    address = this.value;
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout( function() { 
      geocodeSearch(address);
    }, 750);
  });

  google.maps.event.addListener(marker, "dragend", function () {
    geocoder.geocode({ 'location': marker.getPosition() }, function(results, status) {
     geocodeResults(results, status, marker.getPosition());
    });
  });
}
