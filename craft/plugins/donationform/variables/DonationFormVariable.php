<?php

namespace Craft;

/**
 * Donation Form Variable provides access to database objects from templates.
 */
class DonationFormVariable
{
    /**
     * Get all available donations
     *
     * @return array
     */
    public function getAllDonations()
    {
        return craft()->donationForm_model->getAllDonations();
    }

    /**
     * Get all available one time donations.
     *
     * @return array
     */
    public function getAllOneTimeDonations()
    {
        return craft()->donationForm_model->getAllOneTimeDonations();
    }

    /**
     * Get all available recurring donations.
     *
     * @return array
     */
    public function getAllRecurringDonations()
    {
        return craft()->donationForm_model->getAllRecurringDonations();
    }

    /**
     * Get a specific ingredient. If no ingredient is found, returns null
     *
     * @param  int   $id
     * @return mixed
     */
    public function getDonationById($id)
    {
        return craft()->donationForm_model->getDonationById($id);
    }
}
