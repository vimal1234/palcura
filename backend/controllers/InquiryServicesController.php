<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use backend\models\inquiryservices\InquiryServices;
use backend\models\inquiryservices\InquiryServicesSearch;
use common\models\Admin;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

/**
* WebsiteQueries controller
*/
class InquiryServicesController extends Controller {
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
                        'actions' => ['index','delete','view','status'],
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
		
		$searchModel  = Yii::createObject(InquiryServicesSearch::className());
        $dataProvider = $searchModel->search(Yii::$app->request->get());
		$dataProvider->pagination->pageSize = (isset($_GET['p']) ? $_GET['p'] : PAGE_LIMIT);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel'  => $searchModel,
        ]);
    }

	/**
    * Displays a single Message model.
    * @param integer $id
    * @return mixed
    */
    public function actionView($id) {
		return $this->render('view', [
            'model' => $this->findModel($id),
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
		 Yii::$app->session->setFlash('item', 'Selected inquiry deleted successully!');
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
        if (($model = InquiryServices::findOne($id)) !== null) {
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
                    Yii::$app->session->setFlash('item' ,'inquiry status has been changed successfully.');
                } else {
                    Yii::$app->session->setFlash('item', 'No status is set for selected items, please try again.');
                }
                die();
            }
        }
    }
}
