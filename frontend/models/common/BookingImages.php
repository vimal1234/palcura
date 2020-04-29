<?php
namespace frontend\models\common;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
* Documents model
*/
class BookingImages extends \yii\db\ActiveRecord {
	/**
	* @inheritdoc
	*/
    public static function tableName() {
        return 'booking_images';
    }
    
	/**
	* @inheritdoc
	*/
    public function behaviors() {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'datetime',
                'value' => new Expression('NOW()'),
            ],
        ];
    }

	/**
	* @inheritdoc
	*/
    public function rules() {
        return [
                [['name','media_type'], 'string'],
                [['name','media_type','user_id','booking_id'], 'safe'],
        ];
    }
    
    public function savedata($mediaArr,$bookingID,$userID) {
		$response_counter	=	false;
		if(isset($mediaArr['upload_booking_images']) && !empty($mediaArr['upload_booking_images'])) {
			foreach($mediaArr['upload_booking_images'] as $row) {
				$data2 					= new BookingImages();
				$data2->name 			= $row;
				$data2->media_type		= 'BOOKING';
				$data2->booking_id		= $bookingID;
				$data2->delete_status	= '0';
				$data2->user_id 		= $userID;
				if($data2->save()) {
					$response_counter=1;
				}
			}
		}
		return $response_counter;
	}
}
