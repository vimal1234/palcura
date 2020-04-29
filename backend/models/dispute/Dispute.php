<?php
namespace backend\models\dispute;
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
class Dispute extends \yii\db\ActiveRecord {
	/**
	* @inheritdoc
	*/
    public static function tableName() {
        return 'dispute_resolutions';
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
          [['user_id','booking_id','title','description','form_type','verified_by_admin','status','date_created'], 'required'],
        ];        
    }

	/**
	* @inheritdoc
	*/
    public function attributeLabels() {
        return [
            'title' 		=> 'Title',
            'status'	    => 'Status',
            'form_type'	    => 'Forms',
            'verified_by_admin'	    => 'Verifications',
        ];
    }

    public function getBooking() {
        return $this->hasOne(\backend\models\booking\Booking::className(), ['id' => 'booking_id']);
    }   
    public function getUser() {
        return $this->hasOne(\backend\models\sitters\Sitters::className(), ['id' => 'user_id']);
    }    
}
