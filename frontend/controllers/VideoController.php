<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Controller;
use yii\helpers\Url;
use frontend\models\users\Users;
use yii\db\Query;
use frontend\models\Booking;
use yii\data\Pagination;
use frontend\models\Video;
use common\models\User;

//opentok related api

require Yii::$app->basePath."/OpenTok/vendor/autoload.php";
use OpenTok\OpenTok;
use OpenTok\MediaMode;
use OpenTok\ArchiveMode;
use OpenTok\Session;
use OpenTok\Role;

class VideoController extends Controller {
	public $enableCsrfValidation = false;
	private $limit = 10;
	/**
	* @ Function Name		: actionIndex
	* @ Function Params		: NA 
	* @ Function Purpose 	: default index function that will be called to display payments
	* @ Function Returns	: render view
	*/	
    public function actionIndex() {		
		 $userId 	= Yii::$app->user->getId();
         $dataArray = array();
         $today = date('Y-m-d');
         $videoModel = new Video();
         
         //upcoming video sessions
         $query = new Query;
         $query->select('*')
         ->from('video_call_details')
         ->where('pet_sitter_id = '.$userId.' OR pet_owner_id = '.$userId)
         ->andWhere('call_status="0" AND schedule_datetime>="'.$today.'"')->andWhere('approv_status!="2"');
         $countQuery = clone $query;
         $pages 	 = new Pagination(['totalCount' => $countQuery->count()]);
         $pages->setPageSize($this->limit);
		 $query->offset($pages->offset)->orderBy(['id' => SORT_DESC])->limit($this->limit);         
         $videolisting = $query->createCommand()->queryAll();

		 //todays video sessions
         $query = new Query;
         $query->select('id,pet_owner_id,pet_sitter_id,sitter_call_status,owner_call_status')
         ->from('video_call_details')
         ->where('pet_sitter_id = '.$userId.' OR pet_owner_id = '.$userId)
         ->andWhere('call_status="0" AND schedule_datetime="'.$today.'"')->andWhere('approv_status!="2"');
         $countQuery = clone $query;
         $pages 	 = new Pagination(['totalCount' => $countQuery->count()]);
         $pages->setPageSize($this->limit);
		 $query->offset($pages->offset)->orderBy(['id' => SORT_DESC])->limit($this->limit);         
         $todayvideolisting = $query->createCommand()->queryAll();
         
         foreach($todayvideolisting as $key=>$val){        
         $vidid = $val['id'];
         if(($val['owner_call_status']==1 && $val['sitter_call_status']==0) || ($val['owner_call_status']==0 && $val['sitter_call_status']==1)){
		     if($userId == $val['pet_owner_id']){
		     $updatefield = 'owner_call_status';
		     }elseif($userId == $val['pet_sitter_id']){
		     $updatefield = 'sitter_call_status';
		     }
		     //update user_call_status
		     $videoModel->updateusercallstatus($updatefield,$vidid);		     
         }
         }	
						
		//past video sessions
		 $query = new Query;
         $query->select('*')
         ->from('video_call_details')
         ->where('pet_sitter_id = '.$userId.' OR pet_owner_id = '.$userId)
         ->andWhere('call_status="1" AND schedule_datetime<="'.$today.'"');
         $countQuery = clone $query;
         $pagesH 	 = new Pagination(['totalCount' => $countQuery->count()]);
         $pagesH->setPageSize($this->limit);
		 $query->offset($pagesH->offset)->orderBy(['schedule_datetime' => SORT_DESC])->limit($this->limit);         
         $videohistorylisting = $query->createCommand()->queryAll();
		
         $dataArray = array_merge($dataArray, [            
              'listing' 	=> $videolisting,
              'pages' 		=> $pages,
              'videohistory'=> $videohistorylisting,
              'pagesH'		=> $pagesH
         ]);
      
        return $this->render('index',$dataArray);								
    }
    
