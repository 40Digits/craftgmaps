function googleMapify(formattedAddressId, mapId, latId, lngId, defaultLat, defaultLng) {
  var formattedAddressEl = document.getElementById(formattedAddressId);
  var mapEl = document.getElementById(mapId);
  var latEl = document.getElementById(latId);
  var lngEl = document.getElementById(lngId);
  var geocoder = new google.maps.Geocoder();

  if (!!latEl.value && !!lngEl.value) {
    var mapCenter = { 
      lat: parseFloat(latEl.value),
      lng: parseFloat(lngEl.value)
    }
    
    var zoom = 12;
  } else {
    var mapCenter = { 
      lat: parseFloat(defaultLat),
      lng: parseFloat(defaultLng)
    }
    
    var zoom = 4;
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

  var geocodeResults = function(results, status) {
    if (status === google.maps.GeocoderStatus.OK && !!results[0]) {
      var result = results[0];
      var location = result.geometry.location;

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
  }

  var geocodeSearch = function(address) {
    geocoder.geocode({ 'address': address }, geocodeResults);
  };

  var updateValues = function(lat, lng, address) {
    formattedAddressEl.value = address;
    latEl.value = lat;
    lngEl.value = lng;
  }
  
  var searchTimeout;
  formattedAddressEl.addEventListener('keyup', function() {
    address = this.value;
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout( function() { 
      geocodeSearch(address);
    }, 750);
  });

  google.maps.event.addListener(marker, "dragend", function () {
    var latlng = { 
      lat: marker.getPosition().lat(),
      lng: marker.getPosition().lng()
    };

    geocoder.geocode({ 'location': latlng }, geocodeResults);
  });
};
