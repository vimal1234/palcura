<?php
namespace backend\models\feedback;
use Yii;

/**
 * This is the model class for table "feedback".
 *
 * @property integer $id
 * @property string $sender_userid
 * @property string $receiver_userid
 * @property string $comment
 * @property string $starrating
 * @property string $date_time
 * @property string $status
 * @property string $booking_id
 */
class Feedback extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'feedback_rating';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['comment'], 'string'],
            [['comment','starrating'], 'required'],
            [['comment'], 'string', 'max' => 2000],
            ['starrating', 'string', 'max'=> 1],
            ['starrating', 'integer', 'min'=> 0, 'max' => 5]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' 				=> 'ID',
            'sender_userid' 	=> 'Sender ID',
            'receiver_userid' 	=> 'Receiver ID',
            'comment' 			=> 'Comment',
            'starrating' 		=> 'Rating',
            'date_time' 		=> 'Date Time',
            'status' 			=> 'Status',
            'booking_id' 		=> 'Booking ID',
        ];
    }

    /**
    * @inheritdoc
    */   
    public function getUserSender() {
        return $this->hasOne(\backend\models\users\Users::className(), ['id' => 'sender_userid']);
    }

    /**
    * @inheritdoc
    */   
    //~ public function getUserReceiver() {
        //~ return $this->hasOne(\backend\models\users\Users::className(), ['id' => 'receiver_userid']);
    //~ }

    public function getUserReceiver() {
        return $this->hasOne(\backend\models\users\Users::className(), ['id' => 'receiver_userid'])->from(['uto' => \backend\models\users\Users::tableName()]);
    }
    
    /**
    * @inheritdoc
    */   
    public function getStatusFormat() {
        return ($this->status == '1') ? 'Active' : 'Inactive';
    }
}
