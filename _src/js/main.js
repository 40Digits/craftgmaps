/* global google */

// TODO refactor needed - it even affects the js
window.googleMapify = function googleMapify(
    formattedAddressId,
    mapId,
    zoomId,
    latId,
    lngId,
    streetId,
    cityId,
    stateId,
    countryId,
    zipId
) {
  // TODO refactor needed - it's here too
  const formattedAddressEl = document.getElementById(formattedAddressId);
  const mapEl = document.getElementById(mapId);
  const zoomEl = document.getElementById(zoomId);
  const latEl = document.getElementById(latId);
  const lngEl = document.getElementById(lngId);
  const streetEl = document.getElementById(streetId);
  const cityEl = document.getElementById(cityId);
  const stateEl = document.getElementById(stateId);
  const countryEl = document.getElementById(countryId);
  const zipEl = document.getElementById(zipId);
  const geocoder = new google.maps.Geocoder();
  const componentForm = {
    street_number: 'short_name',
    route: 'long_name',
    locality: 'long_name',
    administrative_area_level_1: 'short_name',
    country: 'long_name',
    postal_code: 'short_name',
  };
  let autocomplete;

  const mapCenter = {
    lat: parseFloat(latEl.value),
    lng: parseFloat(lngEl.value),
  };
  const zoom = parseInt(zoomEl.value, 0);

  const map = new google.maps.Map(mapEl, {
    center: mapCenter,
    zoom,
    scrollwheel: false,
    navigationControl: false,
    mapTypeControl: false,
    scaleControl: false,
    draggable: true,
  });

  const marker = new google.maps.Marker({
    map,
    draggable: true,
    position: mapCenter,
  });

  const updateValues = function updateValues(lat, lng, addressComponents) {
    latEl.value = lat;
    lngEl.value = lng;
    zoomEl.value = map.getZoom();

    if (!!addressComponents) {
      streetEl.value = !!addressComponents.street_number ? addressComponents.street_number : '';
      streetEl.value += !!addressComponents.route ? ` ${addressComponents.route}` : '';
      cityEl.value = !!addressComponents.locality ? addressComponents.locality : '';
      stateEl.value = !!addressComponents.administrative_area_level_1 ?
        addressComponents.administrative_area_level_1 : '';
      countryEl.value = !!addressComponents.country ? addressComponents.country : '';
      zipEl.value = !!addressComponents.postal_code ? addressComponents.postal_code : '';
    }
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
    const addressComponents = {};

    // Get each component of the address from the place details
    // and fill the corresponding field on the form.
    for (let i = 0; i < place.address_components.length; i++) {
      const addressType = place.address_components[i].types[0];
      if (componentForm[addressType]) {
        const val = place.address_components[i][componentForm[addressType]];
        addressComponents[addressType] = val;
      }
    }

    updateValues(
      place.geometry.location.lat(),
      place.geometry.location.lng(),
      addressComponents
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

  google.maps.event.addListener(map, 'zoom_changed', () => {
    console.log(map.getZoom());
    zoomEl.value = map.getZoom();
  });

  initAutocomplete();
};
