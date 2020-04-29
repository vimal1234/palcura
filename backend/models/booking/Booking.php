<?php
namespace backend\models\booking;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
* Booking model
*
* @property integer $id
* @property string $title
* @property string $description
* @property string $dateCreated
* @property string $status
*/
class Booking extends \yii\db\ActiveRecord {
    /**
    * @inheritdoc
    */
    public static function tableName() {
        return 'booking';
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
                [['booking_from_date','booking_to_date','status','payment_status','date_created'], 'string'],
                [['name','pet_sitter_id','pet_owner_id','booking_from_date','booking_to_date','amount','status','payment_status','date_created'], 'required'],
        ];
    }

    /**
    * @inheritdoc
    */
    public function attributeLabels() {
        return [
            'amount' 		=> 'Amount',
            'status'	    => 'Status',
        ];
    }

	/**
	* @inheritdoc
	*/   
    public function getSitter() {
        return $this->hasOne(\backend\models\sitters\Sitters::className(), ['id' => 'pet_sitter_id']);
    }

	/**
	* @inheritdoc
	*/   
    public function getOwner() {
		return $this->hasOne(\backend\models\owners\Owners::className(), ['id' => 'pet_owner_id'])->from(['uname' => \backend\models\users\Users::tableName()]);
    }
    
    public function getRenter() {
		return $this->hasOne(\backend\models\owners\Owners::className(), ['id' => 'pet_renter_id'])->from(['urname' => \backend\models\users\Users::tableName()]);
    }    
}
