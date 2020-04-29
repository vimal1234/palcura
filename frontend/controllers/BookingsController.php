<?php

namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Controller;
use yii\helpers\Url;
use frontend\models\users\Users;
use frontend\models\Booking;
use frontend\models\Activity;
use frontend\models\Refund;
use frontend\models\Connectadmin;
use frontend\models\FeedbackRating;
use common\models\User;
use yii\data\Pagination;
use yii\db\Query;
use frontend\models\common\BookingDiscount;
use frontend\models\Vaccinationdetails;
use yii\web\UploadedFile;
use frontend\models\Uploads;
use frontend\models\UserPet;
use frontend\models\BookingCareNote;
use yii\base\ErrorException;


class BookingsController extends Controller {

    private $limit = 10;

    /**
     * @ Function Name		: actionIndex
     * @ Function Params		: NA 
     * @ Function Purpose 	: default index function that will be called to display bookings
     * @ Function Returns	: render view
     * 
     * 
     */


    public function beforeAction($action) {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    public function actionIndex() {

		
		
        $this->limit = 10;
        $userId = Yii::$app->user->getId();
        $dataArray = array();
        $ConnectadminModel = new Connectadmin();
        $model = new FeedbackRating();
        $session = Yii::$app->session;

        $usertype = $session->get('loggedinusertype');

              
       
        if ($ConnectadminModel->load(Yii::$app->request->post()) && $ConnectadminModel->validate()) {
            $postData = Yii::$app->request->post();
          
            $messagedesc = $postData['Connectadmin']['description'];
            $messagetitle = $postData['Connectadmin']['title'];
            $form_type = $postData['Connectadmin']['form_type'];
            $formtype = Yii::$app->commonmethod->getFormType();
            $messagetype = $formtype[$form_type];

            $booking_id = $postData['booking_id'];
            $ConnectadminModel->booking_id = $booking_id;
            if ($ConnectadminModel->savedata()) {
                $this->sendtalktoadminemail($booking_id, $messagedesc, $messagetitle, $messagetype);
                Yii::$app->session->setFlash('success', 'Your message is received by Palcura. We will get back to you within 24 hours');
                return $this->redirect(['bookings/index']);
            } else {
                Yii::$app->session->setFlash('error', 'server error');
                return $this->redirect(['bookings/index']);
            }
        }

        if ($usertype == OWNER) {
            $where = "(booking.pet_owner_id = " . $userId . " OR booking.pet_sitter_id = " . $userId . ") AND booking.payment_status != '2'";
        } else if ($usertype == SITTER) {
            $where = "(booking.pet_sitter_id = " . $userId . " OR booking.pet_owner_id = " . $userId . ") AND booking.payment_status != '2'";
        } else if ($usertype == RENTER) {
            $where = "(booking.pet_renter_id = " . $userId . " OR booking.pet_owner_id = " . $userId . ") AND booking.payment_status != '2'";
        } else {
      
            $this->redirect(['site/home']);
        }
      
        $compareDate = date('Y-m-d');
        #################= Current Booking Histroy =###############
        $query = new Query;
        $query->select('booking.*,owner.firstname as o_fname,owner.lastname as o_lname,sitter.firstname as s_fname,sitter.lastname as s_lname,renter.firstname as r_fname,renter.lastname as r_lname,o_cns.name as o_country,o_ct.name as o_city,s_cns.name as s_country,s_ct.name as s_city,r_cns.name as r_country,r_ct.name as r_city,owner.zip_code as o_zip_code,sitter.zip_code as s_zip_code,renter.zip_code as r_zip_code,owner.profile_image as o_pimage,sitter.profile_image as s_pimage,renter.profile_image as r_pimage')
                ->from('booking')
                ->join('LEFT JOIN', 'user owner', 'owner.id = booking.pet_owner_id')
                ->join('LEFT JOIN', 'user sitter', 'sitter.id = booking.pet_sitter_id')
                ->join('LEFT JOIN', 'user renter', 'renter.id = booking.pet_renter_id')
                ->join('LEFT JOIN', 'countries as o_cns', 'owner.country = o_cns.id')
                ->join('LEFT JOIN', 'cities as o_ct', 'owner.city = o_ct.id')
                ->join('LEFT JOIN', 'countries as s_cns', 'sitter.country = s_cns.id')
                ->join('LEFT JOIN', 'cities as s_ct', 'sitter.city = s_ct.id')
                ->join('LEFT JOIN', 'countries as r_cns', 'renter.country = r_cns.id')
                ->join('LEFT JOIN', 'cities as r_ct', 'renter.city = r_ct.id')
                ->where($where . ' AND booking.completed = 0 AND booking.cancelled_by = "0" AND booking_to_date >= DATE("' . $compareDate . '")');
        $countQuery = clone $query;
        $pagesA = new Pagination(['totalCount' => $countQuery->count()]);
        $pagesA->setPageSize($this->limit);
        $query->offset($pagesA->offset)->orderBy('booking.id DESC')->limit($this->limit);
        $current_bookings = $query->createCommand()->queryAll();
        // echo $query->createCommand()->getRawSql();die;
        #################= Post Booking History =###############         
        $query = new Query;
        $query->select('booking.*,owner.firstname as o_fname,owner.lastname as o_lname,sitter.firstname as s_fname,sitter.lastname as s_lname,renter.firstname as r_fname,renter.lastname as r_lname,o_cns.name as o_country,o_ct.name as o_city,s_cns.name as s_country,s_ct.name as s_city,r_cns.name as r_country,r_ct.name as r_city,owner.zip_code as o_zip_code,sitter.zip_code as s_zip_code,renter.zip_code as r_zip_code,owner.profile_image as o_pimage,sitter.profile_image as s_pimage,renter.profile_image as r_pimage')
                ->from('booking')
                ->join('LEFT JOIN', 'user owner', 'owner.id = booking.pet_owner_id')
                ->join('LEFT JOIN', 'user sitter', 'sitter.id = booking.pet_sitter_id')
                ->join('LEFT JOIN', 'user renter', 'renter.id = booking.pet_renter_id')
                ->join('LEFT JOIN', 'countries as o_cns', 'owner.country = o_cns.id')
                ->join('LEFT JOIN', 'cities as o_ct', 'owner.city = o_ct.id')
                ->join('LEFT JOIN', 'countries as s_cns', 'sitter.country = s_cns.id')
                ->join('LEFT JOIN', 'cities as s_ct', 'sitter.city = s_ct.id')
                ->join('LEFT JOIN', 'countries as r_cns', 'renter.country = r_cns.id')
                ->join('LEFT JOIN', 'cities as r_ct', 'renter.city = r_ct.id')
                ->where($where . ' AND booking_to_date < DATE("' . $compareDate . '") AND booking.cancelled_by = "0"');
        $countQuery = clone $query;
        $pagesB = new Pagination(['totalCount' => $countQuery->count()]);
        $pagesB->setPageSize($this->limit);
        $query->offset($pagesB->offset)->orderBy('booking.id DESC')->limit($this->limit);
        $completed_bookings = $query->createCommand()->queryAll();

        #################= Declined Booking History=###############
        $query = new Query;
        $query->select('booking.*,owner.firstname as o_fname,owner.lastname as o_lname,sitter.firstname as s_fname,sitter.lastname as s_lname,renter.firstname as r_fname,renter.lastname as r_lname,o_cns.name as o_country,o_ct.name as o_city,s_cns.name as s_country,s_ct.name as s_city,r_cns.name as r_country,r_ct.name as r_city,owner.zip_code as o_zip_code,sitter.zip_code as s_zip_code,renter.zip_code as r_zip_code,owner.profile_image as o_pimage,sitter.profile_image as s_pimage,renter.profile_image as r_pimage')
                ->from('booking')
                ->join('LEFT JOIN', 'user owner', 'owner.id = booking.pet_owner_id')
                ->join('LEFT JOIN', 'user sitter', 'sitter.id = booking.pet_sitter_id')
                ->join('LEFT JOIN', 'user renter', 'renter.id = booking.pet_renter_id')
                ->join('LEFT JOIN', 'countries as o_cns', 'owner.country = o_cns.id')
                ->join('LEFT JOIN', 'cities as o_ct', 'owner.city = o_ct.id')
                ->join('LEFT JOIN', 'countries as s_cns', 'sitter.country = s_cns.id')
                ->join('LEFT JOIN', 'cities as s_ct', 'sitter.city = s_ct.id')
                ->join('LEFT JOIN', 'countries as r_cns', 'renter.country = r_cns.id')
                ->join('LEFT JOIN', 'cities as r_ct', 'renter.city = r_ct.id')
                ->where($where . ' AND booking.cancelled_by != "0"');
        $countQuery = clone $query;
        $pagesC = new Pagination(['totalCount' => $countQuery->count()]);
        $pagesC->setPageSize($this->limit);
        $query->offset($pagesC->offset)->orderBy('booking.id DESC')->limit($this->limit);
        $declined_bookings = $query->createCommand()->queryAll();
       // echo'<pre>'; print_r($completed_bookings); exit();
        $dataArray = array_merge($dataArray, [
            'current_bookings' => $current_bookings,
            'completed_bookings' => $completed_bookings,
            'declined_bookings' => $declined_bookings,
            'pagesA' => $pagesA,
            'pagesB' => $pagesB,
            'pagesC' => $pagesC,
            'ConnectadminModel' => $ConnectadminModel,
            'model' => $model,
        ]);
		
        return $this->render('bookings', $dataArray);
    }

    public function sendtalktoadminemail($booking_id, $messagedesc, $messagetitle, $messagetype) {
        $booking = \backend\models\booking\Booking::findOne($booking_id);

        $senderfirstname = Yii::$app->user->identity->firstname;
        $senderlastname = Yii::$app->user->identity->lastname;
        $sendername = $senderfirstname . ' ' . $senderlastname;
        $senderemail = Yii::$app->user->identity->email;
        $ownername = '';
        $rentername = '';
        $sittername = '';

        if ($booking->pet_owner_id > 0) {
            $ownerdata = User::findOne($booking->pet_owner_id);
            $ownername = $ownerdata->firstname . ' ' . $ownerdata->lastname;
        }
        if ($booking->pet_renter_id > 0) {
            $renterdata = User::findOne($booking->pet_renter_id);
            $rentername = $renterdata->firstname . ' ' . $renterdata->lastname;
        }
        if ($booking->pet_renter_id > 0) {
            $sitterdata = User::findOne($booking->pet_renter_id);
            $sittername = $sitterdata->firstname . ' ' . $sitterdata->lastname;
        }
        $adminEmail = Yii::$app->commonmethod->getAdminEmailID();
        $subject = "Talk to admin";
        $message = '';
        $message .= '<tr>';
        $message .= '<td height="26" style="font-size:15px; font-weight:500; color:#2c2c2c;">Dear Admin,</td>';
        $message .= '</tr>';
        $message .= '<tr>';
        $message .= '<td style="font-size:13px; color:#656565; line-height:18px; padding-bottom:10px;">' . $sendername . ' sent you a message.</td>';
        $message .= '</tr>';
        $message .= '<tr>';
        $message .= '<td height="15"></td>';
        $message .= '</tr>';
        $message .= '<tr><td align="left"><table width="500" border="0" bgcolor="#656565" cellspacing="1" cellpadding="6" style=" color:#656565;">
						<tr  bgcolor="#ff8447">
						  <td colspan="2" style="border-top:#203367 solid 0px; font-size:14px; font-weight:400; color:#ffffff; text-transform:capitalize;padding:8px;">Booking Detail</td>
						</tr>
						<tr  bgcolor="#ffffff">
						  <td width="100" >Booking</td>
						  <td width="270" >' . $booking->name . '</td>
						</tr>					
						<tr  bgcolor="#ffffff">
						  <td>Booking From</td>
						  <td >' . $booking->booking_from_date . '</td>
						</tr>
						<tr  bgcolor="#ffffff">
						  <td>Booking to</td>
						  <td >' . $booking->booking_to_date . '</td>
						</tr>
						<tr  bgcolor="#ffffff">
						  <td>Message Title</td>
						  <td >' . $messagetitle . '</td>
						</tr>
						<tr  bgcolor="#ffffff">
						  <td>Type</td>
						  <td >' . $messagetype . '</td>
						</tr>	
						<tr  bgcolor="#ffffff">
						  <td>Message</td>
						  <td >' . $messagedesc . '</td>
						</tr>																				
					  </table></td>
					</tr>';

        $mail = Yii::$app->mailer->compose(['html' => 'layouts/mail'], ['content' => $message, 'subject' => $subject])
                ->setFrom([$senderemail => 'Palcura Member'])
                ->setTo($adminEmail)
                ->setSubject($subject)
                ->send();
        return $mail;
    }
    
    public function updatesitterNewcredits(){
    
    
    
    }

    public function actionCancelbooking() {
		

        $this->layout = false;
        $model = new Refund();
        $userModel = new User();
        $currentDate = date('Y-m-d');
        $userId = Yii::$app->user->getId();

        $session = Yii::$app->session;
        $usertype = $session->get('loggedinusertype');
		
		$transactionvoid = true;
		$amountdeducted = 0;
		
        if (!empty(Yii::$app->request->post())) {
			
            $postData = Yii::$app->request->post();
        
            $bookingId = $postData['bookingid'];
            //$bookingId = 2;
            //$amount = 10;
            //get used_rewards for booking 
            $usedRewards = $model->getusedRewards($bookingId);
            $bookingservices = $usedRewards['booking_services'];
              
            if (!empty($bookingservices)) {
                $user_services = explode(",", $bookingservices);
                $userservices = Yii::$app->commonmethod->getServicesName($user_services);               
                $servicenames = implode(',', $userservices);
            } else {
                $servicenames = '';
            }
           
            if (!empty($usedRewards)) {
                if ($usedRewards['booking_status'] == 3) {
                    return $this->redirect(['booking/index']);
                }
                $used_credits = $usedRewards['user_credits'] ? $usedRewards['user_credits'] : null;
                $used_reward_points = $usedRewards['user_reward_points'] ? $usedRewards['user_reward_points'] : null;
                $booking_credits = $usedRewards['booking_credits'] ? $usedRewards['booking_credits'] : null;
                $ownerid = $usedRewards['pet_owner_id'] ? $usedRewards['pet_owner_id'] : null;
                $sitterid = $usedRewards['pet_sitter_id'] ? $usedRewards['pet_sitter_id'] : null;
                $renterid = $usedRewards['pet_renter_id'] ? $usedRewards['pet_renter_id'] : null;
                $fromdate = $usedRewards['booking_from_date'] ? $usedRewards['booking_from_date'] : null;
                $todate = $usedRewards['booking_to_date'] ? $usedRewards['booking_to_date'] : null;
                $bookingname = $usedRewards['name'] ? $usedRewards['name'] : null;
                $transactionamount = $usedRewards['amount'] ? $usedRewards['amount'] : null;
                $transactionid = $usedRewards['trans_id'] ? $usedRewards['trans_id'] : null;
                $winreward_points = $usedRewards['reward_points'] ? $usedRewards['reward_points'] : null;
$winreward_points_owner = $usedRewards['fullamount'] ? $usedRewards['fullamount'] : null;


                //condition for 6 % deduction
                //$withinhours = date('Y-m-d', strtotime('-1 day', strtotime($fromdate));
                $withinhours = date('Y-m-d',strtotime($fromdate));
                //owner details
                $ownerdetails = $userModel->findIdentity($ownerid);
                //sitter details
                $sitterdetails = array();
                if (!empty($sitterid) && empty($renterid)) {
                    $sitterdetails = $userModel->findIdentity($sitterid);
                }
                
                //renter details
                $renterdetails = array();
                if (!empty($renterid) && empty($sitterid)) {
                    $renterdetails = $userModel->findIdentity($renterid);
                }

                $refundAmount = 0;
                $rewardpointdeducted = 0;
				
                if ($userId == $ownerid && empty($renterid)) {
                    //service requester	
                    $owner_reward_points = Yii::$app->user->identity->reward_points;
                    $owner_credits = Yii::$app->user->identity->owner_credits;
                                      
                    if ($owner_credits > 0){
                    $newcredits = $owner_credits;
                    }else{
                    $newcredits = 0;
                    }

                    
                    if($owner_reward_points > 0){
                    $newrewardpoints = $owner_reward_points - $winreward_points;
                    }else{
                    $newrewardpoints = 0;
                    }
                    
                   /* if ($owner_credits > 0 && $owner_reward_points > 0) {
                        $newcredits = $owner_credits;
                        $newrewardpoints = $owner_reward_points - $winreward_points;
                    } else {
                        $newcredits = 0;
                        $newrewardpoints = 0;
                    }*/


                    if ($currentDate < $withinhours) {
                        if (!empty($transactionamount) && $transactionamount > 0) {
                            $refundAmount = $transactionamount;
                        }
                        //if credits have been used for making payment
                        if (!empty($used_credits) && $used_credits > 0) {
                            $newcredits = $owner_credits + $used_credits;
                        }
                        if (!empty($used_reward_points) && $used_reward_points > 0) {

                            $totalrewardpoints = $owner_reward_points + $used_reward_points - $winreward_points_owner ;
                            if ($totalrewardpoints > 0) {
                                $newrewardpoints = $totalrewardpoints;
                            } else {
                                $newrewardpoints = 0;
                            }
                        }
                    } elseif ($currentDate == $withinhours) {
                        if (!empty($transactionamount) && $transactionamount > 0) {
                            $amountdeducted = (6 * $transactionamount) / 100;
                            $refundAmount = $transactionamount - $amountdeducted;
							$transactionvoid = false;
                        }
                        if (!empty($used_credits) && $used_credits > 0) {
                            $creditsdeducted = (6 * $used_credits) / 100;
                            $refundcredits = $used_credits - $creditsdeducted;
                            $newcredits = $owner_credits + $refundcredits;
                        }
                        if (!empty($used_reward_points) && $used_reward_points > 0) {
                            $rewardpointdeducted = (6 * $used_reward_points) / 100;
                            $refundrewards = $used_reward_points - $rewardpointdeducted;
                            $totalrewardpoints = $owner_reward_points + $refundrewards - $winreward_points_owner;

                            if ($totalrewardpoints > 0) {
                                $newrewardpoints = $totalrewardpoints;
                            } else {
                                $newrewardpoints = 0;
                            }
                        }
                    }
                    //update credits
                    $forUser = 'owner';
                    $model->updatecredits($ownerid, $newcredits, $forUser);           
                    $model->updaterewardpoints($ownerid, $newrewardpoints);
                    
                    
                    /******sitter credit update starts***need to add this script into a seperate function**/
                    //update credits earned by sitter
                     	$bookingSitterCredits = (float) $sitterdetails->sitter_credits;
               			$bookingsitterOwnerCredits = (float) $sitterdetails->owner_credits;
               			$totalsittercredits = $bookingSitterCredits + $bookingsitterOwnerCredits;
               			$pending_credits = $totalsittercredits-$booking_credits;
               			
                    	
               			if ($bookingsitterOwnerCredits == NULL || $bookingsitterOwnerCredits == 0) {
                    	$bookingsitterOwnerCredits = 0;
                    	$bookingSitterCredits = $pending_credits;
				        } else {
				            if ($bookingsitterOwnerCredits >= $booking_credits) {
				                $bookingsitterOwnerCredits = $bookingsitterOwnerCredits - $booking_credits;
				            } else {				                
				                $bookingSitterCredits = $bookingSitterCredits - $booking_credits;
				            }
				            
				            
				        }
				        
				         Yii::$app->db->createCommand()->update('user', ['owner_credits' => (float)$bookingsitterOwnerCredits, 'sitter_credits' => (float)$bookingSitterCredits], 'id = ' . $sitterdetails->id)->execute();
				         
				         /******sitter credit update ends***/
				       
				         				 
                    $cancelledby = $ownerdetails->firstname;
                    $cancelledbyuser = OWNER;
                } elseif ($userId == $sitterid) {
                                  
                    //Service Provider 
                    if (!empty($transactionamount) && $transactionamount > 0) {
                        $refundAmount = $transactionamount;
                    }
                   /* $sitter_credits = Yii::$app->user->identity->sitter_credits;
                    $totalsittercredits = $sitter_credits - $booking_credits;
                    if ($totalsittercredits > 0) {
                        $newsittercredits = $totalsittercredits;
                    } else {
                        $newsittercredits = 0;
                    }*/                                        
                    //owner will get back full credits and reward points					
                    $owner_credits = $ownerdetails->owner_credits;
                    $owner_reward_points = $ownerdetails->reward_points;
                    //if credits have been used for making payment
                    if (!empty($used_credits) && $used_credits > 0) {
                        $newcredits = $owner_credits + $used_credits;
                        $forUser = 'owner';
                        $model->updatecredits($ownerid, $newcredits, $forUser);
                    }
                    
                    if (!empty($used_reward_points) && $used_reward_points > 0) {
                        $newrewardpoints = $owner_reward_points + $used_reward_points - $winreward_points_owner;
                        $model->updaterewardpoints($ownerid, $newrewardpoints);
                    }else{
                    	$newrewardpoints = $owner_reward_points-$winreward_points_owner;
                    	$model->updaterewardpoints($ownerid, $newrewardpoints);
                    }
                    $forUser = 'sitter';
                   
                     /******sitter credit update starts***need to add this script into a seperate function**/
                    //update credits earned by sitter
                     	$bookingSitterCredits = (float) $sitterdetails->sitter_credits;
               			$bookingsitterOwnerCredits = (float) $sitterdetails->owner_credits;
               			$totalsittercredits = $bookingSitterCredits + $bookingsitterOwnerCredits;
               			$pending_credits = $totalsittercredits-$booking_credits;
               			
                    	
               			if ($bookingsitterOwnerCredits == NULL || $bookingsitterOwnerCredits == 0) {
                    	$bookingsitterOwnerCredits = 0;
                    	$bookingSitterCredits = $pending_credits;
				        } else {
				            if ($bookingsitterOwnerCredits >= $booking_credits) {
				                $bookingsitterOwnerCredits = $bookingsitterOwnerCredits - $booking_credits;
				            } else {				                
				                $bookingSitterCredits = $bookingSitterCredits - $booking_credits;
				            }
				            				            
				        }
				        
				         Yii::$app->db->createCommand()->update('user', ['owner_credits' => (float)$bookingsitterOwnerCredits, 'sitter_credits' => (float)$bookingSitterCredits], 'id = ' . $sitterid)->execute();
				         
				         /******sitter credit update ends***/
                    
                    
                   // $model->updatecredits($sitterid, $newsittercredits, $forUser);

                    //update owner credits and reward points					
                    $cancelledby = $sitterdetails->firstname;
                    $cancelledbyuser = SITTER;
					
                } elseif ($userId == $renterid) { 
                    //service requester
                    $owner_reward_points = Yii::$app->user->identity->reward_points;
                    $owner_credits = Yii::$app->user->identity->owner_credits;
                    
                    if ($owner_credits > 0 ){
                    $newcredits = $owner_credits;
                    }else{
                    $newcredits = 0;
                    }
                    if($owner_reward_points > 0){
                    $newrewardpoints = $owner_reward_points - $winreward_points;
                    }else{
                    $newrewardpoints = 0;
                    }
                    
                    /*if ($owner_credits > 0 && $owner_reward_points > 0) {
                        $newcredits = $owner_credits;
                        $newrewardpoints = $owner_reward_points - $winreward_points;
                    } else {
                        $newcredits = 0;
                        $newrewardpoints = 0;
                    }*/
                    
                    
                    if ($currentDate < $withinhours) {
                        if (!empty($transactionamount) && $transactionamount > 0) {
                            $refundAmount = $transactionamount;
                        }
                        //if credits have been used for making payment
                        if (!empty($used_credits) && $used_credits > 0) {
                            $newcredits = $owner_credits + $used_credits;
                        }
                        if (!empty($used_reward_points) && $used_reward_points > 0) {
                            $newrewardpoints = $owner_reward_points + $used_reward_points - $winreward_points_owner ;
                        }
                    } elseif ($currentDate == $withinhours) {
                        if (!empty($transactionamount) && $transactionamount > 0) {
                            $amountdeducted = (6 * $transactionamount) / 100;
                            $refundAmount = $transactionamount - $amountdeducted;
							$transactionvoid = false;
                        }
                        if (!empty($used_credits) && $used_credits > 0) {
                            $creditsdeducted = (6 * $used_credits) / 100;
                            $refundcredits = $used_credits - $creditsdeducted;
                            $newcredits = $owner_credits + $refundcredits;
                        }

                        if (!empty($used_reward_points) && $used_reward_points > 0) {
                            $rewardpointdeducted = (6 * $used_reward_points) / 100;
                            $refundrewards = $used_reward_points - $rewardpointdeducted;
                            $totalrewardpoints = $owner_reward_points + $refundrewards - $winreward_points_owner ;
                            if ($totalrewardpoints > 0) {
                                $newrewardpoints = $totalrewardpoints;
                            } else {
                                $newrewardpoints = 0;
                            }
                        }
                    }
                                      
                    $forUser = 'owner';
                    $model->updatecredits($renterid, $newcredits, $forUser);
                    $model->updaterewardpoints($renterid, $newrewardpoints);
                       
                    /******owner credit update starts***need to add this script into a seperate function**/
                    //update credits earned by owner
                     	$bookingOwnerCredits = (float) $ownerdetails->sitter_credits;
               			$bookingOwnerOwnerCredits = (float) $ownerdetails->owner_credits;
               			$totalOwnercredits = $bookingOwnerCredits + $bookingOwnerOwnerCredits;
               			$pending_credits = $totalOwnercredits-$booking_credits;
               			
                    	
               			if ($bookingOwnerOwnerCredits == NULL || $bookingOwnerOwnerCredits == 0) {
                    	$bookingOwnerOwnerCredits = 0;
                    	$bookingOwnerCredits = $pending_credits;
				        } else {
				            if ($bookingOwnerOwnerCredits >= $booking_credits) {
				                $bookingOwnerOwnerCredits = $bookingOwnerOwnerCredits - $booking_credits;
				            } else {				                
				                $bookingOwnerCredits = $bookingOwnerCredits - $booking_credits;
				            }
				            				            
				        }
				        
				         Yii::$app->db->createCommand()->update('user', ['owner_credits' => (float)$bookingOwnerOwnerCredits, 'sitter_credits' => (float)$bookingOwnerCredits], 'id = ' . $ownerid)->execute();
				         
				         /******owner credit update ends***/
        
                    $cancelledby = $renterdetails->firstname;
                    $cancelledbyuser = RENTER;
                } elseif ($userId == $ownerid && !empty($renterid)) { 
                    //Service Provider
                    if (!empty($transactionamount) && $transactionamount > 0) {
                        $refundAmount = $transactionamount;
                    }
                    $sitter_credits = Yii::$app->user->identity->sitter_credits;

                    $totalsittercredits = $sitter_credits - $booking_credits;
                    if ($totalsittercredits > 0) {
                        $newsittercredits = $totalsittercredits;
                    } else {
                        $newsittercredits = 0;
                    }
                    //owner wil get back full credits and reward points					
                    $renter_credits = $renterdetails->owner_credits;
                    $renter_reward_points = $renterdetails->reward_points;
                    //if credits have been used for making payment
                    if (!empty($used_credits) && $used_credits > 0) {
                        $newcredits = $renter_credits + $used_credits;
                        $forUser = 'owner';
                        $model->updatecredits($renterid, $newcredits, $forUser);
                    }
                    if (!empty($used_reward_points) && $used_reward_points > 0) {
                        $newrewardpoints = $renter_reward_points + $used_reward_points - $winreward_points;
                        $model->updaterewardpoints($renterid, $newrewardpoints);
                    }else{
                    	$newrewardpoints = $renter_reward_points - $winreward_points;
                        $model->updaterewardpoints($renterid, $newrewardpoints);
                    }
                    
                    /*$forUser = 'sitter';
                    $model->updatecredits($ownerid, $newsittercredits, $forUser);*/
                    
                    
                    /*** update credits earned by owner starts****/
                    
                    $bookingOwnerCredits = (float) $ownerdetails->sitter_credits;
               			$bookingOwnerOwnerCredits = (float) $ownerdetails->owner_credits;
               			$totalOwnercredits = $bookingOwnerCredits + $bookingOwnerOwnerCredits;
               			$pending_credits = $totalOwnercredits-$booking_credits;
               			                   	
               			if ($bookingOwnerOwnerCredits == NULL || $bookingOwnerOwnerCredits == 0) {
                    	$bookingOwnerOwnerCredits = 0;
                    	$bookingOwnerCredits = $pending_credits;
				        } else {
				            if ($bookingOwnerOwnerCredits >= $booking_credits) {
				                $bookingOwnerOwnerCredits = $bookingOwnerOwnerCredits - $booking_credits;
				            } else {				                
				                $bookingOwnerCredits = $bookingOwnerCredits - $booking_credits;
				            }
				            				            
				        }
				        
				         Yii::$app->db->createCommand()->update('user', ['owner_credits' => (float)$bookingOwnerOwnerCredits, 'sitter_credits' => (float)$bookingOwnerCredits], 'id = ' . $ownerid)->execute();
                                        
                     /*** update credits earned by owner ends****/
                                        
                    $cancelledby = $ownerdetails->firstname;
                    $cancelledbyuser = SITTER;
                }
             
                //if transaction has been made using paypal 
                if (!empty($refundAmount) && $refundAmount > 0) {
                    /*$paymentInfo = array(
                        'PAYERID' => '',
                        'TRANSACTIONID' => $transactionid,
                        'REFUNDTYPE' => 'Partial',
                        'AMT' => $refundAmount
                    );

                    $result = array();
                    $result = Yii::$app->Paypal->RefundTransaction($paymentInfo);*/
                   
                     if($usedRewards['payment_type'] == 'Credits-Points'){
                    
                    		$model->ref_user_id = $userId;
		                    $model->ref_booking_id = $bookingId;
		                    $model->ref_txn_id = $usedRewards['trans_id'];
		                    $model->ref_status = 'Credits-Points';
		                    $model->ref_ack = '	Success';
		                    $model->ref_amount = $usedRewards['amount'];
		                    $model->ref_created_at = date('y-m-d h:i:s');		              
		                    //send refund using transaction id
		                    //update or save refund information	
		                    $saveRefund = $model->saveRefundinfo();
                    }else{
                                       
				         $result =  Yii::$app->braintree->partialRefundAcustomer($transactionid,$refundAmount,$transactionvoid,$amountdeducted);
						          //echo "<pre>"; print_r($result);   
				            if (isset($result['success']) AND  $result['success']== 1) {

				                $model->ref_user_id = $userId;
				                $model->ref_booking_id = $bookingId;
				                $model->ref_txn_id = $result['transaction_id'];
				                $model->ref_status = $result['status'];
				                $model->ref_ack = '	Success';
				                $model->ref_amount = $result['amount'];
				                $model->ref_created_at = date('y-m-d h:i:s');
				                //echo "<pre>"; print_r($model); die;
				                //send refund using transaction id
				                //update or save refund information	
				                $saveRefund = $model->saveRefundinfo();
				            }
                    
                    }
                }
 
                //$cancelledbyuser = 1;
                //update cancellation status
                $cancelBooking = $model->updateBookingStatus($bookingId, $cancelledbyuser);

                if ($cancelBooking) {
                    //send email to users 
                    $ownerinfoarray = array(
                        'username' => $ownerdetails->firstname,
                        'toemail' => $ownerdetails->email,
                        'bookingname' => $bookingname,
                        'cancelledby' => $cancelledby,
                        'from_date' => $fromdate,
                        'to_date' => $todate,
                        'sittername' => (isset($sitterdetails->firstname)?$sitterdetails->firstname:''),
                        'ownername' => $ownerdetails->firstname,
                        'rentername' => (isset($renterdetails->firstname)?$renterdetails->firstname:'')
                    );

                    if (!empty($sitterdetails)) {
                        $sitterinfoarray = array(
                            'username' => $sitterdetails->firstname,
                            'toemail' => $sitterdetails->email,
                            'bookingname' => $bookingname,
                            'cancelledby' => $cancelledby,
                            'from_date' => $fromdate,
                            'to_date' => $todate,
                            'sittername' => $sitterdetails->firstname,
                            'ownername' => $ownerdetails->firstname,
                        );
                        //email to sitter
                        $this->cancellationemailToMember($sitterinfoarray, $servicenames);
                    }
                    if (!empty($renterdetails)) {
                        $renterinfoarray = array(
                            'username' => $renterdetails->firstname,
                            'toemail' => $renterdetails->email,
                            'bookingname' => $bookingname,
                            'cancelledby' => $cancelledby,
                            'from_date' => $fromdate,
                            'to_date' => $todate,
                            'sittername' => '',
                            'ownername' => $ownerdetails->firstname,
                        );
                        //email to renter
                        $this->cancellationemailToMember($renterinfoarray, $servicenames);
                    }
                    //email to owner
                    $this->cancellationemailToMemberowner($ownerinfoarray, $servicenames);

					
                    //email to admin 
                    $this->cancellationemailToAdmin($ownerinfoarray);
                    Yii::$app->session->setFlash('success', Yii::t('yii', 'Booking cancelled.'));
                    return true;
                } else {
                    Yii::$app->session->setFlash('error', Yii::t('yii', 'Transaction refused.Please contact website admin.'));
                    return true;
                }
            }
        }
    }
	
	 public function cancellationemailToMemberowner($emailInfo, $servicenames) {
        $username = $emailInfo['username'];
        $toemail = $emailInfo['toemail'];
        $bookingname = $emailInfo['bookingname'];
        $cancelledby = $emailInfo['cancelledby'];
        $from_date = date('m/d/y', strtotime($emailInfo['from_date']));
        $to_date = date('m/d/y', strtotime($emailInfo['to_date']));
        $sittername = $emailInfo['sittername'];
        $ownername = $emailInfo['ownername'];
        $daterange = $from_date . ' - ' . $to_date;

        $adminEmail = Yii::$app->commonmethod->getAdminEmailID();

        $subject = "Booking has been cancelled";
        $message = '';
        $message .= '<tr>
                            <td height="5" style="font-size:13px; color:#656565; line-height:5px; padding-bottom:10px;">Hi ' . $username . ',</td>
                          </tr>
                          <tr>
                            <td style="font-size:13px; color:#656565; line-height:18px; padding-bottom:10px;">This is a booking cancellation confirmation. Here are the details :</td>
                          </tr>
                          <tr>
                            <td height="5"></td>
                          </tr>
                          <tr>
                            <td align="left">
								<ul style="background-color: #fff3e3; color:#000000; font-size:15px; list-style:none; margin:0; padding:0; font-weight:600; padding: 10px 20px; letter-spacing: 0.3px;">
									<li>Cancelled by: <span style="font-weight:400; line-height:30px;">' . $cancelledby . '</span></li>
									<li>Date(s): <span style="font-style:italic; font-weight:400; line-height:30px;">' . $daterange . '</span></li>
									<li>Service Type: <span style="font-weight:400; line-height:30px;">' . $servicenames . '</span></li>	
									
								</ul>
							</td>
                          </tr>
                          <tr>
                            <td height="15"></td>
                            </tr>		
	                          <tr style="color:#656565; font-size:13px; line-height:19px;" >		
	                            <td>Please click <a href="' . SITE_URL . 'search/petsitter" target="_blank">here</a> Seach for petsitter </td>
								
							</tr>
						<tr style="color:#656565; font-size:13px; line-height:19px;" >		
	                    
								<td>Please click <a href="' . SITE_URL . 'Terms-and-Conditions" target="_blank">here</a>to read Terms and Conditions </td>
                          </tr>';
						  
						  
        $mail = Yii::$app->mailer->compose(['html' => 'layouts/mail'], ['content' => $message, 'subject' => $subject])
                ->setFrom([$adminEmail => 'Palcura'])
                ->setTo($toemail)
                ->setSubject($subject)
                ->send();
        return $mail;
    }

    /**
     * @ Function Name		: emailToOwner and sitter
     * @ Function Params	: 
     * @ Function Purpose 	: email to member 
     * @ Function Returns	: boolean true/false
     */
    public function cancellationemailToMember($emailInfo, $servicenames) {
        $username = $emailInfo['username'];
        $toemail = $emailInfo['toemail'];
        $bookingname = $emailInfo['bookingname'];
        $cancelledby = $emailInfo['cancelledby'];
        $from_date = date('m/d/y', strtotime($emailInfo['from_date']));
        $to_date = date('m/d/y', strtotime($emailInfo['to_date']));
        $sittername = $emailInfo['sittername'];
        $ownername = $emailInfo['ownername'];
        $daterange = $from_date . ' - ' . $to_date;

        $adminEmail = Yii::$app->commonmethod->getAdminEmailID();

        $subject = "Booking has been cancelled";
        $message = '';
        $message .= '<tr>
                            <td height="5" style="font-size:13px; color:#656565; line-height:5px; padding-bottom:10px;">Hi ' . $username . ',</td>
                          </tr>
                          <tr>
                            <td style="font-size:13px; color:#656565; line-height:18px; padding-bottom:10px;">This is a booking cancellation confirmation. Here are the details :</td>
                          </tr>
                          <tr>
                            <td height="5"></td>
                          </tr>
                          <tr>
                            <td align="left">
								<ul style="background-color: #fff3e3; color:#000000; font-size:15px; list-style:none; margin:0; padding:0; font-weight:600; padding: 10px 20px; letter-spacing: 0.3px;">
									<li>Cancelled by: <span style="font-weight:400; line-height:30px;">' . $cancelledby . '</span></li>
									<li>Date(s): <span style="font-style:italic; font-weight:400; line-height:30px;">' . $daterange . '</span></li>
									<li>Service Type: <span style="font-weight:400; line-height:30px;">' . $servicenames . '</span></li>	
								</ul>
							</td>
                          </tr>
                          <tr>
                            <td height="15"></td>
                          </tr>';
        $mail = Yii::$app->mailer->compose(['html' => 'layouts/mail'], ['content' => $message, 'subject' => $subject])
                ->setFrom([$adminEmail => 'Palcura'])
                ->setTo($toemail)
                ->setSubject($subject)
                ->send();
        return $mail;
    }

    /**
     * @ Function Name		: emailToOwner and sitter
     * @ Function Params	: 
     * @ Function Purpose 	: email to member 
     * @ Function Returns	: boolean true/false
     */
    public function cancellationemailToAdmin($emailInfo) {
        $username = $emailInfo['username'];
        $toemail = $emailInfo['toemail'];
        $bookingname = $emailInfo['bookingname'];
        $cancelledby = $emailInfo['cancelledby'];
        $from_date = $emailInfo['from_date'];
        $to_date = $emailInfo['to_date'];
        $sittername = $emailInfo['sittername'];
        $ownername = $emailInfo['ownername'];
        $rentername = $emailInfo['rentername'];

        $adminEmail = Yii::$app->commonmethod->getAdminEmailID();

        $subject = "Booking has been Cancelled";
        $message = '';
        $message .= '<tr>';
        $message .= '<td height="26" style="font-size:15px; font-weight:500; color:#2c2c2c;">Dear Admin,</td>';
        $message .= '</tr>';
        $message .= '<tr>';
        $message .= '<td style="font-size:13px; color:#656565; line-height:18px; padding-bottom:10px;">A booking has been cancelled. Please click <a href="' . SITE_URL . '" target="_blank">here</a> to access your account. Booking details are given below:</td>';
        $message .= '</tr>';
        $message .= '<tr>';
        $message .= '<td height="15"></td>';
        $message .= '</tr>';
        $message .= '<tr><td align="left"><table width="500" border="0" bgcolor="#656565" cellspacing="1" cellpadding="6" style="color:#656565;">
						<tr  bgcolor="#ff8447">
						  <td colspan="2" style="border-top:#203367 solid 0px; font-size:14px; font-weight:400; color:#ffffff; text-transform:capitalize;padding:8px;">Booking Detail</td>
						</tr>
						<tr  bgcolor="#ffffff">
						  <td width="100" >Booking</td>
						  <td width="270" >' . $bookingname . '</td>
						</tr>
						<tr  bgcolor="#ffffff">
						  <td width="100" >Sitter name</td>
						  <td width="270" >' . $sittername . '</td>
						</tr>
						<tr  bgcolor="#ffffff">
						  <td width="100" >Owner name</td>
						  <td width="270" >' . $ownername . '</td>
						</tr>
						<tr  bgcolor="#ffffff">
						  <td width="100" >Renter name</td>
						  <td width="270" >' . $rentername . '</td>
						</tr>
						<tr  bgcolor="#ffffff">
						  <td width="100" >Cancelled By</td>
						  <td width="270" >' . $cancelledby . '</td>
						</tr>							
						<tr  bgcolor="#ffffff">
						  <td>From Date</td>
						  <td >' . $from_date . '</td>
						</tr>
						<tr  bgcolor="#ffffff">
						  <td>To Date</td>
						  <td >' . $to_date . '</td>
						</tr>
								
					  </table></td>
					</tr>';

        $mail = Yii::$app->mailer->compose(['html' => 'layouts/mail'], ['content' => $message, 'subject' => $subject])
                ->setFrom([$adminEmail => 'Palcura'])
                ->setTo($adminEmail)
                ->setSubject($subject)
                ->send();
        return $mail;
    }

    public function actionGetPets($search) {
        $UserPet = new UserPet();
        $loggeduser = Yii::$app->user->identity->attributes;
        $pets = $UserPet->find()->select(['name AS value', 'id', 'user_id', 'type', 'care_note'])->where(['LIKE', 'name', $search])->andWhere(['user_id' => $loggeduser['id']])->asArray()->all();
        return json_encode($pets);
    }

    /**
     * @ Function Name		: actionBookingDetails
     * @ Function Params		: booking id
     * @ Function Purpose 	: default index function that will be called to display bookings details
     * @ Function Returns	: render view
     */
    public function actionBookingDetails($booking_id = 0) {
        $dataArray = array();
        $booking_id = Yii::$app->request->get('id');
        if ($booking_id == 0)
            return $this->redirect(['bookings/index']);

        $session = Yii::$app->session;
        $logged_user = $session->get('loggedinusertype');

        $query = new Query;

        $query->select('booking.*,sitter.firstname as fname,sitter.lastname as lname,sitter.address,GROUP_CONCAT(s.name SEPARATOR "|") as services_name')->from('booking')
                ->join('LEFT JOIN', 'user sitter', 'sitter.id = booking.pet_sitter_id')
                ->join('LEFT JOIN', 'user_services u_s', 'u_s.user_id = booking.pet_sitter_id')
                ->join('LEFT JOIN', 'services s', 's.id = u_s.service_id')
                ->where('booking.id = ' . $booking_id);
        $bookingInformation = $query->createCommand()->queryOne();

        $bookingFeedback = $this->getBookingFeedback($booking_id);
        $userPet = new UserPet();
        $pets = $userPet->getPets($bookingInformation['pet_id']);
// get booking activities
        $activityModel = new Activity();
        $activityids = array();
        $activityDataArray = array();
        $getActivityData = $activityModel->getActivityData($booking_id);
        $tmp = array();
        foreach ($getActivityData as $arg) {
            $tmp[$arg['activity_date']][] = $arg;
            $activityids[$arg['activity_date']][] = $arg['activity_id'];
        }
        $activityDataArray = $tmp;
//krsort($activityDataArray);
        $dataArray = array_merge($dataArray, [
            'bookingInformation' => $bookingInformation,
            'reviews' => $bookingFeedback,
            'pets' => $pets,
            'activityDataArray' => $activityDataArray
        ]);

        return $this->render('booking-details', $dataArray);
    }

    public function getBookingFeedback($booking_id = 0) {
        $query = new Query;
        $query->select('id,comment,starrating,date_time')->from('feedback_rating')->where('booking_id = ' . $booking_id)->orderBy(['id' => SORT_DESC]);
        return $query->createCommand()->queryAll();
    }

    public function actionPetinfo() {

        $modelImageUploadF = new Uploads();

        $vaccinationModel = new Vaccinationdetails();
        $vaccinationModel->scenario = 'upf';

        if ($vaccinationModel->load(Yii::$app->request->post()) && $vaccinationModel->validate()) {

            $modelImageUploadF->vaccination_doc = UploadedFile::getInstance($modelImageUploadF, 'vaccination_doc');
            if ($modelImageUploadF->vaccination_doc && $uploadedFileNameArray = $modelImageUploadF->uploadF()) {
                $vaccinationdoc = $uploadedFileNameArray['docname'];
            }

            if ($vaccinationModel->saveVacc(Yii::$app->request->post('Vaccinationdetails'), $vaccinationdoc)) {
                Yii::$app->session->setFlash('item', 'Pet Information is added successfully.');
            }
        }
        return $this->render('petinformation', [
                    'vaccinationModel' => $vaccinationModel,
                    'modelImageUploadF' => $modelImageUploadF,
        ]);
    }

    public function actionBookNow() {
	
        $session = Yii::$app->session;
        $logged_user = $session->get('loggedinusertype');
        $searchrequestdata = $session->get('searchrequestdata');
        $requestedBookigData = '';

        if ($logged_user == SITTER || $logged_user == "") {
            return $this->redirect(['site/home']);
        }
		$oldbookingdetails = Yii::$app->session->get('booking_details');
        
		if(isset($oldbookingdetails) && !empty($oldbookingdetails)){
		$requestedBookigData = $oldbookingdetails;
		}
        
		 $userId = Yii::$app->user->getId();
		/*
		####= get card details { param: $userId, true/false(to get count or all info).
        $cardDetails = Yii::$app->commonmethod->getCardInformation($userId, true);
        if ($cardDetails == false) {
        $session->set('refercard','true');
            $this->redirect(["account/save-card-details"]);
        } */

        ####+ get pet name and vaccination details
        /* if ($logged_user == OWNER) {
          $vaccinationDetails = Yii::$app->commonmethod->getPetvaccinationdetails($userId);
          if ($vaccinationDetails == false) {
          $this->redirect(["bookings/petinfo"]);
          }
          } */

        $postArr = Yii::$app->request->post();
        
      
        $session = Yii::$app->session;
        if (isset($postArr['book_user_id']) && !empty($postArr['book_user_id'])) {
            Yii::$app->session->set('booking_data', ['book_sitter_id' => $postArr['book_user_id']]);
        }
        $booking_data = $session->get('booking_data');
        if (isset($booking_data['book_sitter_id']) && !empty($booking_data['book_sitter_id'])) {

            $owner = Yii::$app->user->identity;

            // Throw exception if user try to book him/herself
            // uncomment after booking module completed
            
           
            if ($owner->id == $booking_data['book_sitter_id']) {
                throw new \yii\web\ForbiddenHttpException(Yii::t('yii', 'You are not allowed to access this page.'));
                return false;
            }

            $guideDetails = Users::findOne($booking_data['book_sitter_id']);
            if ($guideDetails === null) {
                throw new NotFoundHttpException(Yii::t('yii', 'Page not found.'));
                return false;
            }


            $getServicesPrice = '';
            $getServicesPrice = '';

            $model = new Booking;
            $userPet = new UserPet;
            $palDetails = (Yii::$app->request->post('palDetails')) ? Yii::$app->request->post('palDetails') : array();
            
            if ($model->load(Yii::$app->request->post()) && $model->validate() && ($logged_user == BORROWER || ($logged_user == OWNER && $userPet->validatePets($palDetails)))) {
                $postArr = Yii::$app->request->post('Booking');
                
				$numOfDays = 1;
								
                if (empty($postArr['booking_from_date']) && empty($postArr['booking_to_date']) && empty($postArr['number_of_pets']) && empty($postArr['services']) && empty($postArr['description'])) {
                    Yii::$app->session->setFlash('item', Yii::t('yii', 'Invailid information.'));
                    return $this->redirect(["bookings/book-now"]);
                }
                
                $date_from = strtotime($postArr['booking_from_date']);
                $date_to = strtotime($postArr['booking_to_date']);
                $datediff = $date_to - $date_from;
                $sDays = ceil($datediff / (60 * 60 * 24));
                
                //check for over night service
               
               
                                           
                if(!empty($postArr) && count($postArr['services']) == 1 && $postArr['services'][0]==4){
				$numOfDays = 0;
					if($sDays == 0){
					$numOfDays = 1;
					}
				}
				
				if(!empty($postArr) && count($postArr['services']) > 1){				
					if(in_array("4", $postArr['services'])){
					$numOfDays = 0;
						if($sDays == 0){
						$numOfDays = 1;
						}		
					}			
				}
																
                if ($sDays > 0) {
                    $numOfDays = $numOfDays + $sDays;
                }
				
                $renter_user_id = 0;
                if ($logged_user == BORROWER) {
                    $getServicesPrice = Yii::$app->commonmethod->getOwnerPrice($booking_data['book_sitter_id']);
                    $getServicesPrice = $numOfDays * $getServicesPrice;
                    $renter_user_id = $userId;
                } else {
                    $getServicesPrice = Yii::$app->commonmethod->calculateServicesPrice($postArr['services'], $numOfDays, $booking_data['book_sitter_id']);
                }
                
                $websiteFee = Yii::$app->commonmethod->getWebsiteFee();
                $finalPrice = $getServicesPrice;
				
                if ($finalPrice <= 0) {
                    Yii::$app->session->setFlash('item', Yii::t('yii', 'Insufficient amount.'));
                    return $this->redirect(["bookings/book-now"]);
                }
               
                if ($logged_user == BORROWER) {
                   
                    $booking_charges = $this->calculateFinalPrice($userId, $booking_data['book_sitter_id'], $getServicesPrice, $numOfDays);
                } else {
					 
                    $booking_charges = $this->calculateFinalPrice($userId, $booking_data['book_sitter_id'], $getServicesPrice, $numOfDays, $postArr['number_of_pets']);
                }
                  
               
                // save pets in database
                $pets = array();
                if (isset($palDetails) && !empty($palDetails) && count($palDetails) > 0) {
                    $pets = array();
                   
                    foreach ($palDetails['type'] as $key => $petType) {
                       
                     if ($key != 0) {
                            $saveUserpet = new UserPet();
                            if (trim($palDetails['id'][$key]) != "" && $palDetails['id'][$key] > 0) {
                                   $pets[] = $saveUserpet->update_pet($palDetails['id'][$key], $palDetails['care_note'][$key]);
                            } else {
                              
                                 $pets[] = $saveUserpet->save_pets($petType, $palDetails['name'][$key], $palDetails['care_note'][$key], $userId);
                              
                            }
                        }
                    }
					
                    $pets = array_filter($pets);
                    // it means added pet count is not same as that of saved pets
                    if (count($pets) != count($palDetails['type']) - 1) {
                        // Need to do rolback in case of error will do that later for now just throwing error
                        // write code to throw error here
                        throw new \yii\web\ForbiddenHttpException(Yii::t('yii', 'Something went wrong please try again.'));
                        return false;
                    }
                }
				
				
                Yii::$app->session->set('booking_details', [
                    'booking_name' => (isset($postArr['name']) ? $postArr['name'] : ''),
                    'description' => $postArr['description'],
                    'pet_sitter_id' => $booking_data['book_sitter_id'],
                    'pet_owner_id' => $userId,
                    'pet_renter_id' => $renter_user_id,
                    'booking_from_date' => date('Y-m-d', strtotime($postArr['booking_from_date'])),
                    'booking_to_date' => date('Y-m-d', strtotime($postArr['booking_to_date'])),
                    'booking_amount' => $booking_charges['booking_price'],
                    'booking_admin_fee' => $booking_charges['admin_fee'],
                    'reward_points' => round($booking_charges['booking_price']),
                    'booking_credits' => $booking_charges['sitter_revenue'],
                    'number_of_pets' => (isset($postArr['number_of_pets']) ? $postArr['number_of_pets'] : 0),
                    'services' => (isset($postArr['services']) ? $postArr['services'] : ''),
                    'in_payment_transaction_fee' => $booking_charges['in_payment_transaction_fee'],
                    'out_payment_transaction_fee' => $booking_charges['out_payment_transaction_fee'],
                    'palcura_revenue' => $booking_charges['palcura_revenue'],
                    'booking_id' => $model->id,
                    'booking_type' => (isset($postArr['booking_type']) ? $postArr['booking_type'] : 0),
                    'pets' => $pets
                ]);
					
                //$this->redirect(['payments/pay-now']);
                return $this->redirect(['bookings/requestbooking']);
                //return $this->render('requestbooking'); 
            }
           
         
            $dataArray = array();
            $dataArray = array_merge($dataArray, [
                'model' => $model,
                'userPet' => $userPet,
                'palDetails' => $palDetails,
                'searchrequestdata' => $searchrequestdata,
                'requestedBookigData' => $requestedBookigData,
            ]);
           

            if ($logged_user == BORROWER) {
                 $model->booking_type = BORROWER;
                return $this->render('book-now-renter', $dataArray);
            } else {
               
                $model->booking_type = OWNER;
                return $this->render('book-now', $dataArray);
            }
        }
        return $this->redirect(['site/home']);
    }

    public function actionRequestbooking() {
         
		 
        $session = Yii::$app->session;
        $logged_user = $session->get('loggedinusertype');
        $model = new Booking;
        if ($logged_user == SITTER || $logged_user == "") {
            return $this->redirect(['site/home']);
        }
        $userId = Yii::$app->user->getId();
        ####= get card details { param: $userId, true/false(to get count or all info).

     /*
		$cardDetails = Yii::$app->commonmethod->getCardInformation($userId, true);
		if ($cardDetails == false) {
            $this->redirect(["account/save-card-details"]);
        }
       */
  
        
     
            //get booking details from session and save to database
            $bookingdetails = Yii::$app->session->get('booking_details');
      
            if (!empty($bookingdetails)) {
                $model->name = $bookingdetails['booking_name'];
                $model->description = $bookingdetails['description'];
                if (!empty($bookingdetails['services'])) {
                    //$bookingservices = implode(',', $bookingdetails['services']);
                    $bookingservices = $bookingdetails['services'];
                } else {
                    $bookingservices = '';
                }
              
                if ($logged_user == BORROWER) {
                    $model->pet_renter_id = $userId;
                    $model->pet_owner_id = $bookingdetails['pet_sitter_id'];
                } else {
                    $model->pet_sitter_id = $bookingdetails['pet_sitter_id'];
                    $model->pet_owner_id = $userId;
                }
                $model->booking_type = $bookingdetails['booking_type'];
                $model->services = $bookingdetails['services'];
                $model->number_of_pets = $bookingdetails['number_of_pets'];
                $model->description = $bookingdetails['description'];
                $model->booking_from_date = $bookingdetails['booking_from_date'];
                $model->booking_to_date = $bookingdetails['booking_to_date'];
                $model->amount = $bookingdetails['booking_amount'];
//                echo  $bookingdetails['booking_amount']; die;
//                echo  $model->amount; die;
                $model->admin_fee = $bookingdetails['booking_admin_fee'];
                $model->reward_points = $bookingdetails['reward_points'];
                $model->booking_credits = $bookingdetails['booking_credits'];
                $model->booking_services = $bookingservices;
                $model->in_payment_transaction_fee = $bookingdetails['in_payment_transaction_fee'];
                $model->out_payment_transaction_fee = $bookingdetails['out_payment_transaction_fee'];
                $model->palcura_revenue = $bookingdetails['palcura_revenue'];
                $model->booking_status = PENDING;
                $model->completed = PENDING;
                $model->cancelled_by = PENDING;
                $model->status = ACTIVE;
                $model->payment_status = PENDING;
                $pet = '';
                if ($bookingdetails['pets']) {
                    foreach ($bookingdetails['pets'] as $key => $pet) {
                      

                        $pets = ($key == count($bookingdetails['pets']) - 1) ? $pet['id'] : $pet['id'] . ',';
                        
                    }
                }
                $model->pet_id = $pets;
                $model->date_created = date('Y-m-d H:i:s');
                
                
                $model->save();

                
                if (!isset($model->id)) {
                    Yii::$app->session->setFlash('error', 'Sorry! their was an error in sending your pet care request. Please try again!');
                    $this->redirect(['bookings/book-now']);
                } else {
                    // save data in booking care note table
                    if ($bookingdetails['pets']) {
                        foreach ($bookingdetails['pets'] as $key => $pet) {
                            $bookingCareNote = new BookingCareNote();
                            $bookingCareNote->save_care_note($model->id,$pet['care_note'],$pet['id']);
                        }
                    }

                   
                    $bookingDate = $bookingdetails['booking_from_date'];
                    //$datediff = strtotime($bookingdetails['booking_to_date']) - strtotime($bookingdetails['booking_from_date']);
                    $bookingDate = date('m/d/y', strtotime($bookingdetails['booking_from_date']));
                    //if ($datediff > 0) {
                        $bookingDate .= ' - ' . date('m/d/y', strtotime($bookingdetails['booking_to_date']));
                    //}
                    $bookingPriceForSitter = (isset($bookingdetails['booking_credits']) ? $bookingdetails['booking_credits'] : $bookingdetails['booking_amount'] - $bookingdetails['booking_admin_fee']);
                    $bookingPricePaidByOwner = $bookingdetails['booking_amount'];
                    $adminEmail = Yii::$app->commonmethod->getAdminEmailID();
                    $booking_type = $bookingdetails['pet_renter_id'];
                    
                 
					//$session->remove('booking_details');
					
                    Yii::$app->session->setFlash('items', 'Thank You Your pet care request has been sent for confirmation.');
                    $this->emailToSitterForBookingReq($bookingDate, $bookingPriceForSitter, $model, $adminEmail, $booking_type);
                    
                    return $this->render('requestbooking');
                  //  return $this->redirect(['bookings/book-now']);
                }
            } else {
                if ($logged_user == BORROWER) {
                    return $this->redirect(['search/petrenter']);
                } elseif ($logged_user == OWNER) {
                    return $this->redirect(['search/petsitter']);
                } else {
                    return $this->redirect(['site/home']);
                    //echo "<pre>"; print_r($bookingdetails); die;
                }
            }
      
           //  return $this->render('requestbooking');
    }

    public function emailToSitterForBookingReq($bookingDate, $bookingPriceForSitter, $booking, $adminEmail, $booking_type) {

        if ($booking_type > 0) {
            $nameS = "Renter name";
            //find pet's name

            //$petinfo = Vaccinationdetails::find()->select('pet_name')->where(['id' => $booking->pet_owner_id])->One();

            $renterinfo = User::find()->select('firstname,lastname')->where(['id' => $booking->pet_renter_id])->One();
            $sitterinfo = User::find()->select('firstname,lastname,email')->where(['id' => $booking->pet_owner_id])->One();

            $ownerName = (isset($renterinfo->firstname) ? $renterinfo->firstname : '');
            $sitterEmail = (isset($sitterinfo->email) ? $sitterinfo->email : '');
            $sitterName = (isset($sitterinfo->firstname) ? $sitterinfo->firstname : '');

            $subject = "You have received a new borrowing request";
            $message = '';
            $message .= '<tr>
                            <td height="5" style="font-size:13px; color:#656565; line-height:5px; padding-bottom:10px;">Hi ' . $sitterName . ',</td>
                          </tr>
                          <tr>
                            <td style="font-size:13px; color:#656565; line-height:18px; padding-bottom:10px;">Great news! You just received a new borrowing request from ' . $ownerName . '.</td>
                          </tr>
                          <tr>
                            <td height="5"></td>
                          </tr>
                          <tr>
                            <td align="left">
								<ul style="background-color: #fff3e3; color:#000000; font-size:15px; list-style:none; margin:0; padding:0; font-weight:600; padding: 10px 20px; letter-spacing: 0.3px;">
									
									<li>Date(s): <span style="font-style:italic; font-weight:400; line-height:30px;">' . $bookingDate . '</span></li>
									<li>Potential Earnings: <span style="font-weight:400; line-height:30px;">' . CURRENCY_SIGN . $bookingPriceForSitter . '</span></li>
								</ul>
							</td>
                          </tr>
                          <tr>
                            <td height="15"></td>
                          </tr>
                          <tr style="color:#656565; font-size:13px; line-height:19px;" >
                            <td>Accept/Decline or View this request <a href="' . SITE_URL . 'bookings" target="_blank">here</a>.</td>
                          </tr>';
        } else {
            $nameS = "Owner name";
            //$petinfo = Vaccinationdetails::find()->select('pet_name')->where(['id' => $booking->pet_owner_id])->One();
            $services = $booking->services;
            //$services = implode(',',$services);
            $query = new Query;
            $query->select('name')->from('services')->where(['IN', 'id', $services]);

            $servicesnames = $query->createCommand()->queryAll();

            //$serviceName = implode(',',$servicesnames[0]['name']); 
            $serviceName = implode(', ', array_column($servicesnames, 'name'));

            $ownerinfo = User::find()->select('firstname,lastname')->where(['id' => $booking->pet_owner_id])->One();
            $sitterinfo = User::find()->select('firstname,lastname,email')->where(['id' => $booking->pet_sitter_id])->One();

            $ownerName = (isset($ownerinfo->firstname) ? $ownerinfo->firstname : '');
            $sitterEmail = (isset($sitterinfo->email) ? $sitterinfo->email : '');
            $sitterName = (isset($sitterinfo->firstname) ? $sitterinfo->firstname : '');
            $subject = "You have received a new service request";
            $message = '';
            $message .= '<tr>
                            <td height="5" style="font-size:13px; color:#656565; line-height:5px; padding-bottom:10px;">Hi ' . $sitterName . ',</td>
                          </tr>
<tr>
                            <td height="5"></td>
                          </tr>
                          <tr>
                            <td style="font-size:13px; color:#656565; line-height:18px; padding-bottom:10px;">Great news! You just received a new service request from ' . $ownerName . '. </td>
                          </tr>
                          <tr>
                            <td height="5"></td>
                          </tr>
                          <tr>
                            <td align="left">
								<ul style="background-color: #fff3e3; color:#000000; font-size:15px; list-style:none; margin:0; padding:0; font-weight:600; padding: 10px 20px; letter-spacing: 0.3px;">
									
									<li>Date(s): <span style="font-style:italic; font-weight:400; line-height:30px;">' . $bookingDate . '</span></li>
									<li>Service Type: <span style="font-weight:400; line-height:30px;">' . $serviceName . '</span></li>
							
									<li>Potential Earnings: <span style="font-weight:400; line-height:30px;">' . CURRENCY_SIGN . $bookingPriceForSitter . '</span></li>
								</ul>
							</td>
                          </tr>
                          <tr>
                            <td height="15"></td>
                          </tr>
                          <tr style="color:#656565; font-size:13px; line-height:19px;" >
                            <td>Accept/Decline or View this request <a href="' . SITE_URL . 'bookings" target="_blank">here</a>.</td>
                          </tr>';
        }

        $mail = Yii::$app->mailer->compose(['html' => 'layouts/mail'], ['content' => $message, 'subject' => $subject])
                ->setFrom([$adminEmail => 'Palcura'])
                ->setTo($sitterEmail)
                ->setSubject($subject)
                ->send();
		
        return $mail;
    }

    public function actionScheduleVideoCall($sitterId = 0) {

        $userId = Yii::$app->user->getId();
        $dataArray = array();
        $sitterId = Yii::$app->request->get('id');
        $session = Yii::$app->session;
        
 		$searchrequestdata = $session->get('searchrequestdata');
 		$logged_user 			= $session->get('loggedinusertype');
		if($logged_user == OWNER) {
		$servicetypeserch = array($searchrequestdata['service_type']);
		}else{
		$servicetypeserch = '';
		}
		$searchservicetype = $searchrequestdata['service_type'];
		if($logged_user == RENTER){
		
		$searchservicetype = '';
		}
		
        $video_data = $session->get('schedule_video_call');
        if (isset($video_data['sitter_id']) && !empty($video_data['sitter_id'])) {
            if ($video_data['sitter_id'] != $sitterId) {
                Yii::$app->session->set('schedule_video_call', [
                    'sitter_id' => $sitterId,
                ]);
            }
        } else {
            if ($sitterId == 0) {
                return $this->redirect(['bookings/index']);
            }
            if ($sitterId != 0) {
                Yii::$app->session->set('schedule_video_call', [
                    'sitter_id' => $sitterId,
                ]);
            }
        }

        $video_data = $session->get('schedule_video_call');
        if (isset($video_data['sitter_id']) && !empty($video_data['sitter_id'])) {
            $owner = Yii::$app->user->identity;

            // Throw exception if user try to book him/herself
            // uncomment after booking module completed

            if ($owner->id == $video_data['sitter_id']) {
                throw new \yii\web\ForbiddenHttpException(Yii::t('yii', 'You are not allowed to access this page.'));
                return false;
            }

            $sitterData = Users::findOne($video_data['sitter_id']);
            if ($sitterData === null) {
                throw new NotFoundHttpException(Yii::t('yii', 'Page not found.'));
                return false;
            }

            $model = new \frontend\models\common\Videoconversation;
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                $postArr = Yii::$app->request->post('Videoconversation');
                $datechk = $model->chkfordate($video_data['sitter_id'], $postArr['schedule_datetime']);
                if ($datechk) {
                    Yii::$app->session->setFlash('error', Yii::t('yii', 'Only one video session for the day with the selected party is allowed.'));
                    return $this->redirect(['bookings/schedule-video-call/' . $video_data['sitter_id']]);
                }
                $bookingdetails = Yii::$app->session->get('booking_details');


                //$services = $bookingdetails['services'];
                if (!empty($servicetypeserch)) {
                    $servicesName = Yii::$app->commonmethod->getServicesName($servicetypeserch);
                    $servicesName = implode(',', $servicesName);
                } else {
                    $servicesName = 'NA';
                }

                $model->name = 'Option removed';
                $model->description = $postArr['description'];
                $model->start_time = $postArr['start_time'];
                $model->pet_owner_id = $userId;
                $model->services = $searchservicetype;
                $model->pet_sitter_id = $video_data['sitter_id'];
                $model->schedule_datetime = date('Y-m-d H:i:s', strtotime($postArr['schedule_datetime']));
                $model->schedule_duration_time = '10';
                $model->call_status = PENDING;

                if ($model->save()) {
                    $this->sendschedulecallemail($video_data['sitter_id'], $postArr['description'], $postArr['schedule_datetime'], $postArr['start_time'], $servicesName,$logged_user);
                    Yii::$app->session->setFlash('item', Yii::t('yii', 'Your request for video session has been sent for confirmation.'));

                    return $this->redirect(['users/view-user-details/' . $video_data['sitter_id']]);
                }
            }

            $dataArray = array();
            $dataArray = array_merge($dataArray, [
                'model' => $model,
            ]);

            return $this->render('schedule-video-call', $dataArray);
        }
    }

