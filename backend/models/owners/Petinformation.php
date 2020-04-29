<?php
namespace backend\models\owners;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
* Petinformation model
*/
class Petinformation extends \yii\db\ActiveRecord {
	/**
	* @inheritdoc
	*/
    public static function tableName() {
        return 'pet_information';
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
                [['interested_in_renting'], 'string'],
                [['pet_type_id','user_id','per_day_price','interested_in_renting'], 'safe'],
        ];
    }
}
