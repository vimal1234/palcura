<?php
namespace frontend\models\common;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
* UserServices model
*/
class Videoconversation extends \yii\db\ActiveRecord {
	/**
	* @inheritdoc
	*/
    public static function tableName() {
        return 'video_call_details';
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
			[['description','schedule_datetime','start_time'], 'required'],			
			[['name','description'], 'string'],
			[['name','description','pet_owner_id','pet_sitter_id','schedule_datetime','schedule_duration_time','call_status'], 'safe'],
			//['schedule_datetime', 'validateDates'],
        ];
    }
    /**
	* @inheritdoc
	*/
    public function attributeLabels() {
        return [
            'name' 				=> 'Title',
            'schedule_datetime' => 'Schedule date',
            'start_time'		=> 'Scheduled Time'
        ];
    }
    
	/**
	* validateDates
	* @param N/A
	* @return array
	*/
	/*public function validateDates() {
	$userid = Yii::$app->user->identity->id;
	
		$date = date('Y-m-d H:i:s',strtotime($this->schedule_datetime));
		$WHERE = "schedule_datetime = '".$date."' AND pet_owner_id='".$userid."' AND pet_sitter_id='".$this->pet_sitter_id."'";
		$query = new yii\db\Query;
		$query->select('id')
				->from('video_call_details')
				->where($WHERE);
		$Result = $query->createCommand()->getRawSql();
		
		if(isset($Result['id']) && !empty($Result['id'])) {
			 $this->addError('schedule_datetime', Yii::t('yii', 'Please select a valid date range.'));
		}
	}*/
	
	public function chkfordate($sitterid,$date){
		$userid = Yii::$app->user->identity->id;
		$date = date('Y-m-d',strtotime($date));
		$WHERE = "schedule_datetime = '".$date."' AND pet_owner_id='".$userid."' AND pet_sitter_id='".$sitterid."' AND approv_status in ('0','1')";
		$query = new yii\db\Query;
		$query->select('id')
				->from('video_call_details')
				->where($WHERE);
		$Result = $query->createCommand()->queryOne();
		
		if(!empty($Result) && count($Result) > 0){
		return true;
		}else{
		return false;
		}
	
	
	}
}
