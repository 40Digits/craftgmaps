# A Simple Google Maps Field Type for CraftCMS

## Overview

This is a plugin that provides a simple Field Type for CraftCMS. It's very similar to ACF's Google Maps Field with a few twists. First of all, it stores all of the locations in a separate table. Secondly, it stores address components and lat/lng as different fields on a row. 

## Getting Started

1. Download and copy to plugins directory.
  * Make sure to name this plugin's folder `craftgmaps/`
  * A quick command to do this from the root of your Craft project is `mkdir tmp && curl -L https://github.com/40Digits/craftgmaps/archive/master.zip -o tmp/craftgmaps.zip && unzip tmp/craftgmaps.zip -d tmp/ && cp -r tmp/craftgmaps craft/plugins/.; rm -rf tmp/;` (note: this won't work currently because it's a private repo).
2. Enable the plugin via the admin interface
3. Add a field with the "Google Maps" fieldtype
4. Create an entry and fill in the address field. (Make sure to click on one of the autocompleted address).
5. Now your template will return a `CraftGmaps_LocationModel` which has attributes like `lat`, `lng`, and `formattedAddress`.
  * If you need help adding a marker to a map check out our [example usage](#example-usage).

## Settings

### Plugin Settings

If you anticipate lots of admin usage (like more than 2500 requests per day) then you'll need to create a Google Maps API key. If for no other reason to remove that pesky console mesage, it's still a good idea to add a key (they are free).

### Field Settings

For each Google Maps field, you have the options to add a Default Latitude, Default Longitude, and Default Zoom. This is what the map defaults to in the admin interface.

## Template Variable

We also provide a template variable/method that allows you to retrieve all the locations associated with a specific field. Example usage:

```
{% for location in craft.craftGmaps.findLocationsByField(‘fieldSlug') %}
      Lat: {{ location.lat }}
      Lng: {{ location.lng }}
{% endfor %}
```
## Example Usage

### Single Marker on a Map

I wouldn’t suggest using this for production, but it’s an easy proof of concept.

```
    <div id="map" style="height: 400px; width: 400px;"></div>

    <script>
      function initMap() {
        var myLatLng = { lat: {{ entry.fieldSlug.lat }}, lng: {{ entry.fieldSlug.lng }} };

        var zoom = {{ entry.fieldSlug.zoom }};

        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: zoom,
          center: myLatLng
        });

        var marker = new google.maps.Marker({
          position: myLatLng,
          map: map,
          title: 'My Location!'
        });
      }
    </script>

    <script async defer
    src="https://maps.googleapis.com/maps/api/js?&callback=initMap">
    </script>
```

### Multiple Markers on a Map
TODO Add example usage for multiple markers on a map

## Contributing

We encourage you to contribute to this plugin! Here are details how to set it up locally for development:

1. Setup a Craft install.
  * I have one local install that I use to develop all Craft plugins.
2. Fork this repo
3. Clone the forked repo to your local
  * We would suggest doing this outside of the Craft install
4. npm install
  * To build the Javascript run `npm run watch`
  * Uses babel and ES2015
5. Symlink the repo to your Craft install's plugin repo
6. Install in the admin interface

## License

This plugin is released under the [MIT License](http://www.opensource.org/licenses/MIT).