    public function actionChatinit($id){
    $videoid = $id;
    $userid = Yii::$app->identity->user->id;
    $videoModel = new Video();
    //get session users
    $getsessionusers = $videoModel->getSessionUsers($id);
    
    }
    
    /*public function actionTalk($id){ 
   
    $ownerid='';
    $sitterid= '';
    $duration="0";
    //$apiKey = "46095912";
	//$apiSecret = "83c3a478e8c4a7df0495b784a14df532ae9d67e5";
$apiKey = "46095692";
	$apiSecret = "d38e9af4fda8b603e4bde4e2b7afbec968900f63";
	$sessionId = '';
	$token = '';
    $temp_id = '';
	
    	$sessionCreatedfor  = $id ;
    	$videoModel = new Video();
    	//get session users
    	$getsessionusers = $videoModel->getSessionUsers($id);
    	$ownerid = $getsessionusers['pet_owner_id'];
    	$sitterid =	$getsessionusers['pet_sitter_id'];
    	$durationtime = $getsessionusers['schedule_duration_time']; 
    	$scheduledtime = $getsessionusers['schedule_datetime'];   	
		$currentuserid = Yii::$app->user->identity->id;
		$scheduleddate = date('Y-m-d', strtotime($scheduledtime));
		$currentdate = date('Y-m-d');
    	if(!empty($getsessionusers) && ($scheduleddate==$currentdate)){
    	//chk if session is already strated by any one of the users
    	$chekSession = $videoModel->getVidSessionData($ownerid,$sitterid,$id);
    	
			if(empty($chekSession)){				   			   		
				$opentok = new OpenTok($apiKey, $apiSecret);
				//generate a sessionId 
				$sessionOptions = array(
					'archiveMode' => ArchiveMode::ALWAYS,
					'mediaMode' => MediaMode::ROUTED
				);
				$session = $opentok->createSession($sessionOptions);
				$sessionId = $session->getSessionId();

				//generate token for session
				$token = $session->generateToken(array(
					'role'       => Role::MODERATOR,
					'expireTime' => time()+(7 * 24 * 60 * 60), // in one week
					//'data'       => 'name=Johnny'
				));
				
				//save session data to database.it will store a temporary video session data for owner and sitter session 
				$sessioncreatedat=date("Y-m-d H:i:s");
				$savesessiondata = $videoModel->saveTempSessiondata($sessionId,$token,$ownerid,$sitterid,$sessioncreatedat,$id);

				$temp_id = $savesessiondata;
			 }else{			 
			    $temp_id = $chekSession['temp_id'];
			    $sessionId = $chekSession['temp_session_id'];
			    $token = $chekSession['temp_token'];
			    $sessioncreatedat = $chekSession['temp_created_at'];
			    $currenttime = date("Y-m-d H:i:s");

			    
			    $updateUserAvailability = $videoModel->updateTempUserSessionstatus($temp_id,$ownerid,$sitterid);
			 }
    	    	
    	}else{
    	Yii::$app->session->setFlash('error', 'No video session scheduled for today.');
		return $this->redirect(['video/index']);    	    	
    	}    	
    	//check if a session is already created by any user
     
   //$this->layout = false;
   return  $this->render('talk',[
   'apiKey' => $apiKey,
   'sessionId' => $sessionId,
   'token' => $token,
   //'duration' => $con_duration,
   'duration' => $durationtime,
   'sessioncreatedat' =>$sessioncreatedat,
   'temp_id' => $temp_id, 
   'model' =>$videoModel,
   'vid_id' => $id, 
  
   ]);
  }*/
  
