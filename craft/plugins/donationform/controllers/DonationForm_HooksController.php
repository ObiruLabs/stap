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
            $this->sendEmail($customer->email, $event_json);
        }

        http_response_code(200); // PHP 5.4 or greater
        $this->returnJson(array('photos' => 'sldafkj'));
    }

    private function sendEmail($email, $json)
    {
        $mandrill = craft()->mandrillService_wrapper->requireApi();

        $body = '<a href="http://localhost:8888/donation/cancel?a='.$json->data->object->customer.'">Click here to cancel</a>';

        $message = array(
            'to' => array(array('email' => $email, 'name' => 'mikki')),
            'merge' => true,
            'merge_vars' => array(array(
                'rcpt' => $email,
                'vars' =>
                array(
                    array(
                        'name' => 'BODY',
                        'content' => $body)
            )))
        );

        $template_content = array();
        $mandrill->messages->sendTemplate(self::EMAIL_CONFIRMATION_TEMPLATE, $template_content, $message);
    }
}
