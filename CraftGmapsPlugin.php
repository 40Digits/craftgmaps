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
     * Plugins can have descriptions of themselves displayed on the Plugins page by adding a getDescription() method
     * on the primary plugin class:
     *
     * @return mixed
     */
    public function getDescription()
    {
        return Craft::t('A simple Google Maps field type for CraftCMS');
    }
    
    /**
     * Plugins can have links to their documentation on the Plugins page by adding a getDocumentationUrl() method on
     * the primary plugin class:
     *
     * @return string
     */
    public function getDocumentationUrl()
    {
        return 'https://github.com/40Digits/craftgmaps';
    }

    /**
     * Icon in admin plugin area
     */
    public function getIconPath()
    {
        return craft()->path->getPluginsPath() . '/icon.svg';
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
        return '0.0.3';
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
        return '40digits.com';
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
