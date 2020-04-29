<?php
namespace backend\models\petvaccinations;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
* Petvaccination model
*
* @property integer $id
* @property integer $service_id
* @property integer $user_id
* @property number  $price
* @property string $dateCreated
* @property string $status
*/
class Petvaccinations extends \yii\db\ActiveRecord {
	/**
	* @inheritdoc
	*/
    public static function tableName() {
        return 'pet_vaccination_details';
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
            [['user_id'], 'required'],
        ];
    }
    
	/**
	* @inheritdoc
	*/
    public function attributeLabels() {
        return [
            'user_id' 		=> 'Username',
            'pet_name'		=> 'Pet Name ',
            'emergency_contact' => 'Phone',
            'start_date' 	=> 'Start Date',
            'end_date' 		=> 'End date',
            'status'	    => 'Status',
        ];
    }

    public function getUser() {
        return $this->hasOne(\backend\models\sitters\Sitters::className(), ['id' => 'user_id']);
    }            
}
