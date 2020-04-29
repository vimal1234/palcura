<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $username
 * @property string $fullname
 * @property string $dob
 * @property string $gender
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'fullname', 'dob', 'gender', 'auth_key', 'password_hash', 'email', 'created_at', 'updated_at'], 'required'],
            [['dob'], 'safe'],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['username', 'fullname', 'password_hash', 'password_reset_token', 'email'], 'string', 'max' => 255],
            [['gender'], 'string', 'max' => 2],
            [['auth_key'], 'string', 'max' => 32],
            [['username'], 'unique'],
            [['email'], 'unique'],
            [['password_reset_token'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' 			=> Yii::t('yii','ID'),
            'username'  	=> Yii::t('yii','Username'),
            'fullname'  	=> Yii::t('yii','Fullname'),
            'dob' 			=> Yii::t('yii','Date of birth'),
            'gender' 		=> Yii::t('yii','Gender'),
            'auth_key' 		=> Yii::t('yii','Auth Key'),
            'password_hash' => Yii::t('yii','Password Hash'),
            'password_reset_token' => Yii::t('yii','Password Reset Token'),
            'email' 		=> Yii::t('yii','Email'),
            'status' 		=> Yii::t('yii','Status'),
            'created_at' 	=> Yii::t('yii','Created At'),
            'updated_at' 	=> Yii::t('yii','Updated At'),
        ];
    }
}
