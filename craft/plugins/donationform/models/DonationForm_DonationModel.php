<?php
namespace Craft;

class DonationForm_DonationModel extends BaseModel
{
    /**
     * Define the attributes this model will have.
     *
     * @return array
     */
    public function defineAttributes()
    {
        return array(
            'id'    => AttributeType::Number,
            'donorFirstName' => AttributeType::String,
            'donorLastName' => AttributeType::String,
            'donorEmail' => AttributeType::String,
//            'donorAddress1' => AttributeType::String,
//            'donorAddress2' => AttributeType::String,
//            'donorCity' => AttributeType::String,
//            'donorState' => AttributeType::String,
//            'donorCountry' => AttributeType::String,
            'donorZip' => AttributeType::String,

            'behalfType' => AttributeType::String,
            'behalfOtherType' => AttributeType::String,
            'behalfName' => AttributeType::String,

            'notifyType' => AttributeType::String,
            'notifyName' => AttributeType::String,
            'notifyEmail' => AttributeType::String,
            'notifyAddress1' => AttributeType::String,
            'notifyAddress2' => AttributeType::String,
            'notifyCity' => AttributeType::String,
            'notifyState' => AttributeType::String,
            'notifyCountry' => AttributeType::String,
            'notifyZip' => AttributeType::String,

            'chargeAmount' => AttributeType::String,
            'chargeType' => AttributeType::String,
            'chargeStatus' => AttributeType::String,

            'stripeCustomerId' => AttributeType::String,
            'stripeChargeId' => AttributeType::String,

            'dateCreated' => AttributeType::String
        );
    }
}
