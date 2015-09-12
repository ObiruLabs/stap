<?php

namespace Craft;

class DonationForm_DonationsController extends BaseController
{
    const BASE_PLAN_NAME = 'stapdonations';
    const EMAIL_CONFIRMATION_TEMPLATE = 'stap-donation-confirmation';

    protected $allowAnonymous = true;

    public function actionDonate()
    {
        $this->requirePostRequest();
        craft()->stripeService_wrapper->requireApi();

        $attributes = craft()->request->getPost('donation');
        $stripeToken = craft()->request->getPost('stripeToken');

        try {
            $name = $attributes['donorFirstName'] . ' ' . $attributes['donorLastName'];

            if ($attributes['chargeType'] == 'once') {
                $charge = \Stripe\Charge::create(array(
                    'amount' => $attributes['chargeAmount'],
                    'currency' => 'usd',
                    'source' => $stripeToken,
                    'description' => 'Charge for ' . $attributes['donorEmail'],
                    'metadata' => array(
                        'name' => $name,
                        'email' => $attributes['donorEmail'],
                        'frequency' => 'once'
                    )
                ));

                $attributes['stripeChargeId'] = $charge->id;
                $attributes['chargeStatus'] = 'complete';
            } elseif ($attributes['chargeType'] == 'recurring') {
                $this->getOrCreateBasePlan();

                $customer = \Stripe\Customer::create(array(
                    "source" => $stripeToken,
                    'plan' => self::BASE_PLAN_NAME,
                    'quantity' => $attributes['chargeAmount'],
                    "description" => "Customer for " . $attributes['donorEmail'],
                    'email' => $attributes['donorEmail'],
                    'metadata' => array(
                        'name' => $name,
                        'email' => $attributes['donorEmail'],
                        'frequency' => 'recurring'
                    )
                ));

                $attributes['stripeCustomerId'] = $customer->id;
                $attributes['chargeStatus'] = 'active';
            }

            $model = craft()->donationForm_model->newDonation();
            $model->setAttributes($attributes);
            craft()->donationForm_model->saveDonation($model);

            $this->sendEmailConfirmation($name, $attributes['donorEmail']);

            $this->returnJson(array('status' => 'success'));

        } catch (\Stripe\Error\Card $e) {
            // Since it's a decline, \Stripe\Error\Card will be caught
            $body = $e->getJsonBody();
            $error = $body['error'];

            $this->returnJson(array('status' => 'failed', 'error' => $error));
        } catch (\Stripe\Error\InvalidRequest $e) {
            // Invalid parameters were supplied to Stripe's API
            $this->returnJson(array('status' => 'failed'));
        } catch (\Stripe\Error\Authentication $e) {
            // Authentication with Stripe's API failed
            // (maybe you changed API keys recently)
            $this->returnJson(array('status' => 'failed'));
        } catch (\Stripe\Error\ApiConnection $e) {
            // Network communication with Stripe failed
            $this->returnJson(array('status' => 'failed'));
        } catch (\Stripe\Error\Base $e) {
            // Display a very generic error to the user, and maybe send
            // yourself an email
            $this->returnJson(array('status' => 'failed'));
        }
    }

    public function actionCancelStatus()
    {
        craft()->stripeService_wrapper->requireApi();
        $id = craft()->request->getQuery('customer');
        $customer = \Stripe\Customer::retrieve($id);
        $this->returnJson(array('status' => $customer->offsetExists('deleted')));
    }

    public function actionCancel()
    {
        $this->requirePostRequest();
        craft()->stripeService_wrapper->requireApi();
        $id = craft()->request->getPost('customer');
        $customer = \Stripe\Customer::retrieve($id);

        $customer->delete();
        $model = craft()->donationForm_model->getDonationByCustomerId($id);
        $model->chargeStatus = 'complete';
        craft()->donationForm_model->saveDonation($model);

        if (craft()->request->getPost('emptyResponse') != 'true') {
            $this->returnJson(array('status' => 'success'));
        }
    }

    private function sendEmailConfirmation($name, $email)
    {
        $mandrill = craft()->mandrillService_wrapper->requireApi();

        $body = craft()->donationForm_emailSettings->getOrCreateEmailSetting()->receiptBody;

        $message = array(
            'to' => array(array('email' => $email, 'name' => $name)),
            'merge' => true,
            'merge_vars' => array(array(
                'rcpt' => $email,
                'vars' =>
                array(
                    array(
                        'name' => 'NAME',
                        'content' => $name),
                    array(
                        'name' => 'BODY',
                        'content' => $body)
            )))
        );

        $template_content = array();
        $mandrill->messages->sendTemplate(self::EMAIL_CONFIRMATION_TEMPLATE, $template_content, $message);
    }

    /**
     * https://support.stripe.com/questions/how-can-i-create-plans-that-dont-have-a-fixed-price
     */
    private function getOrCreateBasePlan()
    {
        try {
            return \Stripe\Plan::retrieve(self::BASE_PLAN_NAME);
        } catch (\Stripe\Error\InvalidRequest $e) {
            return \Stripe\Plan::create(array(
              'amount' => 1,
              'interval' => 'month',
              'name' => 'STAP Monthly Donation Plan',
              'currency' => 'usd',
              'id' => self::BASE_PLAN_NAME)
            );
        }
    }
}
