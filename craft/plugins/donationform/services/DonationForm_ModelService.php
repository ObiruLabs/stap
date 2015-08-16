<?php

namespace Craft;

/**
 * Donation Form Model Service
 *
 * Provides a consistent API for our plugin to access the database
 */
class DonationForm_ModelService extends BaseApplicationComponent
{
    protected $donationRecord;

    /**
     * Create a new instance of the Cocktail Recpies Service.
     * Constructor allows DonationRecord dependency to be injected to assist with unit testing.
     *
     * @param @donationRecord DonationRecord The donation record to access the database
     */
    public function __construct($donationRecord = null)
    {
        $this->donationRecord = $donationRecord;
        if (is_null($this->donationRecord)) {
            $this->donationRecord = DonationForm_DonationRecord::model();
        }
    }

    /**
     * Get a new blank donation
     *
     * @param  array $attributes
     * @return DonationForm_DonationModel
     */
    public function newDonation($attributes = array())
    {
        $model = new DonationForm_DonationModel();
        $model->setAttributes($attributes);

        return $model;
    }

    /**
     * Get all donations from the database.
     *
     * @return array
     */
    public function getAllDonations()
    {
        $records = $this->donationRecord->findAll(array('order'=>'t.dateCreated DESC'));

        return DonationForm_DonationModel::populateModels($records, 'id');
    }

    /**
     * Get all one time donations from the database.
     *
     * @return array
     */
    public function getAllOneTimeDonations()
    {
        $records = $this->donationRecord->findAll(array('condition'=>'t.chargeType IN ("once")', 'order'=>'t.dateCreated DESC'));

        return DonationForm_DonationModel::populateModels($records, 'id');
    }

    /**
     * Get all recurring donations from the database.
     *
     * @return array
     */
    public function getAllRecurringDonations()
    {
        $records = $this->donationRecord->findAll(array('condition'=>'t.chargeType IN ("recurring")', 'order'=>'t.dateCreated DESC'));

        return DonationForm_DonationModel::populateModels($records, 'id');
    }

    /**
     * Get a specific donation from the database based on ID. If no donation exists, null is returned.
     *
     * @param  int   $id
     * @return mixed
     */
    public function getDonationById($id)
    {
        if ($record = $this->donationRecord->findByPk($id)) {
            return DonationForm_DonationModel::populateModel($record);
        }
    }

    public function getDonationByCustomerId($id)
    {
        if ($record = $this->donationRecord->findByAttributes(array('stripeCustomerId' => $id))) {
            return DonationForm_DonationModel::populateModel($record);
        }
    }

    /**
     * Save a new or existing donation back to the database.
     *
     * @param  DonationForm_DonationModel $model
     * @return bool
     */
    public function saveDonation(DonationForm_DonationModel &$model)
    {
        if ($id = $model->getAttribute('id')) {
            if (null === ($record = $this->donationRecord->findByPk($id))) {
                throw new Exception(Craft::t('Can\'t find donation with ID "{id}"', array('id' => $id)));
            }
        } else {
            $record = $this->donationRecord->create();
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

    /**
     * Delete an donation from the database.
     *
     * @param  int $id
     * @return int The number of rows affected
     */
    public function deleteDonationById($id)
    {
        return $this->donationRecord->deleteByPk($id);
    }
}