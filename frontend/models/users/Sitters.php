<?php
namespace frontend\models\users;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * Users model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class Sitters extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            TimestampBehavior::className(),
        ];
    }
    /**
     * user and country one to one relation
     * @return string
    */
    public function getCountryname() {
        return $this->hasOne(\common\models\Country::className(), ['id' => 'country']);
    }
    
    public function getCurrentnation() {
        return $this->hasOne(\common\models\Country::className(), ['id' => 'nationality']);
    }    
    
    public function getRegionname() {
        return $this->hasOne(\common\models\State::className(), ['id' => 'region']);
    }
    
    public function getCityname() {
        return $this->hasOne(\common\models\City::className(), ['id' => 'city']);
    }

    public function getUsertype() {
        return $this->hasOne(\common\models\Usertype::className(), ['id' => 'user_type']);
    }
    
    public function getServiceprovider() {
        return $this->hasOne(\backend\models\sitters\Serviceprovider::className(), ['user_id' => 'id']);
    }    
}
