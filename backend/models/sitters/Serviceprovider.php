<?php
namespace backend\models\sitters;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
* Serviceprovider model
*/
class Serviceprovider extends \yii\db\ActiveRecord {
	/**
	* @inheritdoc
	*/
    public static function tableName() {
        return 'service_provider_details';
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
                [['pitch'], 'string'],
                [['user_id','day_price','pitch','pet_weight_limit'], 'safe'],
        ];
    }
}
