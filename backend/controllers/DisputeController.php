<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use backend\models\dispute\DisputeSearch;
use backend\models\dispute\UpdateDispute;
use backend\models\dispute\Dispute;
use common\models\Admin;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\db\Query;

/**
* Dispute controller
*/
class DisputeController extends Controller {
	/**
	* @inheritdoc
	*/
	public function behaviors() {
		return [
			'access' => [
				'class' => AccessControl::className(),
				'rules' => [
					[
						'actions' => ['index','delete','view','status','verification-badge','resolve-dispute'],
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
		$searchModel  = Yii::createObject(DisputeSearch::className());
		$dataProvider = $searchModel->search(Yii::$app->request->get());
		$dataProvider->pagination->pageSize = (isset($_GET['p']) ? $_GET['p'] : PAGE_LIMIT);
		return $this->render('index', [
			'dataProvider' => $dataProvider,
			'searchModel'  => $searchModel,
		]);
	}

	public function verificationBadge($id) {
		#### query 1
		$query 		= new Query;
		$query->select('COUNT(vb.id) as badge_cnt,vb.user_id')
			  ->from('dispute_resolutions')
			  ->join('LEFT JOIN', 'booking b', 'b.id = dispute_resolutions.booking_id')
			  ->join('LEFT JOIN', 'verification_badge vb', 'vb.user_id = b.pet_sitter_id')
			  ->where(['dispute_resolutions.id' => $id, 'dispute_resolutions.status' => ACTIVE]);
		$data = $query->createCommand()->queryOne();

		$connection = \Yii::$app->db;
		if(isset($data['badge_cnt']) && $data['badge_cnt'] > 0) {
			#### query 2
			$connection->createCommand()->update('verification_badge', ['booking_charges' => 20, 'status' => INACTIVE], 'user_id = ' . $data['user_id'])->execute();			
		}
	}

    /**
    * Update an existing record.
    * If update is successful, the browser will be redirected to the 'view' page.
    * @param integer $id
    * @return mixed
    */
    public function actionResolveDispute($id) {
		$data 			  	= array();
        $updateModel 		= $this->findModel($id);
        $updateService	 	= new UpdateDispute();
        $model 		 		= $updateService->findIdentity($id);
        $userpost 	 		= Yii::$app->request->post('UpdateDispute');

        if(isset($userpost) && !empty($userpost)) {
				if($model->load(Yii::$app->request->post()) && $model->updatedata($id)) {
					if($model['verified_by_admin'] == ACTIVE && $model['paid_charges'] == ACTIVE) {
						$this->verificationBadge($id);
						Yii::$app->session->setFlash('item', 'The verification badge has been removed successully!');
						return $this->redirect(['index']);	
					}
					Yii::$app->session->setFlash('item', 'Dispute has been updated successfully!');
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
		Yii::$app->session->setFlash('item', 'Selected item deleted successully!');
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
		if (($model = Dispute::findOne($id)) !== null) {
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
					Yii::$app->session->setFlash('item' ,'Query status has been changed successfully.');
				} else {
					Yii::$app->session->setFlash('item', 'No status is set for selected items, please try again.');
				}
				die();
			}
		}
	}
}
