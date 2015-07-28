<?php

namespace Craft;

class DonationForm_DonationsController extends BaseController
{
    public function actionDonate()
    {
        $this->requirePostRequest();

        $model = craft()->donationForm_model->newDonation();
        $attributes = craft()->request->getPost('donation');
        $model->setAttributes($attributes);

        craft()->donationForm_model->saveDonation($model);

        $this->returnJson(array('photos' => 'sldafkj'));

//        if ($id = craft()->request->getPost('ingredientId')) {
//            $model = craft()->cocktailRecipes->getIngredientById($id);
//        } else {
//            $model = craft()->cocktailRecipes->newIngredient($id);
//        }
//
//        $attributes = craft()->request->getPost('ingredient');
//        $model->setAttributes($attributes);

//        if (craft()->cocktailRecipes->saveIngredient($model)) {
//            craft()->userSession->setNotice(Craft::t('Ingredient saved.'));
//            return $this->redirectToPostedUrl(array('ingredientId' => $model->getAttribute('id')));
//        } else {
//            craft()->userSession->setError(Craft::t("Couldn't save ingredient."));
//            craft()->urlManager->setRouteVariables(array('ingredient' => $model));
//        }
    }
}
