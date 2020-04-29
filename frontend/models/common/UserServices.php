<?php
namespace frontend\models\common;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
* UserServices model
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
                [['service_id','user_id'], 'integer'],
                [['service_id','user_id','price','status'], 'safe'],
        ];
    }
    
    public function getUserservicesByid($id){
    $data = array();
    $data = UserServices::find()->where(['user_id' => $id])->asArray()->all();
    return $data;
    }
}
