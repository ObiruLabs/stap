<?php

namespace Craft;

/**
 * Donation Form Model Service
 *
 * Provides a consistent API for our plugin to access the database
 */
class DonationForm_EmailSettingsService extends BaseApplicationComponent
{
    protected $emailSettingRecord;

    public function __construct($emailSettingRecord = null)
    {
        $this->emailSettingRecord = $emailSettingRecord;
        if (is_null($this->emailSettingRecord)) {
            $this->emailSettingRecord = DonationForm_EmailSettingRecord::model();
        }
    }

    public function getOrCreateEmailSetting()
    {
        $records = $this->emailSettingRecord->findAll();

        if (empty($records)) {
            $model = $this->newEmailSetting(array('receiptParagraphOne' => '', 'receiptParagraphTwo' => '', 'receiptParagraphThree' => '', 'notifyEmail' => ''));
            $this->saveEmailSetting($model);
        } else {
            $models = DonationForm_EmailSettingModel::populateModels($records, 'id');
            $model = array_shift($models);
        }

        return $model;
    }

    /**
     * Get a new blank donation
     *
     * @param  array $attributes
     * @return DonationForm_DonationModel
     */
    public function newEmailSetting($attributes = array())
    {
        $model = new DonationForm_EmailSettingModel();
        $model->setAttributes($attributes);

        return $model;
    }

    public function saveEmailSetting(DonationForm_EmailSettingModel &$model)
    {
        if ($id = $model->getAttribute('id')) {
            if (null === ($record = $this->emailSettingRecord->findByPk($id))) {
                throw new Exception(Craft::t('Can\'t find donation with ID "{id}"', array('id' => $id)));
            }
        } else {
            $record = $this->emailSettingRecord->create();
        }

        $record->setAttributes($model->getAttributes());
        if ($record->save()) {
            // update id on model (for new records)
            $model->setAttribute('id', $record->getAttribute('id'));

            return true;
        } else {
            $model->addErrors($record->getErrors());

            return false;
        }
    }
}