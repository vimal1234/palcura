<?php
namespace frontend\controllers;

use Yii;
use yii\base\Model;

use common\models\LoginForm;
use common\models\Emailsubscribers;

use common\models\Country;
use common\models\State;
use common\models\City;
use frontend\models\users\UpdateUser;

use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\users\Users;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use frontend\models\Contactus;
use frontend\models\Contactus1;
use frontend\models\users\AddUserForm;
use backend\models\sitters\Serviceprovider;
use backend\models\owners\Petinformation;
use common\models\Admin;
use yii\helpers\Url;
use yii\web\ErrorAction;
use yii\db\Query;
use yii\data\Pagination;

##############= FOR FILE UPLOAD =################
use yii\web\UploadedFile;
use backend\models\sitters\UserProfilePictureUpload;
use backend\models\sitters\Uploads;

/**
* Site controller
*/
class SiteController extends Controller {
	/**
	* @inheritdoc
	*/
	
    public function actions() {
        return [       
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
            'auth' => [
            'class' => 'yii\authclient\AuthAction',
            'successCallback' => [$this, 'onGPAuthSuccess'],
          ],  
            'authGP' => [
            'class' => 'yii\authclient\AuthAction',
            'successCallback' => [$this, 'onGPAuthSuccess'],
          ],                     
        ];
    }

	/**
	* Displays homepage.
	* @return mixed
	*/
    public function actionIndex() {
    	

        return $this->render('home');
    }

    static function _getuserId() {
        return Yii::$app->user->id;
    }

	/**
	* Displays homepage.
	* @return mixed
	*/
    public function actionHome() {
    	\Yii::$app->view->title = 'Palcura.com | Loving Care For Your Pet – Sitting, Boarding, Walking & More';
    	 \Yii::$app->view->registerMetaTag([
        'name' => 'description',
        'content' => 'Find loving, approved pet sitters near you who offer pet boarding, dog walking, house sitting, or doggy day care. Book and pay securely.'
    ]);
    
   $getattraibutes = Yii::$app->request->get();
   if(!empty($getattraibutes)){
	   if(isset($getattraibutes['dashboard'])){
	   $session = Yii::$app->session;
	   $session->set('dashboardurl',1);
	   }   	  
   }
   
        $model = new \frontend\models\Search();
        $servicesTypes = Yii::$app->commonmethod->servicesTypesbyPet();     
        $reqData = array(
        'serviceType' => $servicesTypes,
        );

        $homeBlocks = array();
        $homeBlocks['o_block01']	=	$this->gethomeblocks('signup');
        $homeBlocks['o_block02']	=	$this->gethomeblocks('connect');
        $homeBlocks['o_block03']	=	$this->gethomeblocks('book');
        $homeBlocks['s_block01']	=	$this->gethomeblocks('createowner');
        $homeBlocks['s_block02']	=	$this->gethomeblocks('connectsitter');
        $homeBlocks['s_block03']	=	$this->gethomeblocks('getpaid');
        $homeBlocks['b_block01']	=	$this->gethomeblocks('createborrower');
        $homeBlocks['b_block02']	=	$this->gethomeblocks('gettoknow');
        $homeBlocks['b_block03']	=	$this->gethomeblocks('share');
        $homeBlocks['o_easy_steps']	=	$this->gethomeblocks('ownerprofilesteps');
        $homeBlocks['s_easy_steps']	=	$this->gethomeblocks('sitterprofilesteps');
        $homeBlocks['b_easy_steps']	=	$this->gethomeblocks('borrowerprofilesteps');
        $homeBlocks['visionblock']	=	$this->gethomeblocks('visionblock');
        $homeBlocks['missionblock']	=	$this->gethomeblocks('missionblock');

        return $this->render('home',[
        'model'=>$model,
        'reqData'=> $reqData,
        'homeBlocks'=> $homeBlocks,
        ]);
    }
    public function actionThank(){
    		return $this->render('thank-you');
    }
	
	public function actionRewards(){
		\Yii::$app->view->title = 'Palcura Loyalty & Rewards Program | Earn Free Credits When You Use Palcura';
    	 \Yii::$app->view->registerMetaTag([
        'name' => 'description',
        'content' => 'The more you use Palcura, the bigger the rewards. Start earning credits that can be redeemed for future pet services.'
    ]);
 				
		return $this->render('rewards');
				
    }
	
 	 public function actionSitterthank(){
			
    		return $this->render('sitter-thanks');
    }
 	public function actionBecomeasitter(){

 		\Yii::$app->view->title = 'Palcura.com | Pet Sitting & Dog Walking Jobs';
    	 \Yii::$app->view->registerMetaTag([
        'name' => 'description',
        'content' => 'Looking for a pet sitting job? With Palcura, you can start your own pet care business and turn your passion for pets into 
        extra money!'
    ]);
 				
	$session = Yii::$app->session;
	
		$model = new Emailsubscribers();
		if ($model->load(Yii::$app->request->post())) {
			  if($user = $model->savedata()) {
			  	//	return $this->render('sitter-thanks');
					
					return $this->redirect(['/sitter-thank']);
			  }
			
		}elseif(!empty(Yii::$app->request->post())) {
			
				$message = Yii::t('yii',"Incorrect Email Address.");
			if(isset($model->errors['user']))
					$message = $model->errors['user'][0];
					Yii::$app->session->setFlash('item', $message);            
			}

		return $this->render('become-sitter', ['model' => $model]);
				

    }
    public function actionBecome(){
		
			
	$session = Yii::$app->session;
	
		$model = new Emailsubscribers();
		if ($model->load(Yii::$app->request->post())) {
			
			  if($user = $model->savedata()) {
				  
			  }
			
		}elseif(!empty(Yii::$app->request->post())) {
			
				$message = Yii::t('yii',"Incorrect Email Address.");
			if(isset($model->errors['user']))
					$message = $model->errors['user'][0];
					Yii::$app->session->setFlash('item', $message);            
			}

		return $this->render('demo', ['model' => $model]);
 		 		//return $this->render('demo');

    }

