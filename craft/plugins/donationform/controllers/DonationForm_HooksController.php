<?php

namespace Craft;

class DonationForm_HooksController extends BaseController
{
    const EMAIL_CONFIRMATION_TEMPLATE = 'stap-donation-recurring';

    protected $allowAnonymous = true;

    public function actionCapture()
    {
        $this->requirePostRequest();
        craft()->stripeService_wrapper->requireApi();

        // Retrieve the request's body and parse it as JSON
        $input = @file_get_contents("php://input");
        $event_json = json_decode($input);

        if ($event_json->type == 'invoice.payment_succeeded') {
            $customer = \Stripe\Customer::retrieve($event_json->data->object->customer);
            $this->sendEmail($customer->email, $customer->metadata->name, $event_json);
        }

        http_response_code(200); // PHP 5.4 or greater
    }

    private function sendEmail($email, $name, $json)
    {
        $mandrill = craft()->mandrillService_wrapper->requireApi();

        $cancelURL =  craft()->getSiteUrl() . 'donation/cancel?a='.$json->data->object->customer;

        $amount = $json->data->object->amount_due;
        $bodyOne = craft()->donationForm_emailSettings->getOrCreateEmailSetting()->receiptParagraphOne;
        $bodyTwo = craft()->donationForm_emailSettings->getOrCreateEmailSetting()->receiptParagraphTwo;
        $bodyThree = craft()->donationForm_emailSettings->getOrCreateEmailSetting()->receiptParagraphThree;
        $toNotifyEmail = craft()->donationForm_emailSettings->getOrCreateEmailSetting()->notifyEmail;

        $vars = array(
                    array(
                        'name' => 'NAME',
                        'content' => $name),
                    array(
                        'name' => 'AMOUNT',
                        'content' => number_format($amount / 100)),
                    array(
                        'name' => 'RECEIPTPARAGRAPHONE',
                        'content' => $bodyOne),
                    array(
                        'name' => 'RECEIPTPARAGRAPHTWO',
                        'content' => $bodyTwo),
                    array(
                        'name' => 'RECEIPTPARAGRAPHTHREE',
                        'content' => $bodyThree),
                    array(
                        'name' => 'CANCEL_URL',
                        'content' => $cancelURL)
        );

        $message = array(
            'to' => array(array('email' => $email, 'name' => $name), array('email' => $toNotifyEmail, 'name' => 'notify', 'type' => 'bcc')),
            'merge' => true,
            'merge_vars' => array(
                array(
                    'rcpt' => $email,
                    'vars' => $vars
                ),
                array(
                    'rcpt' => $toNotifyEmail,
                    'vars' => $vars
                ),
            )
        );

        $template_content = array();
        $mandrill->messages->sendTemplate(self::EMAIL_CONFIRMATION_TEMPLATE, $template_content, $message);
    }
}
