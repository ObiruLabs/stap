<?php
namespace Craft;

class DonationForm_EmailSettingModel extends BaseModel
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
            'receiptParagraphOne' => AttributeType::String,
            'receiptParagraphTwo' => AttributeType::String,
            'receiptParagraphThree' => AttributeType::String,
            'notifyEmail' => AttributeType::String
        );
    }
}