    public function videorequestemail($requestby, $requestfrom) {

        $subject = "You have received a new service request";
        $message = '';
        $message .= '<tr>';
        $message .= '<td height="5" style="font-size:13px; color:#656565; line-height:5px; padding-bottom:10px;">Hi ' . $sitterName . ',</td>';
        $message .= '</tr>';
        $message .= '<tr>';
        $message .= '<td style="font-size:13px; color:#656565; line-height:18px; padding-bottom:10px;">Great news! You just received a new service request from ' . $ownerName . ' for pet’s name. </td>';
        $message .= '</tr>';
        $message .= '<tr>';
        $message .= '<td height="15"></td>';
        $message .= '</tr>  
					<tr ><td align="left"><table width="500" border="0" bgcolor="#656565" cellspacing="1" cellpadding="6" style=" color:#656565;">
						<!--tr  bgcolor="#ff8447">
						  <td colspan="2" style="border-top:#203367 solid 0px; font-size:14px; font-weight:400; color:#ffffff; text-transform:capitalize;   padding:8px;">Booking Detail</td>						  
						</tr-->
						<tr bgcolor="#ffffff">
							<td>
							 Date (s): ' . $bookingDate . '
							</td>						
						</tr>
						<tr bgcolor="#ffffff">
							<td>
							 Service Type: ' . $serviceName . '
							</td>	
						</tr>
						<tr bgcolor="#ffffff">
							<td>
							 Potential Earnings: ' . CURRENCY_SIGN . $bookingPriceForSitter . '
							</td>	
						</tr>
															
					  </table></td>
					  
					</tr>
						<tr  style="color:#656565; font-size:13px; line-height:19px;">
                            <td>Accept/Decline or View this request <a href="' . SITE_URL . '" target="_blank">here</a><br /><br />
                              -Your friends at PalCura <br />
                             Share the care…Multiply the love!</td>
                          </tr>
                          <tr  style="color:#656565; font-size:13px; line-height:19px;">
                            <td><br />Always book through PalCura to earn member discounts, points, coupons and connection to a large pet care community around you.</td>
                          </tr>';


        $mail = Yii::$app->mailer->compose(['html' => 'layouts/mail'], ['content' => $message, 'subject' => $subject])
                ->setFrom([$adminEmail => 'Palcura'])
                ->setTo($sitterEmail)
                ->setSubject($subject)
                ->send();

        return $mail;
    }

