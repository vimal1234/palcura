<?php
namespace common\models;

use Yii;

/**
 * User model
 *
 * @property integer $id
 * @property string $countries
  */
class Country extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'countries';
    }

    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sortname', 'name'], 'required'],
            ['sortname', 'string', 'max' => 3],
            ['name', 'string', 'max' => 150],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [];
    }
    
    public function getState()
    {
        return $this->hasMany(State::className(), ['country_id' => 'id']);
    }
}
?>