  public function actionTalk($id){ 
   
    $ownerid='';
    $sitterid= '';
    $duration="0";
    $apiKey = "46095692";
	$apiSecret = "d38e9af4fda8b603e4bde4e2b7afbec968900f63";
	$sessionId = '';
	$token = '';
    $temp_id = '';
	
    	$sessionCreatedfor  = $id ;
    	$videoModel = new Video();
    	//get session users
    	$getsessionusers = $videoModel->getSessionUsers($id);
    	$vidcallstatus =   $getsessionusers['call_status'];
    	if($vidcallstatus == 1){    
		return $this->redirect(['video/index']);  
    	}
    	
    	$ownerid = $getsessionusers['pet_owner_id'];
    	$sitterid =	$getsessionusers['pet_sitter_id'];
    	$durationtime = $getsessionusers['schedule_duration_time']; 
    	$scheduledtime = $getsessionusers['schedule_datetime']; 
    	
		$currentuserid = Yii::$app->user->identity->id;
		$scheduleddate = date('Y-m-d', strtotime($scheduledtime));
		$currentdate = date('Y-m-d');
    	if(!empty($getsessionusers) && ($scheduleddate==$currentdate)){
    	//chk if session is already strated by any one of the users
    	$chekSession = $videoModel->getVidSessionData($ownerid,$sitterid,$id);
    	
			if(empty($chekSession)){
			//send email notification to other user
			if($currentuserid == $ownerid){
			$recieverid = $sitterid;
			$usertype = 'owner';
			}else{
			$recieverid = $ownerid;
			$usertype = 'sitter';
			}			
	  		$videoModel->updatesessionusercallstatus($usertype,$id);
	  		
			  $ownerstatus = $getsessionusers['owner_call_status'];
			  $sitterstatus =	$getsessionusers['sitter_call_status'];
  		
				$this->videocalnotifyEmail($recieverid);	
				if($ownerstatus == 1 && $sitterstatus == 1){		   			   		
				$opentok = new OpenTok($apiKey, $apiSecret);
				//generate a sessionId 
				$sessionOptions = array(
					'archiveMode' => ArchiveMode::ALWAYS,
					'mediaMode' => MediaMode::ROUTED
				);
				$session = $opentok->createSession($sessionOptions);
				$sessionId = $session->getSessionId();

				//generate token for session
				$token = $session->generateToken(array(
					'role'       => Role::MODERATOR,
					'expireTime' => time()+(7 * 24 * 60 * 60), // in one week
					//'data'       => 'name=Johnny'
				));
				
				//save session data to database.it will store a temporary video session data for owner and sitter session 
				$sessioncreatedat=date("Y-m-d H:i:s");
				$savesessiondata = $videoModel->saveTempSessiondata($sessionId,$token,$ownerid,$sitterid,$sessioncreatedat,$id);

				$temp_id = $savesessiondata;
				}else{
				$sessionId = '';
				$sessioncreatedat = '';
				$temp_id = '';
				}
			 }else{			 
			    $temp_id = $chekSession['temp_id'];
			    $sessionId = $chekSession['temp_session_id'];
			    $token = $chekSession['temp_token'];
			    $sessioncreatedat = $chekSession['temp_created_at'];
			    $currenttime = date("Y-m-d H:i:s");
			    
			    $updateUserAvailability = $videoModel->updateTempUserSessionstatus($temp_id,$ownerid,$sitterid);
			 }
    	    	
    	}else{
    	Yii::$app->session->setFlash('error', 'No video session scheduled for today.');
		return $this->redirect(['video/index']);    	    	
    	}    	
    	//check if a session is already created by any user
     
   //$this->layout = false;
   return  $this->render('talk',[
   'apiKey' => $apiKey,
   'sessionId' => $sessionId,
   'token' => $token,
   //'duration' => $con_duration,
   'duration' => $durationtime,
   'sessioncreatedat' =>$sessioncreatedat,
   'temp_id' => $temp_id, 
   'model' =>$videoModel,
   'vid_id' => $id, 
  
   ]);
  }
  
  
  public function videocalnotifyEmail($recieverid){
 	
	$adminEmail		= Yii::$app->commonmethod->getAdminEmailID();
    $senderfirstname  = Yii::$app->user->identity->firstname;
    $senderlastname  = Yii::$app->user->identity->lastname;
    $sendername = $senderfirstname;

    $recieverdata = User::findOne($recieverid);    
    $recievername = $recieverdata->firstname;
   	$recieveremail =  $recieverdata->email; 

    $subject  ="Join your video session";
		$message  ='';                          		
		$message .='<tr>
                            <td height="5" style="font-size:13px; color:#656565; line-height:5px; padding-bottom:10px;">Hi '.$recievername.',</td>
                          </tr>
                          <tr>
                            <td height="5"></td>
                          </tr>
                          <tr>
                            <td style="font-size:13px; color:#656565; line-height:19px; padding-bottom:10px;">'.$sendername.' requested you to join video session. Please click <a href="'.SITE_URL.'video" target="_blank">here</a> to join video session.</td>
                          </tr>';
		$mail = Yii::$app->mailer->compose(['html' => 'layouts/mail'],['content' => $message, 'subject' => $subject])
		->setFrom([$adminEmail=>"Palcura"])
		->setTo($recieveremail)
		->setSubject($subject)
		->send();
		return $mail;
	
	}
	