    public function sendschedulecallemail($recieverid, $comment, $scheduleddate, $time, $servicesName,$logged_user) {

        $adminEmail = Yii::$app->commonmethod->getAdminEmailID();
        $senderfirstname = Yii::$app->user->identity->firstname;
        $senderlastname = Yii::$app->user->identity->lastname;
        $sendername = $senderfirstname;

        $recieverdata = User::findOne($recieverid);
        $recievername = $recieverdata->firstname;
        $recieveremail = $recieverdata->email;

        $subject = "You have received a video call request";
        $message = '';

        $message .= '<tr>
                            <td height="5" style="font-size:13px; color:#656565; line-height:5px; padding-bottom:10px;">Hi ' . $recievername . ',</td>
                          </tr>
                          <tr>
                            <td style="font-size:13px; color:#656565; line-height:18px; padding-bottom:10px;">You have received a video call request from ' . $sendername . '. Here are the details:</td>
                          </tr>
                          <tr>
                            <td height="5"></td>
                          </tr>
                          <tr>
                            <td align="left">
								<ul style="background-color: #fff3e3; color:#000000; font-size:15px; list-style:none; margin:0; padding:0; font-weight:600; padding: 10px 20px; letter-spacing: 0.3px;">
									<li>Request by:  <span style="font-weight:400; line-height:30px;">' . $sendername . '</span></li>
									<li>Date: <span style="font-style:italic; font-weight:400; line-height:30px;">' . $scheduleddate . '</span></li>							<li>Time: <span style="font-weight:400; line-height:30px;">' . $time . '</span></li>';
									if($logged_user == OWNER){
					$message.='<li>Service Type: <span style="font-weight:400; line-height:30px;">' . $servicesName . '</span></li>';
							}											
					$message.='</ul>
							</td>
                          </tr>
                          <tr>
                            <td height="15"></td>
                          </tr>
                          <tr style="color:#656565; font-size:13px; line-height:19px;" >
                            <td>Accept/Decline or View this request <a href="' . SITE_URL . 'video" target="_blank">here</a></td>
                          </tr>';

        $mail = Yii::$app->mailer->compose(['html' => 'layouts/mail'], ['content' => $message, 'subject' => $subject])
                ->setFrom([$adminEmail => "Palcura"])
                ->setTo($recieveremail)
                ->setSubject($subject)
                ->send();
        return $mail;
    }

