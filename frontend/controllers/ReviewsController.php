<?php
namespace frontend\controllers;
use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Controller;
use yii\helpers\Url;
use frontend\models\FeedbackRating;
use yii\data\Pagination;
use yii\db\Query;
use common\models\User;

class ReviewsController extends Controller {
	private $limit = 10;
	/**
	* @ Function Name		: actionIndex
	* @ Function Params		: NA 
	* @ Function Purpose 	: default index function that will be called to display reviews
	* @ Function Returns	: render view
	*/
    public function actionIndex() {
		 $userId 	= Yii::$app->user->getId();
         $dataArray = array();
         $query = new Query;
         $query->select('feedback_rating.*,us.firstname as fname,us.lastname as lname,us.profile_image')
         ->from('feedback_rating')
         ->join('LEFT JOIN', 'user us', 'us.id = feedback_rating.sender_userid')
         ->where('feedback_rating.receiver_userid = '.$userId.' ORDER BY feedback_rating.id DESC');
         $countQuery = clone $query;
         $pages 	 = new Pagination(['totalCount' => $countQuery->count()]);
         $pages->setPageSize($this->limit);
		 $query->offset($pages->offset)->limit($this->limit);         
         $bookingFeedback = $query->createCommand()->queryAll();

         $dataArray = array_merge($dataArray, [            
              'reviews' 	=> $bookingFeedback,
              'pages' 				=> $pages,
         ]);
        return $this->render('reviews',$dataArray);
    }

	/**
	* @ Function Name		: actionIndex
	* @ Function Params		: NA 
	* @ Function Purpose 	: default index function that will be called to display reviews
	* @ Function Returns	: render view
	*/
    public function actionUserRatings($userId=0) { 
		$userId = Yii::$app->request->get('id');
		if($userId == 0)
			return $this->redirect(['reviews/index']);
			
         $dataArray = array();
         $query = new Query;
         $query->select('feedback_rating.*,us.firstname as fname,us.lastname as lname')
         ->from('feedback_rating')
         ->join('LEFT JOIN', 'user us', 'us.id = feedback_rating.sender_userid')
         ->where('feedback_rating.receiver_userid = '.$userId);
         $countQuery = clone $query;
         $pages 	 = new Pagination(['totalCount' => $countQuery->count()]);
         $pages->setPageSize($this->limit);
$query->orderBy(['feedback_rating.date_time' => SORT_DESC]);
		 $query->offset($pages->offset)->limit($this->limit);         
         $bookingFeedback = $query->createCommand()->queryAll();

         $dataArray = array_merge($dataArray, [            
              'reviews' 	=> $bookingFeedback,
              'pages' 				=> $pages,
         ]);
        return $this->render('user-reviews',$dataArray);
    }
    
	/*public function actionAddreview(){	  
	 $model = new FeedbackRating();	
	 if ($model->load(Yii::$app->request->post()) && $model->validate()) { 
		$postData = Yii::$app->request->post();
		$bookingid = $postData['booking_id'];
		$pet_sitter_id = $postData['pet_sitter_id'];
		$pet_owner_id = $postData['pet_owner_id'];
		$pet_renter_id = $postData['pet_renter_id'];
		$starrating = $postData['FeedbackRating']['starrating'];
		$comment = $postData['FeedbackRating']['comment'];
		$session 		= Yii::$app->session;
		$logged_user 	= $session->get('loggedinusertype');
		if($logged_user == 3){
		$reviewfor = $pet_owner_id;
		}elseif($logged_user == 1){
		$reviewfor = $pet_sitter_id;
		}
		$model->receiver_userid = $reviewfor;
		$model->booking_id = $bookingid;
$model->comment = $comment;
		$model->starrating = $starrating;
		
		$model->addReview();
		$this->sendtreviewemail($reviewfor,$starrating,$comment);
	   	Yii::$app->session->setFlash('success', Yii::t('yii','Thanks for submitting your review!.'));
	   	return $this->redirect(['bookings/index']);    
		}else{
		Yii::$app->session->setFlash('error', Yii::t('yii','Review was not submitted!.'));
	   	return $this->redirect(['bookings/index']);   
		}
	  	   
	  }*/

 public function actionAddreview(){
		  
	 	$model = new FeedbackRating();
	 	$postData = Yii::$app->request->post();
	 
	 	$bookingid = $postData['thisid'];
		$pet_sitter_id = $postData['sitid'];
		$pet_owner_id = $postData['ownid'];
		$pet_renter_id = $postData['rentid'];
		$starrating = $postData['rev'];
		$comment = $postData['descript'];
		
		//verify bookingid with current user
		$userId = Yii::$app->user->identity->id;
	
		$verifybookinguser	= Yii::$app->commonmethod->verifyBookinguser($userId,$bookingid);
		
	 	if ($verifybookinguser) { 
		
		$session 		= Yii::$app->session;
		$logged_user 	= $session->get('loggedinusertype');
		
		if($logged_user == 3){
		$reviewfor = $pet_owner_id;
		}elseif($logged_user == 1){
		$reviewfor = $pet_sitter_id;
		}elseif($logged_user == 2){
		Yii::$app->session->setFlash('success', Yii::t('yii','switch to owner or borrower account for submitting your review!.'));
		return $this->redirect(['bookings/index']);    
		}
		$model->receiver_userid = $reviewfor;
		$model->booking_id = $bookingid;
		$model->comment = $comment;
		$model->starrating = $starrating;
		
		$model->addReview();
		$this->sendtreviewemail($reviewfor,$starrating,$comment);
	   	Yii::$app->session->setFlash('success', Yii::t('yii','Thanks for submitting your review!.'));
	  	 
	   	return $this->redirect(['bookings/index']);    
		}else{ 
		Yii::$app->session->setFlash('error', Yii::t('yii','Review was not submitted!.'));
		
	   	return $this->redirect(['bookings/index']);   
		}
	  	   
	  }
	  
	public function sendtreviewemail($recieverid,$starrating,$comment){
    
    $adminEmail		= Yii::$app->commonmethod->getAdminEmailID();
    $senderfirstname  = Yii::$app->user->identity->firstname;
    $senderlastname  = Yii::$app->user->identity->lastname;
    $sendername = $senderfirstname;
	
    $recieverdata = User::findOne($recieverid);    
    $recievername = $recieverdata->firstname;
   	$recieveremail =  $recieverdata->email; 
      
    $subject  ='You have a new review from '.$sendername.'';
		$message  ='';							
		$message .= '<tr>
                            <td height="5" style="font-size:13px; color:#656565; line-height:5px; padding-bottom:10px;">Hi '.$recievername.',</td>
                          </tr>
                          <tr>
                            <td style="font-size:13px; color:#656565; line-height:13px; padding-bottom:10px;">You have a new review! Access your account <a href="'.SITE_URL.'users/dashboard" target="_blank">here</a> and go to ‘Reviews’ to see the new updates.</td>
                          </tr>';
		$mail = Yii::$app->mailer->compose(['html' => 'layouts/mail'],['content' => $message, 'subject' => $subject])
		->setFrom([$adminEmail=>'Palcura'])
		->setTo($recieveremail)
		->setSubject($subject)
		->send();
		return $mail;
    
    
    }
}
