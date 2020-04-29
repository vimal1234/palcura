<?php
namespace common\models;

use Yii;

/**
 * Usermemberships model
 *
 * @property integer $id
 * @property string $countries
  */
class Usermemberships extends \yii\db\ActiveRecord
{
    
    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'user_memberships';
    }

    
    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['user_id','membership_id','start_date','end_date'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'user_id' => 'User Id',
            'membership_id' => 'Membership Id'
        ];
    }
    public function getPackage() {
        return $this->hasOne(\backend\models\memberships\Package::className(), ['id' => 'membership_id']);
    }    
}
?>