    public function calculateFinalPrice2($userId, $userType, $totalAmount, $websiteFee) {
        $booked_days = $this->getBookingDuration($userId, $userType);
        $websiteDiscount = Yii::$app->commonmethod->adminDiscount($userType, $booked_days);
        if ($userType == OWNER) {
            $discount = 0;
            if ($websiteDiscount > 0) {
                $discount = ($totalAmount / 100) * $websiteDiscount;
                $totalAmount = $totalAmount - $discount;
            }

            $adminFee = ($totalAmount / 100) * $websiteFee;
            $adminFeeWithDiscount = $adminFee - $discount;
            return array("total_amount" => $totalAmount, "admin_fee" => $adminFee, "discount" => $discount);
        } else if ($userType == BORROWER) {
            $discount = 0;
            if ($websiteDiscount > 0) {
                $discount = ($totalAmount / 100) * $websiteDiscount;
                $totalAmount = $totalAmount - $discount;
            }

            $adminFee = ($totalAmount / 100) * $websiteFee;
            $adminFeeWithDiscount = $adminFee - $discount;
            return array("total_amount" => $totalAmount, "admin_fee" => $adminFee, "discount" => $discount);
        } else {
            $adminFee = ($totalAmount / 100) * $websiteFee;
            $discount = ($adminFee / 100) * $websiteDiscount;
            return ($totalAmount - $adminFee) + $discount;
        }
    }

