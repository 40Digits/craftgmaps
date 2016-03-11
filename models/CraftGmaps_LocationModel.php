<?php
namespace Craft;

class CraftGmaps_LocationModel extends BaseModel
{
    public function defineAttributes()
    {
        return array(
            'id' => AttributeType::Number,
            'name' => AttributeType::String,
            'entryId' => AttributeType::Number,
            'entry' => AttributeType::ClassName,
            'lat' => AttributeType::String,
            'lng' => AttributeType::String,
        );
    }

    public function __toString()
    {
        return $this->id . ' ' . $this->name . ' ' . $this->entryId;
    }
}
