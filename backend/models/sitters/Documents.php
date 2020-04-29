<?php
namespace backend\models\sitters;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
* Documents model
*/
class Documents extends \yii\db\ActiveRecord {
	/**
	* @inheritdoc
	*/
    public static function tableName() {
        return 'users_documents';
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
                [['name','document_type'], 'string'],
                [['name','document_type','user_id'], 'safe'],
        ];
    }
}
