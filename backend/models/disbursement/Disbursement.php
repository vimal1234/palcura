<?php
namespace backend\models\disbursement;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
* WebsiteQueries model
*
* @property integer $id
* @property string $title
* @property string $description
* @property string $dateCreated
* @property string $status
*/
class Disbursement extends \yii\db\ActiveRecord {
	/**
	* @inheritdoc
	*/
    public static function tableName() {
        return 'payment_disbursements';
    }
    
	/**
	* @inheritdoc
	*/
    public function behaviors() {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'datetime',
                'value' => new Expression('NOW()'),
            ],
        ];
    }

	/**
	* @inheritdoc
	*/
    public function rules() {
        return [
         [['id'], 'integer'],
          [['user_id','booking_id','status','date_created'], 'required'],
        ];        
    }

	/**
	* @inheritdoc
	*/
    public function attributeLabels() {
        return [
            'user_id' 		=> 'Username',
            'booking_id'	=> 'Booking',
            'status'	    => 'Status',
            'date_created'  => 'Datetime',
        ];
    }

    public function getBooking() {
        return $this->hasOne(\backend\models\booking\Booking::className(), ['id' => 'booking_id']);
    }   
    public function getUser() {
        return $this->hasOne(\backend\models\sitters\Sitters::className(), ['id' => 'user_id']);
    }    
}