    public function getBookingDuration($userId, $userType) {
        if ($userType == SITTER) {
            $whereCase = array("pet_sitter_id" => $userId, "payment_status" => '1');
        } else if ($userType == BORROWER) {
            $whereCase = array("pet_owner_id" => $userId, "payment_status" => '1');
        } else {
            $whereCase = array("pet_owner_id" => $userId, "payment_status" => '1');
        }

        $query = new Query;
        $query->select('booking_from_date,booking_to_date')->from('booking')->where($whereCase);
        $bookingArr = $query->createCommand()->queryAll();
        $dateDiff = array();
        if (isset($bookingArr) && !empty($bookingArr)) {
            foreach ($bookingArr as $book) {
                $date1 = strtotime($book['booking_from_date']);
                $date2 = strtotime($book['booking_to_date']);
                $dateDiff[] = ceil(abs($date2 - $date1) / 86400) + 1;
            }
        }
        return array_sum($dateDiff);
    }

    public function actionCalculateprice(){

        if(Yii::$app->request->isPost) {
			$numOfDays = 1;
            $userId = Yii::$app->user->getId();
            $getServicesPrice='';
            $logged_user = Yii::$app->request->post('loggedingusertype');
            $sitterId = Yii::$app->request->post('sitterId');
            $sDays = Yii::$app->request->post('numOfDays');
            $numofPets = Yii::$app->request->post('numofPets');
            $services = Yii::$app->request->post('services');
			
				
			
			 if($services == 1 && $services==4){
				$numOfDays = 0;
					if($sDays == 0){
					$numOfDays = 1;
					}
				}
							
				if ($sDays > 0) {
                    $numOfDays = $numOfDays + $sDays;
                }
				
                    
            if ($logged_user == BORROWER) {
                $getServicesPrice = Yii::$app->commonmethod->getOwnerPrice($sitterId);
                $getServicesPrice = $numOfDays * $getServicesPrice;
                $renter_user_id = $userId;
            } else {
                $getServicesPrice = Yii::$app->commonmethod->calculateServicesPrice($services, $numOfDays, $sitterId);
            }
            
            $websiteFee = Yii::$app->commonmethod->getWebsiteFee();
            $finalPrice = $getServicesPrice;
            if ($finalPrice <= 0) {
                $finalPrice=0;
                echo $finalPrice;
                exit;
            }
       
            if ($logged_user == BORROWER) {
                $booking_charges = $this->calculateFinalPrice($userId, $sitterId, $getServicesPrice, $numOfDays);
                echo $booking_charges['booking_price'];
                exit;
            }else{
               
                 $booking_charges = $this->calculateFinalPrice($userId, $sitterId, $getServicesPrice, $numOfDays, $numofPets);
                 echo $booking_charges['booking_price'];
                 exit;
                }
                   
        }
    }

