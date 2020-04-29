<?php
namespace frontend\models;

use Yii;
use yii\web\UploadedFile;
use yii\db\Query;
use yii\base\Model;

class Video extends \yii\db\ActiveRecord
{

 	public $temp_description;
 	public $temp_extendses_min;
 	public $amount;

	public static function tableName()
		{
		    return 'vid_session_temp';
		} 
	public function rules() {
		    return [
		    // [['temp_description'], 'required'],	
		   // [['temp_extendses_min'],'required'],
		   // [['amount'],'number'],	       
		    ];
		 }
		 
	public function attributeLabels() {
        return [
            //'temp_extendses_min' 	=> 'Minutes',
            //'amount' => 'Amount',
           
        ];
    }	 	
		
	//check if session is already started by one of the user	
	/*public function getVidSessionData($docId,$patId){	
		$data = array();
		$connection = \Yii::$app->db;  
		$model = $connection->createCommand('SELECT * FROM vid_session_temp where temp_doctor_id="'.$docId.'" and temp_patient_id="'.$patId.'" and temp_status="1"' );
		$data = $model->queryOne();
		return $data;
		
	 }*/
	 
	/* public function saveTempSessiondata($temp_session_id,$temp_token,$temp_doctor_id,$temp_patient_id,$created_at,$con_id){
	  
	 $connection = \Yii::$app->db;  
	 if(Yii::$app->user->identity->user_type == 2){
	  $model = $connection->createCommand('Insert into vid_session_temp (temp_session_id, temp_token, temp_doctor_id, temp_patient_id, temp_status,temp_created_at,temp_doctor_status, temp_consult_id) values("'.$temp_session_id.'","'.$temp_token.'","'.$temp_doctor_id.'","'.$temp_patient_id.'","1","'.$created_at.'","1","'.$con_id.'")');	  
	  }  	  
	  if(Yii::$app->user->identity->user_type == 1){
	  $model = $connection->createCommand('Insert into vid_session_temp (temp_session_id, temp_token, temp_doctor_id, temp_patient_id, temp_status,temp_created_at,temp_patient_status, temp_consult_id) values("'.$temp_session_id.'","'.$temp_token.'","'.$temp_doctor_id.'","'.$temp_patient_id.'","1","'.$created_at.'","1","'.$con_id.'")');	  
	  } 
				
			if($model->execute()){
			return $connection->getLastInsertID();
			 //return true;
			} else{
			 return false;
			  }
	 	 
	 }*/
	 
	 /*public function updateTempSession($temp_id){
	 $currentTime = date('y-m-d h:i:s');
	  $connection = \Yii::$app->db;    

		$model = $connection->createCommand('update vid_session_temp SET temp_status = "0", temp_disconnected_at="'.$currentTime.'" where temp_id='.$temp_id);		
			if($model->execute()){			
			 return true;
			} else{
			 return false;
			}
	 	 
	 }*/
	 
	public function getVideoSessionHistory(){
	$temp_created_at = date('y-m-d h:i:s');
      if(Yii::$app->user->identity->user_type == 2){
      $data = Video::find()->where(['temp_doctor_id'=> Yii::$app->user->identity->id,'temp_status'=>"2"]);	
      }elseif(Yii::$app->user->identity->user_type == 1){
       $data = Video::find()->where(['temp_patient_id'=> Yii::$app->user->identity->id,'temp_status'=>"2"]);
       }
      //echo $data->createCommand()->getRawSql(); die;
       
		return $data;	
	
	} 
	
	public function updateExtendedSession($temp_id){
	 $currentTime = date('y-m-d h:i:s');
	  $connection = \Yii::$app->db;    
		if(Yii::$app->user->identity->user_type == 2){
		$model = $connection->createCommand('update vid_session_temp SET temp_extendses_doc = "1" where temp_id='.$temp_id);
		}elseif(Yii::$app->user->identity->user_type == 1){
		$model = $connection->createCommand('update vid_session_temp SET temp_extendses_pat = "1" where temp_id='.$temp_id);
		}
			if($model->execute()){			
			 return true;
			} else{
			 return false;
		    }	 	 
	 }
	 
	public function getSessionDatabyId($temp_id){
	
	   $data = array();
		$connection = \Yii::$app->db;  
		$model = $connection->createCommand('SELECT * FROM vid_session_temp where temp_id='.$temp_id );
		$data = $model->queryOne();
		return $data;
		
	} 
	
