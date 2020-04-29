<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use backend\models\socialicons\AddSocialicon;
use backend\models\socialicons\SocialiconSearch;
use backend\models\socialicons\UpdateSocialicon;
use backend\models\socialicons\Socialicons;
use common\models\Admin;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

/**
* Socialicons controller
*/
class SocialiconsController extends Controller {
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
                        'actions' => ['index','create','delete','update','view','status'],
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
	* @inheritdoc
	*/
    public function actionIndex() {
		$searchModel  = Yii::createObject(SocialiconSearch::className());
        $dataProvider = $searchModel->search(Yii::$app->request->get());
		$dataProvider->pagination->pageSize = (isset($_GET['p']) ? $_GET['p'] : PAGE_LIMIT);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel'  => $searchModel,
        ]);
    }

	/**
    * Displays a single Socialicon model.
    * @param integer $id
    * @return mixed
    */
    public function actionView($id) {
		return $this->render('view', [
            'model' => $this->findModel($id),
        ]);	
    }

    /**
    * Create new records.
    * @return mixed
    */	 
    public function actionCreate() {
        $data = array();
        $model = new AddSocialicon();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
			$postval 			= Yii::$app->request->post('AddSocialicon');
            if ($model->addsocialicon()) {
                Yii::$app->session->setFlash('item', 'Socialicon has been created successfully!');
                return $this->redirect(['index']);
            } else {
                Yii::$app->session->setFlash('item', 'Please enter valid values for all the fields!');
            }
        }
        return $this->render('create', [
            'data' => $data,
            'model' => $model,
        ]);
    }
		    
    /**
    * Update an existing record.
    * If update is successful, the browser will be redirected to the 'view' page.
    * @param integer $id
    * @return mixed
    */
    public function actionUpdate($id) {
		$data 			  	= array();
        $updateModel 		= $this->findModel($id);
        $updateSocialicon	 	= new UpdateSocialicon();
        $model 		 		= $updateSocialicon->findIdentity($id);
        $userpost 	 		= Yii::$app->request->post('UpdateSocialicon');

        if(isset($userpost) && !empty($userpost)) {
				if($model->load(Yii::$app->request->post()) && $model->updatesocialicon($id)) {
					Yii::$app->session->setFlash('item', 'Socialicon has been updated successfully!');
					return $this->redirect(['index']);				
				} else {
					Yii::$app->session->setFlash('item', 'Please enter valid values for all the fields!');
				}
		} else {
			$model->setAttributes($updateModel->getAttributes());
		}
		return $this->render('update', [
			'data' => $data,
			'model' => $model,
		]);
    }

    /**
    * Deletes an existing record.
    * If deletion is successful, the browser will be redirected to the 'index' page.
    * @param integer $id
    * @return mixed
    */
    public function actionDelete($id) {
		$this->findModel($id)->delete();
		 Yii::$app->session->setFlash('item', 'Selected socialicon deleted successully!');
		return $this->redirect(['index']);
	}
	
	/**
    * Finds the record based on its primary key value.
    * If the model is not found, a 404 HTTP exception will be thrown.
    * @param integer $id
    * @return CrudTest the loaded model
    * @throws NotFoundHttpException if the model cannot be found
    */
    protected function findModel($id) {
        if (($model = Socialicons::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
    * Action Status
    * If deletion is successful, the browser will be redirected to the 'index' page.
    * @param integer $id
    * @return mixed
    */
    public function actionStatus($id) {
         $model = $this->findModel($id);
         $postArr = Yii::$app->request->post('update');
         if(isset($postArr) && !empty($postArr)) {
            if (Yii::$app->request->isAjax){
                $model->status = $postArr['status'];
                if($model->save()) {
                    Yii::$app->session->setFlash('item' ,'Socialicon status has been changed successfully.');
                } else {
                    Yii::$app->session->setFlash('item', 'No status is set for selected items, please try again.');
                }
                die();
            }
        }
    }
}
