<?php
namespace backend\models\filesubscriber;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
* filesubscriber model
*
* @property integer $id
* @property string $title
* @property string $description
* @property string $dateCreated
* @property string $status
*/
class FileSubscriber extends \yii\db\ActiveRecord {

    /**
    * @inheritdoc
    */
    public static function tableName() {
        return 'subscriber';
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
          [['email'], 'required'],
        ];        
    }

    /**
    * @inheritdoc
    */
    public function attributeLabels() {
        return [
            'id' 		=> 'ID',
            'email'	    => 'Email',
        ];
    }

}
