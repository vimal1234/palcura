<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use common\models\Admin;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

/**
* Exportcsv Controller
**/
class ExportcsvController extends Controller {
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
                        'actions' => ['users','properties'],
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
     * actionUsers
     * @purpose export users
     * @param 
     * @return string
     */
    public function actionUsers() {
        \moonland\phpexcel\Excel::export([
        'models' => \backend\models\users\Users::find()->where(['status'=>'1'])->orderBy('id ASC')->all(), 
            'columns' => [
                'firstname',
                'lastname',
                'email',
                'dob:text:Date of birth',
                'usertype.name:text:User type',
                'gender',
                'countryname.name:text:Country',
                'regionname.name:text:Region',
                'cityname.name:text:City',
                'currentnation.name:text:Nationality',
            ]
        ]);
    }
    
    /**
     * actionProperties
     * @purpose export properties
     * @param 
     * @return string
     */
    public function actionProperties() {
        \moonland\phpexcel\Excel::export([
        'models' => \backend\models\properties\Properties::find()->where(['status'=>'1'])->orderBy('id ASC')->all(), 
            'columns' => [
                'name',
                'reference_number',
                'property_type',
                'countryname.name:text:Country',
                'regionname.name:text:Region',
                'cityname.name:text:City',
                'build_year',
                'area',
                'rooms',
                'floors',
                'price',
                'expiry_date',
                'created_at',
            ]
        ]);
    }
}