    public function calculateFinalPrice($userId, $sitterId, $totalAmount, $numOfDays, $numofPets = 1) {


        
        $criteriaDays = 10;
        $owner_booked_days2 = $this->getBookingDuration($userId, OWNER);
        $sitter_booked_days2 = $this->getBookingDuration($sitterId, SITTER);
        //~ $isOwnerPalcuraFamilyMember			= Yii::$app->commonmethod->isPalcuraFamilyMember($userId);
        //~ $isSitterPalcuraFamilyMember		= Yii::$app->commonmethod->isPalcuraFamilyMember($sitterId);
        $owner_booked_days = $numOfDays;
        $sitter_booked_days = $numOfDays;
        ##### owner is a family member		
        if ($owner_booked_days2 > 50) {
            $isOwnerPalcuraFamilyMember = 0;
        } else {
            $isOwnerPalcuraFamilyMember = 0;
        }

       


        ##### sitter is a family member
        if ($sitter_booked_days2 > 50) {
            $isSitterPalcuraFamilyMember = 1;
        } else {
            $isSitterPalcuraFamilyMember = 0;
        }
       
        $websitePaymentStructure = $this->getebsiteSettings();

        $userdata = Yii::$app->user->identity;
        $varification_badge = (isset($userdata['verification_badge']) ? $userdata['verification_badge'] : 0);
        /* $discountByServiceProvider			= Yii::$app->commonmethod->getFinalSitterDiscount($userId,$sitterId,$totalAmount);
          if($discountByServiceProvider > 0) {
          $serviceProviderD	=	($totalAmount*$discountByServiceProvider)/100;
          $totalAmount		=   $totalAmount-$serviceProviderD;
          } */
        ############### %
       
        $palCuraFamilyMember = (isset($websitePaymentStructure['family_member_discount']) && $websitePaymentStructure['family_member_discount'] > 0 ? $websitePaymentStructure['family_member_discount'] : 1);
        $palCuraFee = (isset($websitePaymentStructure['website_fee']) && $websitePaymentStructure['website_fee'] > 0 ? $websitePaymentStructure['website_fee'] : 15);
        $discount = 0;   #### %
        $paymentGatewayFee = 0; //2.9; #### %
        $paymentGatewayFixed = 0; //.30; ####%
$sitterDiscount=0;
$ownerDiscount = 0; 



if($isSitterPalcuraFamilyMember == 1)
{ 
        $sitterDiscount = $palCuraFamilyMember;
 }

        /*if ($varification_badge == 1) {
            if ($isOwnerPalcuraFamilyMember > 0 && $isSitterPalcuraFamilyMember > 0) {
                if ($owner_booked_days > $criteriaDays && $sitter_booked_days > $criteriaDays) {
                    $ownerDiscount = $discount + $palCuraFamilyMember;
                    $sitterDiscount = $discount + $palCuraFamilyMember;
                } else if ($owner_booked_days > $criteriaDays && $sitter_booked_days <= $criteriaDays) {
                    $ownerDiscount = $discount + $palCuraFamilyMember;
                    $sitterDiscount = $palCuraFamilyMember;
                } else if ($owner_booked_days <= $criteriaDays && $sitter_booked_days > $criteriaDays) {
                    $ownerDiscount = $palCuraFamilyMember;
                    $sitterDiscount = $discount + $palCuraFamilyMember;
                } else {
                    $ownerDiscount = $palCuraFamilyMember;
                    $sitterDiscount = $palCuraFamilyMember;
                }
            } else if ($isOwnerPalcuraFamilyMember > 0 && $isSitterPalcuraFamilyMember == 0) {
                if ($owner_booked_days > $criteriaDays && $sitter_booked_days > $criteriaDays) {
                    $ownerDiscount = $discount + $palCuraFamilyMember;
                    $sitterDiscount = $discount;
                } else if ($owner_booked_days > $criteriaDays && $sitter_booked_days <= $criteriaDays) {
                    $ownerDiscount = $discount + $palCuraFamilyMember;
                    $sitterDiscount = 0;
                } else if ($owner_booked_days <= $criteriaDays && $sitter_booked_days > $criteriaDays) {
                    $ownerDiscount = $palCuraFamilyMember;
                    $sitterDiscount = $discount;
                } else {
                    $ownerDiscount = $palCuraFamilyMember;
                    $sitterDiscount = 0;
                }
            } else  if ($isOwnerPalcuraFamilyMember == 0 && $isSitterPalcuraFamilyMember > 0) {
                if ($owner_booked_days > $criteriaDays && $sitter_booked_days > $criteriaDays) {
                    $ownerDiscount = $discount;
                    $sitterDiscount = $discount + $palCuraFamilyMember;
                } else if ($owner_booked_days > $criteriaDays && $sitter_booked_days <= $criteriaDays) {
                    $ownerDiscount = $discount;
                    $sitterDiscount = $palCuraFamilyMember;
                } else if ($owner_booked_days <= $criteriaDays && $sitter_booked_days > $criteriaDays) {
                    $ownerDiscount = 0;
                    $sitterDiscount = $discount + $palCuraFamilyMember;
                } else {
                    $ownerDiscount = 0;
                    $sitterDiscount = $palCuraFamilyMember;
                }

            } else {
                if ($owner_booked_days > $criteriaDays && $sitter_booked_days > $criteriaDays) {
                    $ownerDiscount = $discount;
                    $sitterDiscount = $discount;
                } else if ($owner_booked_days > $criteriaDays && $sitter_booked_days <= $criteriaDays) {
                    $ownerDiscount = $discount;
                    $sitterDiscount = 0;
                } else if ($owner_booked_days <= $criteriaDays && $sitter_booked_days > $criteriaDays) {
                    $ownerDiscount = 0;
                    $sitterDiscount = $discount;
                } else {
                    $ownerDiscount = 0;
                    $sitterDiscount = 0;
                }
            }
        } else {
            $ownerDiscount = 0;
            $sitterDiscount = 0;
        }*/
        //as per new functionality owner dicount will be zero
        $ownerDiscount = 0;
       
        $totalAmount = $totalAmount * $numofPets;
        $ownerPalCuraFee = $palCuraFee - $ownerDiscount;
        $sitterPalCuraFee = $palCuraFee - $sitterDiscount;
        $adminFee = ($totalAmount * $ownerPalCuraFee) / 100;
        $adminFee = ($totalAmount * $sitterPalCuraFee) / 100;
        $sitterRevenue = $totalAmount - (($totalAmount * $sitterPalCuraFee) / 100);
        $totalAmount = $totalAmount - (($ownerDiscount) * $totalAmount) / 100;
        $ownerTransactionFee = (($totalAmount * $paymentGatewayFee) / 100) + $paymentGatewayFixed;
        $sitterTransactionFee = (($sitterRevenue * $paymentGatewayFee) / 100) + $paymentGatewayFixed;
        $palCuraRevenue = $totalAmount - ($sitterRevenue + $ownerTransactionFee + $sitterTransactionFee);
        $dataArray = array();
        $dataArray['booking_price'] = $totalAmount;
        $dataArray['admin_fee'] = number_format($adminFee,2);
        $dataArray['sitter_revenue'] = number_format($sitterRevenue,2);
        $dataArray['in_payment_transaction_fee'] = $ownerTransactionFee;
        $dataArray['out_payment_transaction_fee'] = $sitterTransactionFee;
        $dataArray['palcura_revenue'] = number_format($palCuraRevenue,2);
        return $dataArray;
    }

    public function getebsiteSettings() {
        $query = new Query;
        $query->select('website_fee,family_member_discount,discount')->from('website_settings');
        return $query->createCommand()->queryOne();
    }