  public function actionGetcallresponse(){
  	$videoModel = new Video();
	  if(Yii::$app->request->post()){
	  $postData = Yii::$app->request->post();
	  $vidid = $postData['temp_id'];
	  $getsessionusers = $videoModel->getSessionUsers($vidid);
	  $ownerstatus = $getsessionusers['owner_call_status'];
      $sitterstatus =	$getsessionusers['sitter_call_status'];
      $callstatus = $getsessionusers['call_status'];
		 if($ownerstatus == 1 && $sitterstatus == 1){
		  return 'accepted';
		 }elseif($callstatus==1){	 
		 return 'decline';
		 }else{
		 return 'waiting';
		 }
	  
	  }    
  }	
  public function actionChektodayssession(){
	$videoModel = new Video();	 
	  $getsessionusers = $videoModel->chkfortodayssession();
	  return $getsessionusers;
	}
	
	public function actionChektodayactivessession(){
	$videoModel = new Video();	 
	  $getsessionusers = $videoModel->getnewactiveSessionUsers();
	 $data = 0;
	  if(!empty($getsessionusers)){
	  $data = $getsessionusers;	  
	  }
	  return $data;
	}
  
   public function actionChkcallrequest($userid){
  	$videoModel = new Video();
	  if(Yii::$app->request->post()){
	  $postData = Yii::$app->request->post();
	  $vidid = $postData['temp_id'];
	  $getsessionusers = $videoModel->getSessionUsers($vidid);
	  $ownerstatus = $getsessionusers['owner_call_status'];
      $sitterstatus =	$getsessionusers['sitter_call_status'];
      
		 if($ownerstatus == 1 && $sitterstatus == 1){
		  return 'accepted';
		 }else{	 
		 return 'waiting';
		 }
	  
	  }    
  }
  
  public function actionDeclinecall(){
  $videoModel = new Video();
   if(Yii::$app->request->post()){
	  $postData = Yii::$app->request->post();
	  $vidid = $postData['vid_id'];
	  $declinecall = $videoModel->declineusercall($vidid);
	 return  $declinecall;
	  
   }
  
  }
  
  public function actionComparesession(){
    $videoModel = new Video();
    
	  if(Yii::$app->request->post()){
	   $sessionCreatedTime = Yii::$app->request->post()['createdDatetime'];
	   $currentTime = date("Y-m-d H:i:s");
	   $tempid = Yii::$app->request->post()['tempid'];
	   $checkconnectionstatus = $videoModel->checkdisconectstatus($tempid);
	   $status = $checkconnectionstatus['temp_status'];
	   if($status==2){
	   return true;
	   }else{
	   return false;
	   }

	  }    
  }
  
