<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Controller;
use yii\helpers\Url;
use frontend\models\users\Users;
use frontend\models\Account;
use yii\db\Query;

class AccountController extends Controller {

	public function beforeAction($action) {
		return true;
	}

	public function actionSaveCardDetails() {
		$session 		= Yii::$app->session;
		$logged_user 	= $session->get('loggedinusertype');
        $attributes = Yii::$app->user->identity->getattributes();
        if(isset($attributes['usr_type']) && $attributes['usr_type'] == OWNER) {
                throw new \yii\web\ForbiddenHttpException(Yii::t('yii','You are not allowed to access this page.'));
                return false;			
        }

        $id 			 = Yii::$app->user->getId();		
        $dataArray 		 = array();
        $ownerDetails 	 = Users::findOne($id);
        if($ownerDetails === null) {
                throw new NotFoundHttpException(Yii::t('yii','Page not found.'));
                return false;
        }

        $accountDetails 	 = Account::findOne(['card_user_id' => $id]);
        if($accountDetails	 != null) {
			$this->redirect(["account/update-card-details"]);
        }

        $model 				 	 = new Account();
		if ($model->load(Yii::$app->request->post()) && $model->validate()) {
		$model->card_bank_name = 'default';
            if($model->savedata()) {
                Yii::$app->session->setFlash('item', Yii::t('yii','Your card information has been saved successfully.'));
               $session = Yii::$app->session;
               $refercardinfo = $session->get('refercard');
               if(isset($refercardinfo) &&  $refercardinfo=='true'){
               	$session->remove('refercard');
               	return $this->redirect(['bookings/book-now']);
               	}else{
               	return $this->redirect(['users/myprofile']);
               	}
               

            } else {
                Yii::$app->session->setFlash('item', Yii::t('yii','Please enter valid values for all the fields.'));
                return $this->redirect(["account/save-card-details"]);
            }
		}
		return $this->render('add-card-information',['model'=>$model]);
	}
	
	public function actionUpdateCardDetails() {
		$session 		= Yii::$app->session;
		$logged_user 	= $session->get('loggedinusertype');
        $attributes = Yii::$app->user->identity->getattributes();
        if(isset($attributes['usr_type']) && $attributes['usr_type'] == OWNER) {
                throw new \yii\web\ForbiddenHttpException(Yii::t('yii','You are not allowed to access this page.'));
                return false;			
        }

        $id 			 = Yii::$app->user->getId();		
        $dataArray 		 = array();
        $guideDetails 	 = Users::findOne($id);
        if($guideDetails === null) {
                throw new NotFoundHttpException(Yii::t('yii','Page not found.'));
                return false;
        }

        $accountDetails 	 = Account::findOne(['card_user_id' => $id]);
        if($accountDetails	 === null) {
			$this->redirect(["account/save-card-details"]);
        }

        $model 				 	 = new Account();
		if ($model->load(Yii::$app->request->post()) && $model->validate()) {
		$model->card_bank_name = 'default';
            if($model->updatedata($id)) {
                Yii::$app->session->setFlash('item', Yii::t('yii','Your card information has been updated successfully.'));
                return $this->redirect(["account/update-card-details"]);
                // return $this->redirect(["bookings/book-now"]);
//~ if($logged_user == OWNER) {
//~ return $this->redirect(['search/petsitter']);
//~ } else if($logged_user == RENTER) {
//~ return $this->redirect(['search/petrenter']);
//~ } else {
//~ return $this->redirect(['site/home']);
//~ }
            } else {
                Yii::$app->session->setFlash('item', Yii::t('yii','Please enter valid values for all the fields.'));
                return $this->redirect(["account/update-card-details"]);
            }
		} else {
			$model->setAttributes($accountDetails->getAttributes());
		}
		return $this->render('add-card-information',['model'=>$model]);
	}

    protected function findModel($id) {
        if (($model = Account::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}	
