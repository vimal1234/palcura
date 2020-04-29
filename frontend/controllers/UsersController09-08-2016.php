<?php
namespace frontend\controllers;

use Yii;
use frontend\models\users\UpdateOwner;
use frontend\models\users\UpdateSitter;
use frontend\models\users\UpdateRenter;
use frontend\models\users\AddUserForm;
use frontend\models\users\Users;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use backend\models\sitters\Documents;
use frontend\models\common\BookingImages;
use frontend\models\common\UserServices;
use frontend\models\Activity;
use frontend\models\Vaccinationdetails;
use yii\data\Pagination;
use yii\db\Query;
##############= FOR FILE UPLOAD =################
use yii\web\UploadedFile;
use backend\models\sitters\UserProfilePictureUpload;
use frontend\models\Uploads;
use common\models\User;
/**
* Users controller
*/
class UsersController extends Controller {
	private $limit = 10;
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index','update','myprofile','settings','dashboard','becomeasitter','becomeanowner','view-user-details','activitylog','updateactivity','newdashboardlist','addavailability','switch-account','newimagelist','removeservice','removepet','testview','bookingreminders','becomeaborrower','updatesubscription','bookingprice'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                ],
            ],
        ];
    }

	/**
	* @inheritdoc
	*/
    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

	/**
	* list users
	* @return mixed
	*/ 
    public function actionIndex() {
		return $this->redirect(['dashboard']);
    }

	/**
	* Displays profile of a user.
	*
	* @return mixed
	*/
	public function actionDashboard() {
	
	$getattraibutes = Yii::$app->request->get();
	if(!empty($getattraibutes)){
	return $this->redirect(['users/dashboard']);
	}
		$session 		= Yii::$app->session;
		$logged_user 	= $session->get('loggedinusertype');
		
		$user_id		= Yii::$app->user->getId();
		$activityModel 	= new Activity();
		$activityTypes 	= $activityModel->getActivitytypes();
		
		$myBookings 	= array();
		$tmp 			= array();	
		$activityids 	= array();	
		$activBookingId = '';
		$bookingImages = array();
		$myBookings 	= $activityModel->getUserBookingIds($user_id); 
		if(!empty($myBookings) && count($myBookings)>0){	
			$activBookingId = $myBookings[0]['id'];
			$getActivityData = $activityModel->getActivityData($activBookingId);
			$bookingImages = Yii::$app->commonmethod->getBookingImages($activBookingId);
	
			foreach($getActivityData as $arg)
			{ 
				$tmp[$arg['activity_date']][] = $arg;
				$activityids[$arg['activity_date']][] = $arg['activity_id'];
				//$tmp[$arg['activity_date']][] = $arg;				
			}	
	 		$activityDataArray = $tmp;
 		}else{
 			$activityDataArray = array();
 		}
 	
 		krsort($activityids);	
 		krsort($activityDataArray);
 		if(!empty($activityids) && !empty($activityDataArray)){
 		$latestactivitylistids = array_unique(reset($activityids));	
 		$latestactivitylist = reset($activityDataArray);
 		
		$activitydate = key($activityDataArray);
		//activitylist to be added to latestactivitylist 		
		$a = array();
			foreach($activityTypes as $k=>$v){
			 $activityid = $v['activity_id'];
			  if(!in_array($activityid,$latestactivitylistids)){
			  $a[]=$v;
			  }
			}	
			
			$dates = array();
			foreach ($latestactivitylist as $key => $row) {	
			$dates[$key]  = $row['activity_created_at'];
			}
		array_multisort($dates, SORT_DESC, $latestactivitylist);					
		$activityTypelisting = array_values(array_merge($latestactivitylist,$a));		   
        }else{
        $activityTypelisting = $activityTypes;
        $activitydate = date('Y-m-d');
        }
        if(empty($myBookings)){
        $activityTypelisting = array();
        $activitydate = date('Y-m-d');
        }
       
		//***get activity data end****/		
		if($logged_user == OWNER) {
			return $this->render('dashboard-owner',['myBookings'=>$myBookings,'activityDataArray' => $activityDataArray,'activityTypelisting'=>$activityTypelisting,'activitydate' => $activitydate,'bookingImages' =>$bookingImages]);
		} else if($logged_user == SITTER || $logged_user == OWNER_SITTER) { 
			$modelImageUpload 				= new Uploads();
			$modelImageUpload->scenario 	= 'upbooking';
			$userpost 						= Yii::$app->request->post();
			//$bookingID = 1;
			if(isset($userpost) && !empty($userpost)) {			
			
				//$booking = $this->getCurrentBooking($user_id);
				if(isset($userpost['Uploads']['bookingid']) && !empty($userpost['Uploads']['bookingid'])) {
					$bookingID	= $userpost['Uploads']['bookingid'];
					$mediaArr 			= array();
					
					if (Yii::$app->request->isPost) {
						$modelImageUpload->upload_booking_images = UploadedFile::getInstances($modelImageUpload, 'upload_booking_images');
						if ($modelImageUpload->upload_booking_images && $uploadedFileNameArray = $modelImageUpload->uploadBookings() ) {
							$mediaArr['upload_booking_images'] = $uploadedFileNameArray['originalImage'];
						}
					}
					
					$model 	= new BookingImages();
					if ($model->savedata($mediaArr,$bookingID,$user_id)) {
					
						$selectcols = 'name,pet_sitter_id,pet_owner_id,pet_renter_id';
						$bookigdata = $activityModel->getBookingprice($selectcols,$bookingID);
						$this->sendpicUploademail($bookigdata,$bookingID);	
								
						Yii::$app->session->setFlash('item', 'The pictures/videos have been uploaded successfully!');
						return $this->redirect(['dashboard']);
					} else {
						Yii::$app->session->setFlash('item', 'The pictures/videos have not been uploaded successfully!');
						return $this->redirect(['dashboard']);					
					}
				}
			}
			
			return $this->render('dashboard-sitter',['modelImageUpload' =>$modelImageUpload,'myBookings'=>$myBookings,'activityDataArray'=>$activityDataArray,'activityTypelisting'=>$activityTypelisting,'activitydate' => $activitydate,'bookingImages' => $bookingImages]);
		} else if($logged_user == RENTER) {
		$modelImageUpload 				= new Uploads();
			$modelImageUpload->scenario 	= 'upbooking';
			$userpost 						= Yii::$app->request->post();
			//$bookingID = 1;
			if(isset($userpost) && !empty($userpost)) {			
			
				//$booking = $this->getCurrentBooking($user_id);
				if(isset($userpost['Uploads']['bookingid']) && !empty($userpost['Uploads']['bookingid'])) {
					$bookingID	= $userpost['Uploads']['bookingid'];
					$mediaArr 			= array();
					
					if (Yii::$app->request->isPost) {
						$modelImageUpload->upload_booking_images = UploadedFile::getInstances($modelImageUpload, 'upload_booking_images');
						if ($modelImageUpload->upload_booking_images && $uploadedFileNameArray = $modelImageUpload->uploadBookings() ) {
							$mediaArr['upload_booking_images'] = $uploadedFileNameArray['originalImage'];
						}
					}
					
					$model 	= new BookingImages();
					if ($model->savedata($mediaArr,$bookingID,$user_id)) {
					
						$selectcols = 'name,pet_sitter_id,pet_owner_id,pet_renter_id';
						$bookigdata = $activityModel->getBookingprice($selectcols,$bookingID);
						$this->sendpicUploademail($bookigdata,$bookingID);
							
						Yii::$app->session->setFlash('item', 'The images have been uploaded successfully!');
						return $this->redirect(['dashboard']);
					} else {
						Yii::$app->session->setFlash('item', 'The images have not been uploaded successfully!');
						return $this->redirect(['dashboard']);					
					}
				}
			}
			return $this->render('dashboard-renter',['modelImageUpload' =>$modelImageUpload,'myBookings'=>$myBookings,'activityDataArray'=>$activityDataArray,'activityTypelisting'=>$activityTypelisting,'activitydate' => $activitydate,'bookingImages' => $bookingImages]);
		} else {
			return $this->render('dashboard-owner',['myBookings'=>$myBookings,'activityDataArray'=>$activityDataArray,'activityTypelisting'=>$activityTypelisting,'activitydate' => $activitydate,'bookingImages' => $bookingImages]);
		}
	}
	
	public function sendpicUploademail($bookigdata,$activBookingId){
		$adminEmail		= Yii::$app->commonmethod->getAdminEmailID();
		
		$petinfo = Vaccinationdetails::find()->select('pet_name')->where(['user_id'=>$bookigdata['pet_owner_id']])->asArray()->One();
		if(!empty($petinfo)){
		$petname = $petinfo['pet_name'];
		}else{
		$petname = 'Pet';
		}
       
		$ownerinfo = User::find()->select('firstname,lastname,email')->where(['id'=>$bookigdata['pet_owner_id']])->One();
		if($bookigdata['pet_renter_id']>0){
		$sitterinfo = User::find()->select('firstname,lastname,email')->where(['id'=>$bookigdata['pet_renter_id']])->One();
		}else{
		$sitterinfo = User::find()->select('firstname,lastname,email')->where(['id'=>$bookigdata['pet_sitter_id']])->One();
		}

		$ownerName 	  	= (isset($ownerinfo->firstname) ? $ownerinfo->firstname : '');
		$ownerEmail 	  = (isset($ownerinfo->email) ? $ownerinfo->email : '');			
		$sitterName 	  = (isset($sitterinfo->firstname) ? $sitterinfo->firstname : '');
		$subject  ="You have a new photo/video of your Pal";
		$message  ='';
                         
           $message .= '<tr>
                            <td height="5" style="font-size:13px; color:#656565; line-height:5px; padding-bottom:10px;">Hi '.$ownerName.',</td>
                          </tr>
                          <tr>
                            <td style="font-size:13px; color:#656565; line-height:18px; padding-bottom:10px;">'.$sitterName.' has uploaded new photo/video for your pal. Access your <a href="'.SITE_URL.'users/dashboard" target="_blank">dashboard</a>to see the new updates.</td>
                          </tr>';               

		$mail = Yii::$app->mailer->compose(['html' => 'layouts/mail'],['content' => $message, 'subject' => $subject])
		->setFrom([$adminEmail => 'Palcura'])
		->setTo($ownerEmail)
		->setSubject($subject)
		->send();
		
		return $mail;
	
	}
	
	public function actionActivitylog() {
	$user_id = Yii::$app->user->getId();
	$activityModel = new Activity();
	$activityTypes = $activityModel->getActivitytypes();
	$activitydate = date('Y-m-d');
	$this->layout = false;
	$post = Yii::$app->request->post();
        if(!empty($post)) {
			$searchFilter = $post['filter'];
		
			 if(isset($searchFilter['bookingid']) && !empty($searchFilter['bookingid'])) {
				$activBookingId = $searchFilter['bookingid'];
				$getActivityData = $activityModel->getActivityData($activBookingId);

				$tmp = array();
		
				foreach($getActivityData as $arg)
				{ 
					$tmp[$arg['activity_date']][] = $arg;
					$activityids[$arg['activity_date']][] = $arg['activity_id'];				
				}
	
		 		$activityDataArray = $tmp;
		 		if(!empty($activityDataArray) && !empty($activityids)){
		 		krsort($activityDataArray);
		 		krsort($activityids);
		 		
					$latestactivitylistids = array_unique(reset($activityids));	
			 		$latestactivitylist = reset($activityDataArray);
			 		$activitydate = key($activityDataArray);
					//activitylist to be added to latestactivitylist 		
					$a = array();
					foreach($activityTypes as $k=>$v){
					 $activityid = $v['activity_id'];
					  if(!in_array($activityid,$latestactivitylistids)){
					  $a[]=$v;
					  }
					}		
					$dates = array();
					foreach ($latestactivitylist as $key => $row) {	
					$dates[$key]  = $row['activity_created_at'];
					}
					array_multisort($dates, SORT_DESC, $latestactivitylist);				
					$activityTypelisting = array_values(array_merge($latestactivitylist,$a));
				}else{
					$activityTypelisting = $activityTypes;
				}		
 	
		 		return $this->render('activitylog',[
				'activityDataArray' => $activityDataArray,
				'activityTypelisting'=>$activityTypelisting,
				'activitydate' => $activitydate
				]);
			 }else{
			 
				$myBookings = array();
				$myBookings = $activityModel->getUserBookingIds($user_id);
				if(!empty($myBookings) && count($myBookings)>0){	
			
					$activBookingId = $myBookings[0]['id'];
					$getActivityData = $activityModel->getActivityData($activBookingId);

					$tmp = array();
		
					foreach($getActivityData as $arg)
					{ 
						$tmp[$arg['activity_date']][] = $arg;	
						$activityids[$arg['activity_date']][] = $arg['activity_id'];			
					}
	
			 		$activityDataArray = $tmp;
		 		}else{
		 			$activityDataArray = array();
		 		}
		 		if(!empty($activityDataArray) && !empty($activityids)){
		 		krsort($activityDataArray);
		 		krsort($activityids);
		 		
				$latestactivitylistids = array_unique(reset($activityids));	
		 		$latestactivitylist = reset($activityDataArray);
		 		$activitydate = key($activityDataArray);
				//activitylist to be added to latestactivitylist 		
				$a = array();
				foreach($activityTypes as $k=>$v){
				 $activityid = $v['activity_id'];
				  if(!in_array($activityid,$latestactivitylistids)){
				  $a[]=$v;
				  }
				}
				$dates = array();
				foreach ($latestactivitylist as $key => $row) {	
				$dates[$key]  = $row['activity_created_at'];
				}
				array_multisort($dates, SORT_DESC, $latestactivitylist);				
				$activityTypelisting = array_values(array_merge($latestactivitylist,$a));
				}else{
					$activityTypelisting = $activityTypes;
				}					
 				return $this->render('activitylog',[
				'activityDataArray' => $activityDataArray,
				'activityTypelisting'=>$activityTypelisting,
				'activitydate' => $activitydate
				]);
			
			}
		}		
	}
	
	public function actionUpdateactivity(){
	
	$usertype = Yii::$app->user->identity->user_type;
	//check for specific user type to update activity	
	//add an booking activity to list
	$activityModel = new Activity(); 
	$activitydate = date('Y-m-d');
	$this->layout = false;
	$post = Yii::$app->request->post();
	if(!empty($post)){
	
	$time = date('h:i A');
	$date = date('Y-m-d');
	$created_at = date('Y-m-d h:i:s');
	$data = array('activity_id' => $post['activityid'],'activity_booking_id'=>$post['bookingid'],'activity_start'=>$time,'activity_date'=>$date,'activity_created_at'=>$created_at);
	//insert  activity data
		if($activityModel->addNewActivity($data)){
		$activityTypes = $activityModel->getActivitytypes();
		$activBookingId = $post['bookingid'];
			$getActivityData = $activityModel->getActivityData($activBookingId);
			$selectcols = 'name,pet_sitter_id,pet_owner_id,pet_renter_id';
			$bookigdata = $activityModel->getBookingprice($selectcols,$activBookingId);
			
			$tmp = array();	
			$activityids = array();	
			foreach($getActivityData as $arg)
			{ 
				$tmp[$arg['activity_date']][] = $arg;
				$activityids[$arg['activity_date']][] = $arg['activity_id'];
						
			}
			krsort($activityids);	
 		    
	 		$activityDataArray = $tmp;
	 		krsort($activityDataArray);
	 		$latestactivitylistids = array_unique(reset($activityids));	
	 		
	 		$latestactivitylist = reset($activityDataArray);
	 		$activitydate = key($activityDataArray);
			//activitylist to be added to latestactivitylist 
			$a = array();
			foreach($activityTypes as $k=>$v){
			 $activityid = $v['activity_id'];
			  if(!in_array($activityid,$latestactivitylistids)){
			  $a[]=$v;
			  }
			}
		
			$dates = array();
			foreach ($latestactivitylist as $key => $row) {	
			$dates[$key]  = $row['activity_created_at'];
			}
			array_multisort($dates, SORT_DESC, $latestactivitylist);
		
		    $activityTypelisting = array_values(array_merge($latestactivitylist,$a));
		    //send activity update email to your owner.
		    $this->sendactivityupdateemail($bookigdata,$activBookingId);
		 	return $this->render('dashboardlist',['activityTypelisting'=>$activityTypelisting,'activitydate'=>$activitydate]);	
			
		}else{
		return false;
		}
	
	}else{
	return false;
	}
		
	}
	
	public function sendactivityupdateemail($bookigdata,$activBookingId){
		$adminEmail		= Yii::$app->commonmethod->getAdminEmailID();
		
		$petinfo = Vaccinationdetails::find()->select('pet_name')->where(['user_id'=>$bookigdata['pet_owner_id']])->asArray()->One();
		if(!empty($petinfo)){
		$petname = $petinfo['pet_name'];
		}else{
		$petname = 'Pet';
		}
       
		$ownerinfo = User::find()->select('firstname,lastname,email')->where(['id'=>$bookigdata['pet_owner_id']])->One();
		if($bookigdata['pet_renter_id']>0){
		$sitterinfo = User::find()->select('firstname,lastname,email')->where(['id'=>$bookigdata['pet_renter_id']])->One();
		}else{
		$sitterinfo = User::find()->select('firstname,lastname,email')->where(['id'=>$bookigdata['pet_sitter_id']])->One();
		}

		$ownerName 	  	= (isset($ownerinfo->firstname) ? $ownerinfo->firstname : '');
		$ownerEmail 	  = (isset($ownerinfo->email) ? $ownerinfo->email : '');			
		$sitterName 	  = (isset($sitterinfo->firstname) ? $sitterinfo->firstname : '');
		$subject  =$sitterName." has updated your pal’s activity";
		$message  ='';
	
		$message .= '<tr>
                            <td height="5" style="font-size:13px; color:#656565; line-height:5px; padding-bottom:10px;">Hi '.$ownerName.',</td>
                          </tr>
                          <tr>
                            <td style="font-size:13px; color:#656565; line-height:18px; padding-bottom:10px;">You have a new activity update for your pal. Access your <a href="'.SITE_URL.'users/dashboard" target="_blank">dashboard</a> to see your pal’s activity.</td>
                          </tr>';
		
		$mail = Yii::$app->mailer->compose(['html' => 'layouts/mail'],['content' => $message, 'subject' => $subject])
		->setFrom([$adminEmail => 'Palcura'])
		->setTo($ownerEmail)
		->setSubject($subject)
		->send();
		
		return $mail;
	
	}
	
	public function actionAddavailability(){
	$activityModel = new Activity();
	if(Yii::$app->request->post()){
	$post = Yii::$app->request->post();
	$availabilitydataes = $post['dates'];
	$adddates = $activityModel->addUnavailabilityDates($availabilitydataes);
	if($adddates){

	return true;
	}else{
	return false;
	}
	}	
	}
	
	public function actionNewdashboardlist(){ 
	$session 		= Yii::$app->session;
	$logged_user 	= $session->get('loggedinusertype');
	$activityModel = new Activity();
	$activityTypes = $activityModel->getActivitytypes();
	$activitydate = date('Y-m-d'); 
	$this->layout = false;
	
	$post = Yii::$app->request->post();
	$searchFilter = $post['filter'];		
		 if(isset($searchFilter['bookingid']) && !empty($searchFilter['bookingid'])) {
		 
		 $activBookingId = $searchFilter['bookingid'];
	
				$getActivityData = $activityModel->getActivityData($activBookingId);

				$tmp = array();
		
				foreach($getActivityData as $arg)
				{ 
					$tmp[$arg['activity_date']][] = $arg;
					$activityids[$arg['activity_date']][] = $arg['activity_id'];				
				}
	
		 		$activityDataArray = $tmp;
		 		if(!empty($activityDataArray) && !empty($activityids)){
		 		krsort($activityDataArray);
		 		krsort($activityids);
		 		
					$latestactivitylistids = array_unique(reset($activityids));	
			 		$latestactivitylist = reset($activityDataArray);
			 		$activitydate = key($activityDataArray);
					//activitylist to be added to latestactivitylist 		
					$a = array();
						foreach($activityTypes as $k=>$v){
						  $activityid = $v['activity_id'];
						  if(!in_array($activityid,$latestactivitylistids)){
						  $a[]=$v;
						  }
						}		
						$dates = array();
						foreach ($latestactivitylist as $key => $row) {	
						$dates[$key]  = $row['activity_created_at'];
						}
						array_multisort($dates, SORT_DESC, $latestactivitylist);
					
						$activityTypelisting = array_values(array_merge($latestactivitylist,$a));	
				}else{
					$activityTypelisting = $activityTypes;
				}
				
		 		return $this->render('dashboardlist',[
				'activityTypelisting'=>$activityTypelisting,
				'activitydate' => $activitydate,
				
				]);
		 
		 }	
	}
	
	public function actionBookingprice(){
	$bookingprice = '--';
	$post = Yii::$app->request->post();
	$activityModel = new Activity();
	$searchFilter = $post['filter'];
	 if(isset($searchFilter['bookingid']) && !empty($searchFilter['bookingid'])) {
		 
		 $activBookingId = $searchFilter['bookingid'];
		 		$selectcols='amount';
				$bookingamount = $activityModel->getBookingprice($selectcols,$activBookingId);
				$bookingprice  = $bookingamount['amount'];
	 }
	
	 return '$ '.$bookingprice;
	} 
	
	public function actionNewimagelist(){
	$session 		= Yii::$app->session;
	$logged_user 	= $session->get('loggedinusertype');
	
	$this->layout = false;
	$post = Yii::$app->request->post();
	$searchFilter = $post['filter'];		
		 if(isset($searchFilter['bookingid']) && !empty($searchFilter['bookingid'])) {
		 
		 $activBookingId = $searchFilter['bookingid'];
		 $bookingImages = Yii::$app->commonmethod->getBookingImages($activBookingId);		 										
		 		return $this->render('bookingimageslider',[
				'bookingImages' => $bookingImages,				
				]);		 
		 }	
	}
	    
	/**
	* Displays profile of a user.
	*
	* @return mixed
	*/
    public function actionMyprofile() {
    	$userid 			= Yii::$app->user->identity->id;
    	$userServices		= Yii::$app->commonmethod->getUserServices($userid);
        return $this->render('myprofile',[
        'user_services' 		=> $userServices,
        ]);
    }

	/**
	* Update an existing Users model.
	* If update is successful, the browser will be redirected to the 'setting' page.
	* @param integer $id
	* @return mixed
	*/
    public function actionSettings() {
		
		// Note: Each profile will update in same function
		$session 		= Yii::$app->session;
		$logged_user 	= $session->get('loggedinusertype');
        $id 			= Yii::$app->user->getId();
        $userModel 		= $this->findModel($id);

		if($logged_user == OWNER) {
			$modelImageUpload  = new UserProfilePictureUpload();
			$modelImageUploadF = new Uploads();
			$modelImageUploadK = new Uploads();
			
			$uploadmodel = new Uploads();
			$vaccinationModel  = new Vaccinationdetails();
			$modelImageUploadF->scenario = 'upf';
			$modelImageUpload->scenario = 'signup';
			$modelImageUploadK->scenario = 'upk';
			$updateUser 			= new UpdateOwner();
			$userpost 				= Yii::$app->request->post('UpdateOwner');
			$setting_page			= "owner-profile-settings";
			$model 					= $updateUser->findIdentity($id);
			$model->scenario 		= 'update';
			$vaccinationdetails 	= $this->findvaccinationModel($id);	
				
			/*$petinformation	=	Yii::$app->commonmethod->getUserPetsInfo($id);
			if(empty($petinformation)){
			$modelImageUploadK->scenario = 'uppetpic';
			}*/
									
			//if (isset($userpost) && !empty($userpost)) {
if (isset($userpost) && !empty($userpost) && $modelImageUploadK->load(Yii::$app->request->post()) && $modelImageUploadK->validate()) { 
					if(empty($vaccinationdetails)){
					$vaccinationdoc = '';	
						if (Yii::$app->request->isPost) {
							$modelImageUploadF->vaccination_doc = UploadedFile::getInstance($modelImageUploadF, 'vaccination_doc');
							if ($modelImageUploadF->vaccination_doc && $uploadedFileNameArray = $modelImageUploadF->uploadF()) {
								$vaccinationdoc = $uploadedFileNameArray['docname'];
							}
						}							
					$vaccinationModel->saveVacc(Yii::$app->request->post('Vaccinationdetails'),$vaccinationdoc);			
					}else{	
					$vaccinationdoc = '';
					if (Yii::$app->request->isPost) {
							$modelImageUploadF->vaccination_doc = UploadedFile::getInstance($modelImageUploadF, 'vaccination_doc');
					
							if ($modelImageUploadF->vaccination_doc && $uploadedFileNameArray = $modelImageUploadF->uploadF()) {
						
								$vaccinationdoc = $uploadedFileNameArray['docname'];
							}
					}	
					
					$vaccid = 	$vaccinationdetails['id'];
						if($vaccinationdoc==''){
						$docname = $vaccinationdetails['vaccination_doc'];
						}else{
						$docname = $vaccinationdoc;
						}						
					$vaccinationModel->updateVacc(Yii::$app->request->post('Vaccinationdetails'),$vaccid,$docname);
					}
		
				$model->profile_image = '';
				$model->picture_of_pet = array();
				if (Yii::$app->request->isPost) {
					$modelImageUpload->profile_image = UploadedFile::getInstance($modelImageUpload, 'profile_image');
					if ($modelImageUpload->profile_image && $uploadedFileNameArray = $modelImageUpload->upload()) {
						$model->profile_image = $uploadedFileNameArray['originalImage'];
					}						
					//$petpictures = $_FILES['Uploads']['name']['picture_of_pet'];
					//echo "<pre>"; print_r($_FILES); die;
					$modelImageUploadK->picture_of_pet = UploadedFile::getInstances($modelImageUploadK, 'picture_of_pet');
					//echo "<pre>"; print_r($modelImageUploadK->picture_of_pet); die;		
					if ($modelImageUploadK->picture_of_pet && $uploadedFileNameArray = $modelImageUploadK->uploadK() ) {
						$model->picture_of_pet = $uploadedFileNameArray['originalImage'];
					}					
				}

				$model->profile_image = $model->profile_image;
				$model->interested_in_renting = $userpost['renting_pet'];
				$model->pet_parent_type 	  = $userpost['pet_parent_type'];
				$model->pet_type			  = $userpost['pet_type'];
				$model->pet_name			  = $userpost['pet_name'];
				$model->care_note			  = $userpost['care_note'];
				$model->pet_id			  	  = $userpost['pet_id'];
				$model->per_day_price		  = $userpost['per_day_price'];	
				if ($model->load(Yii::$app->request->post()) && $model->becomeanowner($id)) {
			
				if (isset($model->profile_completed_owner) && !$model->profile_completed_owner){				
					
                                        $emailOwner	= ( isset($model->email) ? $model->email : '' ) ;
                                        $firstName = ( isset(Yii::$app->request->post()['UpdateOwner']['firstname']) ? Yii::$app->request->post()['UpdateOwner']['firstname'] : '' );
				
				
					$ownerName	=  $firstName ;
					$this->sendprofilecompletionemail('owner',$emailOwner,$ownerName);

				}
				
					Yii::$app->session->setFlash('item', '<i class="fa fa-check"></i> Your profile has been updated successfully!');
					return $this->redirect(['myprofile']);
				} else {
					Yii::$app->session->setFlash('item', '<i class="fa fa-close"></i> There is some error in the details submitted. Please check and update accordingly.');
				}
			} else {
				$model->setAttributes($userModel->getAttributes());
			}	
			//find owner pets
			$petinformation	=	Yii::$app->commonmethod->getUserPetsInfo($id);
			$posteditdata = Yii::$app->request->post();
			####= load view			
			$vaccinationdetails['vaccination_validity'] = (isset($vaccinationdetails['vaccination_validity']) ? date(DATEPICKER_FORMAT_PHP,strtotime($vaccinationdetails['vaccination_validity'])) : "");
			return $this->render($setting_page, [
				'model' => $model,
				'modelImageUpload' => $modelImageUpload,
				'modelImageUploadF'=> $modelImageUploadF,
				'vaccinationModel' => $vaccinationModel,
				'vaccinationdetails'=>$vaccinationdetails,
				'petinformation' => $petinformation,
				'modelImgK' => $modelImageUploadK,
				'posteditdata' => $posteditdata,
			]);
						
		} else if($logged_user == SITTER) { 
			
			$updateUser 	= new UpdateSitter();
			$model 			= $updateUser->findIdentity($id);
				
			$modelImageUploadA = new UserProfilePictureUpload();
			$modelImageUploadB = new Uploads();
			$modelImageUploadC = new Uploads();
			$modelImageUploadD = new Uploads();
			
			//check if user have already uploded documents
			$currentHomeDocs =  $updateUser->getuserdocuments($id,3);//check for home images
			if(!empty($currentHomeDocs) && count($currentHomeDocs)>0){
			
			$modelImageUploadD->scenario = 'upd';
			}else{
			
			$modelImageUploadD->scenario = 'uph';
			}	
			
			
			//check if user have already uploded documents
			$currentIdDocs =  $updateUser->getuserdocuments($id,1);//check for ID Docs
			if(!empty($currentIdDocs) && count($currentIdDocs)>0){
			$modelImageUploadB->scenario = 'upb';
			}else{
			$modelImageUploadB->scenario = 'upg';
			}

			//check if user have already uploded profile image
			$currentProfileimage =  Yii::$app->user->identity->profile_image;//check for profileimage			
			if(!empty($currentProfileimage)){
			$modelImageUploadA->scenario = 'signup';
			}else{
			$modelImageUploadA->scenario = 'update-profile';
			}						
			$modelImageUploadC->scenario = 'upc';
						
			$servicesModel 			= new UserServices();
			$userpost 				= Yii::$app->request->post('UpdateSitter');
			$setting_page			= "sitter-profile-settings";
			$userid 			= Yii::$app->user->identity->id;
			$getloggedinuserservices = $servicesModel->getUserservicesByid($userid);
						
			$model->scenario 		= 'update';
			

			if (isset($userpost) && !empty($userpost) && $modelImageUploadD->load(Yii::$app->request->post()) && $modelImageUploadD->validate()) {
				$model->profile_image 	= '';
				$documentsArr 			= array();
				if (Yii::$app->request->isPost) { 

					$modelImageUploadD->upload_home_images = UploadedFile::getInstances($modelImageUploadD, 'upload_home_images');
					/*$user_home_images	=	Yii::$app->commonmethod->getUserDocuments($userid,HOME_IMAGES);
					if(count($user_home_images) == 0 && count($modelImageUploadD->upload_home_images)<2){
					Yii::$app->session->setFlash('item', '<i class="fa fa-check"></i> Minimum two inside house pictures are required.');
					return $this->redirect(['settings']);
					}*/
				
					$modelImageUploadA->profile_image = UploadedFile::getInstance($modelImageUploadA, 'profile_image');
					if ($modelImageUploadA->profile_image && $uploadedFileNameArray = $modelImageUploadA->upload()) {
						$model->profile_image = $uploadedFileNameArray['originalImage'];
					}

					$modelImageUploadB->upload_documents = UploadedFile::getInstances($modelImageUploadB, 'upload_documents');
					if ($modelImageUploadB->upload_documents && $uploadedFileNameArray = $modelImageUploadB->uploadB() ) {
						$documentsArr['upload_documents'] = $uploadedFileNameArray['originalImage'];
					}

					$modelImageUploadC->upload_images = UploadedFile::getInstances($modelImageUploadC, 'upload_images');
					if ($modelImageUploadC->upload_images && $uploadedFileNameArray = $modelImageUploadC->uploadC() ) {
						$documentsArr['upload_images'] = $uploadedFileNameArray['originalImage'];
					}
					
					if ($modelImageUploadD->upload_home_images && $uploadedFileNameArray = $modelImageUploadD->uploadD() ) {
						$documentsArr['upload_home_images'] = $uploadedFileNameArray['originalImage'];
					}
				}

				$model->profile_image = $model->profile_image;
				$model->pet_service_id = Yii::$app->request->post()['UpdateSitter']['pet_service_id'];
				$model->pet_weight_limit = Yii::$app->request->post()['UpdateSitter']['pet_weight_limit'];

				#Get data From certification from View
				$certifications=Yii::$app->request->post()['UpdateSitter']['certification'];
				if (isset($certifications) && !empty($certifications)) {
					$model->certification= implode(",",$certifications);
				}else{
					$model->certification=NULL;
				}
				
				if (isset($model->profile_completed) AND !empty($model->profile_completed)){
				/*
					$emailSitter= ( isset($model->email) ? $model->email : '' ) ;
                    $firstName = ( isset(Yii::$app->request->post()['UpdateSitter']['firstname']) ? Yii::$app->request->post()['UpdateSitter']['firstname'] : '' );
					$lastName = ( isset(Yii::$app->request->post()['UpdateSitter']['lastname']) ? Yii::$app->request->post()['UpdateSitter']['lastname'] : '' );
					$sitterName	=  $firstName ;
					$this->sendprofilecompletionemail('sitter',$emailSitter,$sitterName);
					$this->sendprofilecompletionemail('admin',$email="",$sitterName,'sitter');
				*/
					Yii::$app->session->setFlash('item', '<i class="fa fa-check"></i> Your profile has been submited for approval!');
					return $this->redirect(['myprofile']);	
				}	
				if($model->load(Yii::$app->request->post()) && $model->updateRecords($id,$documentsArr)) {

				
					Yii::$app->session->setFlash('item', '<i class="fa fa-check"></i> Your profile has been updated successfully!');
					return $this->redirect(['settings']);
				} else {
					Yii::$app->session->setFlash('item', '<i class="fa fa-close"></i> There is some error in the details submitted. Please check and update accordingly!');
				}

			} else {
				
				$model->setAttributes($userModel->getAttributes());
			}
			
			
			$model->dob = (isset($model->dob) ? date(DATEPICKER_FORMAT_PHP,strtotime($model->dob)) : "");
			$petweightdata = Yii::$app->commonmethod->getServiceproviderPetWdata($userid);
			$petweightdata = $petweightdata['pet_weight_limit'];
			$posteditdata = Yii::$app->request->post();

		
			####= load view
			return $this->render($setting_page, [
				'model' => $model,
				'modelImageUpload' => 
					array('modelImgA' => $modelImageUploadA,
				    		 'modelImgB' => $modelImageUploadB,
						  'modelImgC' => $modelImageUploadC,
						   'modelImgD' => $modelImageUploadD,
						   'userServices' => $getloggedinuserservices,
						   'petweightdata' => $petweightdata, 
						   'posteditdata' => $posteditdata
						),		
			]);
		} else if($logged_user == RENTER) {
			$modelImageUpload 			= new UserProfilePictureUpload();
			$modelImageUploadA = new UserProfilePictureUpload();
			$modelImageUploadB = new Uploads();
			$modelImageUploadC = new Uploads();
			$modelImageUploadD = new Uploads();
			
			$currentProfileimage =  Yii::$app->user->identity->profile_image;//check for profileimage	
			
			if(!empty($currentProfileimage)){
			$modelImageUpload->scenario = 'signup';
			}else{
			$modelImageUpload->scenario = 'update-profile';
			}
			
			$modelImageUploadB->scenario = 'upb';
			$modelImageUploadC->scenario = 'upc';
			$modelImageUploadD->scenario = 'upd';			
			$updateUser 			= new UpdateRenter();
			$userpost 				= Yii::$app->request->post('UpdateRenter');
			$setting_page			= "renter-profile-settings";

			$model 					= $updateUser->findIdentity($id);
			$model->scenario 		= 'update';
			if (isset($userpost) && !empty($userpost)) {
				$model->profile_image 	= '';
				$documentsArr 			= array();
				if (Yii::$app->request->isPost) {
					$modelImageUpload->profile_image = UploadedFile::getInstance($modelImageUpload, 'profile_image');		
					if ($modelImageUpload->profile_image && $uploadedFileNameArray = $modelImageUpload->upload()) {
						$model->profile_image = $uploadedFileNameArray['originalImage'];
					}					
				}

				$model->profile_image = $model->profile_image;
				
				if ($model->load(Yii::$app->request->post()) && $model->updateRecords($id,$documentsArr)) {
				
					if (isset($model->profile_completed_borrower) && !$model->profile_completed_borrower){
				
					
                                        $emailSitter	= ( isset($model->email) ? $model->email : '' ) ;
                                        $firstName = ( isset(Yii::$app->request->post()['UpdateRenter']['firstname']) ? Yii::$app->request->post()['UpdateRenter']['firstname'] : '' );
				$lastName = ( isset(Yii::$app->request->post()['UpdateRenter']['lastname']) ? Yii::$app->request->post()['UpdateRenter']['lastname'] : '' );
				
					$sitterName	=  $firstName ;
					$this->sendprofilecompletionemail('borrower',$emailSitter,$sitterName);
					$this->sendprofilecompletionemail('admin',$email="",$sitterName,'borrower');
											
				}
								
					Yii::$app->session->setFlash('item', '<i class="fa fa-check"></i> Your profile has been updated successfully!');
					return $this->redirect(['myprofile']);
				} else {
					Yii::$app->session->setFlash('item', '<i class="fa fa-close"></i> There is some error in the details submitted. Please check and update accordingly!');
				}

			} else {
				$model->setAttributes($userModel->getAttributes());
			}
			
			####= load view
			return $this->render($setting_page, [
				'model' => $model,
				//'modelImageUpload' 	=> 	array('modelImgA' => $modelImageUploadA, 'modelImgB' => $modelImageUploadB, 'modelImgC' => $modelImageUploadC, 'modelImgD' => $modelImageUploadD),	
				'modelImageUpload' => $modelImageUpload	
			]);
		} else {
		$modelImageUpload 			= new UserProfilePictureUpload();
		$logged_user 	= $session->get(OWNER);
			$updateUser 			= new UpdateOwner();
			$userpost 				= Yii::$app->request->post('UpdateOwner');
			$setting_page			= "owner-profile-settings";
			$model 					= $updateUser->findIdentity($id);
			$model->scenario 		= 'update';
			if (isset($userpost) && !empty($userpost)) {
				$model->profile_image = '';
				if (Yii::$app->request->isPost) {
					$modelImageUpload->profile_image = UploadedFile::getInstance($modelImageUpload, 'profile_image');
					if ($modelImageUpload->profile_image && $uploadedFileNameArray = $modelImageUpload->upload()) {
						$model->profile_image = $uploadedFileNameArray['originalImage'];
					}
				}
				$model->profile_image = $model->profile_image;
				if ($model->load(Yii::$app->request->post()) && $model->updateRecords($id)) {
					Yii::$app->session->setFlash('item', '<i class="fa fa-check"></i> Your profile has been updated successfully!');
					return $this->redirect(['settings']);
				} else {
					Yii::$app->session->setFlash('item', '<i class="fa fa-close"></i> There is some error in the details submitted. Please check and update accordingly!');
				}
			} else {
				$model->setAttributes($userModel->getAttributes());
			}
			$model->dob = (isset($model->dob) ? date(DATEPICKER_FORMAT_PHP,strtotime($model->dob)) : "");
			####= load view
			return $this->render($setting_page, [
				'model' => $model,
				'modelImageUpload' => $modelImageUpload,
			]);	
		}
    }
    
    protected function findvaccinationModel($id) {  
        if (($model = Vaccinationdetails::find()->where(['user_id' => $id])->asArray()->One()) !== null) {
            return $model;
        } else {
            return array();
        }
    }
    
    public function actionRemoveservice(){
		if(Yii::$app->request->post()){
		$post = Yii::$app->request->post();
		$serviceid = $post['id'];
		//$model = UserServices::find($serviceid);
		//$model->delete();
		$query 		= new Query;
        $query->createCommand()
			->delete('user_services', 'id = "'.$serviceid.'"')
			->execute();
		return true;
		}else{
		return false;
		}    
    }
    
      public function actionRemovepet(){
		if(Yii::$app->request->post()){
		$post = Yii::$app->request->post();
		$petid = $post['id'];
		$query 		= new Query;
        $query->createCommand()
			->delete('user_pets', 'id = "'.$petid.'"')
			->execute();
		return true;
		}else{
		return false;
		}    
    }

	public function actionBecomeaborrower() {
        $id 						= Yii::$app->user->getId();
        $userModel 					= $this->findModel($id);		
		$modelImageUpload 			= new UserProfilePictureUpload();	
		
		$currentProfileimage =  Yii::$app->user->identity->profile_image;//check for profileimage				
			if(!empty($currentProfileimage)){
			$modelImageUpload->scenario = 'signup';
			}else{
			$modelImageUpload->scenario = 'update-profile';
			}
			
		$updateUser 				= new UpdateRenter();
		$userpost 					= Yii::$app->request->post('UpdateRenter');
		$setting_page				= "renter-profile-settings";
		$model 						= $updateUser->findIdentity($id);
		$model->scenario 			= 'update';
		if (isset($userpost) && !empty($userpost)) {
			$model->profile_image = '';
			if (Yii::$app->request->isPost) {
				$modelImageUpload->profile_image = UploadedFile::getInstance($modelImageUpload, 'profile_image');
				if ($modelImageUpload->profile_image && $uploadedFileNameArray = $modelImageUpload->upload()) {
					$model->profile_image = $uploadedFileNameArray['originalImage'];
				}
			}
			$model->profile_image = $model->profile_image;
			if ($model->load(Yii::$app->request->post()) && $model->becomeaborrower($id)) { 
				$session 		= Yii::$app->session;
				$logged_user 	= $session->set('loggedinusertype',RENTER);
				Yii::$app->session->setFlash('item', '<i class="fa fa-check"></i> Your profile has been updated successfully!');
				return $this->redirect(['myprofile']);
			} else {
				Yii::$app->session->setFlash('item', '<i class="fa fa-close"></i> There is some error in the details submitted. Please check and update accordingly!');
			}
		} else {
			$model->setAttributes($userModel->getAttributes());
		}
		$model->dob = (isset($model->dob) ? date(DATEPICKER_FORMAT_PHP,strtotime($model->dob)) : "");
		####= load view
		return $this->render('become-a-borrower', [
			'model' => $model,
			'modelImageUpload' => $modelImageUpload,
		]);		
	}
	
	/**
	* Update an existing Users model.
	* If update is successful, the browser will be redirected to the 'setting' page.
	* @param integer $id
	* @return mixed
	*/
    public function actionBecomeanowner() {
        $id 						= Yii::$app->user->getId();
        $userModel 					= $this->findModel($id);		
		$modelImageUpload 			= new UserProfilePictureUpload();
		$modelImageUploadK = new Uploads();
		$modelImageUploadF = new Uploads();
		$vaccinationModel  = new Vaccinationdetails();
		$modelImageUploadK->scenario = 'upk';
		$modelImageUploadF->scenario = 'upf';
		$modelImageUpload->scenario = 'signup';
		$updateUser 			= new UpdateOwner();
		$userpost 				= Yii::$app->request->post('UpdateOwner');
		$setting_page			= "owner-profile-settings";
		$model 					= $updateUser->findIdentity($id);
		$model->scenario 		= 'update';
		//if (isset($userpost) && !empty($userpost)) {
if (isset($userpost) && !empty($userpost) && $modelImageUploadK->load(Yii::$app->request->post()) && $modelImageUploadK->validate()) { 
			$model->profile_image = '';
			if (Yii::$app->request->isPost) {
				$modelImageUpload->profile_image = UploadedFile::getInstance($modelImageUpload, 'profile_image');
				if ($modelImageUpload->profile_image && $uploadedFileNameArray = $modelImageUpload->upload()) {
					$model->profile_image = $uploadedFileNameArray['originalImage'];
				}
				
				$modelImageUploadK->picture_of_pet = UploadedFile::getInstances($modelImageUploadK, 'picture_of_pet');		
					if ($modelImageUploadK->picture_of_pet && $uploadedFileNameArray = $modelImageUploadK->uploadK() ) {
						$model->picture_of_pet = $uploadedFileNameArray['originalImage'];
					}
			}
			
			$vaccinationdoc = '';	
						if (Yii::$app->request->isPost) {
							$modelImageUploadF->vaccination_doc = UploadedFile::getInstance($modelImageUploadF, 'vaccination_doc');
							if ($modelImageUploadF->vaccination_doc && $uploadedFileNameArray = $modelImageUploadF->uploadF()) {
								$vaccinationdoc = $uploadedFileNameArray['docname'];
							}
						}			
							
			$vaccinationModel->saveVacc(Yii::$app->request->post('Vaccinationdetails'),$vaccinationdoc);
					
			$model->profile_image 		  = $model->profile_image;
			$model->interested_in_renting = $userpost['renting_pet'];
			$model->pet_parent_type 	  = $userpost['pet_parent_type'];
			$model->pet_type			  = $userpost['pet_type'];
			$model->per_day_price		  = $userpost['per_day_price'];	
			$model->pet_name			  = $userpost['pet_name'];
			$model->care_note			  = $userpost['care_note'];
				//$model->pet_id			  	  = $userpost['pet_id'];			
			if ($model->load(Yii::$app->request->post()) && $model->updateRecords($id,1)) { 
				$session 		= Yii::$app->session;
				$logged_user 	= $session->set('loggedinusertype',OWNER);
				Yii::$app->session->setFlash('item', 'Your profile has been updated successfully!');
				return $this->redirect(['settings']);
			} else {
				Yii::$app->session->setFlash('item', '<i class="fa fa-close"></i> There is some error in the details submitted. Please check and update accordingly!');
			}
		} else {
			$model->setAttributes($userModel->getAttributes());
		}
			$model->dob = (isset($model->dob) ? date(DATEPICKER_FORMAT_PHP,strtotime($model->dob)) : "");
			####= load view					
		####= load view
		$posteditdata = Yii::$app->request->post();
		return $this->render('become-an-owner', [
			'model' => $model,
			'modelImageUpload' => $modelImageUpload,
			'modelImgK' => $modelImageUploadK,
			'modelImageUploadF'=> $modelImageUploadF,
			'vaccinationModel' => $vaccinationModel,
			'posteditdata' => $posteditdata,
		]);				
	}

	/**
	* Update an existing Users model.
	* If update is successful, the browser will be redirected to the 'setting' page.
	* @param integer $id
	* @return mixed
	*/
    public function actionBecomeasitter() {
        $updateUser 		= new UpdateSitter();
   		$userid = Yii::$app->user->identity->id;
        $modelImageUploadA = new UserProfilePictureUpload();
        $modelImageUploadB = new Uploads();
        $modelImageUploadC = new Uploads();
        $modelImageUploadD = new Uploads();
                           
			$modelImageUploadD->scenario = 'uph';				
			$modelImageUploadB->scenario = 'upg';					
			//check if user have already uploded profile image
			$currentProfileimage =  Yii::$app->user->identity->profile_image;//check for profileimage				
			if(!empty($currentProfileimage)){
			$modelImageUploadA->scenario = 'signup';
			}else{
			$modelImageUploadA->scenario = 'update-profile';
			}
        
        $modelImageUploadC->scenario = 'upc';
        $servicesModel 			= new UserServices();
        $getloggedinuserservices = $servicesModel->getUserservicesByid($userid);

        $id 						= Yii::$app->user->getId();
        $userModel 					= $this->findModel($id);
       
        $userpost 					= Yii::$app->request->post('UpdateSitter');
        $model 						= $updateUser->findIdentity($id);
		$model->scenario 			= 'update';
		if (isset($userpost) && !empty($userpost) && $modelImageUploadD->load(Yii::$app->request->post()) && $modelImageUploadD->validate()) {
			$model->profile_image 	= '';
			$documentsArr 			= array();
			if (Yii::$app->request->isPost) {
                $modelImageUploadA->profile_image = UploadedFile::getInstance($modelImageUploadA, 'profile_image');
                if ($modelImageUploadA->profile_image && $uploadedFileNameArray = $modelImageUploadA->upload()) {
                    $model->profile_image = $uploadedFileNameArray['originalImage'];
                }

				$modelImageUploadB->upload_documents = UploadedFile::getInstances($modelImageUploadB, 'upload_documents');
				if ($modelImageUploadB->upload_documents && $uploadedFileNameArray = $modelImageUploadB->uploadB() ) {
					$documentsArr['upload_documents'] = $uploadedFileNameArray['originalImage'];
				}

				$modelImageUploadC->upload_images = UploadedFile::getInstances($modelImageUploadC, 'upload_images');
				if ($modelImageUploadC->upload_images && $uploadedFileNameArray = $modelImageUploadC->uploadC() ) {
					$documentsArr['upload_images'] = $uploadedFileNameArray['originalImage'];
				}

				$modelImageUploadD->upload_home_images = UploadedFile::getInstances($modelImageUploadD, 'upload_home_images');
				if ($modelImageUploadD->upload_home_images && $uploadedFileNameArray = $modelImageUploadD->uploadD() ) {
					$documentsArr['upload_home_images'] = $uploadedFileNameArray['originalImage'];
				}
			}

			$model->profile_image = $model->profile_image;
			if ($model->load(Yii::$app->request->post()) && $model->becomeASitter($id,$documentsArr)) {
				$session 		= Yii::$app->session;
				$logged_user 	= $session->set('loggedinusertype',SITTER);				
				Yii::$app->session->setFlash('item', 'Your profile has been updated successfully!');
				return $this->redirect(['users/myprofile']);
			} else {
				Yii::$app->session->setFlash('item', '<i class="fa fa-close"></i> There is some error in the details submitted. Please check and update accordingly!');
			}

		} else {
			$model->setAttributes($userModel->getAttributes());
		}
	$posteditdata = Yii::$app->request->post();
        return $this->render('become-a-sitter', [
            'model' => $model,
            'modelImageUpload' 		=> 	array('modelImgA' => $modelImageUploadA, 'modelImgB' => $modelImageUploadB, 'modelImgC' => $modelImageUploadC, 'modelImgD' => $modelImageUploadD,'userServices' => $getloggedinuserservices, 'posteditdata' => $posteditdata),		
        ]);
    }

	public function actionTestview() {
		$dataArray 	= array();
		$user_id 	= Yii::$app->request->get('id');
		if($user_id == 0)
			return $this->redirect(['users/index']);
			
		$this->redirect(['users/view-user-details/'.$user_id]);
	}

	/**
	* Finds the CrudTest model based on its primary key value.
	* If the model is not found, a 404 HTTP exception will be thrown.
	* @param integer $id
	* @return CrudTest the loaded model
	* @throws NotFoundHttpException if the model cannot be found
	*/
    protected function findModel($id) {
        if (($model = Users::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionViewUserDetails() {
    $this->limit= 10;
		$dataArray 	= array();
		$user_id 	= Yii::$app->request->get('id');
		if($user_id == 0)
			return $this->redirect(['users/index']);

		####= { $selectCol } use to get specific columns of users. 
		//$selectCol  		= "id,email,firstname,lastname";
		//$userInfo			= Yii::$app->commonmethod->getUserColumnsData($user_id,$selectCol);
		$userInfo 			= \frontend\models\users\Sitters::findOne($user_id);
		$userServices		= Yii::$app->commonmethod->getUserServices($user_id);
		$userImages			= Yii::$app->commonmethod->getUserActiveDocuments($user_id,USER_IMAGES);
		$completedServices	= Yii::$app->commonmethod->getCompletedServices($user_id);
		$userRatings		= Yii::$app->commonmethod->getUserRatings($user_id);

        $dataArray 	= array();
        $query 		= new Query;
        $query->select('feedback_rating.*,us.firstname as fname,us.lastname as lname,us.profile_image, us.description as user_description, us.unsubscribe_owner,us.unsubscribe_sitter')
        ->from('feedback_rating')
        ->join('LEFT JOIN', 'user us', 'us.id = feedback_rating.sender_userid')       
        ->where('feedback_rating.receiver_userid = '.$user_id.' ORDER BY feedback_rating.id DESC');
        $countQuery = clone $query;
        $pages 	 	= new Pagination(['totalCount' => $countQuery->count()]);
        $pages->setPageSize($this->limit);
		$query->offset($pages->offset)->limit($this->limit);         
        $userReviews = $query->createCommand()->queryAll();
$query1 		= new Query;
$query1->select('*')->from('pet_information')->where('user_id = '.$user_id);
$renterprice = $query1->createCommand()->queryAll();

        $dataArray = array_merge($dataArray, [            
             'user_information' 	=> $userInfo,
             'user_services' 		=> $userServices,
             'user_images'			=> $userImages,
             'user_reviews'			=> $userReviews,
             'pages' 				=> $pages,
             'completed_services' 	=> $completedServices,
             'user_ratings' 		=> $userRatings,
'renterprice' => $renterprice
        ]);

		return $this->render('view-user-details',$dataArray);
	}
	####= get today booking
	public function getCurrentBooking($userID) {
		$currentDate= date("Y-m-d");
		$userID = 37;
        $query 	= new Query;
        //$query->select('*')->from('booking')->where(['pet_sitter_id' => $userID]);
        $query->select('*')->from('booking')->where('pet_sitter_id = '.$userID.' AND booking_from_date <= "'.$currentDate.'" AND booking_to_date >= "'.$currentDate.'"');
        return $query->createCommand()->queryOne();
        //echo $query->createCommand()->getRawSql();
	}
	
	public function actionSwitchAccount($user_type=0) {
		$session 	= Yii::$app->session;
		$user_type 	= Yii::$app->request->get('id');
		$attributes = Yii::$app->user->identity->getattributes();
		if($attributes['user_type'] == OWNER_SITTER) {
			if($user_type == OWNER) {
				$session->set('loggedinusertype', OWNER);
			} else if($user_type == SITTER) {
				$session->set('loggedinusertype', SITTER);
			}
		} else if($attributes['user_type'] == BORROWER_SITTER) {
			if($user_type == SITTER) {
				$session->set('loggedinusertype', SITTER);
			} else if($user_type == BORROWER) {
				$session->set('loggedinusertype', BORROWER);
			}
		} else if($attributes['user_type'] == OWNER_BORROWER) {
			if($user_type == OWNER) {
				$session->set('loggedinusertype', OWNER);
			} else if($user_type == BORROWER) {
				$session->set('loggedinusertype', BORROWER);
			}
		} else if($attributes['user_type'] == ALL_PROFILES) {
			if($user_type == OWNER) {
				$session->set('loggedinusertype', OWNER);
			} else if($user_type == BORROWER) {
				$session->set('loggedinusertype', BORROWER);
			} else if($user_type == SITTER) {
				$session->set('loggedinusertype', SITTER);
			}
		}
		return $this->redirect(['users/myprofile']);
	}
	
	public function actionBookingreminders(){
	$session 	= Yii::$app->session;
	$message = '';
	$userid = Yii::$app->user->identity->id;
	//get users next booking date to set a reminder message
	$loggedingusertype = $session->get('loggedinusertype');
	
	if($loggedingusertype==OWNER || $loggedingusertype==BORROWER){
	$nextbookingData = Yii::$app->commonmethod->getBookingReminder($userid);
	if(!empty($nextbookingData)){
	$bookingdate =  $nextbookingData[0]['booking_from_date'];
	$bookingname =  $nextbookingData[0]['name'];
	$message = 
	'<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
            <div class="output alert  alert-dismissible ">
                      <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span></button>
                      <i class="fa fa-clock-o" aria-hidden="true"></i> Upcoming booking Scheduled on '.$bookingdate.'<b> Booking name:</b> '.$bookingname.'</div>
			</div>
	</div>';
	}
	
	}elseif($loggedingusertype==SITTER){
	$currentbookingData = Yii::$app->commonmethod->getSitterbookings($userid);
		if(empty($currentbookingData)){
	
	   $nextbookingData = Yii::$app->commonmethod->getSitterupcomingbookings($userid);
		   if(!empty($nextbookingData)){
		   $bookingdate =  $nextbookingData[0]['booking_from_date'];
			$bookingname =  $nextbookingData[0]['name'];
			$message = 
		'<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
		        <div class="output alert  alert-dismissible ">
		                  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span></button>
		                  <i class="fa fa-clock-o" aria-hidden="true"></i> Upcoming booking Scheduled on '.$bookingdate.'<b> Booking name:</b> '.$bookingname.'</div>
				</div>
		</div>';
			}
		}else{
		
		$message = 
	'<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
            <div class="output alert  alert-dismissible ">
                      <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span></button>
                      <i class="fa fa-clock-o" aria-hidden="true"></i> You have Bookings scheduled for today. Please update booking activities.</div>
			</div>
	</div>';
		}
	 }
		
	$session->set('reminedviewed',1);
	
	return $message;	
	}
	
	public function actionUpdatesubscription(){
	
	$model= new AddUserForm();
	$post = Yii::$app->request->post();
	if(!empty($post)){
	$ownerstats = (isset($post['owner'])?$post['owner']:1);
	$sitterstatus = (isset($post['sitter'])?$post['sitter']:1);
	$renterstatus = (isset($post['renter'])?$post['renter']:1);
	$subscription = $model->updatesubscription($ownerstats,$sitterstatus,$renterstatus);
		if($subscription){
		return true;
		}else{
		return false;
		}		
	 }
	}
	
	public function sendprofilecompletionemail($userType,$email="",$sitterName,$type=""){
		$adminEmail		= Yii::$app->commonmethod->getAdminEmailID();
		
		$user = '' ;
		if ($type == 'sitter') {
			$user = 'Sitter';
		}
		if ($type == 'borrower') {
			$user = 'Borrower';
		}
		
		if ($userType == 'admin') {				
			$setTo	  =  $adminEmail ;
		} else {			
			$setTo	  =  $email ;
		}
		$subject  = "Profile verification completed" ;
		$message  ='';
		if ($userType == 'admin') {	
			$message .= '<tr>
								<td height="5" style="font-size:13px; color:#656565; line-height:5px; padding-bottom:10px;">Hi Admin,</td>
							  </tr>
							  <tr>
								<td style="font-size:13px; color:#656565; line-height:18px; padding-bottom:10px;"> '.$user.'(' .$sitterName. ' ) has completed their profile for verification.</td>
							  </tr>';
		} elseif ($userType == 'sitter') {
		
			
			$message .= '<tr>
								<td height="5" style="font-size:13px; color:#656565; line-height:5px; padding-bottom:10px;">Hi '.$sitterName.',</td>
							  </tr>
							  <tr>
								<td style="font-size:13px; color:#656565; line-height:18px; padding-bottom:10px;">Thank you for completing your profile! To ensure Pet owners get the best experience we personally check all sitter profiles.</td>
							  </tr>
							   <tr>
                            <td height="5"></td>
                          </tr>
                          <tr>
								<td style="font-size:13px; color:#656565; line-height:18px; padding-bottom:10px;">We will get back to you real soon. Meanwhile, you can learn more about us on <a href="https://www.palcura.com/" target="_blank">www.palcura.com</a> or follow us on our social media profiles.</td>
							  </tr>
							  <tr>
                            <td height="5"></td>
                          </tr>
                          <tr>
								<td style="font-size:13px; color:#656565; line-height:18px; padding-bottom:10px;">We cannot wait to start this journey with you!</td>
							  </tr>'
							  ;
		
		} elseif ($userType == 'borrower') {

			  $message .= '<tr>
                            <td height="5" style="font-size:13px; color:#656565; line-height:5px; padding-bottom:10px;">Hi '.$sitterName.',</td>
                          </tr>
                          <tr>
                            <td style="font-size:13px; color:#656565; line-height:18px; padding-bottom:10px;">
                            
                            Your have successfully updated your profile.Please follow the below 3 easy steps to borrow from our network of loving pet owners once verified from admin:
                           </td>
                          </tr>
                          <tr>
                            <td height="5"></td>
                          </tr>
                          <tr>
                            <td align="left">
								<ul style="background-color: #fff3e3; color:#000000; font-size:15px; list-style:none; margin:0; padding:0; font-weight:600; padding: 10px 20px; letter-spacing: 0.3px;">
									
									<li><span style="font-style:italic; font-weight:400; line-height:30px;">1. Sign in at <a href="https://www.palcura.com">www.palcura.com</a> and search for nearby pet owners</span></li>
									<li><span style="font-style:italic; font-weight:400; line-height:30px;">2. Send a message, set up a video conference or in person meet & greet with the owner.</span></li>
									<li><span style="font-style:italic; font-weight:400; line-height:30px;">3. Reserve and welcome a bundle of love into your life!</span></li>
									
								</ul>
							</td>
                          </tr>
                          <tr>
                            <td height="15"></td>
                          </tr>
                          <tr style="color:#656565; font-size:13px; line-height:19px;" >
                            <td>Thank you and welcome aboard!</td>
                               </tr>		
<tr>		
                            <td height="15"></td>
                          </tr>';          
		} elseif ($userType == 'owner'){
		
		$subject  = "Welcome to PalCura!"; 
	           
               $message .= '<tr>
                            <td height="5" style="font-size:13px; color:#656565; line-height:5px; padding-bottom:10px;">Hi '.$sitterName.',</td>
                          </tr>
                          <tr>
                            <td style="font-size:13px; color:#656565; line-height:18px; padding-bottom:10px;">We are stoked to have you as a part of PalCura! When you are ready, follow the below 3 easy steps to reserve loving care for your pal from our network of verified pet care providers.</td>
                          </tr>
                          <tr>
                            <td height="5"></td>
                          </tr>
                          <tr>
                            <td align="left">
								<ul style="background-color: #fff3e3; color:#000000; font-size:15px; list-style:none; margin:0; padding:0; font-weight:600; padding: 10px 20px; letter-spacing: 0.3px;">
								
									<li><span style="font-style:italic; font-weight:400; line-height:30px;">1. Sign in at <a href="https://www.palcura.com">www.palcura.com</a> and search for nearby pet care providers</span></li>
									<li><span style="font-style:italic; font-weight:400; line-height:30px;">2. Send a message, set up a video conference or in person meet & greet with the care provider.</span></li>
									<li><span style="font-style:italic; font-weight:400; line-height:30px;">3. Reserve, sit back, and relax!</span></li>
									
								</ul>
							</td>
                          </tr>
                          <tr>
                            <td height="15"></td>
                          </tr>
                          <tr style="color:#656565; font-size:13px; line-height:19px;" >
                            <td>Feel free to share the love and spread the message.
                            </td>
                          </tr><tr>
                            <td height="15"></td>
                          </tr>
						  <tr style="color:#656565; font-size:13px; line-height:19px;" >
                            <td>Thank you and welcome aboard!</td>
                          </tr>
<tr>
                            <td height="15"></td>
                          </tr>'; 
		
		}
	
		$mail = Yii::$app->mailer->compose(['html' => 'layouts/mail'],['content' => $message, 'subject' => $subject])
		->setFrom([$adminEmail => 'Palcura'])
		->setTo($setTo)
		->setSubject($subject)
		->send();
		
		return $mail;
		
	}
	
	
}
