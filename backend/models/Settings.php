<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "setttings".
 *
 * @property integer $setting_id
 * @property string $setting_type
 * @property string $video
 */
class Settings extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'settings';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['setting_type'], 'required'],
            [['setting_type'], 'string'],
            [['video'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'setting_id' => 'Setting ID',
            'setting_type' => 'Setting Type',
            'video' => 'Video',
        ];
    }
}
