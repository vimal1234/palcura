<?php
namespace frontend\models;

use Yii;

/**
 * Geography model
 *
 * @property integer $id
 * @property string $Geography
  */
class Geography extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'geography';
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
