<?php

namespace frontend\models;

use Yii;
use yii\db\Query;
use yii\base\Model;

/**
 * Inerests model
 *
 * @property integer $id
 * @property string $Inerests
 */
class Refund extends \yii\db\ActiveRecord {
	
    /**
     * @inheritdoc
     */
    public static function tableName() {
      
       return 'payment_refund';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [				        
          //[['ref_reason'], 'required'],
          //[['ref_created_at','ref_user_id','ref_for_session','ref_status','ref_amount','ref_reason'],'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            //'ref_amount' 	=> ' Refund Amount',
            //'ref_reason' 	=> 'Reson for refund',
        ];
    }
    
    public function saveRefundinfo(){
    
   		$connection = \Yii::$app->db;   		   	
   		//echo 'Insert into payment_refund(ref_amount, ref_created_at,ref_user_id,ref_booking_id) values("'.$this->ref_amount.'","'.$this->ref_created_at.'","'.$this->ref_user_id.'","'.$this->ref_booking_id.'")'; die;    		
		$model = $connection->createCommand('Insert into payment_refund(ref_amount,ref_created_at,ref_user_id,ref_booking_id,ref_txn_id,ref_ack,ref_status) values("'.$this->ref_amount.'","'.$this->ref_created_at.'","'.$this->ref_user_id.'","'.$this->ref_booking_id.'","'.$this->ref_txn_id.'","'.$this->ref_ack.'","'.$this->ref_status.'")');		
			if($model->execute()){
			 return true;
			}else{
			 return false;
			}         
    }
    
    public function checkForRefund($sessionid){
    
    	$data = array();
		$connection = \Yii::$app->db;  
		$model = $connection->createCommand('SELECT * FROM payment_refund where ref_for_session='.$sessionid );
		$data = $model->queryOne();
		if(count($data)>0){		
		return true;
		}else{		
		return false;
		}    
    }
    
    public function updateBookingStatus($bookingId,$usertype){
   // $session = Yii::$app->session;
   // $usertype = $session->get('loggedinusertype');	
    if(empty($usertype)){
    $usertype = 2;
    }
    	$connection = \Yii::$app->db;     	
		$model = $connection->createCommand('UPDATE booking SET booking_status = "3",cancelled_by="'.$usertype.'" WHERE id ='.$bookingId);	
		if($model->execute()){
		 return true;
		}else{
		 return false;
		} 
    }
    
    public function getTransactionDetails($bookingId){
    
        $data = array();
		$connection = \Yii::$app->db; 
		
		$model = $connection->createCommand('SELECT payment_transaction.trans_id,booking.booking_credits,booking.reward_points,booking.amount,booking.pet_sitter_id,booking.pet_owner_id,booking.pet_renter_id,booking.booking_from_date,booking.booking_to_date,booking.name,payment_transaction.currency FROM payment_transaction LEFT JOIN booking ON booking.id=payment_transaction.payment_transaction_id where payment_transaction.booking_id='.$bookingId );
		$data = $model->queryOne();
		return $data;
    
    }
    
    public function updatecredits($userid,$credit,$forUser){
        $connection = \Yii::$app->db;  
        if($forUser == 'sitter'){   	
		$model = $connection->createCommand('UPDATE user SET sitter_credits = "'.$credit.'" WHERE id ='.$userid);
		}
		if($forUser=='owner'){
		$model = $connection->createCommand('UPDATE user SET owner_credits = "'.$credit.'" WHERE id ='.$userid);		
		}		
		if($model->execute()){
		 return true;
		}else{
		 return false;
		} 
    
    }
    
    public function updaterewardpoints($userid,$rewardpoints){
        $connection = \Yii::$app->db;          
		$model = $connection->createCommand('UPDATE user SET reward_points = "'.$rewardpoints.'" WHERE id ='.$userid);		
		if($model->execute()){
		 return true;
		}else{
		 return false;
		} 
    
    }
    
    public function getusedRewards($bookingid){
    	$data = array();
		$connection = \Yii::$app->db;
		$model = $connection->createCommand('SELECT booking.*,booking.amount as fullamount,used_rewards.*,payment_transaction.* FROM booking left join used_rewards on booking.id=used_rewards.booking_id left join payment_transaction on booking.id=payment_transaction.booking_id where booking.id='.$bookingid );
		$data = $model->queryOne();		
		return $data;
    }
    
  
}

?>
