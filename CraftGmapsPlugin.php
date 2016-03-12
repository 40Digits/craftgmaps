<?php
namespace Craft;

class CraftGmapsPlugin extends BasePlugin
{

    /**
     * Get Plugin Name to display in admin
     *
     * @return String
     */
    public function getName()
    {
         return Craft::t('Google Maps Field Type');
    }

    /**
     * Get version number
     *
     * Be sure to update this when you have a new migration
     *
     * @return String
     */
    public function getVersion()
    {
        return '0.0.1';
    }

    /**
     * Get developer name to display in admin
     *
     * @return String
     */
    public function getDeveloper()
    {
        return 'David Hahn';
    }

    /**
     * Get developer url to display in admin
     *
     * @return String
     */
    public function getDeveloperUrl()
    {
        return 'www.davidhahn.me';
    }

    protected function defineSettings()
    {
        return array(
            'googleMapsApiKey' => array(AttributeType::String),
        );
    }

    public function getSettingsHtml()
    {
        return craft()->templates->render('craftgmaps/settings', array(
            'settings' => $this->getSettings()
        ));
    }
}
