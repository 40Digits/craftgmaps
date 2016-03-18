<?php
namespace Craft;

class CraftGmaps_LocationService extends BaseApplicationComponent
{
    /**
     * Given a location, return status of creation or update
     *
     * @param  CraftGmaps_LocationModel &$locationModel
     * @return Boolean
     */
    public function createOrUpdateRecord(CraftGmaps_LocationModel &$locationModel)
    {
        if ($locationModel->validate()) {
            $locationRecord = $this->findOrInstantiateRecord($locationModel->getAttribute('id'));
            $locationRecord->setAttribute('formattedAddress', $locationModel->formattedAddress);
            $locationRecord->setAttribute('street', $locationModel->street);
            $locationRecord->setAttribute('city', $locationModel->city);
            $locationRecord->setAttribute('state', $locationModel->state);
            $locationRecord->setAttribute('country', $locationModel->country);
            $locationRecord->setAttribute('zip', $locationModel->zip);
            $locationRecord->setAttribute('lat', $locationModel->lat);
            $locationRecord->setAttribute('lng', $locationModel->lng);
            $locationRecord->setAttribute('elementId', $locationModel->elementId);
            $locationRecord->setAttribute('zoom', $locationModel->zoom);
            $locationRecord->save();
            $locationModel->setAttribute('id', $locationRecord->getAttribute('id'));
            return true;
        } else {
            return false;
        }
    }

    public function findLocationsByField($fieldSlug)
    {

        $criteria = new \CDbCriteria;
        $criteria->select = '*';
        $criteria->join = 'INNER JOIN `craft_elements` ON craft_elements.id = `t`.`elementId`'
                        . 'INNER JOIN `craft_content` ON `craft_content`.`elementId` = `craft_elements`.`id`';
        $criteria->condition = '`field_' . $fieldSlug . '` IS NOT NULL';
        $records = CraftGmaps_LocationRecord::model()->findAll($criteria);

        $models = [];
        foreach ($records as $record) {
            $models[] = $this->populateModel($record);
        }
        
        return $models;
    }

    public function find($id)
    {
        $locationRecord = null;

        if (!empty($id)) {
            $locationRecord = CraftGmaps_LocationRecord::model()->findById($id);

            if (!$locationRecord) {
                throw new Exception(Craft::t('Can\'t find location with ID "{id}"', array('id' => $id)));
            }
        }
    
        return $locationRecord;
    }

    public function findOrInstantiate($id)
    {
        return $this->populateModel($this->findOrInstantiateRecord($id));
    }

    /**
     * If LocationRecord cannot be found via ID instantiate new LocationRecord
     *
     * @param  Integer $id
     * @return CraftGmaps_LocationRecord
     */
    private function findOrInstantiateRecord($id)
    {
        $locationRecord = $this->find($id);

        if (empty($locationRecord)) {
            $locationRecord = new CraftGmaps_LocationRecord();
        }

        return $locationRecord;
    }

    private function populateModel(CraftGmaps_LocationRecord $record)
    {
        $model = CraftGmaps_LocationModel::populateModel($record);

        return $model;
    }
}
