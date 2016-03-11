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
        $namespacedTextId = craft()->templates->namespaceInputId($textId);
        $namespacedMapId = craft()->templates->namespaceInputId($mapId);

        craft()->templates->includeJsFile('//maps.googleapis.com/maps/api/js');
        craft()->templates->includeJsResource('craftgmaps/js/input.js');
        craft()->templates->includeJs(
            "googleMapify('" . $namespacedTextId . "', '" . $namespacedMapId . "');"
        );

        return craft()->templates->render('craftgmaps/gmaps/input', array(
            'name'  => $name,
            'location' => $locationModel,
            'textId' => $textId,
            'mapId' => $mapId,
            'namespacedMapId' => $namespacedMapId,
            'namespacedTextId' => $namespacedTextId
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
                $locationModel->entryId = $this->element->id;

                if (!empty($value['id'])) {
                    $locationModel->id = $value['id'];
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
