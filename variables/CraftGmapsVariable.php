<?php
namespace Craft;

class CraftGmapsVariable
{
    public function findLocationsByField($fieldSlug)
    {
        return craft()->craftGmaps_location->findLocationsByField($fieldSlug);
    }
}
