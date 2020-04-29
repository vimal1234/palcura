<?php
namespace frontend\models;
use Yii;
use yii\db\Query;
/**
* Booking model
*
* @property integer $id
* @property string $Inerests
*/
class Activity extends \yii\db\ActiveRecord {
   
	/**
	* @inheritdoc
	*/
	public static function tableName() {
		return 'booking_activity';
	}

	/**
	* @inheritdoc
	*/
	public function rules() {
		return [
			
		];
	}

	/**
	* @inheritdoc
	*/
    public function attributeLabels() {
        return [
          
        ];
    }
    
    ####= get today booking
	public function getUserBookingIds($userID) {
		$currentDate= date("Y-m-d");
        $query 	= new Query;     
        $session 		= Yii::$app->session;
	$logged_user 	= $session->get('loggedinusertype');
	if($logged_user==2){   
        $query->select('*')->from('booking')->select('id,name,pet_sitter_id,pet_owner_id,status,booking_from_date,amount')->where('pet_sitter_id = '.$userID.' AND booking_from_date <= "'.$currentDate.'" AND booking_to_date >= "'.$currentDate.'" AND booking_status="1" AND payment_status="1" AND braintree_payment_status="1"')->orderBy(['booking_from_date'=>SORT_ASC]);
        }elseif($logged_user==1){
          $query->select('*')->from('booking')->select('id,name,pet_sitter_id,pet_owner_id,status,booking_from_date,amount')->where('pet_owner_id = '.$userID.' AND booking_from_date <= "'.$currentDate.'" AND booking_to_date >= "'.$currentDate.'" AND booking_status="1" AND payment_status="1" AND braintree_payment_status="1"')->orderBy(['booking_from_date'=>SORT_ASC]);
        }else{
        $query->select('*')->from('booking')->select('id,name,pet_sitter_id,pet_owner_id,status,booking_from_date,amount')->where('pet_renter_id = '.$userID.' AND booking_from_date <= "'.$currentDate.'" AND booking_to_date >= "'.$currentDate.'" AND booking_status="1" AND payment_status="1" AND braintree_payment_status="1"')->orderBy(['booking_from_date'=>SORT_ASC]);
        
        }
        return $query->createCommand()->queryAll();        
	}
	
	public function getActivityData($bookingId){
	$currentDate= date("Y-m-d");
	 $query 	= new Query;        
     $query->select('*')->from('booking_activity')->select('booking_activity.*,booking_activity_type.activity as activityname')->join('LEFT OUTER JOIN','booking_activity_type','booking_activity_type.id=booking_activity.activity_id')->where('booking_activity.activity_booking_id ="'.$bookingId.'"')->orderBy(['booking_activity.activity_id'=>SORT_ASC]);
     return $query->createCommand()->queryAll(); 	
	}
	
	public function getActivitytypes(){
	$data = array();
	$query 	= new Query;
	$query->select('id as activity_id,activity as activityname')->from('booking_activity_type')->where(['activity_status'=>'1']);
	return $query->createCommand()->queryAll();
	
	}
	
	public function addNewActivity($data){
		$connection = \Yii::$app->db; 
		
		$model = $connection->createCommand('Insert into booking_activity (activity_id,activity_start,activity_booking_id,activity_date,activity_created_at) values("'.$data['activity_id'].'","'.$data['activity_start'].'","'.$data['activity_booking_id'].'","'.$data['activity_date'].'","'.$data['activity_created_at'].'")');
			if($model->execute()){
			 return true;
			} else{
			 return false;
		    }	
	}
	
	public function addUnavailabilityDates($dates){
	
	$userid =  Yii::$app->user->identity->id;
	 	$connection = \Yii::$app->db; 		
		$model = $connection->createCommand('Insert into user_unavailability (user_id,dates) values("'.$userid.'","'.$dates.'")');
			if($model->execute()){
			 return true;
			} else{
			 return false;
		    }	
	}
	
	public function getBookingprice($selectcols,$bookingid){
	 $query 	= new Query;        
     $query->select($selectcols)->from('booking')->where('id ="'.$bookingid.'"');
     return $query->createCommand()->queryOne();
		
	}	
}

?>
