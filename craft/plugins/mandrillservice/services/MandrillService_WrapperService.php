<?php
namespace Craft;

/**
 * Class that exposes the Mandrill library.
 *
 * @package Craft
 */
class MandrillService_WrapperService extends BaseApplicationComponent
{
    /**
     * Include Mandrill API SDK and set the key.
     */
    public function requireApi()
    {
        require_once(CRAFT_PLUGINS_PATH.'mandrillservice/vendor/mandrill-php/Mandrill.php');
        $settings = craft()->plugins->getPlugin('mandrillService')->getSettings();
        return new \Mandrill($settings['apiKey']);
    }
}