   public function actionDestroysession(){
  
  	 if(Yii::$app->request->post()){
	   $sessionCreatedTime = Yii::$app->request->post()['createdDatetime'];
	   $currentTime = date("Y-m-d H:i:s");
	   $duration = Yii::$app->request->post()['duration'];
	   //disconnect after session duration is over
	   //$duration = 3;//in minutes
	   $popuptime = $duration;
	   $minutes = '+'.$popuptime.' minutes';
	   //$alertPopupAt = date("Y-m-d H:i:s", strtotime($sessionCreatedTime."+'".$popuptime."' minutes"));
	   $alertPopupAt= date('Y-m-d H:i:s',strtotime($minutes,strtotime($sessionCreatedTime)));
	   if($currentTime >= $alertPopupAt){
	   return true;
	   }else{
	   return false;   
	   }
	  }
  }
  
  //delete session temporary data after session disconnection
  public function actionDisconnect(){
  $videoModel = new Video();
  
  if(Yii::$app->request->post()){
		$temp_id = Yii::$app->request->post()['temp_id'];
		$vid_id = Yii::$app->request->post()['vid_id'];											
		$deletesession = $videoModel->updateTempSession($temp_id,$vid_id);	
									
		return true;
		}  
  }
  
  public function actionAcceptreq($id){
  $videoModel = new Video();
  $videodata = $videoModel->getvideodatails($id);
	$userid = Yii::$app->user->identity->id;
	if(!in_array($userid,[$videodata['pet_owner_id'],$videodata['pet_sitter_id'],$videodata['pet_renter_id']])){	
	return $this->redirect(['video/index']);
	}
	 
  if(Yii::$app->request->post()){
  	$postData = Yii::$app->request->post();
		$vid_id = $postData['vid_id'];		
		$ownerid = 	$postData['owner_id'];
		$sitter_id = $postData['sitter_id'];
		$schedule_datetime = date('m/d/y',strtotime($postData['schedule_datetime']));
		$start_time = $videodata['start_time'];							
		//$deletesession = $videoModel->updateTempSession($temp_id,$vid_id);
		$confirmreq = $videoModel->confirmvideoreq($vid_id);										
		if($confirmreq){
		$servicenames = '';
		if(!empty($videodata['services'])){
		$servicesarray = explode(',',$videodata['services']);		
		$servicesName = Yii::$app->commonmethod->getServicesName($servicesarray);
		$servicenames = implode(',',$servicesName);
		}
		$this->videoreqconfirmEmail($ownerid,$schedule_datetime,$start_time,$servicenames);
		Yii::$app->session->setFlash('message', 'Thanks for confirming video session request.');
		return $this->redirect(['video/index']);    
		}
	} 
  return $this->render('confirm',[
  'videodata' => $videodata
  ]);
  
  }
  
  public function videoreqconfirmEmail($recieverid,$scheduleddate,$start_time,$servicenames){
 	
	$adminEmail		= Yii::$app->commonmethod->getAdminEmailID();
    $senderfirstname  = Yii::$app->user->identity->firstname;
    $senderlastname  = Yii::$app->user->identity->lastname;
    $sendername = $senderfirstname;

    $recieverdata = User::findOne($recieverid);    
    $recievername = $recieverdata->firstname;
   	$recieveremail =  $recieverdata->email; 

    $subject  ="Video call has been scheduled and confirmed";
		$message  ='';                          		
		$message .='<tr>
                            <td height="5" style="font-size:13px; color:#656565; line-height:5px; padding-bottom:10px;">Hi '.$recievername.',</td>
                          </tr>
                          <tr>
                            <td style="font-size:13px; color:#656565; line-height:18px; padding-bottom:10px;">You have an upcoming Video call scheduled and confirmed. Here’s the details:</td>
                          </tr>
                          <tr>
                            <td height="5"></td>
                          </tr>
                          <tr>
                            <td align="left">
								<ul style="background-color: #fff3e3; color:#000000; font-size:15px; list-style:none; margin:0; padding:0; font-weight:600; padding: 10px 20px; letter-spacing: 0.3px;">
									
									<li>Video call with: <span style="font-weight:400; line-height:30px;">'.$sendername.'</span></li>
									<li>Date(s): <span style="font-style:italic; font-weight:400; line-height:30px;">'.$scheduleddate.'</span></li>																		
									<li>Time: <span style="font-weight:400; line-height:30px;">'.$start_time.'</span></li>
									<li>Service Type: <span style="font-weight:400; line-height:30px;">'.$servicenames.'</span></li>
								</ul>
							</td>
                          </tr>
                          <tr>
                            <td height="15"></td>
                          </tr>
                          <tr style="color:#656565; font-size:13px; line-height:19px;" >
                            <td>Remember to sign in at the time and date of your video call. The call will be capped to 10 minutes for security reasons. You can always set up a meet and greet later. Once you are satisfied, reserve/accept the service request.</td>
                          </tr>';
		$mail = Yii::$app->mailer->compose(['html' => 'layouts/mail'],['content' => $message, 'subject' => $subject])
		->setFrom([$adminEmail=>"Palcura"])
		->setTo($recieveremail)
		->setSubject($subject)
		->send();
		return $mail;
	
	}
	
