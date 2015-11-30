<?php
namespace Craft;

/**
 * @package Craft
 */
class DonationForm_EmailSettingRecord extends BaseRecord
{
    public function getTableName()
    {
        return 'donationform_email_settings';
    }

    protected function defineAttributes()
    {
        return array(
            'receiptParagraphOne' => array(AttributeType::String, 'maxLength' => 600, 'required' => false),
            'receiptParagraphTwo' => array(AttributeType::String, 'maxLength' => 600, 'required' => false),
            'receiptParagraphThree' => array(AttributeType::String, 'maxLength' => 600, 'required' => false),
            'notifyEmail' => array(AttributeType::String, 'maxLength' => 200, 'required' => false)
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
