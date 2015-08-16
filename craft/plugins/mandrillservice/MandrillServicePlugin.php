<?php
namespace Craft;

class MandrillServicePlugin extends BasePlugin
{
    function getName()
    {
        return Craft::t('Mandrill Service');
    }

    function getVersion()
    {
        return '0.0.1';
    }

    function getDeveloper()
    {
        return 'Obiru Labs';
    }

    function getDeveloperUrl()
    {
        return 'http://obirulabs.com';
    }

    protected function defineSettings()
    {
        return array(
            'apiKey' => array(AttributeType::String, 'required' => true, 'label' => 'API Key', 'default' => Craft::t('sd328sd08j32t90tGIO')),
        );
    }

    public function getSettingsHtml()
    {
        return craft()->templates->render('mandrillservice/settings', array(
            'settings' => $this->getSettings()
        ));
    }
}