    public function resetFinalPrice($userId, $sitterId, $totalAmount, $numOfDays, $post) {
        $criteriaDays = 10;
        $owner_booked_days2 = $this->getBookingDuration($userId, OWNER);
        $sitter_booked_days2 = $this->getBookingDuration($sitterId, SITTER);
        //~ $isOwnerPalcuraFamilyMember			= Yii::$app->commonmethod->isPalcuraFamilyMember($userId);
        //~ $isSitterPalcuraFamilyMember		= Yii::$app->commonmethod->isPalcuraFamilyMember($sitterId);
        $owner_booked_days = $numOfDays;
        $sitter_booked_days = $numOfDays;
        ##### owner is a family member		
        if ($owner_booked_days2 > 50) {
            $isOwnerPalcuraFamilyMember = 0;
        } else {
            $isOwnerPalcuraFamilyMember = 0;
        }
        ##### sitter is a family member
        if ($sitter_booked_days2 > 50) {
            $isSitterPalcuraFamilyMember = 1;
        } else {
            $isSitterPalcuraFamilyMember = 0;
        }

        $websitePaymentStructure = $this->getebsiteSettings();

        $userdata = Yii::$app->user->identity;
        $varification_badge = (isset($userdata['verification_badge']) ? $userdata['verification_badge'] : 0);
        /* $discountByServiceProvider			= Yii::$app->commonmethod->getFinalSitterDiscount($userId,$sitterId,$totalAmount);
          if($discountByServiceProvider > 0) {
          $serviceProviderD	=	($totalAmount*$discountByServiceProvider)/100;
          $totalAmount		=   $totalAmount-$serviceProviderD;
          } */
        ############### %
        $palCuraFamilyMember = (isset($websitePaymentStructure['family_member_discount']) && $websitePaymentStructure['family_member_discount'] > 0 ? $websitePaymentStructure['family_member_discount'] : 0);
        $palCuraFee = (isset($websitePaymentStructure['website_fee']) && $websitePaymentStructure['website_fee'] > 0 ? $websitePaymentStructure['website_fee'] : 15);
        $discount = 0;   #### %
        $paymentGatewayFee = 0; //2.9; #### %
        $paymentGatewayFixed = 0; //.30; ####%

$sitterDiscount=0;
$ownerDiscount = 0; 
if($isSitterPalcuraFamilyMember == 1){ $sitterDiscount = $palCuraFamilyMember; }

        /*if ($varification_badge == 1) {
            if ($isOwnerPalcuraFamilyMember > 0 && $isSitterPalcuraFamilyMember > 0) {
                if ($owner_booked_days > $criteriaDays && $sitter_booked_days > $criteriaDays) {
                    $ownerDiscount = $discount + $palCuraFamilyMember;
                    $sitterDiscount = $discount + $palCuraFamilyMember;
                } else if ($owner_booked_days > $criteriaDays && $sitter_booked_days <= $criteriaDays) {
                    $ownerDiscount = $discount + $palCuraFamilyMember;
                    $sitterDiscount = $palCuraFamilyMember;
                } else if ($owner_booked_days <= $criteriaDays && $sitter_booked_days > $criteriaDays) {
                    $ownerDiscount = $palCuraFamilyMember;
                    $sitterDiscount = $discount + $palCuraFamilyMember;
                } else {
                    $ownerDiscount = $palCuraFamilyMember;
                    $sitterDiscount = $palCuraFamilyMember;
                }
            } else if ($isOwnerPalcuraFamilyMember > 0 && $isSitterPalcuraFamilyMember == 0) {
                if ($owner_booked_days > $criteriaDays && $sitter_booked_days > $criteriaDays) {
                    $ownerDiscount = $discount + $palCuraFamilyMember;
                    $sitterDiscount = $discount;
                } else if ($owner_booked_days > $criteriaDays && $sitter_booked_days <= $criteriaDays) {
                    $ownerDiscount = $discount + $palCuraFamilyMember;
                    $sitterDiscount = 0;
                } else if ($owner_booked_days <= $criteriaDays && $sitter_booked_days > $criteriaDays) {
                    $ownerDiscount = $palCuraFamilyMember;
                    $sitterDiscount = $discount;
                } else {
                    $ownerDiscount = $palCuraFamilyMember;
                    $sitterDiscount = 0;
                }
            } else if ($isOwnerPalcuraFamilyMember == 0 && $isSitterPalcuraFamilyMember > 0) {
                if ($owner_booked_days > $criteriaDays && $sitter_booked_days > $criteriaDays) {
                    $ownerDiscount = $discount;
                    $sitterDiscount = $discount + $palCuraFamilyMember;
                } else if ($owner_booked_days > $criteriaDays && $sitter_booked_days <= $criteriaDays) {
                    $ownerDiscount = $discount;
                    $sitterDiscount = $palCuraFamilyMember;
                } else if ($owner_booked_days <= $criteriaDays && $sitter_booked_days > $criteriaDays) {
                    $ownerDiscount = 0;
                    $sitterDiscount = $discount + $palCuraFamilyMember;
                } else {
                    $ownerDiscount = 0;
                    $sitterDiscount = $palCuraFamilyMember;
                }
                $ownerDiscount = 0;
                $sitterDiscount = $palCuraFamilyMember;
            } else {
                if ($owner_booked_days > $criteriaDays && $sitter_booked_days > $criteriaDays) {
                    $ownerDiscount = $discount;
                    $sitterDiscount = $discount;
                } else if ($owner_booked_days > $criteriaDays && $sitter_booked_days <= $criteriaDays) {
                    $ownerDiscount = $discount;
                    $sitterDiscount = 0;
                } else if ($owner_booked_days <= $criteriaDays && $sitter_booked_days > $criteriaDays) {
                    $ownerDiscount = 0;
                    $sitterDiscount = $discount;
                } else {
                    $ownerDiscount = 0;
                    $sitterDiscount = 0;
                }
            }
        } else {
            $ownerDiscount = 0;
            $sitterDiscount = 0;
            //$palCuraFee = 20;
        }*/
      
        $ownerPalCuraFee = $palCuraFee - $ownerDiscount;
       $sitterPalCuraFee = $palCuraFee - $sitterDiscount;

        $adminFee = ($totalAmount * $ownerPalCuraFee) / 100;
        $adminFee = ($totalAmount * $sitterPalCuraFee) / 100;
        $sitterRevenue = $totalAmount - (($totalAmount * $sitterPalCuraFee) / 100);
        $totalAmount = $totalAmount - (($ownerDiscount) * $totalAmount) / 100;
        $ownerTransactionFee = (($totalAmount * $paymentGatewayFee) / 100) + $paymentGatewayFixed;
        $sitterTransactionFee = (($sitterRevenue * $paymentGatewayFee) / 100) + $paymentGatewayFixed;
        $palCuraRevenue = $totalAmount - ($sitterRevenue + $ownerTransactionFee + $sitterTransactionFee);

        $dataArray = array();
        $dataArray['booking_price'] = $totalAmount;
        $dataArray['admin_fee'] = number_format($adminFee,2);
        $dataArray['sitter_revenue'] = number_format($sitterRevenue,2);
        $dataArray['in_payment_transaction_fee'] = $ownerTransactionFee;
        $dataArray['out_payment_transaction_fee'] = $sitterTransactionFee;
        $dataArray['palcura_revenue'] = number_format($palCuraRevenue,2);
        return $dataArray;
    }

    public function actionConfirm($id) {
        
        /* $model = new Booking();
          $post 			= Yii::$app->request->post();
          if(isset($post['bookingid']) && !empty($post['bookingid'])) {
          //confirm booking
          $model->confirmbooking($post['bookingid']);
          } */
        $websitefees = $this->getebsiteSettings();
        $model = new BookingDiscount();
        $bookingModel = new Booking();
        $bookingdata = Booking::findOne(['id' => $id]);
       

        if(empty($bookingdata)){
        return $this->redirect(['bookings/index']);
        }elseif(!empty($bookingdata) && $bookingdata->booking_status != 0){
        return $this->redirect(['bookings/index']);
        }
        if (($model->load(Yii::$app->request->post()) && $model->validate())) {
            $post = Yii::$app->request->post();
           
            $numOfDays = 1;
            $date_from = strtotime($post['booking_from_date']);
            $date_to = strtotime($post['booking_to_date']);
            $datediff = $date_to - $date_from;
            $sDays = ceil($datediff / (60 * 60 * 24));
            if ($sDays > 0) {
                $numOfDays = $numOfDays + $sDays;
            }
            
            $booking_type = $post['renter_id'];
            if ($post['renter_id'] > 0 && sitter == 0) {
                $owner = $post['renter_id'];
                $sitter = $post['owner_id'];
            } else {
                $owner = $post['owner_id'];
                $sitter = $post['sitter_id'];
            }
            $totalAmount = $post['final_price'];
  
            $resetdataArray = $this->resetFinalPrice($owner, $sitter, $totalAmount, $numOfDays, $post);

            if (!empty($resetdataArray)) {
                $sitterrevenue = $resetdataArray['sitter_revenue'];
                $creditsgained = $bookingdata['booking_credits'];
                $updateBooking = $bookingModel->confirmbooking($id, $resetdataArray);
                if ($updateBooking) {

                    $bookingDate = $post['booking_from_date'];
                    //$datediff = strtotime($post['booking_to_date']) - strtotime($post['booking_from_date']);
                    $bookingDate = date('m/d/y', strtotime($post['booking_from_date']));
                    //if ($datediff > 0) {
                     $bookingDate .= ' - ' . date('m/d/y', strtotime($post['booking_to_date']));
                    //}
                    //send email to both users

                    $services = $post['services'];

                    $this->bookAcceptmailtoServiceprovider($owner, $sitter, $sitterrevenue, $booking_type, $bookingDate, $services);
                    //$this->bookAcceptmailtoServiceprovider($owner, $sitter, $creditsgained, $booking_type, $bookingDate, $services);
                    $this->bookingconfirmedemail($owner, $sitter, $totalAmount, $booking_type, $bookingDate, $services);

                    Yii::$app->session->setFlash('success', 'Thanks for accepting pet care request.');
                    return $this->redirect(['bookings/index']);
                } else {
                    Yii::$app->session->setFlash('success', 'Their was an error confirming pet care request.');
                    return $this->redirect(['bookings/index']);
                }
            } else {
                Yii::$app->session->setFlash('success', 'Their was an error confirming pet care request.');
                return $this->redirect(['bookings/index']);
            }
        }
        
        if (!empty($id)) {
        
            //$bookingdata = Booking::findOne(['id' => $id]);
            $userPet = new UserPet();
            $pets = $userPet->getPets($bookingdata['pet_id']);

            return $this->render('confirm', [
                        'bookingid' => $id,
                        'bookingdata' => $bookingdata,
                        'model' => $model,
                        'pets' => $pets,
            ]);
        } else {
            return $this->redirect(['bookings/index']);
        }
    }

    public function actionRejectbooking() {
        $model = new Booking();
        $post = Yii::$app->request->post();
        if ($post) {
            $bookingid = $post['bookingid'];
            $reject = $model->rejectbooking($bookingid);
            if ($reject) {
                $booking_type = $post['renter_id'];
                //send rejection email to owner
                if ($post['renter_id'] > 0 && $post['sitter_id'] == 0) {
                    $ownerid = $post['renter_id'];
                    $sitterid = $post['owner_id'];
                } else {
                    $ownerid = $post['owner_id'];
                    $sitterid = $post['sitter_id'];
                }
                $from_date = $post['from_date'];
                $to_date = $post['to_date'];
                $bookingname = $post['booking_name'];

                $bookingDate = $post['from_date'];
                //$datediff = strtotime($post['to_date']) - strtotime($post['from_date']);
                $bookingDate = date('m/d/y', strtotime($post['from_date']));
                //if ($datediff > 0) {
                 $bookingDate .= ' - ' . date('m/d/y', strtotime($post['to_date']));
                //}
                $this->bookingdeclinedemail($ownerid, $sitterid, $from_date, $to_date, $bookingname, $booking_type, $bookingDate);
                return true;
            } else {
                return false;
            }
        }
    }

