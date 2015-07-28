<?php
namespace Craft;

class StripeServicePlugin extends BasePlugin
{
    function getName()
    {
        return Craft::t('Stripe Service');
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
            'secretKey' => array(AttributeType::String, 'required' => true, 'label' => 'Secret Key', 'default' => Craft::t('sk_livetest_secret_key')),
            'publishableKey' => array(AttributeType::String, 'required' => true, 'label' => 'Publishable Key', 'default' => Craft::t('pk_livetest_publishable_key'))
        );
    }

    public function getSettingsHtml()
    {
        return craft()->templates->render('stripeservice/settings', array(
            'settings' => $this->getSettings()
        ));
    }
}