	public function actionSignup() {
	 $session = Yii::$app->session;
	if (!\Yii::$app->user->isGuest) {
			return $this->goHome();
		}       
		$model  = new AddUserForm();
		if ($model->load(Yii::$app->request->post())) {
		if($model->validate()){
			#####= post data
			$userinfo= Yii::$app->request->post();
		
			$model->interested_in_renting	= (isset($userinfo['AddUserForm']['renting_pet']) ? $userinfo['AddUserForm']['renting_pet'] : 0);				
            if($user = $model->savedata()) {
				
				$this->defaultUnavailability($user);
				$autoCredits = Yii::$app->commonmethod->getTeaserEmail($model->email);
										
				$this->_sendUserRegistrationEmailToAdmin($userinfo['AddUserForm']);
				$this->_sendUserRegistrationEmail($userinfo['AddUserForm'],$user,$autoCredits);
				if($autoCredits !=1){ 
                Yii::$app->session->setFlash('item', Yii::t('yii','Please check your email to verify your <br/> account so you can get started with Palcura.'));
				$session->set('registered',1);
				//return $this->redirect(['signup']);
				/* Devloped by sigma  # give permision to frontend/config/main.php*/
							return $this->redirect(['thank']);
				/* ----End------*/
		
					

				}elseif($autoCredits ==1 && $userinfo['AddUserForm']['user_type']==SITTER){
				
				Yii::$app->session->setFlash('item', Yii::t('yii','Thank you for the sign up. We are currently reviewing your profile and will send you a confirmation email on the verification badge.'));
$session->set('registered',1);
				  //return $this->redirect(['signin']);
				/* Devloped by sigma  # give permision to frontend/config/main.php*/
							return $this->redirect(['thank']);
				/* ###### */
				
				}elseif($autoCredits ==1 && $userinfo['AddUserForm']['user_type']==OWNER || $userinfo['AddUserForm']['user_type']==RENTER){
				Yii::$app->session->setFlash('item', Yii::t('yii','Thank you for the sign up. Login to your account.'));
$session->set('registered',1);
				//return $this->redirect(['signin']);
				/* Devloped by sigma  # give permision to frontend/config/main.php*/
							return $this->redirect(['thank']);
				/* ###### */

				}
            } else {
				
                Yii::$app->session->setFlash('item', Yii::t('yii','Please enter valid values for all the fields.'));
            }
    	}else{
           Yii::$app->session->setFlash('item', Yii::t('yii','Some required information is missing or incorrect. Please make the changes and click on SAVE again.'));
           
        } 
	}

$postdArray = Yii::$app->request->post();
$renting_pet_selected = (isset($postdArray['AddUserForm']['renting_pet']) ? $postdArray['AddUserForm']['renting_pet'] : "");
$dob_selected = (isset($postdArray['AddUserForm']['dob']) ? $postdArray['AddUserForm']['dob'] : "");

		return $this->render('signup',[
			'model'					=>	$model,		
			'postdata'				=> Yii::$app->request->post(),
			'renting_pet_selected'	=>$renting_pet_selected,
			'dob_selected'			=> $dob_selected				
		]);
	}

	public function defaultUnavailability($userId) {
		$insertItems = array('user_id'=> $userId,'dates'=> date("m/d/Y"));
		Yii::$app->db->createCommand()->insert('user_unavailability', $insertItems)->execute();
	}
	
	/*
	* Logs out the current user.
	*
	* @return mixed
	*/
    public function actionLogout() {
        Yii::$app->user->logout();
        $session = Yii::$app->session;
        $session->remove('loggedinusertype');
        return $this->goHome();
    }

	public function actionLogin() {	
		return $this->redirect(['site/signin']);
	}

