<?php
namespace Craft;

/**
 * @package Craft
 */
class DonationForm_DonationRecord extends BaseRecord
{
    public function getTableName()
    {
        return 'donationform_donations';
    }

    protected function defineAttributes()
    {
        return array(
            'donorFirstName' => array(AttributeType::String, 'required' => true),
            'donorLastName' => array(AttributeType::String, 'required' => true),
            'donorEmail' => array(AttributeType::String, 'required' => true),
            'donorAddress1' => array(AttributeType::String, 'required' => true),
            'donorAddress2' => array(AttributeType::String, 'maxLength' => 255, 'required' => false),
            'donorCity' => array(AttributeType::String, 'required' => true),
            'donorState' => array(AttributeType::String, 'required' => true),
            'donorCountry' => array(AttributeType::String, 'required' => true),
            'donorZip' => array(AttributeType::String, 'required' => true),

            'behalfType' => array(AttributeType::Enum, 'values' => "none,memory,honor,other", 'required' => true),
            'behalfOtherType' => array(AttributeType::String, 'maxLength' => 255, 'required' => false),
            'behalfName' => array(AttributeType::String, 'maxLength' => 255, 'required' => false),

            'notifyName' => array(AttributeType::String, 'maxLength' => 255, 'required' => false),
            'notifyEmail' => array(AttributeType::String, 'maxLength' => 255, 'required' => false),
            'notifyAddress1' => array(AttributeType::String, 'maxLength' => 255, 'required' => false),
            'notifyAddress2' => array(AttributeType::String, 'maxLength' => 255, 'required' => false),
            'notifyCity' => array(AttributeType::String, 'maxLength' => 255, 'required' => false),
            'notifyState' => array(AttributeType::String, 'maxLength' => 255, 'required' => false),
            'notifyCountry' => array(AttributeType::String, 'maxLength' => 255, 'required' => false),
            'notifyZip' => array(AttributeType::String, 'maxLength' => 255, 'required' => false),

            'chargeAmount' => array(AttributeType::String, 'required' => true),
            'chargeType' => array(AttributeType::Enum, 'values' => "once,recurring", 'required' => true),
            'chargeStatus' => array(AttributeType::Enum, 'values' => "active,complete", 'required' => true),

            'stripeCustomerId' => array(AttributeType::String, 'maxLength' => 255, 'required' => false),
            'stripeChargeId' => array(AttributeType::String, 'maxLength' => 255, 'required' => false)
        );
    }

    /**
     * Create a new instance of the current class. This allows us to
     * properly unit test our service layer.
     *
     * @return BaseRecord
     */
    public function create()
    {
        $class = get_class($this);
        $record = new $class();
        return $record;
    }
}
