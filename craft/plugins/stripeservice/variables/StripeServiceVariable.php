<?php
namespace Craft;

/**
 * Class that exposes the publishable key for Stripe.js.
 *
 * @package Craft
 */
class StripeServiceVariable
{
    public function publishableKey()
    {
        return $settings = craft()->plugins->getPlugin('stripeService')->getSettings()['publishableKey'];
    }
}
