<?php
namespace Craft;

/**
 * The class name is the UTC timestamp in the format of mYYMMDD_HHMMSS_migrationName
 */
class m160304_192946_CreateLocationsTable extends BaseMigration
{
    /**
     * Any migration code in here is wrapped inside of a transaction.
     *
     * @return bool
     */
    public function safeUp()
    {
        // Create the craft_craftgmaps_locations table
        craft()->db->createCommand()->createTable('craftgmaps_locations', array(
            'name'    => array('required' => false),
            'entryId' => array('maxLength' => 11, 'decimals' => 0, 'unsigned' => false, 'length' => 10, 'column' => 'integer', 'required' => true)
        ), null, true);
        

        craft()->db->createCommand()->addForeignKey('craftgmaps_locations', 'entryId', 'entries', 'id', 'CASCADE', null);

        return true;
    }
}
