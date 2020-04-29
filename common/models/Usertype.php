<?php
namespace common\models;
use Yii;

/**
 * Inerests model
 *
 * @property integer $id
 * @property string $Inerests
  */
class Usertype extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_user_types';
    }

    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [];
    }
}
?>
