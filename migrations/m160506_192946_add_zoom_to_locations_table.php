<?php
namespace Craft;

/**
 * The class name is the UTC timestamp in the format of mYYMMDD_HHMMSS_migrationName
 */


class m160506_192946_add_zoom_to_locations_table extends BaseMigration
{
    /**
     * Any migration code in here is wrapped inside of a transaction.
     *
     * @return bool
     */
    public function safeUp()
    {
        $this->addColumnAfter('craftgmaps_locations', 'zoom', array(ColumnType::Int, 'required' => false), 'lng');

        return true;
    }
}
