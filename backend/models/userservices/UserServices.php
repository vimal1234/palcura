<?php
namespace backend\models\userservices;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
* UserService model
*
* @property integer $id
* @property integer $service_id
* @property integer $user_id
* @property number  $price
* @property string $dateCreated
* @property string $status
*/
class UserServices extends \yii\db\ActiveRecord {
	/**
	* @inheritdoc
	*/
    public static function tableName() {
        return 'user_services';
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
            [['service_id','user_id','price'], 'required'],
			 ['price', 'number', 'max' => 1000], 
        ];
    }
    
	/**
	* @inheritdoc
	*/
    public function attributeLabels() {
        return [
            'user_id' 		=> 'User',
            'service_id'	=> 'Service Name',
            'price' 		=> 'Price',
            'date_created' 	=> 'Datetime',
            'status'	    => 'Status',
        ];
    }

    public function getService() {
        return $this->hasOne(\backend\models\services\Services::className(), ['id' => 'service_id']);
    }   
    public function getUser() {
        return $this->hasOne(\backend\models\sitters\Sitters::className(), ['id' => 'user_id']);
    }            
}
