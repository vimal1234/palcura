<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Controller;
use yii\helpers\Url;
use frontend\models\users\Users;
use yii\db\Query;
use yii\data\Pagination;
use frontend\models\Booking;
use common\models\User;

class CalendarController extends Controller {
	private $limit = 10;
	/**
	* @ Function Name		: actionIndex
	* @ Function Params		: NA 
	* @ Function Purpose 	: default index function that will be called to display payments
	* @ Function Returns	: render view
	*/	
    /*public function actionIndex() {
      $bookingModel = new Booking();
      $userModel = new User();
     
      $currentUser = Yii::$app->user->identity->id;
   	  
  
      $bookingInfo = Booking::find()->select('id,pet_sitter_id,name,pet_owner_id,booking_status,booking_from_date,booking_to_date')->where(['pet_sitter_id'=>$currentUser])->orWhere(['pet_owner_id'=>$currentUser])->andWhere(['status'=>'1'])->all();
      $calenderData = array();
      $bookingdata = array();
      if(!empty($bookingInfo)){
      
		  foreach($bookingInfo as $key=>$val){
		  $bookingdata['id'] = $val->id;
		  $siterData = $userModel->findIdentity($val->pet_sitter_id);
		  $sittername = $siterData->firstname.' '.$siterData->lastname;
		  $ownerData = $userModel->findIdentity($val->pet_owner_id);
		  $ownerName = $ownerData->firstname.' '.$ownerData->lastname;
		  $bookingdata['title'] = $val->name;
		  	
		  $bookingdata['start'] = $val->booking_from_date;
		  $bookingtodate = $val->booking_to_date;
		  $bookingdata['end'] = date("Y-m-d", strtotime("+1 day",strtotime($bookingtodate)));
		  if($val->booking_status==0){
		  $status = 'Pending';
		  }elseif($val->booking_status==1){
		  $status = 'Accepted';
		  }elseif($val->booking_status==2){
		  $status = 'Rejected';
		  }elseif($val->booking_status==3){
		  $status = 'Cancelled';
		  }
		  $bookingdata['description'] = '<ul><li>Sitter Name:'.$sittername.'</li><li>Owner Name:'.$ownerName.'</li><li>Status:'.$status.'</li></ul>';	 
		  $calenderData[] = $bookingdata;        
		  }      
      }
   
        return $this->render('calendar',
        [
        'eventData' => $calenderData,
        ]
        );        								
    }*/
    
    public function actionIndex() {
      $bookingModel = new Booking();
      $userModel = new User();
     
      $currentUser = Yii::$app->user->identity->id;
   	  
  
      $bookingInfo = Booking::find()->select('id,pet_sitter_id,name,pet_owner_id,pet_renter_id,booking_status,booking_from_date,booking_to_date')->where(['pet_sitter_id'=>$currentUser])->orWhere(['pet_owner_id'=>$currentUser])->orWhere(['pet_renter_id'=>$currentUser])->andWhere(['payment_status'=>'1'])->andWhere(['status'=>'1'])->all();
       $calenderData = array();
      $bookingdata = array();
      if(!empty($bookingInfo)){
     
		  foreach($bookingInfo as $key=>$val){
		  //$bookingdata['id'] = $val->id;
		  if(!empty($val->pet_sitter_id) && $val->pet_sitter_id>0){
		  $siterData = $userModel->findIdentity($val->pet_sitter_id);
		  $sittername = $siterData->firstname.' '.$siterData->lastname;
		  }elseif(!empty($val->pet_renter_id) && $val->pet_renter_id>0){
		  $siterData = $userModel->findIdentity($val->pet_owner_id);
		  $sittername = $siterData->firstname.' '.$siterData->lastname;
		  }
		  if(!empty($val->pet_owner_id) && $val->pet_owner_id>0){
		  $ownerData = $userModel->findIdentity($val->pet_owner_id);
		  $ownerName = $ownerData->firstname.' '.$ownerData->lastname;
		  }
		  if(!empty($val->pet_renter_id) && $val->pet_renter_id>0){
		  $ownerData = $userModel->findIdentity($val->pet_renter_id);
		  $ownerName = $ownerData->firstname.' '.$ownerData->lastname;		   		  
		  }	
		  $bookingdata['id'] = $val->id;	  
		  		  	
		  $bookingdata['start'] = $val->booking_from_date;
		  $bookingdata['end'] = $val->booking_to_date;
		  if($val->booking_status==0){
		  $status = 'Pending';
		  }elseif($val->booking_status==1){
		  $status = 'Accepted';
		  }elseif($val->booking_status==2){
		  $status = 'Rejected';
		  }elseif($val->booking_status==3){
		  $status = 'Cancelled';
		  }
		  $bookingdata['title'] = $val->name.' - '.$status;
		  
		  $bookingdata['description'] = 'SITTER NAME:'.$sittername.' OWNER NAME:'.$ownerName;	 
		  $calenderData[] = $bookingdata;        
		  }      
      }
		$data  = $this->getUnavailabledates($currentUser);
		$model = new \frontend\models\Calender();
		$model->unavailabilty	=	(isset($data['dates']) ? $data['dates'] : '');
        return $this->render('calendar',[
			'eventData' => $calenderData,
			'model' => $model,
		]);        								
    }

	public function actionAddunavailability() {
		$session 		= Yii::$app->session;
		$logged_user 	= $session->get('loggedinusertype');
        $attributes 	= Yii::$app->user->identity->getattributes();
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

        $model = new \frontend\models\Calender();
		if ($model->load(Yii::$app->request->post()) && $model->validate()) {
			$data  = $this->getUnavailabledates($attributes['id']);
			if(isset($data['id']) && $data['id'] > 0) {
				Yii::$app->db->createCommand()->update('user_unavailability', ['dates' => $model->unavailabilty], 'user_id = '.$attributes['id'])->execute();
				//Yii::$app->db->createCommand()->update('used_rewards', ['status' => ACTIVE], 'booking_id = '.$booking_id)->execute();	
				Yii::$app->session->setFlash('item', Yii::t('yii','<i class="fa fa-check"></i> Your unavailable dates have been updated successfully.'));
                return $this->redirect(["calendar/index"]);
			} else {
				$query = new Query;
				$query->createCommand()->insert('user_unavailability', ['dates' => $model->unavailabilty, 'user_id' => $attributes['id']])->execute();
				Yii::$app->session->setFlash('item', Yii::t('yii','<i class="fa fa-check"></i> Your unavailable dates have been added successfully.'));
                return $this->redirect(["calendar/index"]);
			}
           //Yii::$app->session->setFlash('item', Yii::t('yii','<i class="fa fa-close"></i> Please enter valid values for all the fields.'));
           //return $this->redirect(["account/save-card-details"]);
		}
		return $this->redirect(["calendar/index"]);
	}
	
	public function getUnavailabledates($userId) {
		$query = new Query;
		$query->select('id,dates')->from('user_unavailability')->where(['user_id' => $userId]);       
		return $query->createCommand()->queryOne();		
	}  
}
