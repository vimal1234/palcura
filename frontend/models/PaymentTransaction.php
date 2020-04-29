<?php
namespace frontend\models;
use Yii;
use yii\db\Query;
/**
 * This is the model class for table "payment_transaction".
 *
 * @property integer $payment_transaction_id
 */
class PaymentTransaction extends \yii\db\ActiveRecord
{
    /**
     * @ Function Name		: tableName
     * @ Function Params	: NA
     * @ Function Purpose 	: get table name
     * @ Function Returns	: String
     */
 
    public static function tableName() {
        return 'payment_transaction';
    }

    /**
     * @ Function Name		: rules
     * @ Function Params		: NA 
     * @ Function Purpose 	: define the validations rules to apply on submitted form data
     * @ Function Returns	: array
     */
    public function rules() {
        return [
            [['user_id','amount','trans_id','payment_type','payment_status'], 'required'],
            
        ];
    }

    /**
     * @ Function Name		: attributeLabels
     * @ Function Params	: NA 
     * @ Function Purpose 	: defing the custom label for fields
     * @ Function Returns	: array
     */
    public function attributeLabels() {
        return [
            'trans_id' => Yii::t('yii','Transaction Id'),
        ];
    }
    
    public function relations(){
    return array(
            'booking'=>array(self::HAS_ONE, 'Booking', 'booking_id'),
        );

    }
    
    public function getTransactiondetails($bookingIds){
   
    $Idarray = explode(',',$bookingIds);  
    $data = array();
	//$connection = \Yii::$app->db;    
	$data = PaymentTransaction::find()->select('payment_transaction.*,payment_disbursements.status AS reqstatus,booking.*')->where(array('in', 'payment_transaction.booking_id', $Idarray));
	return $data;    
    }
    
    public function addpaymentRequest($bookingid){
    $data = array();
    $currentuser = Yii::$app->user->identity->id;
	$connection = \Yii::$app->db;    
	$model = $connection->createCommand('Insert into payment_disbursements (user_id, booking_id) values("'.$currentuser.'","'.$bookingid.'")');	
		if($model->execute()){
		 return true;
		} else{
		 return false;
		}        
    }
    public function getdisbursementdata($bookingid){
   $data = array();
		$connection = \Yii::$app->db;
		$model = $connection->createCommand('SELECT * from payment_disbursements where booking_id='.$bookingid );
		$data = $model->queryOne();		
		return $data;
    }
    public function getBookingCredits($bookingid){
   $data = array();
		$connection = \Yii::$app->db;
		$model = $connection->createCommand('SELECT booking_credits from booking where id='.$bookingid );
		$data = $model->queryOne();		
		return $data;
    }
    
     public function updateCredits($userid,$ownercredits,$sittercredits){
        $connection = \Yii::$app->db;          
		$model = $connection->createCommand('UPDATE user SET sitter_credits = "'.$sittercredits.'", owner_credits = "'.$ownercredits.'" WHERE id ='.$userid);		
		if($model->execute()){
		 return true;
		}else{
		 return false;
		} 
    
    }
    
     public function updateBraintreeCustId($userid,$newcustomerid){
        $connection = \Yii::$app->db;   
        
		$model = $connection->createCommand('UPDATE user SET braintree_customer_id = "'.$newcustomerid.'"  WHERE id ='.$userid);		
		if($model->execute()){
		 return true;
		}else{
		 return false;
		} 
    
    }
    
}
