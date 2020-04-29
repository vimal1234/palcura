<?php
namespace backend\models\users;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * News model
 *
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property string $dateCreated
 * @property string $status
 */
class Documents extends \yii\db\ActiveRecord
{
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
    
    /**
    * @inheritdoc
    */
    public function attributeLabels() {
        return [
            'id'		 	=> 'ID',
            'name' 			=> 'Title',
            'date_created' 		=> 'Datetime',
            'status'	    => 'Status',
        ];
    }
}
