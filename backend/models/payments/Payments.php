<?php
namespace backend\models\payments;
use Yii;
use backend\models\users\Users;

/**
* @Payment
*/
class Payments extends \yii\db\ActiveRecord {
	/**
	* @inheritdoc
	*/
    public static function tableName() {
        return 'payment_transaction';
    }

	/**
	* @inheritdoc
	*/
    public function rules() {
        return [
            [['user_id', 'booking_id','trans_id','amount','trans_date','payment_type'], 'required'],
        ];
    }
    
	/**
	* @inheritdoc
	*/
    public function attributeLabels() {
        return [
            'payment_transaction_id' 	=> 'Payment Transaction Id',
            'user_id' 					=> 'Customer Name',
            'booking_id' 				=> 'Booking Id',
            'amount'                    => 'Amount',
            'trans_id'                  => 'Transaction ID',
            'trans_date'                => 'Date of Transaction',
            'payment_type'              => 'Payment Type',
            'payment_status' 			=> 'Status',
        ];
    }

	/**
	* @getUser: get users
	*/
    public function getUser() {
        return $this->hasOne(Users::className(), ['id' => 'user_id']);
    }
	/**
	* @getUser: get booking
	*/
    public function getBooking() {
        return $this->hasOne(\backend\models\booking\Booking::className(), ['id' => 'booking_id']);
    }    
}
