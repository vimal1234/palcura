<?php
namespace frontend\models;

use Yii;
use yii\db\ActiveRecord;

class FeedbackRating extends ActiveRecord {
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%feedback_rating}}';
    }

    /**
     * @inheritdoc
     */
    
    public function rules()
    {
        return [
            ['starrating', 'required','message'=>Yii::t('yii','Please select the stars to give your rating.')],
            ['comment', 'required'],
			[['comment'], 'string', 'max' => 2000],
        ];
    }
	
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'starrating' => Yii::t('yii','How many stars would you like to rathe this Insider?'),
            'comment' => Yii::t('yii','Comments'), 
        ];
    }
    
    /**
     * one on one relation between booking and feedback_rating
     * @return object
     */
    public function getBooking()
    {
        return $this->hasOne(Jobs::className(), ['booking_id' => 'booking_id']);
    }
    
    public function getUserRating($id){
    
      $data = array();     
      $data = FeedbackRating::find()->where(['receiver_userid'=> $id])->orderBy(['id' => SORT_DESC])->asArray()->all();
      $totalreviews = count($data);
if($totalreviews > 0){
		  $rating1 = 0;$rating2=0;$rating3=0;$rating4=0;$rating5=0;
			foreach ($data as $key=>$value){
				if($value['starrating'] == 1){
				$rating1++;
				}elseif($value['starrating'] == 2){
				$rating2++;
				}
				elseif($value['starrating'] == 3){
				$rating3++;
				}elseif($value['starrating'] == 4){
				$rating4++;
				}elseif($value['starrating'] == 5){
				$rating5++;
				}								
			}
			$averageRating = (5*$rating5 + 4*$rating4 + 3*$rating3 + 2*$rating2 + 1*$rating1) / ($rating5+$rating4+$rating3+$rating2+$rating1);
			$ratinginfo = $data;
			}else{
			$averageRating = 0;
			$ratinginfo = array();
			}
			$dataArray = array('averagerating' => $averageRating,'totalreviews'=> $totalreviews,'ratinginfo'=>$ratinginfo);
		   return $dataArray;
		  		 
    }
    
     // function to add review  
     public function addReview(){
     
     $this->date_time = date('Y-m-d h:i:s');
     $this->sender_userid = Yii::$app->user->identity->id;
     $receiver_userid=$this->receiver_userid;  
     $starrating=$this->starrating;  
     $connection = \Yii::$app->db;        
     //update overall rating for reciever          
	 $model = $connection->createCommand('Insert into feedback_rating (booking_id,starrating, comment, sender_userid, receiver_userid, date_time) values("'.$this->booking_id.'","'.$this->starrating.'","'.$this->comment.'","'.$this->sender_userid.'","'.$this->receiver_userid.'","'.$this->date_time.'")');
		
			if($model->execute()){	
				$this->updateRating($receiver_userid,$starrating);
			 return true;
			} else{
			 return false;
			  }
     
     }
     
     public function updateRating($userID,$starrating) {
		####= { $selectCol } use to get specific columns of users. 
		$selectCol  = "user_average_rating,user_rating,total_rating";
		$userInfo	= Yii::$app->commonmethod->getUserColumnsData($userID,$selectCol);
		$rating	= (isset($userInfo['user_rating']) ? $userInfo['user_rating'] : 0) + $starrating;
		$total_rating	= (isset($userInfo['total_rating']) ? $userInfo['total_rating'] : 0) + 5;
		$averageRating  = round($rating/($total_rating/5));
		Yii::$app->db->createCommand()->update('user', ['user_average_rating' => $averageRating, 'user_rating' => $rating, 'total_rating' => $total_rating], 'id = '.$userID)->execute();
	 }

    
}