    public function bookAcceptmailtoServiceprovider($owner, $sitter, $bookingPriceForSitter, $booking_type, $bookingDate, $services) {
        $adminEmail = Yii::$app->commonmethod->getAdminEmailID();
        if ($booking_type > 0) {

            $nameS = "Renter name";
            //$petinfo = Vaccinationdetails::find()->select('pet_name')->where(['id' => $sitter])->One();
            //$services = $booking->services;
            //$services = implode(',',$services);
            //$query = new Query;
            //$query->select('name')->from('services')->where(['IN','id',$services]);
            //$servicesnames  = $query->createCommand()->queryAll();
            //$serviceName = implode(',',$servicesnames[0]['name']); 
            $serviceName = '';

            $ownerinfo = User::find()->select('firstname,lastname')->where(['id' => $owner])->One();
            $sitterinfo = User::find()->select('firstname,lastname,email')->where(['id' => $sitter])->One();

            $ownerName = (isset($ownerinfo->firstname) ? $ownerinfo->firstname : '');
            $sitterEmail = (isset($sitterinfo->email) ? $sitterinfo->email : '');
            $sitterName = (isset($sitterinfo->firstname) ? $sitterinfo->firstname : '');
            $subject = "You have accepted a borrowing request!";
            $message = '';
            $message .= '<tr>
                            <td height="5" style="font-size:13px; color:#656565; line-height:5px; padding-bottom:10px;">Hi ' . $sitterName . ',</td>
                          </tr>
                          <tr>
                            <td style="font-size:13px; color:#656565; line-height:18px; padding-bottom:10px;">Thank you for accepting borrowing request for your Pal.</td>
                          </tr>
                          <tr>
                            <td height="5"></td>
                          </tr>
                          <tr>
                            <td align="left">
								<ul style="background-color: #fff3e3; color:#000000; font-size:15px; list-style:none; margin:0; padding:0; font-weight:600; padding: 10px 20px; letter-spacing: 0.3px;">
									<li style="line-height:30px; text-decoration:underline">Itinerary</li>
									<li>Date(s): <span style="font-style:italic; font-weight:400; line-height:30px;">' . $bookingDate . '</span></li>
									<li>Service Type: <span style="font-weight:400; line-height:30px;">' . $services . '</span></li>
									<li>Your Earnings: <span style="font-weight:400; line-height:30px;">' . CURRENCY_SIGN . $bookingPriceForSitter . '</span></li>
								</ul>
							</td>
                          </tr>';
        } else {
            $nameS = "Owner name";
            //$petinfo = Vaccinationdetails::find()->select('pet_name')->where(['id' => $owner])->One();

            $serviceName = '';

            $ownerinfo = User::find()->select('firstname,lastname')->where(['id' => $owner])->One();
            $sitterinfo = User::find()->select('firstname,lastname,email')->where(['id' => $sitter])->One();

            $ownerName = (isset($ownerinfo->firstname) ? $ownerinfo->firstname : '');
            $sitterEmail = (isset($sitterinfo->email) ? $sitterinfo->email : '');
            $sitterName = (isset($sitterinfo->firstname) ? $sitterinfo->firstname : '');
            $subject = "You have accepted a service request!";
            $message = '';
            $message .= '<tr>
                            <td height="5" style="font-size:13px; color:#656565; line-height:5px; padding-bottom:10px;">Hi ' . $sitterName . ',</td>
                          </tr>
                          <tr>
                            <td style="font-size:13px; color:#656565; line-height:18px; padding-bottom:10px;">Thank you for accepting the pet care request from ' . $ownerName . '. We are sure you are going to have a great time!</td>
                          </tr>
                          <tr>
                            <td height="5"></td>
                          </tr>
                          <tr>
                            <td align="left">
								<ul style="background-color: #fff3e3; color:#000000; font-size:15px; list-style:none; margin:0; padding:0; font-weight:600; padding: 10px 20px; letter-spacing: 0.3px;">
									<li style="line-height:30px; text-decoration:underline">Itinerary</li>
									<li>Date (s): <span style="font-style:italic; font-weight:400; line-height:30px;">' . $bookingDate . '</span></li>
									<li>Service Type: <span style="font-weight:400; line-height:30px;">' . $services . '</span></li>
									
									<li>Your Earnings: <span style="font-weight:400; line-height:30px;">' . CURRENCY_SIGN . $bookingPriceForSitter . '</span></li>
								</ul>
							</td>
                          </tr>
                          <tr>
                            <td height="15"></td>
                            </tr>		
	                          <tr style="color:#656565; font-size:13px; line-height:19px;" >		
	                            <td>Please click <a href="' . SITE_URL . 'bookings" target="_blank">here</a> to access your account. Remember to post activities, upload pictures and videos when you are taking care of '.$ownerName.'’s pal. </td>
                          </tr>';
        }


        $mail = Yii::$app->mailer->compose(['html' => 'layouts/mail'], ['content' => $message, 'subject' => $subject])
                ->setFrom([$adminEmail => 'Palcura'])
                ->setTo($sitterEmail)
                ->setSubject($subject)
                ->send();

        return $mail;
    }

    public function bookingconfirmedemail($owner, $sitter, $totalAmount, $booking_type, $bookingDate, $services) {
        $adminEmail = Yii::$app->commonmethod->getAdminEmailID();
        if ($booking_type > 0) {

            $nameS = "Renter name";
            //$petinfo = Vaccinationdetails::find()->select('pet_name')->where(['id' => $sitter])->One();
            //$services = $booking->services;
            //$services = implode(',',$services);
            //$query = new Query;
            //$query->select('name')->from('services')->where(['IN','id',$services]);
            //$servicesnames  = $query->createCommand()->queryAll();
            //$serviceName = implode(',',$servicesnames[0]['name']); 
            $serviceName = '';

            $ownerinfo = User::find()->select('firstname,lastname,email')->where(['id' => $owner])->One();
            $sitterinfo = User::find()->select('firstname,lastname,email')->where(['id' => $sitter])->One();

            $ownerName = (isset($ownerinfo->firstname) ? $ownerinfo->firstname : '');
            $sitterEmail = (isset($sitterinfo->email) ? $sitterinfo->email : '');
            $ownerEmail = (isset($ownerinfo->email) ? $ownerinfo->email : '');
            $sitterName = (isset($sitterinfo->firstname) ? $sitterinfo->firstname : '');
            $subject = "Your borrowing request has been accepted!";
            $message = '';
            $message .= '<tr>
                            <td height="5" style="font-size:13px; color:#656565; line-height:5px; padding-bottom:10px;">Hi ' . $ownerName . ',</td>
                          </tr>
                          <tr>
                            <td style="font-size:13px; color:#656565; line-height:18px; padding-bottom:10px;">Your borrowing request has been accepted. Please go ahead and make the payment. Your card will be charged on the day of service.</td>
                          </tr>
                          <tr>
                            <td height="5"></td>
                          </tr>
                          <tr>
                            <td align="left">
								<ul style="background-color: #fff3e3; color:#000000; font-size:15px; list-style:none; margin:0; padding:0; font-weight:600; padding: 10px 20px; letter-spacing: 0.3px;">
									<li style="line-height:30px; text-decoration:underline">Itinerary:</li>
									<li>Date(s): <span style="font-style:italic; font-weight:400; line-height:30px;">' . $bookingDate . '</span></li>
									<li>Service Type: <span style="font-weight:400; line-height:30px;">' . $services . '</span></li>									
									<li>Price: <span style="font-weight:400; line-height:30px;">' . CURRENCY_SIGN . $totalAmount . '</span></li>
								</ul>
							</td>
                          </tr>
                          <tr>
                            <td height="15"></td>
                          </tr>
                          <tr style="color:#656565; font-size:13px; line-height:19px;" >
                            <td>Please click <a href="' . SITE_URL . 'bookings" target="_blank">here</a> to access your account. Remember to post activities, upload pictures, and videos when you are taking care of '.$sitterName.'’s pal..</td>
                          </tr>';
        } else {
            $nameS = "Owner name";
            //$petinfo = Vaccinationdetails::find()->select('pet_name')->where(['id' => $owner])->One();

            $serviceName = '';

            $ownerinfo = User::find()->select('firstname,lastname,email')->where(['id' => $owner])->One();
            $sitterinfo = User::find()->select('firstname,lastname,email')->where(['id' => $sitter])->One();

            $ownerName = (isset($ownerinfo->firstname) ? $ownerinfo->firstname : '');
            $sitterEmail = (isset($sitterinfo->email) ? $sitterinfo->email : '');
            $ownerEmail = (isset($ownerinfo->email) ? $ownerinfo->email : '');
            $sitterName = (isset($sitterinfo->firstname) ? $sitterinfo->firstname : '');
            $subject = "Your pet care request has been accepted!";
            $message = '';

            $message .= '<tr>
                            <td height="5" style="font-size:13px; color:#656565; line-height:5px; padding-bottom:10px;">Hi ' . $ownerName . ',</td>
                          </tr>
                          <tr>
                            <td style="font-size:13px; color:#656565; line-height:18px; padding-bottom:10px;">Great news! Your service request with ' . $sitterName . ' has been accepted! Please go ahead and make the payment. </td>
                          </tr>
                          <tr>
                            <td height="5"></td>
                          </tr>
                          <tr>
                            <td align="left">
								<ul style="background-color: #fff3e3; color:#000000; font-size:15px; list-style:none; margin:0; padding:0; font-weight:600; padding: 10px 20px; letter-spacing: 0.3px;">
									<li style="line-height:30px; text-decoration:underline">Itinerary:</li>
									<li>Date(s): <span style="font-style:italic; font-weight:400; line-height:30px;">' . $bookingDate . '</span></li>
									<li>Service Type: <span style="font-weight:400; line-height:30px;">' . $services . '</span></li>
									<li>Pet Care Provider: <span style="font-weight:400; line-height:30px;">' . $sitterName . '</span></li>
									<li>Price: <span style="font-weight:400; line-height:30px;">' . CURRENCY_SIGN . $totalAmount . '</span></li>
								</ul>
							</td>
                          </tr>
                          <tr>
                            <td height="15"></td>
                            </tr>		
                          <tr style="color:#656565; font-size:13px; line-height:19px;" >		
	                            <td>Please click <a href="' . SITE_URL . 'bookings" target="_blank">here</a> to access your account. We will notify you when ' . $sitterName . ' posts activities and photos/videos of your pal.</td>
                          </tr>';
        }


        $mail = Yii::$app->mailer->compose(['html' => 'layouts/mail'], ['content' => $message, 'subject' => $subject])
                ->setFrom([$adminEmail => 'Palcura'])
                ->setTo($ownerEmail)
                ->setSubject($subject)
                ->send();

        return $mail;
    }

    public function bookingdeclinedemail($owner, $sitter, $from_date, $to_date, $bookingname, $booking_type, $bookingDate) {

        $adminEmail = Yii::$app->commonmethod->getAdminEmailID();
        if ($booking_type > 0) {

            $nameS = "Renter name";
            //$petinfo = Vaccinationdetails::find()->select('pet_name')->where(['id' => $sitter])->One();
            //$services = $booking->services;
            //$services = implode(',',$services);
            //$query = new Query;
            //$query->select('name')->from('services')->where(['IN','id',$services]);
            //$servicesnames  = $query->createCommand()->queryAll();
            //$serviceName = implode(',',$servicesnames[0]['name']); 
            $serviceName = '';

            $ownerinfo = User::find()->select('firstname,lastname,email')->where(['id' => $owner])->One();
            $sitterinfo = User::find()->select('firstname,lastname,email')->where(['id' => $sitter])->One();

            $ownerName = (isset($ownerinfo->firstname) ? $ownerinfo->firstname : '');
            $sitterEmail = (isset($sitterinfo->email) ? $sitterinfo->email : '');
            $ownerEmail = (isset($ownerinfo->email) ? $ownerinfo->email : '');
            $sitterName = (isset($sitterinfo->firstname) ? $sitterinfo->firstname : '');
            $subject = "Your borrowing request is declined!";
            $message = '';
            $message .= '<tr>
                            <td height="5" style="font-size:13px; color:#656565; line-height:5px; padding-bottom:10px;">Hi ' . $ownerName . ',</td>
                          </tr>
                          <tr>
                            <td style="font-size:13px; color:#656565; line-height:18px; padding-bottom:10px;">' . $sitterName . ' declined your borrowing request. </td>
                          </tr>
                          <tr>
                            <td height="5"></td>
                          </tr>
                          <tr>
                            <td align="left">
								<ul style="background-color: #fff3e3; color:#000000; font-size:15px; list-style:none; margin:0; padding:0; font-weight:600; padding: 10px 20px; letter-spacing: 0.3px;">
									<li style="line-height:30px; text-decoration:underline">Itinerary:</li>
									<li>Date(s): <span style="font-style:italic; font-weight:400; line-height:30px;">' . $bookingDate . '</span></li>
									<li>Booking Name: <span style="font-weight:400; line-height:30px;">' . $bookingname . '</span></li>
									
								</ul>
							</td>
                          </tr>
                          <tr>
                            <td height="15"></td>
                          </tr>
                          <tr style="color:#656565; font-size:13px; line-height:19px;" >
                            <td>Please click <a href="' . SITE_URL . 'users/dashboard" target="_blank">here</a> to access your account.</td>
                          </tr>';
        } else {
            $nameS = "Owner name";
            //$petinfo = Vaccinationdetails::find()->select('pet_name')->where(['id' => $owner])->One();

            $serviceName = '';

            $ownerinfo = User::find()->select('firstname,lastname,email')->where(['id' => $owner])->One();
            $sitterinfo = User::find()->select('firstname,lastname,email')->where(['id' => $sitter])->One();

            $ownerName = (isset($ownerinfo->firstname) ? $ownerinfo->firstname : '');
            $sitterEmail = (isset($sitterinfo->email) ? $sitterinfo->email : '');
            $ownerEmail = (isset($ownerinfo->email) ? $ownerinfo->email : '');
            $sitterName = (isset($sitterinfo->firstname) ? $sitterinfo->firstname : '');
            $subject = "Your pet care request has been declined!";
            $message = '';
            $message .= '<tr>
                            <td height="5" style="font-size:13px; color:#656565; line-height:5px; padding-bottom:10px;">Hi ' . $ownerName . ',</td>
                          </tr>
                          <tr>
                            <td style="font-size:13px; color:#656565; line-height:18px; padding-bottom:10px;">' . $sitterName . ' declined your pet care request. </td>
                          </tr>
                          <tr>
                            <td height="5"></td>
                          </tr>
                          <tr>
                            <td align="left">
								<ul style="background-color: #fff3e3; color:#000000; font-size:15px; list-style:none; margin:0; padding:0; font-weight:600; padding: 10px 20px; letter-spacing: 0.3px;">
									<li style="line-height:30px; text-decoration:underline">Itinerary:</li>
									<li>Date(s): <span style="font-style:italic; font-weight:400; line-height:30px;">' . $bookingDate . '</span></li>
									<li>Booking Name: <span style="font-weight:400; line-height:30px;">' . $bookingname . '</span></li>
									
								</ul>
							</td>
                          </tr>
                          <tr>
                            <td height="15"></td>
                          </tr>
                          <tr style="color:#656565; font-size:13px; line-height:19px;" >
                            <td>Please click <a href="' . SITE_URL . 'users/dashboard" target="_blank">here</a> to access your account.</td>
                          </tr>';
        }

        $mail = Yii::$app->mailer->compose(['html' => 'layouts/mail'], ['content' => $message, 'subject' => $subject])
                ->setFrom([$adminEmail => 'Palcura'])
                ->setTo($ownerEmail)
                ->setSubject($subject)
                ->send();

        return $mail;
    }

}
