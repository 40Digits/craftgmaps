/* global google */

window.googleMapify = function googleMapify(
    formattedAddressId, mapId, latId, lngId, defaultLat, defaultLng
) {
  // Dom nodes
  const formattedAddressEl = document.getElementById(formattedAddressId);
  const mapEl = document.getElementById(mapId);
  const latEl = document.getElementById(latId);
  const lngEl = document.getElementById(lngId);
  const geocoder = new google.maps.Geocoder();
  let autocomplete;
  let mapCenter;
  let zoom;

  if (!!latEl.value && !!lngEl.value) {
    mapCenter = {
      lat: parseFloat(latEl.value),
      lng: parseFloat(lngEl.value),
    };
    zoom = 12;
  } else {
    mapCenter = {
      lat: parseFloat(defaultLat),
      lng: parseFloat(defaultLng),
    };
    zoom = 4;
  }

  const map = new google.maps.Map(mapEl, {
    center: mapCenter,
    zoom,
  });

  const marker = new google.maps.Marker({
    map,
    draggable: true,
    position: mapCenter,
  });

  const updateValues = function updateValues(lat, lng) {
    latEl.value = lat;
    lngEl.value = lng;
  };

  const geocodeResults = function geocodeResults(results, status, givenLocation) {
    if (status === google.maps.GeocoderStatus.OK && !!results[0]) {
      const result = results[0];
      const location = !!givenLocation ? givenLocation : result.geometry.location;

      map.fitBounds(result.geometry.viewport);
      map.setCenter(location);
      marker.setPosition(location);

      updateValues(
          location.lat(),
          location.lng()
      );
    } else {
      alert(`Geocode was not successful for the following reason: ${status}`);
    }
  };

  const onPredictionSelection = function onPredictionSelection() {
    const place = autocomplete.getPlace();

    updateValues(
      place.geometry.location.lat(),
      place.geometry.location.lng()
    );

    map.setCenter(place.geometry.location);
    map.setZoom(15);
    marker.setPosition(place.geometry.location);
  };

  const initAutocomplete = function initAutocomplete() {
    // Create the autocomplete object, restricting the search to geographical location types.
    autocomplete = new google.maps.places.Autocomplete(
        formattedAddressEl,
        { types: ['geocode'] }
    );

    // When the user selects an address from the dropdown, populate the address fields in the form.
    autocomplete.addListener('place_changed', onPredictionSelection);
  };

  google.maps.event.addListener(marker, 'dragend', () => {
    geocoder.geocode({
      location: marker.getPosition(),
    }, (results, status) => {
      geocodeResults(results, status, marker.getPosition());
    });
  });

  initAutocomplete();
};
