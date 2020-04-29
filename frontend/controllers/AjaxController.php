<?php

namespace frontend\controllers;

use Yii;

class AjaxController extends \yii\web\Controller
{
	public $enableCsrfValidation = false;
   /**
     * Displays actionGetCountries.
     *
     * @return json
     */
    public function actionGetCountries() {
		$countries = \common\models\Country ::find()->asArray()->all();
		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		return $countries;
    }
    
   /**
     * Displays actionGetStates.
     *
     * @return json
     */
    public function actionGetStates() {
		$countryID = Yii::$app->request->post('country_id');
		$states = \common\models\State ::find()->where(['country_id' => $countryID])->asArray()->all();
		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		return $states;
    }
    
    /**
    * Displays actionGetCities.
    *
    * @return json
    */
    public function actionGetCities() {
		$stateID = Yii::$app->request->post('state_id');
		$cities = \common\models\City ::find()->where(['state_id' => $stateID])->asArray()->all();
		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		return $cities;
    }

   /**
     * Displays actionGetStates.
     *
     * @return json
     */
    public function actionUpdatestates() {
		$countryID = Yii::$app->request->post('id');
		$states = \common\models\State ::find()->where(['country_id' => $countryID])->orderBy(['name' => SORT_ASC])->asArray()->all();
		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		return $states;
    }
    
    /**
    * Displays actionGetCities.
    *
    * @return json
    */
    public function actionUpdatecities() {
		$stateID = Yii::$app->request->post('id');
		$cities = \common\models\City ::find()->where(['state_id' => $stateID])->orderBy(['name' => SORT_ASC])->asArray()->all();
		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		return $cities;
    }
    
    /**
    * Displays actionGetInterests.
    *
    * @return json
    */
    public function actionGetInterests() {
	
		$interests = \common\models\Interests ::find()->where(['status' => 1])->asArray()->all();
		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		return $interests;
    }
    
    /**
    * Displays actionGetInterests.
    *
    * @return json
    */
    public function actionGetMessagesCount() {
		if(Yii::$app->request->isPost) {
			$userId 	   = Yii::$app->user->getId();
			$messagesCount = \frontend\models\messages\Messages ::find()->where(['is_trashed' => '0', 'is_read' => '0', 'status' => '1','user_to' => $userId])->count();
			Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
			return $messagesCount;
		} else {
			return $this->redirect(['site/index']);
		}
    }
    
}
