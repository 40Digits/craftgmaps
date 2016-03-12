<?php
namespace Craft;

class CraftGmaps_LocationRecord extends BaseRecord
{
    public function getTableName()
    {
        return 'craftgmaps_locations';
    }

    protected function defineAttributes()
    {
        return array(
            'formattedAddress' => array(AttributeType::String),
            'elementId' => array(AttributeType::Number),
            'lat' => array(AttributeType::String),
            'lng' => array(AttributeType::String),
        );
    }

    public function defineRelations()
    {
        return array(
            'element' => array(static::BELONGS_TO, 'ElementRecord', 'elementId'),
        );
    }
}
