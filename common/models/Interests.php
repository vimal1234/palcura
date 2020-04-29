<?php
namespace frontend\models;

use Yii;

/**
 * Inerests model
 *
 * @property integer $id
 * @property string $Inerests
  */
class Interests extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'interests';
    }

    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
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
