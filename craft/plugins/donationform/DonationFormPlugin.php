<?php
namespace Craft;

class DonationFormPlugin extends BasePlugin
{
    function getName()
    {
        return Craft::t('Donations');
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

    public function registerCpRoutes()
    {
        return array(
            'donationForm\/donations\/(?P<donationId>\d+)' => 'donationForm/donations/_donor',
            'donationForm\/donations\/(?P<donationId>\d+\/dedication)' => 'donationForm/donations/_dedication',
            'donationForm\/donations\/(?P<donationId>\d+\/donation)' => 'donationForm/donations/_donation'
        );
    }

    public function hasCpSection()
    {
        return true;
    }
}
