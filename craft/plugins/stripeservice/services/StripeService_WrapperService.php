<?php
namespace Craft;

/**
 * Class that exposes the Stripe library.
 *
 * @package Craft
 */
class StripeService_WrapperService extends BaseApplicationComponent
{
    /**
     * Include Stripe API SDK and set the private keys.
     */
    public function requireApi()
    {
        require_once(CRAFT_PLUGINS_PATH.'stripeservice/vendor/stripe-php/init.php');
        $settings = craft()->plugins->getPlugin('stripeService')->getSettings();
        \Stripe\Stripe::setApiKey($settings['secretKey']);
    }
}