	public function addExtendedMinutes($temp_id,$minutes,$consultationid){
	
	$connection = \Yii::$app->db; 
	$model = $connection->createCommand('update vid_session_temp SET temp_extendses_min = "'.$minutes.'", temp_extendses_status="1" where temp_id='.$temp_id);
	$updateconsultation = $connection->createCommand('update consultations SET con_extended_duration = "'.$minutes.'" where con_id='.$consultationid);
	if($model->execute()){	
	$updateconsultation->execute();		
			 return true;
			} else{
			 return false;
		    }
	
	}
	
	public function checkExtendSesStatus($temp_id){
	
	$data = array();
		$connection = \Yii::$app->db;  
		$model = $connection->createCommand('SELECT temp_extendses_status FROM vid_session_temp where temp_id='.$temp_id );
		$data = $model->queryOne();
		return $data;
	
	}
	
	public function estendSesFailed($temp_id){
	
	$connection = \Yii::$app->db; 
	$model = $connection->createCommand('update vid_session_temp SET temp_extendses_status="2" where temp_id='.$temp_id);
	if($model->execute()){
	return true;
	}else{
	return false;
	}
	
	}

	public function getTreatmentCost(){
	
	   $data = array();
		$connection = \Yii::$app->db;  
		$model = $connection->createCommand('SELECT * FROM treatment' );
		$data = $model->queryOne();
		return $data;
		
	} 
	///new functions for palcura start
	public function getSessionUsers($vid_id){
	$currentuserid = Yii::$app->user->identity->id;
	   $data = array();
		$connection = \Yii::$app->db;  
		$model = $connection->createCommand('SELECT * FROM video_call_details where (pet_owner_id='.$currentuserid.' OR pet_sitter_id='.$currentuserid.') AND  id='.$vid_id );
		$data = $model->queryOne();
		return $data;
		
	} 
	
	//check if session is already started by one of the user	
	public function getVidSessionData($ownerid,$sitterid,$id){	
		$data = array();
		$connection = \Yii::$app->db;  
		$model = $connection->createCommand('SELECT * FROM vid_session_temp where temp_owner_id="'.$ownerid.'" and temp_sitter_id="'.$sitterid.'" and temp_vid_id="'.$id.'" and temp_status="1"' );
		$data = $model->queryOne();
		return $data;
		
	 }
	 
	  public function saveTempSessiondata($temp_session_id,$temp_token,$temp_owner_id,$temp_sitter_id,$created_at,$vid_id){
	  
	 $connection = \Yii::$app->db;  
	 if(Yii::$app->user->identity->id == $temp_owner_id){
	  $model = $connection->createCommand('Insert into vid_session_temp (temp_session_id, temp_token, temp_owner_id, temp_sitter_id, temp_status,temp_created_at,temp_owner_status, temp_vid_id) values("'.$temp_session_id.'","'.$temp_token.'","'.$temp_owner_id.'","'.$temp_sitter_id.'","1","'.$created_at.'","1","'.$vid_id.'")');	  
	  }  	  
	  if(Yii::$app->user->identity->id == $temp_sitter_id){
	  $model = $connection->createCommand('Insert into vid_session_temp (temp_session_id, temp_token, temp_owner_id, temp_sitter_id, temp_status,temp_created_at,temp_sitter_status, temp_vid_id) values("'.$temp_session_id.'","'.$temp_token.'","'.$temp_owner_id.'","'.$temp_sitter_id.'","1","'.$created_at.'","1","'.$vid_id.'")');	  
	  } 				
			if($model->execute()){
			return $connection->getLastInsertID();
			 //return true;
			} else{
			 return false;
		   }	 	 
	 }
	 
	 public function updateTempSession($temp_id,$vid_id){
	 $currentTime = date('y-m-d h:i:s');
	 $connection = \Yii::$app->db;    

	$model = $connection->createCommand('update vid_session_temp SET temp_status = "2", temp_disconnected_at="'.$currentTime.'" where temp_id='.$temp_id);		
			if($model->execute()){	
			$newmodel = $connection->createCommand('update video_call_details SET call_status = "1" where id='.$vid_id);	
			$newmodel->execute();
			 return true;
			} else{
			 return false;
			}	 	 
	 }
	 
	 
	 public function updateTempUserSessionstatus($temp_id,$temp_owner_id,$temp_sitter_id){
	
	 $connection = \Yii::$app->db;    
		if(Yii::$app->user->identity->id == $temp_owner_id){
		$model = $connection->createCommand('update vid_session_temp SET temp_owner_status = "1" where temp_id='.$temp_id);
		}elseif(Yii::$app->user->identity->id == $temp_sitter_id){
		$model = $connection->createCommand('update vid_session_temp SET temp_sitter_status = "1" where temp_id='.$temp_id);
		}
			if($model->execute()){			
			 return true;
			} else{
			 return false;
		    }		
	}
	
