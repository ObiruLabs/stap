<?php

namespace Craft;

class DonationForm_EmailSettingsController extends BaseController
{
    public function actionUpdate()
    {
        $this->requirePostRequest();
        $model = craft()->donationForm_emailSettings->getOrCreateEmailSetting();
        $attributes = craft()->request->getPost('settings');

        $model->setAttributes($attributes);
        craft()->donationForm_emailSettings->saveEmailSetting($model);
        craft()->userSession->setNotice('Settings saved');
        return $this->redirectToPostedUrl();
    }
}
