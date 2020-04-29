<?php
namespace common\models;

use Yii;

/**
 * User model
 *
 * @property integer $id
 * @property string $countries
  */
class Currency extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'currencies';
    }

    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['currency_description', 'currency_description'], 'required'],
            ['currency_name', 'string', 'max' => 10],
            ['currency_description', 'string', 'max' => 250],
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
