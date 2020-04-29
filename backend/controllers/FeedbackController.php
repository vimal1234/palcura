<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use backend\models\feedback\FeedbackSearch;
use backend\models\feedback\Feedback;
use common\models\Admin;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

/**
 * Feedback controller
 */
class FeedbackController extends Controller
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

    public function actionIndex() {
		$searchModel  = Yii::createObject(FeedbackSearch::className());
        $dataProvider = $searchModel->search(Yii::$app->request->get());
		$dataProvider->pagination->pageSize = (isset($_GET['p']) ? $_GET['p'] : PAGE_LIMIT);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel'  => $searchModel,
        ]);
    }

	/**
    * Displays a single Service model.
    * @param integer $id
    * @return mixed
    */
    public function actionView($id) {
		$data 			 					= $this->findModel($id);
		$data['sender_userid'] 	 			= Yii::$app->commonmethod->getUsername($data->sender_userid);
		$data['receiver_userid'] 	 		= Yii::$app->commonmethod->getUsername($data->receiver_userid);
		return $this->render('view', [
            'model' => $data,
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
		 Yii::$app->session->setFlash('item', 'Selected feedback deleted successully!');
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
        if (($model = FeedbackSearch::findOne($id)) !== null) {
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
                    Yii::$app->session->setFlash('item' ,'Feedback status has been changed successfully.');
                } else {
                    Yii::$app->session->setFlash('item', 'No status is set for selected images, please try again.');
                }
                die();
            }
        }
    }
}
