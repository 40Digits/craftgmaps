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
                '" . $namespacedLatId . "'
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
        ));
    }

    public function getSettingsHtml()
    {
        #TODO add google maps api keys
        #TODO add default center of map

        return '';
    }

    public function prepValueFromPost($value) {}

    public function onAfterElementSave()
    {
        $fieldHandle = $this->model->handle;

        if ($this->element !== null) {
            if (empty($this->element->getContent()->$fieldHandle)) {
                $value = $_POST['fields'][$fieldHandle];

                $locationModel = new CraftGmaps_LocationModel();
                $locationModel->name = $value['text'];
                $locationModel->lat = $value['lat'];
                $locationModel->lng = $value['lng'];
                $locationModel->entryId = $this->element->id;

                if (!empty($value['textId'])) {
                    $locationModel->id = $value['textId'];
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
