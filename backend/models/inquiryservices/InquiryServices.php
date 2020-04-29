<?php
namespace backend\models\InquiryServices;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
* WebsiteQueries model
*
* @property integer $id
* @property string $title
* @property string $dateCreated
* @property string $status
*/
class InquiryServices extends \yii\db\ActiveRecord {

    /**
    * @inheritdoc
    */
    public static function tableName() {
        return 'website_queriesnear';
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
          [['user_id','title','name','status','reviewed_by_admin','date_created'], 'required'],
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

	/**
	* @inheritdoc
	*/   
    public function getUsername() {
        return $this->hasOne(\backend\models\sitters\Sitters::className(), ['id' => 'user_id']);
    }

}
