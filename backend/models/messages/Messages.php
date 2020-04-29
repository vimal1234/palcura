<?php
namespace backend\models\messages;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
* Messages model
*
* @property integer $id
* @property string $title
* @property string $description
* @property string $dateCreated
* @property string $status
*/
class Messages extends \yii\db\ActiveRecord {
    /**
    * @inheritdoc
    */
    public static function tableName() {
        return 'messages';
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
          [['title','message','user_from','user_to','removed_from','status','date_created'], 'required'],
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
    public function getUserfrom() {
        return $this->hasOne(\backend\models\sitters\Sitters::className(), ['id' => 'user_from']);
    }

	/**
	* @inheritdoc
	*/   
    public function getUserto() {
		return $this->hasOne(\backend\models\owners\Owners::className(), ['id' => 'user_to'])->from(['muserto' => \backend\models\users\Users::tableName()]);
    }
}
