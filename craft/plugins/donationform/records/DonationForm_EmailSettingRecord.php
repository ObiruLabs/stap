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
            'receiptBody' => array(AttributeType::String, 'maxLength' => 255, 'required' => false)
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