	/*
	* Logs in a user.
	* @return mixed
	*/
	public function actionSignin() {
		if (!\Yii::$app->user->isGuest) {
			return $this->goHome();
		}
$session = Yii::$app->session;
	$setReminder 	= $session->set('reminedviewed', 0);
		$model = new LoginForm();
		if ($model->load(Yii::$app->request->post()) && $model->login()) {

		
			$attributes = 	Yii::$app->user->identity->getattributes();
			$session 	= 	Yii::$app->session;
			$usersignintype = $attributes['user_signin_type'];
			

			switch ($attributes['user_type']) {
				case OWNER:
					$session->set('loggedinusertype', OWNER);
					break;
				case SITTER:
					$session->set('loggedinusertype', SITTER);
					break;
				case BORROWER:
					$session->set('loggedinusertype', BORROWER);
					break;
				case OWNER_SITTER:
					$session->set('loggedinusertype', $usersignintype);
					break;
				case BORROWER_SITTER:
					$session->set('loggedinusertype', $usersignintype);
					break;
				case OWNER_BORROWER:
					$session->set('loggedinusertype', $usersignintype);
					break;
				case ALL_PROFILES:
					$session->set('loggedinusertype', $usersignintype);
					break;													
				default:
					$this->redirect(['users/logout']);
			}
			
			//if search request has been made before login then redirect to search result post login
			$checkdashboardreq = $session->get('dashboard');
			$chksearchrequest = $session->get('searchrequest');
			$currentloggeduser = $session->get('loggedinusertype');
			
		
	
			//echo $_SERVER['HTTP_HOST'].$returnurl; die;
			
			
			if(!empty($chksearchrequest) && $chksearchrequest==1 && $currentloggeduser==OWNER){
			return $this->redirect(['search/petsitter']);
			}
			if(!empty($chksearchrequest) && $chksearchrequest==1 && $currentloggeduser==BORROWER){
			return $this->redirect(['search/petrenter']);
			}
			if(!empty($chksearchrequest) && $chksearchrequest==1 && $currentloggeduser==SITTER){
			Yii::$app->session->setFlash('error', 'Switch Profile in Your Profile, or Sign up As Owner/Borrower to search.');
			return $this->redirect(['users/dashboard']);
			}
		    $returnurl = Yii::$app->user->getReturnUrl();
			$base_retrun_url=$this->url();
			if (Yii::$app->user->getReturnUrl() == $base_retrun_url) {
			    return $this->redirect('users/dashboard');
			} else {
				return $this->redirect($returnurl);
                	//return $this->redirect('users/settings');
			}
					
			if(!empty($checkdashboardreq) && $checkdashboardreq==1){
			$session->set('dashboard',0);
			return $this->redirect(['users/dashboard']);
			}

			return $this->redirect(['users/dashboard']);
		} elseif(!empty(Yii::$app->request->post())) {
			$message = Yii::t('yii',"Incorrect username or password.");
		if(isset($model->errors['user']))
			$message = $model->errors['user'][0];
			Yii::$app->session->setFlash('item', $message);            
		}
		
		return $this->render('signin', [
			'model' => $model
		]);
	}
/*
return url Function
*/
function url(){
    if(isset($_SERVER['HTTPS'])){
        $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
    }
    else{
        $protocol = 'http';
    }
    return $protocol . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
}


