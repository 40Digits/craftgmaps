<?php
namespace Craft;

class CraftGmaps_GmapsFieldType extends BaseFieldType
{
    public function getName()
    {
        return Craft::t('Google Maps');
    }

    public function defineContentAttribute()
    {
        return AttributeType::Number;
    }

    public function getInputHtml($name, $locationModel)
    {
        //TODO refactor needed - let's find a way to not have to do this
        $textId = craft()->templates->formatInputId($name);
        $mapId = 'maps';
        $zoomId = 'zoom';
        $latId = 'lat';
        $lngId = 'lng';
        $streetId = 'street';
        $cityId = 'city';
        $stateId = 'state';
        $countryId = 'country';
        $zipId = 'zip';

        //TODO refactor needed - there's gotta be a better way
        $namespacedTextId = craft()->templates->namespaceInputId($textId);
        $namespacedMapId = craft()->templates->namespaceInputId($mapId);
        $namespacedZoomId = craft()->templates->namespaceInputId($zoomId);
        $namespacedLatId = craft()->templates->namespaceInputId($latId);
        $namespacedLngId = craft()->templates->namespaceInputId($lngId);
        $namespacedStreetId = craft()->templates->namespaceInputId($streetId);
        $namespacedCityId = craft()->templates->namespaceInputId($cityId);
        $namespacedStateId = craft()->templates->namespaceInputId($stateId);
        $namespacedCountryId = craft()->templates->namespaceInputId($countryId);
        $namespacedZipId = craft()->templates->namespaceInputId($zipId);

        $apiKey = "?key=" . craft()->plugins->getPlugin('craftgmaps')->getSettings()->googleMapsApiKey;

        craft()->templates->includeJsFile('//maps.googleapis.com/maps/api/js' . $apiKey . '&libraries=places');
        craft()->templates->includeJsResource('craftgmaps/js/input.js');
        //TODO refactor needed - oh my god
        craft()->templates->includeJs(
            "window.googleMapify(
                '" . $namespacedTextId . "', 
                '" . $namespacedMapId . "',
                '" . $namespacedZoomId . "',
                '" . $namespacedLatId . "',
                '" . $namespacedLngId . "',
                '" . $namespacedStreetId . "',
                '" . $namespacedCityId . "',
                '" . $namespacedStateId . "',
                '" . $namespacedCountryId . "',
                '" . $namespacedZipId . "'
            );"
        );

        //TODO refactor needed - this is terrible
        return craft()->templates->render('craftgmaps/gmaps/input', array(
            'name'  => $name,
            'location' => $locationModel,
            'textId' => $textId,
            'mapId' => $mapId,
            'zoomId' => $zoomId,
            'latId' => $latId,
            'lngId' => $lngId,
            'streetId' => $streetId,
            'cityId' => $cityId,
            'stateId' => $stateId,
            'countryId' => $countryId,
            'zipId' => $zipId,
            'namespacedMapId' => $namespacedMapId,
            'settings' => $this->getSettings()
        ));
    }

    protected function defineSettings()
    {
        return array(
            'defaultLat' => array(AttributeType::String),
            'defaultLng' => array(AttributeType::String),
            'defaultZoom' => array(AttributeType::Number)
        );
    }

    public function getSettingsHtml()
    {
        return craft()->templates->render('craftgmaps/gmaps/settings', array(
            'settings' => $this->getSettings()
        ));
    }

    public function prepValueFromPost($value)
    {
    }

    public function onAfterElementSave()
    {
        $fieldHandle = $this->model->handle;

        if ($this->element !== null) {
            if (empty($this->element->getContent()->$fieldHandle)) {
                $value = $_POST['fields'][$fieldHandle];

                $locationModel = new CraftGmaps_LocationModel();
                $locationModel->formattedAddress = $value['formattedAddress'];
                $locationModel->zoom = $value['zoom'];
                $locationModel->lat = $value['lat'];
                $locationModel->lng = $value['lng'];
                $locationModel->street = $value['street'];
                $locationModel->city = $value['city'];
                $locationModel->state = $value['state'];
                $locationModel->country = $value['country'];
                $locationModel->zip = $value['zip'];
                $locationModel->elementId = $this->element->id;

                if (!empty($value['locationId'])) {
                    $locationModel->id = $value['locationId'];
                }


                craft()->craftGmaps_location->createOrUpdateRecord($locationModel);
                $this->element->getContent()->setAttribute($fieldHandle, $locationModel->id);
                craft()->elements->saveElement($this->element);
            }
        }
    }
    
    public function prepValue($value)
    {
        return craft()->craftGmaps_location->findOrInstantiate($value);
    }
}
