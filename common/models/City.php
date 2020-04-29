<?php
namespace common\models;

use Yii;

/**
 * User model
 *
 * @property integer $id
 * @property string $countries
  */
class City extends \yii\db\ActiveRecord
{
    public $country_id;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cities';
    }

    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'state_id', 'country_id'], 'required'],
            ['name', 'string', 'max' => 60],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'state_id' => 'State',
            'country_id' => 'Country'
        ];
    }
    
    public function getState()
    {
        return $this->hasOne(State::className(), ['id' => 'state_id']);
    }
    
    public function afterFind() {
        parent::afterFind();
        return $this->country_id = $this->state['country_id'];
    }
    
################################################//MODEL EVENTS############################################################    
//    public function init(){
//        // first parameter is the name of the event and second is the handler. 
//        // For handlers I use methods prepopulateFields from $this class.
//        $this->on(self::EVENT_AFTER_FIND, [$this, 'prepopulateFields']);        
//    }
//    
//    public function prepopulateFields($event)
//    {
//        $this->country_id = $this->state['country_id'];
//        #echo $this->country_id."SHIVGRE".$this->state['country_id'];
//    }
}
?>
