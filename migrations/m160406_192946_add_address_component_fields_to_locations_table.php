<?php
namespace Craft;

/**
 * The class name is the UTC timestamp in the format of mYYMMDD_HHMMSS_migrationName
 */

class m160406_192946_add_address_component_fields_to_locations_table extends BaseMigration
{
    /**
     * Any migration code in here is wrapped inside of a transaction.
     *
     * @return bool
     */
    public function safeUp()
    {
        $this->addColumnAfter('craftgmaps_locations', 'street', array(ColumnType::Varchar, 'required' => false), 'formattedAddress');
        $this->addColumnAfter('craftgmaps_locations', 'city', array(ColumnType::Varchar, 'required' => false), 'street');
        $this->addColumnAfter('craftgmaps_locations', 'state', array(ColumnType::Varchar, 'required' => false), 'city');
        $this->addColumnAfter('craftgmaps_locations', 'country', array(ColumnType::Varchar, 'required' => false), 'state');
        $this->addColumnAfter('craftgmaps_locations', 'zip', array(ColumnType::Varchar, 'required' => false), 'country');

        return true;
    }
}
