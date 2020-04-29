<?php
namespace backend\models\teaser;
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
class Teaser extends \yii\db\ActiveRecord {

	/**
	* @inheritdoc
	*/
    public static function tableName() {
        return 'teaser_details';
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
          [['firstname','lastname','email','status','date_created'], 'required'],
        ];        
    }

	/**
	* @inheritdoc
	*/
    public function attributeLabels() {
        return [
            'title' 		=> 'Title',
            'status'	    => 'Status',
        ];
    }
}
