<?php
namespace common\models;

use Yii;

/**
 * User model
 *
 * @property integer $id
 * @property string $countries
  */
class State extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'states';
    }

    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['country_id', 'name'], 'required'],
            ['name', 'string', 'max' => 60],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'country_id'=>'Country',
            'name'=>'State',
        ];
    }
    
    public function getCountry()
    {
        return $this->hasOne(Country::className(), ['id' => 'country_id']);
    }
}
?>
