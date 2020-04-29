<?php
namespace backend\models\socialicons;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * Socialicon model
 *
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property string $dateCreated
 * @property string $status
 */
class Socialicons extends \yii\db\ActiveRecord
{
    /**
    * @inheritdoc
    */
    public static function tableName() {
        return 'social_icons';
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
                [['name','description'], 'string'],
                [['name','description'], 'required'],
        ];
    }
    
    /**
    * @inheritdoc
    */
    public function attributeLabels() {
        return [
            'name' 			=> 'Name',
            'image' 		=> 'Image',
            'description' 	=> 'Description',
            'url' 			=> 'Url',
            'date_created' 	=> 'Date',
            'status' 		=> 'Status',
        ];
    }
}