	public function actionRejectcall(){
	
		$videoModel = new Video();
		$post = Yii::$app->request->post();
		if(!empty($post)){
		$temp_id = $post['id'];
		$deletevid = $videoModel->declinevideoreq($temp_id);
		
		if($deletevid){
		$recieverid = $post['owner_id'];
		$scheduleddate = date('m/d/y',strtotime($post['date']));
		$start_time = $post['time'];
		$services = $post['sid'];
		Yii::$app->session->setFlash('message', 'Video session request has been declined.');
		$this->videoreqdeclinedEmail($recieverid,$scheduleddate,$start_time,$services);
		return true;
		}else{
		return false;
		}
	  }
		
	}
	
	
	
	 public function videoreqdeclinedEmail($recieverid,$scheduleddate,$start_time,$services){
 	
	$adminEmail		= Yii::$app->commonmethod->getAdminEmailID();
    $senderfirstname  = Yii::$app->user->identity->firstname;
    $senderlastname  = Yii::$app->user->identity->lastname;
    $sendername = $senderfirstname;

    $recieverdata = User::findOne($recieverid);    
    $recievername = $recieverdata->firstname;
   	$recieveremail =  $recieverdata->email; 

    $subject  ="Video call request has been declined";
		$message  ='';
                         	
		$message .=' <tr>
                            <td height="5" style="font-size:13px; color:#656565; line-height:5px; padding-bottom:10px;">Hi '.$recievername.',</td>
                          </tr>
                          <tr>
                            <td style="font-size:13px; color:#656565; line-height:18px; padding-bottom:10px;">Your request for video call has been declined by '.$senderfirstname.'. Here are the details:</td>
                          </tr>
                          <tr>
                            <td height="5"></td>
                          </tr>
                          <tr>
                            <td align="left">
								<ul style="background-color: #fff3e3; color:#000000; font-size:15px; list-style:none; margin:0; padding:0; font-weight:600; padding: 10px 20px; letter-spacing: 0.3px;">								
									<li>Declined by: <span style="font-weight:400; line-height:30px;">'.$senderfirstname.'</span></li>
									<li>Date: <span style="font-style:italic; font-weight:400; line-height:30px;">'.$scheduleddate.'</span></li>
									<li>Time: <span style="font-weight:400; line-height:30px;">'.$start_time.'</span></li>
									<li>Service Type: <span style="font-weight:400; line-height:30px;">'.$services.'</span></li>																		
								</ul>
							</td>
                          </tr>
                          <tr>
                            <td height="15"></td>
                          </tr>';
		$mail = Yii::$app->mailer->compose(['html' => 'layouts/mail'],['content' => $message, 'subject' => $subject])
		->setFrom([$adminEmail=>"Palcura"])
		->setTo($recieveremail)
		->setSubject($subject)
		->send();
		return $mail;
	
	}
	
