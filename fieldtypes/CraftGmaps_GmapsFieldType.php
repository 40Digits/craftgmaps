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
        $textId = craft()->templates->formatInputId($name);
        $mapId = 'maps';
        $lngId = 'lat';
        $latId = 'lng';

        $namespacedTextId = craft()->templates->namespaceInputId($textId);
        $namespacedMapId = craft()->templates->namespaceInputId($mapId);
        $namespacedLngId = craft()->templates->namespaceInputId($lngId);
        $namespacedLatId = craft()->templates->namespaceInputId($latId);

        craft()->templates->includeJsFile('//maps.googleapis.com/maps/api/js');
        craft()->templates->includeJsResource('craftgmaps/js/input.js');
        craft()->templates->includeJs(
            "googleMapify(
                '" . $namespacedTextId . "', 
                '" . $namespacedMapId . "',
                '" . $namespacedLngId . "',
                '" . $namespacedLatId . "',
                '" . $this->getSettings()->defaultLat . "',
                '" . $this->getSettings()->defaultLng . "'
            );"
        );

        return craft()->templates->render('craftgmaps/gmaps/input', array(
            'name'  => $name,
            'location' => $locationModel,
            'textId' => $textId,
            'mapId' => $mapId,
            'latId' => $latId,
            'lngId' => $lngId,
            'namespacedMapId' => $namespacedMapId,
            'settings' => $this->getSettings()
        ));
    }

    protected function defineSettings()
    {
        return array(
            'defaultLat' => array(AttributeType::String),
            'defaultLng' => array(AttributeType::String)
        );
    }

    public function getSettingsHtml()
    {
        return craft()->templates->render('craftgmaps/gmaps/settings', array(
            'settings' => $this->getSettings()
        ));
    }

    public function prepValueFromPost($value) {}

    public function onAfterElementSave()
    {
        $fieldHandle = $this->model->handle;

        if ($this->element !== null) {
            if (empty($this->element->getContent()->$fieldHandle)) {
                $value = $_POST['fields'][$fieldHandle];

                $locationModel = new CraftGmaps_LocationModel();
                $locationModel->formattedAddress = $value['formattedAddress'];
                $locationModel->lat = $value['lat'];
                $locationModel->lng = $value['lng'];
                $locationModel->entryId = $this->element->id;

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