	/*
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
            throw new NotFoundHttpException(Yii::t('yii','The requested page does not exist.'));
        }
    }

	/*
	* Requests password reset.
	* @return mixed
	*/
    public function actionForgotPassword() {
       $model = new PasswordResetRequestForm();
       if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('item', Yii::t('yii','Password reset link has been sent to your email address.'));
				return $this->redirect( Url::to(['site/login']) );
            } else {
                Yii::$app->session->setFlash('item', Yii::t('yii','Sorry, We are unable to reset the password for the email provided.'));
                return $this->redirect( Url::to(['site/login']) );
            }
        }
        return $this->render('requestPasswordResetToken', [
                    'model' => $model,
        ]);
    }

	/**
	* Displays reset password request.
	* @return mixed
	*/
	public function actionRequestpasswordreset() {
		return $this->render('requestpasswordreset');
	}

	/**
	* Resets password.
	* @param string $token
	* @return mixed
	* @throws BadRequestHttpException
	*/
    public function actionResetPassword($token) {


		try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
			Yii::$app->session->setFlash('item', Yii::t('yii','Your link has expired.'));
			return $this->redirect( Url::to(['site/forgot-password']) );
			
           // throw new BadRequestHttpException($e->getMessage());
        }
        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
			
			    Yii::$app->session->setFlash('item', Yii::t('yii','Your password has been successfully reset.'));
           
            return $this->redirect( Url::to(['site/login']) );
        }
        return $this->render('resetPassword', [
            'model' => $model,
        ]);
	
    }
     
    
	/**
	* @ Function Name		: _sendUserRegistrationEmail
	* @ Function Params		: 
	* @ Function Purpose 	: email to user
	* @ Function Returns	: boolean true/false
	*/
   /* public function _sendUserRegistrationEmail($data,$userId=0,$autoCredits) {
		$link		= SITE_URL."site/login";		
		if($userId > 0) {
			$token 	=	uniqid();
			$link	=	SITE_URL.'site/verifyemail/'.$token;
			$insertItems = array('user_id'=> $userId,'token'=> $token,'status'=>'0','dateCreated' =>date(DATETIME_FORMAT));
			Yii::$app->db->createCommand()->insert('userverification', $insertItems)->execute();
		}
		$adminEmail	= Yii::$app->commonmethod->getAdminEmailID();
		$creditmessage = '';	
		if($autoCredits==1){
		$subject  = "We just launched! Welcome aboard!"; 
		$message  = '';
		$message .='<tr>';
			$message .='<td height="26" style="font-size:15px; font-weight:500; color:#2c2c2c;">Hi '.$data['firstname'].' '.$data['lastname'].',</td>';
		$message .='</tr><tr>
                            <td height="5"></td>
                          </tr>';
		$message .='<tr>';
			$message .='<td style="font-size:13px; color:#656565; line-height:18px; padding-bottom:10px;">Palcura is live! Thank you for patiently waiting as we ironed out the paw-prints.</td>';
		$message .='</tr>';
		$message .='<tr>';
			$message .='<td height="5"></td>';
		$message .='</tr>';
		$message .='<tr>';
			$message .='<td style="font-size:13px; color:#656565; line-height:18px; padding-bottom:10px;">Don’t forget to avail your $5 credit!  Follow the below 3 easy steps to use your credit:
			<ul>
			<li>1. ‘Sign in’ at www.palcura.com</li>
			<li>2. Fill out some additional details</li>
			<li>3. Reserve or accept a reservation and you will be able to use your $5 credit</li>
			</ul>
			</td>';
		$message .='</tr>';
		$message .='<tr>';
			$message .='<td height="15"></td>';
		$message .='</tr>
		
		<tr  style="color:#656565; font-size:13px; line-height:19px;">
                            <td>We cannot wait to go on this journey with you!<br /><br />
                              -Your friends at PalCura <br />
                             Share the care…Multiply the love!</td>
                          </tr>
                          <tr  style="color:#656565; font-size:13px; line-height:19px;">
                            <td><br />Always book through PalCura to earn member discounts, points, coupons and connection to a large pet care community around you.</td>
                          </tr>';
		}else{	
		
		$subject  = "Congratulations! Your Account has been successfully created."; 
		$message  = '';
		$message .='<tr>';
			$message .='<td height="26" style="font-size:15px; font-weight:500; color:#2c2c2c;">Hi '.$data['firstname'].' '.$data['lastname'].',</td>';
		$message .='</tr><tr>
                            <td height="5"></td>
                          </tr>';
		$message .='<tr>';
			$message .='<td style="font-size:13px; color:#656565; line-height:18px; padding-bottom:10px;">Congratulations! You are successfully registered. Please click <a target="_blank" href="'.$link.'">here</a> to verify your email address. Below are login details:</td>';
		$message .='</tr>';
		$message .='<tr>';
			$message .='<td height="5"></td>';
		$message .='</tr>';
		$message .='<tr>';
			$message .='<td align="left">';
				$message .='<table width="100%" border="0" bgcolor="#656565" cellspacing="1" cellpadding="6" style=" color:#656565;">';
					$message .='<tr bgcolor="#ff8447">';
						$message .='<td colspan="2" style="border-top:#203367 solid 0px; font-size:14px; font-weight:400; color:#ffffff; text-transform:capitalize; padding:8px;">User Information</td>';
					$message .='</tr>';
					$message .='<tr  bgcolor="#ffffff">';
						$message .='<td>Email</td>';
						$message .='<td>' . @$data['email'] . '</td>';
					$message .='</tr>';
					$message .='<tr  bgcolor="#ffffff">';
						$message .='<td>Password</td>';
						$message .='<td >' . @$data['password'] . '</td>';
					$message .='</tr>';	
				$message .='</table>';
			$message .='</td>';
		$message .='</tr>';
		$message .='<tr>';
			$message .='<td height="15"></td>';
		$message .='</tr>
		
		<tr  style="color:#656565; font-size:13px; line-height:19px;">
                            <td>Feel free to share the love and spread the message.<br /><br />
                            Thank you and welcome aboard!<br /><br />
                              -Your friends at PalCura <br />
                             Share the care…Multiply the love!</td>
                          </tr>
                          <tr  style="color:#656565; font-size:13px; line-height:19px;">
                            <td><br />Always book through PalCura to earn member discounts, points, coupons and connection to a large pet care community around you.</td>
                          </tr>';
           }               
		return Yii::$app->mailer->compose(['html' => 'layouts/mail'], ['content' => $message, 'subject' => $subject])
						->setTo($data['email'])
						->setFrom([$adminEmail => 'Palcura' ])
						->setSubject($subject)
						->setTextBody($message)
						->send();
    }*/
    
    public function _sendUserRegistrationEmail($data,$userId=0,$autoCredits) {
		$link		= SITE_URL."site/login";		
		if($userId > 0) {
			$token 	=	uniqid();
			$link	= SITE_URL."site/verifyemail/".$token;
			$insertItems = array('user_id'=> $userId,'token'=> $token,'status'=>'0','dateCreated' =>date(DATETIME_FORMAT));
			Yii::$app->db->createCommand()->insert('userverification', $insertItems)->execute();
		}
		$adminEmail	= Yii::$app->commonmethod->getAdminEmailID();
		$creditmessage = '';	
		if($autoCredits==1){
		
			if($data['user_type'] == OWNER){
			$userInfoA = $data;
			$this->verificationmailtoowner($userInfoA);
			return true;			
			}elseif($data['user_type'] == SITTER){
		    return true;					
			}elseif($data['user_type'] == RENTER){
			$userInfoA = $data;
			$this->verificationmailtoborrower($userInfoA);
			return true;
			}else{		
			return false;
			}
			
			
			/*Yii::$app->session->setFlash('item', Yii::t('yii','Thank you! You have successfully verified your email address. Login to your account.'));
				}
				return $this->redirect( Url::to(['site/login']) );	*/

		}else{	
		
		$subject  = "Please verify your email"; 
		$message  = '';                          
                        $message .= ' <tr>
                            <td height="5" style="font-size:13px; color:#656565; line-height:5px; padding-bottom:10px;">Hi '.$data['firstname'].',</td>
                          </tr>
<tr>
                            <td height="5"></td>
                          </tr>
                          <tr>
                            <td style="font-size:13px; color:#656565; line-height:18px; padding-bottom:10px;">To ensure, we have your email address captured correctly, please verify your email address by clicking on the following link:</td>
                          </tr>
                          <tr>
                            <td height="5"><a target="_blank" href="'.$link.'">Verify Email</a></td>
                          </tr>
                          <tr>
                            <td height="15"></td>
                          </tr>';  
                          
                         return Yii::$app->mailer->compose(['html' => 'layouts/mail'], ['content' => $message, 'subject' => $subject])
						->setTo($data['email'])
						->setFrom([$adminEmail => 'Palcura' ])
						->setSubject($subject)
						->setTextBody($message)
						->send();
           }               
		
    }
    
    

	/**
	* @ Function Name		: _sendUserRegistrationEmailToAdmin
	* @ Function Params		: 
	* @ Function Purpose 	: email to admin
	* @ Function Returns	: boolean true/false
	*/
    public function _sendUserRegistrationEmailToAdmin($data) {
		$adminEmail	= Yii::$app->commonmethod->getAdminEmailID();
		$subject  ="User Registration"; 
		$message  ='';
		$message .='<tr>';
			$message .='<td height="5" style="font-size:13px; color:#656565; line-height:5px; padding-bottom:10px;">Hi Admin,</td>';
		$message .='</tr><tr>
                            <td height="5"></td>
                          </tr>';
		$message .='<tr>';
			$message .='<td style="font-size:13px; color:#656565; line-height:18px; padding-bottom:10px;">A new user has registered on website. Below are the details:</td>';
		$message .='</tr>';
		$message .='<tr>';
			$message .='<td height="5"></td>';
		$message .='</tr>';
		$message .='<tr>';
			$message .='<td align="left">';
				$message .='<table width="100%" border="0" bgcolor="#656565" cellspacing="1" cellpadding="6" style=" color:#656565;">';
					$message .='<tr bgcolor="#ff8447">';
						$message .='<td colspan="2" style="border-top:#203367 solid 0px; font-size:14px; font-weight:400; color:#ffffff; text-transform:capitalize; padding:8px;">User information</td>';
					$message .='</tr>';
					$message .='<tr  bgcolor="#ffffff">';
						$message .='<td>Firstname</td>';
						$message .='<td>' . @$data['firstname'] . '</td>';
					$message .='</tr>';
					$message .='<tr  bgcolor="#ffffff">';
						$message .='<td>Lastname</td>';
						$message .='<td >' . @$data['lastname'] . '</td>';
					$message .='</tr>';	
					$message .='<tr  bgcolor="#ffffff">';
						$message .='<td>Email</td>';
						$message .='<td>' . @$data['email'] . '</td>';
					$message .='</tr>';										
				$message .='</table>';
			$message .='</td>';
		$message .='</tr>';
		$message .='<tr>';
			$message .='<td height="15"></td>';
		$message .='</tr>';
		return Yii::$app->mailer->compose(['html' => 'layouts/mail'], ['content' => $message, 'subject' => $subject])
						->setTo($adminEmail)
						->setFrom([$adminEmail => 'Palcura' ])
						->setSubject($subject)
						->setTextBody($message)
						->send();
    }
    
	/**
	* @ Function Name		: actionVerifyemail
	* @ Function Params	: 
	* @ Function Purpose 	: verify email
	* @ Function Returns	: render view
	*/ 
	public function actionVerifyemail() {
		//sleep(5);
		$request = Yii::$app->request;
		$uniqToken  = $request->get();
		
	if(isset($uniqToken['id']) && !empty($uniqToken['id'])) {
			$query = new Query;
			$query->select('user_id')->from('userverification')->where('token = "'.$uniqToken['id'].'" AND status = "0"');   
			$token = $query->createCommand()->queryOne();
			if( isset($token['user_id']) && $token['user_id'] > 0 ) {
				
				$selectColA = "user_type,firstname,lastname,email,renting_pet";
				$userInfoA	= Yii::$app->commonmethod->getUserColumnsData($token['user_id'],$selectColA);
				if(isset($userInfoA['user_type']) && $userInfoA['user_type'] == SITTER) {
					Yii::$app->db->createCommand()->update('userverification', ['status' => '1'], 'user_id = '.$token['user_id'])->execute();
					Yii::$app->db->createCommand()->update('user', ['status' => '1'], 'id = '.$token['user_id'])->execute();
					
					
							$email = $userInfoA['email'];
							$list_id = '10f7c53647';
							$api_key = 'f1f9682348cebeff5f49e9ca83fa1b38-us18';
							$data_center = substr($api_key,strpos($api_key,'-')+1);
							$url = 'https://'. $data_center .'.api.mailchimp.com/3.0/lists/'. $list_id .'/members';
							$json = json_encode([
								'email_address' =>$email,
								'status'=> 'subscribed',//pass 'subscribed' or 'pending'
								'merge_fields'=>[
								'FNAME'=> $userInfoA['firstname'],
								'LNAME'=> $userInfoA['lastname'],
							//	'ADDRESS'=>$userInfoA['address'],
								'PHONE'=> $userInfoA['phone'],
								'MMERGE5'=>'Application Not Submitted'],
								
							]);	

							$ch = curl_init($url);
							curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $api_key);
							curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
							curl_setopt($ch, CURLOPT_TIMEOUT, 10);
							curl_setopt($ch, CURLOPT_POST, 1);
							curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
							curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
							$result = curl_exec($ch);
					
							Yii::$app->session->setFlash('item', Yii::t('yii','Thank you! You have successfully verified your email address. Login to your account.'));					
								
							$this->verificationmailtositter($userInfoA);
							
					
				
					//Yii::$app->session->setFlash('item', Yii::t('yii','Thank you for the sign up. We are currently reviewing your profile and will send you a confirmation email on the verification badge.'));
					
				} else {
					####= update status user and userverification =####
					$query->select('COUNT(id) as cnt')->from('user')->where('id = '.$token['user_id']);   
					$customer = $query->createCommand()->queryOne();
					if(isset($customer['cnt']) && $customer['cnt'] > 0) {
					Yii::$app->db->createCommand()->update('user', ['status' => '1'], 'id = '.$token['user_id'])->execute();
					if(isset($userInfoA['user_type']) && $userInfoA['user_type'] == OWNER){
					
					$this->verificationmailtoowner($userInfoA);
					}else{
					
					$this->verificationmailtoborrower($userInfoA);
					}
					
						
					}
					Yii::$app->db->createCommand()->update('userverification', ['status' => '1'], 'user_id = '.$token['user_id'])->execute();
					Yii::$app->session->setFlash('item', Yii::t('yii','Thank you! You have successfully verified your email address. Login to your account.'));
				}
				return $this->redirect( Url::to(['site/login']) );			
			}
		}
		Yii::$app->session->setFlash('item', Yii::t('yii','Your link has expired.'));
		return $this->redirect( Url::to(['site/login']) );
	}
	
	public function actionContact(){

	\Yii::$app->view->title = 'Contact Us | Palcura ';
    	 \Yii::$app->view->registerMetaTag([
        'name' => 'description',
        'content' => 'Contact us with any questions or comments. At Palcura we are dedicated to connecting loving pet parents with loving, trusted pet sitters.'
    ]);	

	$model = new Contactus();
	if ($model->load(Yii::$app->request->post()) && $model->validate()) { 
		$postData = Yii::$app->request->post();
		if($model->saveContactInfo()){
		Yii::$app->session->setFlash('success', Yii::t('yii','Thanks for contacting us!.'));
		$this->_sendContactusEmailToAdmin($postData);
		return $this->redirect(['site/contact']);			
		}else{
		Yii::$app->session->setFlash('error', Yii::t('yii','Contact message was not sent. Please try again!.'));
		return $this->redirect(['site/contact']);	
		}
	}	
	return $this->render('contactus', [
            'model' => $model,
        ]);
		
	}
	
	public function actionNearme(){
    
   
	\Yii::$app->view->title = 'Request Palcura Near Me | Palcura ';
    	 \Yii::$app->view->registerMetaTag([
        'name' => 'description',
        'content' => 'Request Palcura Near Me us with any questions or comments. At Palcura we are dedicated to connecting loving pet parents with loving, trusted pet sitters.'
    ]);	

	$model = new Contactus1();
	
	if ($model->load(Yii::$app->request->post()) && $model->validate()) { 
		$postData = Yii::$app->request->post();
		if($model->saveContactInfo()){
			
		Yii::$app->session->setFlash('success', Yii::t('yii','Thanks so much for your inquiry. We will let you know when Palcura becomes available in your area.'));
		$this->_sendContactusEmailToAdmin1($postData);
		return $this->redirect(['/near-me']);			
		}else{
		Yii::$app->session->setFlash('error', Yii::t('yii','Contact message was not sent. Please try again!.'));
		return $this->redirect(['/near-me']);	
		}
	}
	
	return $this->render('contactus1', [
            'model' => $model,
        ]);
	}
	public function _sendContactusEmailToAdmin1($data) {
		$adminEmail	= Yii::$app->commonmethod->getAdminEmailID();
		$subjectoptions = Yii::$app->commonmethod->getFormType1();
		$contactsubject = $subjectoptions[$data['Contactus1']['subject']];
		$fromEmail = $data['Contactus1']['email'];
		$subject  ="New message received"; 
		$message  ='';
		$message .='<tr>';
			$message .='<td height="5" style="font-size:13px; color:#656565; line-height:5px; padding-bottom:10px;">Hi Admin,</td>';
		$message .='</tr><tr>
                            <td height="5"></td>
                          </tr>';
		$message .='<tr>';
			$message .='<td style="font-size:13px; color:#656565; line-height:18px; padding-bottom:10px;">A new contact message is recieved. Below are the details:</td>';
		$message .='</tr>';
		$message .='<tr>';
			$message .='<td height="5"></td>';
		$message .='</tr>';
		$message .='<tr>';
			$message .='<td align="left">';
				$message .='<table width="100%" border="0" bgcolor="#656565" cellspacing="1" cellpadding="6" style=" color:#656565;">';
					$message .='<tr bgcolor="#ff8447">';
						$message .='<td colspan="2" style="border-top:#203367 solid 0px; font-size:14px; font-weight:400; color:#ffffff; text-transform:capitalize; padding:8px;">User information</td>';
					$message .='</tr>';
					$message .='<tr  bgcolor="#ffffff">';
						$message .='<td>Name</td>';
						$message .='<td>' . @$data['Contactus1']['name'] . '</td>';
					$message .='</tr>';
					
					$message .='<tr  bgcolor="#ffffff">';
						$message .='<td>Email</td>';
						$message .='<td>' . @$data['Contactus1']['email'] . '</td>';
					$message .='</tr>';
					$message .='<tr  bgcolor="#ffffff">';
						$message .='<td>Phone</td>';
						$message .='<td >' . @$data['Contactus1']['phone'] . '</td>';
					$message .='</tr>';	
					$message .='<tr  bgcolor="#ffffff">';
						$message .='<td>I am a</td>';
						$message .='<td >' . @$contactsubject . '</td>';
					$message .='</tr>';	
									
				$message .='</table>';
			$message .='</td>';
		$message .='</tr>';
		$message .='<tr>';
			$message .='<td height="15"></td>';
		$message .='</tr>';
		return Yii::$app->mailer->compose(['html' => 'layouts/mail'], ['content' => $message, 'subject' => $subject])
						->setTo($adminEmail)
						->setFrom([$fromEmail => $data['Contactus1']['name'] ])
						->setSubject($subject)
						->setTextBody($message)
						->send();
    }
	public function verificationmailtoborrower($userInfoA){
	$adminEmail	= Yii::$app->commonmethod->getAdminEmailID();
	$useremail = $userInfoA['email'];
	$subject  = "Welcome! You are almost there…"; 
	
	 	$message = '';
        $message .= '<tr>';
        $message .= '<td height="5" style="font-size:13px; color:#656565; line-height:5px; padding-bottom:10px;">Hi ' . $userInfoA['firstname'] . ',</td>';
        $message .= '</tr><tr>
                            <td height="5"></td>
                          </tr>';
        $message .= '<tr>';
        $message .= '<td style="font-size:13px; color:#656565; line-height:15px; padding-bottom:10px;">We are stoked to have you as a part of PalCura and are excited on you taking the first step towards the wonderful world of pets!</td>';
        $message .= '</tr>';     
        $message .= '
					<tr ><td align="left"><table width="500" border="0" bgcolor="#656565" cellspacing="1" cellpadding="6" style=" color:#656565;">													
					  </table></td>
					  
					</tr>
						<tr  style="color:#656565; font-size:13px; line-height:19px;">
                            <td>To help you get started on your search, please click <a href="' . SITE_URL . 'users/settings" target="_blank">here</a> to access your account and complete your profile. We will notify you as soon as your account is verified.
                          
							</td>
                          </tr>
                           <tr>
                            <td height="15"></td>
                          </tr>
                          ';
	         
             return Yii::$app->mailer->compose(['html' => 'layouts/mail'], ['content' => $message, 'subject' => $subject])
			->setTo($useremail)
			->setFrom([$adminEmail => 'Palcura' ])
			->setSubject($subject)
			->setTextBody($message)
			->send();
	
	}
	
	public function verificationmailtositter($userInfoA){
	$adminEmail	= Yii::$app->commonmethod->getAdminEmailID();
	$useremail = $userInfoA['email'];
	$subject  = "Welcome! You are almost there…"; 
	
	 	$message = '';
        $message .= '<tr>';
        $message .= '<td height="5" style="font-size:13px; color:#656565; line-height:5px; padding-bottom:10px;">Hi ' . $userInfoA['firstname'] . ',</td>';
        $message .= '</tr><tr>
                            <td height="5"></td>
                          </tr>';
        $message .= '<tr>';
        $message .= '<td style="font-size:13px; color:#656565; line-height:18px; padding-bottom:10px;">We are stoked to have you as a part of PalCura. We would just need a <strong>few more details</strong> to help us verify your account and get you started as a pet sitter!</td>';
        $message .= '</tr>';
        $message .= '<tr>';
        $message .= '<td height="5"></td>';
        $message .= '</tr>  
					<tr ><td align="left"><table width="500" border="0" bgcolor="#656565" cellspacing="1" cellpadding="6" style=" color:#656565;">													
					  </table></td>
					  
					</tr>
						<tr  style="color:#656565; font-size:13px; line-height:19px;">
                            <td>Please click <a href="'.SITE_URL.'users/settings" target="_blank">here</a> to access your account and fill in the additional information. We will notify you as soon as your account is verified.
							
						</td>
                          </tr>
                           <tr>
                            <td height="15"></td>
                          </tr>
                          ';
	         
             return Yii::$app->mailer->compose(['html' => 'layouts/mail'], ['content' => $message, 'subject' => $subject])
			->setTo($useremail)
			->setFrom([$adminEmail => 'Palcura' ])
			->setSubject($subject)
			->setTextBody($message)
			->send();
	
	}
	
	
	public function verificationmailtoowner($userInfoA){
	$adminEmail	= Yii::$app->commonmethod->getAdminEmailID();
	$useremail = $userInfoA['email'];
	$renting_pet = $userInfoA['renting_pet'];
	if($renting_pet == 1){
		
	$subject  = "Welcome! You are almost there…"; 
	
	 	$message = '';
        $message .= '<tr>';
        $message .= '<td height="5" style="font-size:13px; color:#656565; line-height:5px; padding-bottom:10px;">Hi ' . $userInfoA['firstname'] . ',</td>';
        $message .= '</tr>';
$message .= '<tr>
                            <td height="5"></td>
                          </tr>';
        $message .= '<tr>';
        $message .= '<td style="font-size:13px; color:#656565; line-height:18px; padding-bottom:10px;">We are stoked to have you as a part of PalCura and excited on your participation in the pet borrowing program! </td>';
       
        $message .= '
						<tr  style="color:#656565; font-size:13px; line-height:19px;">
                            <td>To ensure we can list you in the top search results for the pet borrowers, please complete your profile <a href="' . SITE_URL . 'users/settings" target="_blank">here</a>. 
                          </td>
                          </tr>
                          <tr>
                            <td height="15"></td>
                          </tr>
                          ';
	}else{

	$subject  = "Welcome to PalCura!"; 
		$message  = '';		                         
               $message .= '<tr>
                            <td height="5" style="font-size:13px; color:#656565; line-height:5px; padding-bottom:10px;">Hi '.$userInfoA['firstname'].',</td>
                          </tr>
<tr>
                            <td height="5"></td>
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
								
									<li><span style="font-style:italic; font-weight:400; line-height:30px;">1. Sign in at <a href="'.SITE_URL.'">www.palcura.com</a> and search for nearby pet care providers</span></li>
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
            return Yii::$app->mailer->compose(['html' => 'layouts/mail'], ['content' => $message, 'subject' => $subject])
			->setTo($useremail)
			->setFrom([$adminEmail => 'Palcura' ])
			->setSubject($subject)
			->setTextBody($message)
			->send();
		
	}
	
	/**
	* @ Function Name		: _sendContactusEmailToAdmin
	* @ Function Params		: 
	* @ Function Purpose 	: email to admin
	* @ Function Returns	: boolean true/false
	*/
    public function _sendContactusEmailToAdmin($data) {
		$adminEmail	= Yii::$app->commonmethod->getAdminEmailID();
		$subjectoptions = Yii::$app->commonmethod->getFormType();
		$contactsubject = $subjectoptions[$data['Contactus']['subject']];
		$fromEmail = $data['Contactus']['email'];
		$subject  ="New message received"; 
		$message  ='';
		$message .='<tr>';
			$message .='<td height="5" style="font-size:13px; color:#656565; line-height:5px; padding-bottom:10px;">Hi Admin,</td>';
		$message .='</tr><tr>
                            <td height="5"></td>
                          </tr>';
		$message .='<tr>';
			$message .='<td style="font-size:13px; color:#656565; line-height:18px; padding-bottom:10px;">A new contact message is recieved. Below are the details:</td>';
		$message .='</tr>';
		$message .='<tr>';
			$message .='<td height="5"></td>';
		$message .='</tr>';
		$message .='<tr>';
			$message .='<td align="left">';
				$message .='<table width="100%" border="0" bgcolor="#656565" cellspacing="1" cellpadding="6" style=" color:#656565;">';
					$message .='<tr bgcolor="#ff8447">';
						$message .='<td colspan="2" style="border-top:#203367 solid 0px; font-size:14px; font-weight:400; color:#ffffff; text-transform:capitalize; padding:8px;">User information</td>';
					$message .='</tr>';
					$message .='<tr  bgcolor="#ffffff">';
						$message .='<td>Name</td>';
						$message .='<td>' . @$data['Contactus']['name'] . '</td>';
					$message .='</tr>';
					
					$message .='<tr  bgcolor="#ffffff">';
						$message .='<td>Email</td>';
						$message .='<td>' . @$data['Contactus']['email'] . '</td>';
					$message .='</tr>';
					$message .='<tr  bgcolor="#ffffff">';
						$message .='<td>Phone</td>';
						$message .='<td >' . @$data['Contactus']['phone'] . '</td>';
					$message .='</tr>';	
					$message .='<tr  bgcolor="#ffffff">';
						$message .='<td>Subject</td>';
						$message .='<td >' . @$contactsubject . '</td>';
					$message .='</tr>';	
					$message .='<tr  bgcolor="#ffffff">';
						$message .='<td>Message</td>';
						$message .='<td >' . @$data['Contactus']['description'] . '</td>';
					$message .='</tr>';						
				$message .='</table>';
			$message .='</td>';
		$message .='</tr>';
		$message .='<tr>';
			$message .='<td height="15"></td>';
		$message .='</tr>';
		return Yii::$app->mailer->compose(['html' => 'layouts/mail'], ['content' => $message, 'subject' => $subject])
						->setTo($adminEmail)
						->setFrom([$fromEmail => $data['Contactus']['name'] ])
						->setSubject($subject)
						->setTextBody($message)
						->send();
    }

    public function gethomeblocks($id) {
		$siteimage 			= Yii::getAlias('@siteimage');
		$pageContent1 		= \backend\models\page\page::find()->where(['slug'=> $id])->one();
		if(isset($pageContent1->pageContent) && !empty($pageContent1->pageContent)) {
			$p_content1   	= (isset($pageContent1->pageContent) ? $pageContent1->pageContent : '');    
			$p_content1 	= str_ireplace('../../..//admin/', SITE_URL.'admin/', $p_content1);
			$p_content1 	= str_ireplace('../../admin/', SITE_URL.'admin/', $p_content1);
			$p_content1 	= str_ireplace('../../..//', SITE_URL.'admin/', $p_content1);
			$p_content1 	= str_ireplace('../../', SITE_URL.'admin/', $p_content1);
			$pageBlock01	= str_replace("images/",$siteimage.'/',$p_content1);
			return $pageBlock01;
		}
	}

public function actionDemo() {

		$link		=	SITE_URL."site/signup";
		$adminEmail	= 'puneet@webworldexpertsindia.com';//Yii::$app->commonmethod->getAdminEmailID();
		$fromemail	= 'hello@palcura.com';
		$subject  = "Congratulations! The personality profile quiz has been successfully completed."; 
		$message  = '';
		$message .='<tr>';
			$message .='<td height="26" style="font-size:15px; font-weight:500; color:#2c2c2c;">Dear user,</td>';
		$message .='</tr>';
		$message .='<tr>';
			$message .='<td style="font-size:13px; color:#656565; line-height:18px; padding-bottom:10px;">Congratulations! The personality profile quiz has been successfully completed. Please click <a target="_blank" href="'.$link.'">here</a> to register with smartmatch420. Below are result details:</td>';
		$message .='</tr>';
		$message .='<tr>';
			$message .='<td height="5"></td>';
		$message .='</tr>';
		$message .='<tr>';
			$message .='<td align="left">';
				$message .='<table width="100%" border="0" bgcolor="#656565" cellspacing="1" cellpadding="6" style=" color:#656565;">';
					$message .='<tr bgcolor="#62b023">';
						$message .='<td colspan="2" style="border-top:#203367 solid 0px; font-size:14px; font-weight:400; color:#ffffff; text-transform:capitalize;padding:8px;">Result Information</td>';
					$message .='</tr>';
					$message .='<tr  bgcolor="#ffffff">';
						$message .='<td>Result score</td>';
						$message .='<td>gffdgfd</td>';
					$message .='</tr>';
				$message .='</table>';
			$message .='</td>';
		$message .='</tr>';
		$message .='<tr>';
			$message .='<td height="15"></td>';
		$message .='</tr>';

		$aee = Yii::$app->mailer->compose(['html' => 'layouts/mail'], ['content' => $message, 'subject' => $subject])
						->setTo("narinder@webworldexpertsindia.com")
						->setFrom([$fromemail => 'Palcura' ])
						->setSubject($subject)
						->setTextBody($message)
						->send();	
						
		/* $to_email = 'rajbir@webworldexpertsindia.com';
		$subject = 'Testing PHP Mail';
		$message = 'This mail is sent using the PHP mail function';
		$headers = 'From: rajbiraujla22@gmail.com';
		$a = mail($to_email,$subject,$message,$headers); */			
//echo 'adsfsf';		
		print_r($aee); 
	}
}
