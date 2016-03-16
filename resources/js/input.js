function googleMapify(formattedAddressId, mapId, latId, lngId, defaultLat, defaultLng, resultsId) {
  
  //Dom nodes
  var formattedAddressEl = document.getElementById(formattedAddressId),
  mapEl = document.getElementById(mapId),
  latEl = document.getElementById(latId),
  lngEl = document.getElementById(lngId),
  geocoder = new google.maps.Geocoder(),
  service = new google.maps.places.AutocompleteService();
  autocomplete;
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
          location.lng()
      );
    } else {
      alert('Geocode was not successful for the following reason: ' + status);
    }
  };

  var geocodeSearch = function geocodeSearch(address) {
    geocoder.geocode({ 'address': address }, geocodeResults);
  };

  var updateValues = function(lat, lng) {
    latEl.value = lat;
    lngEl.value = lng;
  }

  var initAutocomplete = function initAutocomplete() {
    // Create the autocomplete object, restricting the search to geographical location types.
    autocomplete = new google.maps.places.Autocomplete(
        (document.getElementById(formattedAddressId)),
        {types: ['geocode']});

    // When the user selects an address from the dropdown, populate the address fields in the form.
    autocomplete.addListener('place_changed', onPredictionSelection);
  }

  var onPredictionSelection = function onPredictionSelection() {
    // Get the place details from the autocomplete object.
    var place = autocomplete.getPlace();

    updateValues(
      place.geometry.location.lat(), 
      place.geometry.location.lng()
    )
  
    map.setCenter(place.geometry.location);
    map.setZoom(15);
    marker.setPosition(place.geometry.location);
  }

  google.maps.event.addListener(marker, "dragend", function () {
    geocoder.geocode({ 'location': marker.getPosition() }, function(results, status) {
     geocodeResults(results, status, marker.getPosition());
    });
  });

  initAutocomplete();
};
