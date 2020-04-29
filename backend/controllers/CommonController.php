<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;


/**
* Common controller
*/
class CommonController extends Controller
{
	/**
	* @inheritdoc
	*/
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['updatestates', 'updatecities'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                ],
            ],
        ];
    }

	/**
	* @inheritdoc
	*/
    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

	/**
	* Get States based by country id
	* @return JSON
	*/
    public function actionUpdatestates() {
        $countryID = Yii::$app->request->post('id');
        return Yii::$app->commonmethod->updateStates($countryID);
    }

	/**
	* Get City based on State id
	* @return JSON
	*/
    public function actionUpdatecities() {
        $stateID = Yii::$app->request->post('id');
        return Yii::$app->commonmethod->updateCities($stateID);
    }
}
