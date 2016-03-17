<?php
namespace Craft;

class CraftGmaps_LocationModel extends BaseModel
{
    public function defineAttributes()
    {
        return array(
            'id' => AttributeType::Number,
            'formattedAddress' => AttributeType::String,
            'street' => array(AttributeType::String),
            'city' => array(AttributeType::String),
            'state' => array(AttributeType::String),
            'country' => array(AttributeType::String),
            'zip' => array(AttributeType::String),
            'elementId' => AttributeType::Number,
            'element' => AttributeType::ClassName,
            'zoom' => AttributeType::Number,
            'lat' => AttributeType::String,
            'lng' => AttributeType::String,
        );
    }

    public function __toString()
    {
        return $this->id . ' ' . $this->name . ' ' . $this->entryId;
    }
}
