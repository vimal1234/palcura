<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use backend\models\userservices\AddUserService;
use backend\models\userservices\UserServiceSearch;
use backend\models\userservices\UpdateUserService;
use backend\models\userservices\UserServices;
use common\models\Admin;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

/**
* UserServices controller
*/
class UserservicesController extends Controller {
	/**
	* @inheritdoc
	*/
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index','create','delete','update','view','status','userservices'],
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
    public function actionIndex($id=0) {
		$searchModel  = Yii::createObject(UserServiceSearch::className());
        $dataProvider = $searchModel->search(Yii::$app->request->get(),$id);
		$dataProvider->pagination->pageSize = (isset($_GET['p']) ? $_GET['p'] : PAGE_LIMIT);
        return $this->render('index', [
            'user_id' => $id,
            'dataProvider' => $dataProvider,
            'searchModel'  => $searchModel,
        ]);
    }

    public function actionUserservices($id) {
		return $this->redirect(['userservices/index/'.$id]);
    }

	/**
    * Displays a single Service model.
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
    public function actionCreate($id=0) {
		if($id == 0 ) {
			return $this->redirect(['index']);
		}
        $data = array();
        $model = new AddUserService();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
			$postval 			= Yii::$app->request->post('AddUserService');
            if ($model->addservice()) {
                Yii::$app->session->setFlash('item', 'Service has been created successfully!');
                return $this->redirect(['index','id' => $id]);
            } else {
                Yii::$app->session->setFlash('item', 'Please enter valid values for all the fields!');
            }
        }
        $model['user_id'] = $id;
        return $this->render('create', [
            'data' 		=> $data,
            'model' 	=> $model,
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
        $updateService	 	= new UpdateUserService();
        $model 		 		= $updateService->findIdentity($id);
        $userpost 	 		= Yii::$app->request->post('UpdateUserService');

        if(isset($userpost) && !empty($userpost)) {
				if($model->load(Yii::$app->request->post()) && $model->updateUserService($id)) {
					Yii::$app->session->setFlash('item', 'Service has been updated successfully!');
					return $this->redirect(['index','id' => $model->user_id]);			
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
		$model = $this->findModel($id);
		$this->findModel($id)->delete();
		Yii::$app->session->setFlash('item', 'Selected service deleted successully!');
		//return $this->redirect(['index']);
		return $this->redirect(['index','id' => $model->user_id]);
	}
	
	/**
    * Finds the record based on its primary key value.
    * If the model is not found, a 404 HTTP exception will be thrown.
    * @param integer $id
    * @return CrudTest the loaded model
    * @throws NotFoundHttpException if the model cannot be found
    */
    protected function findModel($id) {
        if (($model = UserServices::findOne($id)) !== null) {
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
                    Yii::$app->session->setFlash('item' ,'Service status has been changed successfully.');
                } else {
                    Yii::$app->session->setFlash('item', 'No status is set for selected items, please try again.');
                }
                return $this->redirect(['index','id' => $model->user_id]);
                die();
            }
        }
    }
}