	public function checkdisconectstatus($temp_id){
     $data = array();
	 $connection = \Yii::$app->db;    
	 $data = $connection->createCommand('select temp_status from vid_session_temp where temp_id='.$temp_id)->queryOne();		
	 return $data;		
	 }
	 
	 public function getvideodatails($vid_id){
	$currentuserid = Yii::$app->user->identity->id;
	   $data = array();
		$connection = \Yii::$app->db;  
		$model = $connection->createCommand('SELECT * FROM video_call_details where id='.$vid_id );
		$data = $model->queryOne();
		return $data;
		
	}
	
	 public function confirmvideoreq($temp_id){
	  $connection = \Yii::$app->db;    

		$model = $connection->createCommand('update video_call_details SET approv_status = "1" where id='.$temp_id);		
			if($model->execute()){
			 return true;
			} else{
			 return false;
			}
	 	
	 }
	 
	 public function declinevideoreq($temp_id){
	  $connection = \Yii::$app->db;    

		$model = $connection->createCommand('update video_call_details SET approv_status = "2" where id='.$temp_id);		
			if($model->execute()){			
			 return true;
			} else{
			 return false;
			}
	 	
	 }
	 
	 public function updatesessiondate($date,$time,$id){
	  $connection = \Yii::$app->db;    
		$model = $connection->createCommand('update video_call_details SET schedule_datetime="'.$date.'",start_time="'.$time.'" where id='.$id);		
			if($model->execute()){
			 return true;
			} else {
			 return false;
			}
	 	
	 }
	 
	  public function updatesessionusercallstatus($usertype,$id){
	  $connection = \Yii::$app->db;  
	 
	  if($usertype == 'owner'){  
		$model = $connection->createCommand('update video_call_details SET owner_call_status="1"  where id='.$id);	
		}else{
		$model = $connection->createCommand('update video_call_details SET sitter_call_status="1"  where id='.$id);
		
		}	
			if($model->execute()){
			 return true;
			} else {
			 return false;
			}
	 	
	 }
	 
	 
	 public function chkfortodayssession(){
	 	 
	 $currentuserid =  Yii::$app->user->getId();	
	   $data = array();
	   $today = date('Y-m-d');
		$connection = \Yii::$app->db; 
		
		$model = $connection->createCommand('SELECT id FROM video_call_details where (pet_owner_id='.$currentuserid.' OR pet_sitter_id='.$currentuserid.') AND schedule_datetime="'.$today.'"');
		$data = $model->queryAll();
		if(count($data)>0){
		return true;
		}else{
		return false;
		}
	 }
	 
	 public function getnewactiveSessionUsers(){
	 	 
	 $currentuserid =  Yii::$app->user->getId();	
	   $data = array();
	   $today = date('Y-m-d');
		$connection = \Yii::$app->db;
		$model = $connection->createCommand('SELECT * FROM `video_call_details` WHERE `schedule_datetime` = "'.$today.'" AND `call_status` = "0" AND (`owner_call_status` = "1" OR `sitter_call_status` = "1") AND (`pet_owner_id` = "'.$currentuserid.'" OR pet_sitter_id = "'.$currentuserid.'")');
		$data = $model->queryOne();
		
		if(!empty($data)){
		if($currentuserid == $data['pet_owner_id'] && $data['owner_call_status']==1){
		$data = '';
		}elseif($currentuserid == $data['pet_sitter_id'] && $data['sitter_call_status']==1){
		$data = '';
		}else{
		$data = $data['id'];
		}
		
		}
		return $data;
	 }
	 
	 public function declineusercall($vid_id){
	 $connection = \Yii::$app->db;    
	 $model = $connection->createCommand('update video_call_details SET call_status = "1" where id='.$vid_id);		
			if($model->execute()){				
			 return true;
			} else{
			 return false;
			}

	 }
	 
	 public function updateusercallstatus($field,$id){
	  $connection = \Yii::$app->db;
		$model = $connection->createCommand('update video_call_details SET '.$field.'="0" where id='.$id);		
			if($model->execute()){
			 return true;
			} else {
			 return false;
			}
	 	
	 }


	 
}		
