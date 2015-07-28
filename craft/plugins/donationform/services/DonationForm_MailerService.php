<?php
namespace Craft;

/**
 * @package Craft
 */
class DonationForm_MailerService extends BaseApplicationComponent
{
    public function send()
    {
        craft()->stripeService_wrapper->requireApi();

        $myCard = array('number' => '4242424242424242', 'exp_month' => 5, 'exp_year' => 2020);
        $charge = \Stripe\Charge::create(array('card' => $myCard, 'amount' => 1000, 'currency' => 'usd'));
        return $charge;
    }
}