	public function actionUpdatevidsession($id){
	 $videoModel = new Video();
  	 $videodata = $videoModel->getvideodatails($id);
  	 $userid = Yii::$app->user->identity->id;
	if(!in_array($userid,[$videodata['pet_owner_id'],$videodata['pet_sitter_id'],$videodata['pet_renter_id']])){	
	return $this->redirect(['video/index']);
	}
	$model = new \frontend\models\common\Videoconversation;	
	
	if(Yii::$app->request->post()) {
	
	$postdata = Yii::$app->request->post();
	$date = date('Y-m-d',strtotime($postdata['Videoconversation']['schedule_datetime']));
	$time = $postdata['Videoconversation']['start_time'];
	
	$updatevideosession = $videoModel->updatesessiondate($date,$time,$id);
	if($updatevideosession){
	
	$sitter = $postdata['sitter_id'];
	$owner  = $postdata['owner_id'];
	$services = $postdata['services'];
	$date = date('m/d/Y',strtotime($date));

	
		$this->videocallupdateEmail($sitter,$owner,$date,$time,$services);
		$this->videocallupdateEmail($owner,$sitter,$date,$time,$services);
		Yii::$app->session->setFlash('message', 'Video call date and time have been updated.');
		return $this->redirect(['video/index']); 	
	}
	}
	return $this->render('updatevideosession',[	
	'videodata' => $videodata,
	'model' => $model,
	]);
	
	}
	
	 public function videocallupdateEmail($recieverid,$otheruser,$scheduleddate,$start_time,$services){
 	
 	$userid = Yii::$app->user->identity->id;
 	
	$adminEmail		= Yii::$app->commonmethod->getAdminEmailID();
		
    $senderfirstname  = Yii::$app->user->identity->firstname;
    $senderlastname  = Yii::$app->user->identity->lastname;
    $sendername = $senderfirstname;

    $recieverdata = User::findOne($recieverid);    
    $recievername = $recieverdata->firstname;
   	$recieveremail =  $recieverdata->email; 
   	
   	$otheruserdata = User::findOne($otheruser);
   	$callsecheduledwith  = $otheruserdata->firstname;  
   	

    $subject  ="Video call date and time have been updated";
		$message  ='';
                          
          $message .= '<tr>
                            <td height="5" style="font-size:13px; color:#656565; line-height:5px; padding-bottom:10px;">Hi '.$recievername.',</td>
                          </tr>
                          <tr>
                            <td style="font-size:13px; color:#656565; line-height:18px; padding-bottom:10px;">Your Video call scheduled date and time have been updated. Here’s the details:</td>
                          </tr>
                          <tr>
                            <td height="5"></td>
                          </tr>
                          <tr>
                            <td align="left">
								<ul style="background-color: #fff3e3; color:#000000; font-size:15px; list-style:none; margin:0; padding:0; font-weight:600; padding: 10px 20px; letter-spacing: 0.3px;">
									
									<li>Video call with: <span style="font-weight:400; line-height:30px;">'.$callsecheduledwith.'</span></li>
									<li>Updated By: <span style="font-weight:400; line-height:30px;">'.$sendername.'</span></li>
									<li>Date: <span style="font-style:italic; font-weight:400; line-height:30px;">'.$scheduleddate.'</span></li>									
									<li>Time: <span style="font-weight:400; line-height:30px;">'.$start_time.'</span></li>
									<li>Service Type: <span style="font-weight:400; line-height:30px;">'.$services.'</span></li>
								</ul>
							</td>
                          </tr>
                          <tr>
                            <td height="15"></td>
                          </tr>
                          <tr style="color:#656565; font-size:13px; line-height:19px;" >
                            <td>Remember to sign in at the time and date of your video call. The call will be capped to 10 minutes for security reasons. You can always set up a meet and greet later. Once you are satisfied, reserve/accept the service request.</td>
                          </tr>';                		
		
		$mail = Yii::$app->mailer->compose(['html' => 'layouts/mail'],['content' => $message, 'subject' => $subject])
		->setFrom([$adminEmail=>"Palcura"])
		->setTo($recieveremail)
		->setSubject($subject)
		->send();
		return $mail;
	
	}
  
  
    
}
