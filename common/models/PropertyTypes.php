<?php
namespace common\models;
use Yii;

/**
 * Property Types Model
 * @property integer $id
 * @property string $countries
 */
class PropertyTypes extends \yii\db\ActiveRecord {
    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'property_types';
    }

    
    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name', 'description'], 'required'],
            ['name', 'string', 'max' => 60],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'name' => 'Name',
            'description' => 'Description'
        ];
    }
}
?>
