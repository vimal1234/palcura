<?php

namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Controller;
use yii\helpers\Url;
use common\models\PaymentForm;
use frontend\models\PaymentTransaction;
use frontend\models\users\Users;
use yii\db\Query;
use frontend\models\Booking;
use yii\data\Pagination;
use common\models\User;
use frontend\models\UserPet;

class PaymentsController extends Controller {

    private $limit = 10;

    /**
     * @ Function Name		: actionIndex
     * @ Function Params		: NA 
     * @ Function Purpose 	: default index function that will be called to display payments
     * @ Function Returns	: render view
     */
    public function actionIndex() {
        $userId = Yii::$app->user->getId();
        $dataArray = array();
		
        $session = Yii::$app->session;
        $logged_user = $session->get('loggedinusertype');
        if ($logged_user == OWNER) {
            $where = 'b.pet_sitter_id = ' . $userId . ' OR b.pet_owner_id = ' . $userId;
        } else if ($logged_user == BORROWER) {
            $where = 'b.pet_owner_id = ' . $userId . ' OR b.pet_renter_id = ' . $userId;
        } else {
            $where = 'b.pet_sitter_id = ' . $userId . ' OR b.pet_owner_id = ' . $userId;
        }

        $query = new Query;
        $query->select('payment_transaction.*,pd.status as request_status,pd.id as disbursementid,user.firstname,user.lastname,b.booking_from_date,b.booking_to_date,b.pet_owner_id,b.pet_sitter_id,b.amount as bookingamount,b.admin_fee,b.booking_credits,b.booking_status')
                ->from('payment_transaction')
                ->join('LEFT JOIN', 'booking b', 'b.id = payment_transaction.booking_id')
                ->join('LEFT JOIN', 'payment_disbursements pd', 'pd.booking_id = payment_transaction.booking_id')
                ->join('LEFT JOIN', 'user', 'user.id = payment_transaction.user_id')
                ->where($where)->orderBy('payment_transaction.payment_transaction_id DESC');
                 $bookingFeedback = $query->createCommand()->queryAll();
                
        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count()]);
        $pages->setPageSize($this->limit);
        $query->offset($pages->offset)->limit($this->limit);
        $bookingFeedback = $query->createCommand()->queryAll();
 
//echo "<pre>"; print_r($bookingFeedback); die;
        $dataArray = array_merge($dataArray, [
            'listing' => $bookingFeedback,
            'pages' => $pages,
            
        ]);
        return $this->render('payments', $dataArray);
    }

    public function updateUserCreditsonReqPay($amount){    
      $userId = Yii::$app->user->getId();
    
      $loggedInuserSitterCredits = (float) Yii::$app->user->identity->sitter_credits;
//            $loggedInuserSitterCredits = 30; //test case
      $loggedInuserOwnerCredits = (float) Yii::$app->user->identity->owner_credits;
//            $loggedInuserOwnerCredits = 50; //test case
      $loggedInuserTotalCredit = $loggedInuserSitterCredits + $loggedInuserOwnerCredits;
           
            //$logged_user = $session->get('loggedinusertype');
            //if ($logged_user == OWNER) {
          
            if($loggedInuserTotalCredit == 0){            
            return false;
            }elseif($loggedInuserTotalCredit<$amount){
            return false;
            }else{
           
            	 if ($loggedInuserOwnerCredits == NULL || $loggedInuserOwnerCredits == 0) {
                    $loggedInuserOwnerCredits = 0;
                    $loggedInuserSitterCredits = $loggedInuserSitterCredits-$amount;
                }else{
                	if ($loggedInuserOwnerCredits >= $amount) {
                        $loggedInuserOwnerCredits = $loggedInuserOwnerCredits - $amount;
                    } else {                                             
                        // now we need to deduct sitter credits
                        $loggedInuserSitterCredits = $loggedInuserSitterCredits - $amount;
                    }
                                
                }            
            $model = new PaymentTransaction();
            $model->updateCredits($userId,$loggedInuserOwnerCredits,$loggedInuserSitterCredits);
            return true;
            }

    }
    
    public function actionSendrequest() {
        $post = Yii::$app->request->post();
		if ($post) {
		 $paypalemail = Yii::$app->user->identity->paypal_email;
		 if(!empty($paypalemail)){
		
            $booking_id = $post['booking_id'];
           
            $model = new PaymentTransaction();
            $disbursementdata = $model->getdisbursementdata($booking_id);

            if (empty($disbursementdata)) {
            $bookingCredits = $model->getBookingCredits($booking_id);
			$currentbookingCredits = $bookingCredits['booking_credits'];
			if($this->updateUserCreditsonReqPay($currentbookingCredits)){
                $addrequest = $model->addpaymentRequest($booking_id);
                //send email to admin		
                $this->paymentrequestEmail($booking_id,$currentbookingCredits);
                return true;
              }else{
              	Yii::$app->session->setFlash('error', 'You do not have enough credits for payment request.');
         		return $this->redirect(['payments/index']);
              }  
            } else {
                return false;
            }
            
         }else{
         Yii::$app->session->setFlash('error', 'Please go to "Dashboard >> Edit Profile" and save the email id associated with your PayPal Account. After you are done, come back to "Booking" and place the payment request.');
         return $this->redirect(['payments/index']);
         }   
        } else {
            return false;
        }
    }
	
	public function actionSendrequest1() {
		
        $post = Yii::$app->request->post();
		if ($post) {
		 $paypalemail = Yii::$app->user->identity->paypal_email;
		 if(!empty($paypalemail))
		 {
			$dataaryfull = $post['booking_id'];
			$dataary = explode(',', $dataaryfull);

			if(!empty($dataary))
			{	
				foreach($dataary as $bokid)
				{
					
					$booking_id = $bokid;
				   
					$model = new PaymentTransaction();
					$disbursementdata = $model->getdisbursementdata($booking_id);

					if (empty($disbursementdata)) {
					$bookingCredits = $model->getBookingCredits($booking_id);
					$currentbookingCredits = $bookingCredits['booking_credits'];
					if($this->updateUserCreditsonReqPay($currentbookingCredits)){
						$addrequest = $model->addpaymentRequest($booking_id);
						//send email to admin		
						$this->paymentrequestEmail($booking_id,$currentbookingCredits);
						return true;
					  }else{
						Yii::$app->session->setFlash('error', 'You do not have enough credits for payment request.');
						return $this->redirect(['payments/index']);
					  }  
					} else {
						return false;
					}
				}
			}	
			else
			{
				
					Yii::$app->session->setFlash('error', 'No data for payment request.');
					return $this->redirect(['payments/index']);
				
			}
			
            
         }else{
         Yii::$app->session->setFlash('error', 'Please go to "Dashboard >> Edit Profile" and save the email id associated with your PayPal Account. After you are done, come back to "Booking" and place the payment request.');
         return $this->redirect(['payments/index']);
         }   
        } else {
            return false;
        }
    }
	
    public function paymentrequestEmail($booking_id,$currentbookingCredits) {
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
        if ($booking->pet_sitter_id > 0) {
            $sitterdata = User::findOne($booking->pet_sitter_id);
            $sittername = $sitterdata->firstname . ' ' . $sitterdata->lastname;
        }

        $adminEmail = Yii::$app->commonmethod->getAdminEmailID();
        $subject = "Payment Request";
        $message = '';
        $message .= '<tr>';
        $message .= '<td height="26" style="font-size:15px; font-weight:500; color:#2c2c2c;">Dear Admin,</td>';
        $message .= '</tr>';
        $message .= '<tr>';
        $message .= '<td style="font-size:13px; color:#656565; line-height:18px; padding-bottom:10px;">' . $sendername . ' have requested for payment.</td>';
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
						  <td>Amount</td>
						  <td >' . $currentbookingCredits . '</td>
						</tr>

						<tr  bgcolor="#ffffff">
						  <td>Owner name</td>
						  <td >' . $ownername . '</td>
						</tr>
						<tr  bgcolor="#ffffff">
						  <td>Sitter Name</td>
						  <td >' . $sittername . '</td>
						</tr>	
						<tr  bgcolor="#ffffff">
						  <td>Renter Name</td>
						  <td >' . $rentername . '</td>
						</tr>																				
					  </table></td>
					</tr>';

        $mail = Yii::$app->mailer->compose(['html' => 'layouts/mail'], ['content' => $message, 'subject' => $subject])
                ->setFrom([$senderemail => 'Palcura'])
                ->setTo($adminEmail)
                ->setSubject($subject)
                ->send();
        return $mail;
    }

    public function actionPayNow($id) {

        //Yii::$app->session->remove('booking_data');
        $session = Yii::$app->session;
        $bookingdata = array();
        if (!empty($id)) {
            $bookingdata = Booking::findOne(['id' => $id]);
           
            Yii::$app->session->set('booking_details', [
                'booking_name' => $bookingdata->name,
                'pet_sitter_id' => $bookingdata->pet_sitter_id,
                'pet_owner_id' => $bookingdata->pet_owner_id,
                'pet_renter_id' => $bookingdata->pet_renter_id,
                'booking_from_date' => $bookingdata->booking_from_date,
                'booking_to_date' => $bookingdata->booking_to_date,
                'booking_amount' => $bookingdata->amount,
                'booking_admin_fee' => $bookingdata->admin_fee,
                'reward_points' => $bookingdata->reward_points,
                'booking_credits' => $bookingdata->booking_credits,
                'services' => $bookingdata->booking_services,
                'in_payment_transaction_fee' => $bookingdata->in_payment_transaction_fee,
                'out_payment_transaction_fee' => $bookingdata->out_payment_transaction_fee,
                'palcura_revenue' => $bookingdata->palcura_revenue,
                'booking_id' => $id
            ]);
            $userPet = new UserPet();
			
			

            $pets = $userPet->getPets($bookingdata['pet_id']);
			
            return $this->render('pay-now', [
                        'bookingid' => $id,
                        'bookingdata' => $bookingdata,
                        'pets' => $pets
            ]);
        } else {
            return $this->redirect(['bookings/index']);
        }
    }
    
    public function createBraintreeCustomer($currentuser,$paymentmethodnonce){
    


    $paymenttransactionmodel = new PaymentTransaction();
    $result = Yii::$app->braintree->createCustomer([
				'firstName' => Yii::$app->user->identity->firstname,
				'lastName' => Yii::$app->user->identity->lastname,
				'company' => '',
				'email' => Yii::$app->user->identity->email,
				'phone' => Yii::$app->user->identity->phone,
				'fax' => '',
				'paymentMethodNonce' => $paymentmethodnonce,
				'website' => '',
				'creditCard' => [
				'options' => [
				    'verifyCard' => true
				]
			]
			]);
	
			if($result['success']){
			# true
			if($result['cvvresponse'] == 'M'){
			
			 $newcustomerid = $result['customer_id'];
			 $paymenttransactionmodel->updateBraintreeCustId($currentuser,$newcustomerid);
			 return $newcustomerid;
			 }else{			 
			 return 'notverified';			 
			 }
			 }else{
			 return 'notverified';
			 			 
			 }
        
    }

    /**
     * New function for payment
     */
    public function newPaymentFunction($postData, $booking_details, $_n_s_b_d, $model,$nonce,$use_internal_credits) {

	
		
    //$currentuser = Yii::$app->user->identity->id;
    //$customerid = $this->createBraintreeCustomer($currentuser,$nonce);
   // die;
//        #1
	/*$result = Yii::$app->braintree->createCard([
  'customerId' => '511134639',
  'number' => "4111111111111111",
  'expirationDate' => "06/2022",
  'cvv' => "678"
]); 
echo "<pre>"; print_r($result); die;
die;*/
		//testing for braintree
		/*$data=array(
                'amount' => '1',
                'merchantAccountId' => Yii::$app->braintree->MERCHANT_ACCOUNT_ID,
                'creditCard' => array(
                          'number' => '6011000990139424',
                          'expirationMonth' => '05',
                          'expirationYear' => '21',
                          'cvv' => '567'
                             ),
                 'paymentMethodNonce' => 'nonce-from-the-client',
                 'options' => [
				 'submitForSettlement' => True
			  ]            
                             	
               );
   $r=Yii::$app->braintree->sale($data);
   echo "<pre>"; print_r('This page is under construction'); die;*/
   //testing for braintree ends
   
   
   
		//create customer
	// get customerid of user 
	$currentuser = Yii::$app->user->identity->id;
	
	$customerid = Yii::$app->user->identity->braintree_customer_id;

		if(!empty($customerid) && $customerid>0){
			
		
		
		$customerdetails =  Yii::$app->braintree->getCustomerById($customerid);

		if(isset($customerdetails['success']) && $customerdetails['success']==0){
		Yii::$app->session->setFlash('error_mesg', "Sorry we were not able to verify your customer id");
        return $this->redirect(['payments/directpayment']);		
		}
		$customerdetails = $customerdetails['data'];
		$token = $customerdetails->creditCards[0]->token;
		
		$updatepaymentMethod = Yii::$app->braintree->updatepaymentMethod($token,$nonce);
		
		if($updatepaymentMethod['success'] == 0){
		//$errorMsg = $updatepaymentMethod['validation_errors'][0]->message;
$errorMsg = 'Sorry we were unable to verify your card! Please check your card details and try again.';
		
		Yii::$app->session->setFlash('item', $errorMsg);
		return $this->redirect(['payments/index']);		
		}		
			$customerid = $customerid;			
		}else{
			
			$customerid = $this->createBraintreeCustomer($currentuser,$nonce);
			
		}
		
	if($customerid == 'notverified'){
		
	Yii::$app->session->setFlash('item', 'Sorry we were unable to verify your card!');
	return $this->redirect(['payments/index']);
	}
		
        $loggeduser = Yii::$app->user->identity->attributes;
        $userewards = ($postData['userrewards']) ? $postData['userrewards'] : 0;
        $usecredits = ($postData['usercredits']) ? $postData['usercredits'] : 0;
        if ($userewards == 1 || $usecredits == 1) {
            $use_internal_credits = true;
        }


        // Check if user want to use reward points or credit amount
        if ($use_internal_credits == true) { 
            // Need to check check this function (Updated according to new changes)
            $internal_credits = $this->usedRewardsForBooking($booking_details, $_n_s_b_d);
          
		

            // If $internal_credits['status'] is bool(TRUE) it means payment is completely done using reward points and/or credit amount no need to make card payments
            if (isset($internal_credits['status']) && $internal_credits['status'] === true) {
                // Check this function (DONE) this functon updates users table for logged in user and update 
                // Logically this should be done after successful payment (For now I am going with the flow)
                $this->updateUserCredits($loggeduser['id'], $internal_credits['pending_reward_points'], $internal_credits['pending_sitter_credits']);
                // Check this function (DONE) This function update booking table and change payment and booking status
                $this->updateBookingforcredits($booking_details['booking_id']);
                // Check this function ( DONE Just giving reward points to owner and credit amount to sitter)
                // Sitter will get credit amount( same as that of actual billing amount) and owner( who is making payment) will get reward points ($1 spent = 1 point earned)
                $booking_details = $this->updateUserPoints($booking_details,$internal_credits['pending_reward_points']);
                
                //save transaction details
                $transmodel = new PaymentTransaction();
                $transmodel->user_id = $loggeduser['id'];
                $transmodel->booking_id = $booking_details['booking_id'];
                $transmodel->amount = $booking_details['booking_amount'];
                $transmodel->trans_id = uniqid();
                $transmodel->payment_type = 'Credits-Points';
                $transmodel->payment_status = 'Completed';
                $transmodel->currency = CURRENCY_NAME;
                $transmodel->save();
                $emailtype = 1;                
                // Check this function 
                $this->paymentSuccessEmail($booking_details,$emailtype);
                Yii::$app->session->remove('booking_details');
                Yii::$app->session->remove('_n_s_b_d');
                $success_message = Yii::t('yii', "Thank you! You have successfully paid for your booking.");
                
                Yii::$app->session->setFlash('item', $success_message);
                return $this->redirect(['payments/index']);
            }
//            $price = $internal_credits['pending_booking_amount'];
        } else {

			

            $internal_credits = array(
                'status' => 0,
                'pending_reward_points' => Yii::$app->user->identity->reward_points,
                'pending_sitter_credits' => Yii::$app->user->identity->sitter_credits+Yii::$app->user->identity->owner_credits,
                'pending_booking_amount' => $booking_details['booking_amount']
            );
        }
   
        $currency_name = (isset($booking_details['currency_name']) ? $booking_details['currency_name'] : 'USD');
			
	
			
        //get customer detail from braintree
        
       	//$customerdetails =  Yii::$app->braintree->getCustomerById($customerid);
       
      
        

     	
     	//sale code ends here


       // $result = Yii::$app->Paypal->DoDirectPayment($paymentInfo);
       /* if ($result['success']==0) {     
                #####= Sandbox output the actual error message to dive in.
                $error = isset($result['message']) ? $result['message'] : Yii::t('yii', 'We were unable to process your request. Please try again later');
           
            Yii::$app->session->setFlash('error_mesg', $error);
            return $this->redirect(["payments/directpayment"]);*/
       // } else { 
            if ($use_internal_credits == true) {
                $this->updateUserCredits($loggeduser['id'], $internal_credits['pending_reward_points'], $internal_credits['pending_sitter_credits']);
                $this->updateUsedRewardStatus($booking_details['booking_id']);
            }

            $this->updateBooking($booking_details['booking_id']);

			
            $booking_details = $this->updateUserPoints($booking_details,$internal_credits['pending_reward_points']);
            #### save payment transaction information
            $transmodel = new PaymentTransaction();
            $transmodel->user_id = $loggeduser['id'];
            $transmodel->booking_id = $booking_details['booking_id'];
            $transmodel->amount = 0;
            $transmodel->trans_id = 0;
            $transmodel->payment_type = 'card';
            $transmodel->payment_status = 'Pending';
            $transmodel->currency = CURRENCY_NAME;//echo "<pre>"; print_r($transmodel); die;            
            $transmodel->save();
			$emailtype = 2;	
            $this->paymentSuccessEmail($booking_details,$emailtype);
            Yii::$app->session->remove('booking_details');
            Yii::$app->session->remove('_n_s_b_d');
            $success_message = Yii::t('yii', "Your card information has been captured. You will be charged only on the day of service.");
            Yii::$app->session->setFlash('item', $success_message);
            return $this->redirect(['payments/index']);
       // }

        die('new_end');
    }

    /**
     * @ Function Name		: actionDirectpayment
     * @ Function Params	: 
     * @ Function Purpose 	: direct payment 
     * @ Function Returns	: boolean true/false
     */
    public function actionDirectpayment() {


        $loggeduser = Yii::$app->user->identity->attributes;
        $model = new PaymentForm();
        $booking_details = Yii::$app->session->get('booking_details');
        if (!($model->load(Yii::$app->request->post()) && $model->validate())) {
          $postArr = Yii::$app->request->post();

            // set data in session
            $sessionTemp = Yii::$app->session;
            if ($postArr['_n_s_b_d']) {          
           // $creditsAndRewards = json_decode($postArr['_n_s_b_d']);
                  
             Yii::$app->session->set('_n_s_b_d', (array) json_decode($postArr['_n_s_b_d']));
                    
           // $used_user_credits = $creditsAndRewards->creditAmount;
            //$used_reward_points = ($creditsAndRewards->rewardPointCredits / 5) * 500;
            //$bookingid = $booking_details['booking_id'];
                          
            }
            $rewardStatus = (isset($postArr['reward_status']) ? $postArr['reward_status'] : 0);
            $creditStatus = (isset($postArr['credit_amount']) ? $postArr['credit_amount'] : 0);
            $data = Yii::$app->commonmethod->getCardInformation($loggeduser['id']);
            $model->fname_oncard = $data['card_holder_name'];
            $model->cc_type = $data['card_type'];
            $model->cc_number = $data['card_number'];
            $model->cvv = $data['card_cvv_number'];
            $model->exp_month = $data['card_exp_month'];
            $model->exp_month = $data['card_exp_year'];
		
            return $this->render('payment-form', [
                        'model' => $model,
                        'data' => $data,
                        'rewardStatus' => $rewardStatus,
                        'creditStatus' => $creditStatus
            ]);
        } else {
  			$postArr = Yii::$app->request->post();
            $nonce = $postArr['nocetoken'];
            $_n_s_b_d = Yii::$app->session->get('_n_s_b_d');

            $price = $booking_details['booking_amount'];
  			
			
            #####= using credits points for booking
            $use_internal_credits = false;

            $postData = Yii::$app->request->post('PaymentForm');
			
			
			
            $data=$this->newPaymentFunction($postData, $booking_details, $_n_s_b_d, $model,$nonce,$use_internal_credits);
            
			
			
			return false;

################################################################################################################
//below code is unused and will be removed later

            $userrewards = (isset($postData['userrewards']) ? $postData['userrewards'] : 0);
            $usercredits = (isset($postData['usercredits']) ? $postData['usercredits'] : 0);
            if ($userrewards == 1 || $usercredits == 1) {
                $use_internal_credits = true;
            }

            // Check if user want to use reward points or credit amount
            if ($use_internal_credits == true) {

                // Need to check check this function (Updated according to new changes)
                $internal_credits = $this->usedRewardsForBooking($booking_details, $_n_s_b_d);

                // If $internal_credits['status'] is bool(TRUE) it means payment is completely done using reward points and/or credit amount no need to make card payments
                if (isset($internal_credits['status']) && $internal_credits['status'] === true) {

                    // Check this function (DONE)
                    $this->updateUserCredits($loggeduser['id'], $internal_credits['pending_reward_points'], $internal_credits['pending_sitter_credits']);
                    // Check this function (DONE)
                    $this->updateBooking($booking_details['booking_id']);
                    // Check this function ( DONE Just giving reward points to owner and credit amount yo sitter)
                    $this->updateUserPoints($booking_details);
                    // Check this function 
                    
                    $this->paymentSuccessEmail($booking_details);
                    Yii::$app->session->remove('booking_details');
                    $success_message = Yii::t('yii', "Thank you! You have successfully paid for your booking.");
                    Yii::$app->session->setFlash('item', $success_message);
                    return $this->redirect(['payments/index']);
                }
                $price = $internal_credits['pending_booking_amount'];
            }
            $currency_name = (isset($booking_details['currency_name']) ? $booking_details['currency_name'] : 'USD');
            $cardHolderName = explode(" ", $model->fname_oncard);
            $fname = (isset($cardHolderName[0]) ? $cardHolderName[0] : $cardHolderName);
            $lname = (isset($cardHolderName[1]) ? $cardHolderName[1] : '');

            $paymentInfo = array('Member' =>
                array(
                    'first_name' => $fname,
                    'last_name' => $lname,
                    'currency_name' => $currency_name,
                    'billing_address' => 'address_here',
                    'billing_address2' => 'address2_here',
                    'billing_country' => 'country_here',
                    'billing_city' => 'city_here',
                    'billing_state' => 'state_here',
                    'billing_zip' => isset($model->billing_zipcode) ? $model->billing_zipcode : ''
                ),
                'CreditCard' =>
                array(
                    'credit_type' => $model->cc_type,
                    'card_number' => $model->cc_number,
                    'expiration_month' => $model->exp_month,
                    'expiration_year' => $model->exp_year,
                    'cv_code' => $model->cvv
                ),
                'Order' =>
                array('theTotal' => $price)
            );

            /*
             * On Success, $result contains [AMT] [CURRENCYCODE] [AVSCODE] [CVV2MATCH]  
             * [TRANSACTIONID] [TIMESTAMP] [CORRELATIONID] [ACK] [VERSION] [BUILD] 
             *  
             * On Fail, $ result contains [AMT] [CURRENCYCODE] [TIMESTAMP] [CORRELATIONID]  
             * [ACK] [VERSION] [BUILD] [L_ERRORCODE0] [L_SHORTMESSAGE0] [L_LONGMESSAGE0]  
             * [L_SEVERITYCODE0]  
             */

            $result = Yii::$app->Paypal->DoDirectPayment($paymentInfo);

            #####= Detect Errors 
            if (!Yii::$app->Paypal->isCallSucceeded($result)) {
                if (Yii::$app->Paypal->apiLive === true) {
                    #####= Live mode basic error message
                    $error = Yii::t('yii', 'We were unable to process your request. Please try again later');
                } else {
                    #####= Sandbox output the actual error message to dive in.
                    $error = isset($result['L_LONGMESSAGE0']) ? $result['L_LONGMESSAGE0'] : Yii::t('yii', 'We were unable to process your request. Please try again later');
                }
                Yii::$app->session->setFlash('error_mesg', $error);
                return $this->redirect(["payments/directpayment"]);
            } else {
                if ($use_internal_credits == true) {
                    $this->updateUserCredits($loggeduser['id'], $internal_credits['pending_reward_points'], $internal_credits['pending_sitter_credits']);
                    $this->updateUsedRewardStatus($booking_details['booking_id']);
                }
                $this->updateBooking($booking_details['booking_id']);
                $this->updateUserPoints($booking_details);
                #### save payment transaction information
                $transmodel = new PaymentTransaction();
                $transmodel->user_id = $loggeduser['id'];
                $transmodel->booking_id = $booking_details['booking_id'];
                $transmodel->amount = $result['AMT'];
                $transmodel->trans_id = $result['TRANSACTIONID'];
                $transmodel->payment_type = $paymentInfo['CreditCard']['credit_type'];
                $transmodel->payment_status = 'Completed';
                $transmodel->currency = CURRENCY_NAME;
				
                $transmodel->save();

                $this->paymentSuccessEmail($booking_details);
                Yii::$app->session->remove('booking_details');
                $success_message = Yii::t('yii', "Thank you! You have successfully paid for your booking.");
                Yii::$app->session->setFlash('item', $success_message);
                return $this->redirect(['payments/index']);
            }
        }
    }

    #####=save used reward points

    public function usedRewardsForBooking($booking_details, $_n_s_b_d = NULL) {
        $user = Yii::$app->user->identity->attributes;
        if ($_n_s_b_d == NULL) {
            $user_reward_points = $user['reward_points'];
            $user_credits = $user['sitter_credits'] + $user['owner_credits'];
            $reward_credits = 0;
            $pending_amount = false;
            if ($user_reward_points >= 500) {
                //$reward_credits	= ($user_reward_points*1)/100;
                $rewardPair = floor($user_reward_points / 500);
                $user_reward_points = $rewardPair * 500;
                $reward_credits = ($user_reward_points * 1) / 100;
            }
            $total_credits = $user_credits + $reward_credits;

            $pending_reward_points = 0;
            $pending_sitter_credits = 0;

            if ($total_credits == $booking_details['booking_amount']) {
                //$this->updateUserCredits($user['id']);
                $used_reward_points = $user_reward_points;
                $used_user_credits = $user_credits;
                $generate_amount = $booking_details['booking_amount'];
            } else if ($total_credits > $booking_details['booking_amount']) {
                if ($reward_credits >= $booking_details['booking_amount']) {
                    $new_reward_points = $reward_credits - $booking_details['booking_amount'];
                    $new_reward_points = $new_reward_points * 100;

                    $pending_reward_points = $user_credits;
                    $pending_sitter_credits = $new_reward_points;

                    //$this->updateUserCredits($user['id'],$new_reward_points,$user_credits);
                    $used_reward_points = $new_reward_points * 100;
                    $used_user_credits = 0;
                    $generate_amount = $booking_details['booking_amount'];
                } else {
                    $new_booking_amount = $booking_details['booking_amount'] - $reward_credits;
                    $new_user_credits = $user_credits - $new_booking_amount;

                    $pending_reward_points = 0;
                    $pending_sitter_credits = $new_user_credits;

                    //$this->updateUserCredits($user['id'],0,$new_user_credits);
                    $used_reward_points = $reward_credits * 100;
                    $used_user_credits = $new_booking_amount;
                    $generate_amount = $booking_details['booking_amount'];
                }
            } else {
                //$this->updateUserCredits($user['id']);
                $used_reward_points = $user_reward_points;
                $used_user_credits = $user_credits;
                $generate_amount = $total_credits;
                $pending_amount = $booking_details['booking_amount'] - $total_credits;
            }

            $newArray = array();
            $newArray['status'] = false;
            $st = PENDING;
            if ($pending_amount == false) {
                $st = ACTIVE;
                $newArray['status'] = true;
            }
        } else {
            $used_user_credits = $_n_s_b_d['creditAmount'];
            $used_reward_points = ($_n_s_b_d['rewardPointCredits'] / 5) * 500;
            $generate_amount = $_n_s_b_d['rewardPointCredits'];
            
            //as per new functionality below two lines are updated
            //$pending_reward_points = $_n_s_b_d['remainingRewardPoints'];
            //$pending_sitter_credits = $_n_s_b_d['remainingCreditAmount'];
            
            /**new code starts**/
             $user_reward_points = $user['reward_points'];
             $user_credits = $user['sitter_credits'] + $user['owner_credits'];            
             /**new code ends**/
            
            $pending_reward_points = $user_reward_points-$used_reward_points;
            if($pending_reward_points<0){
            $pending_reward_points = 0;
            }
            $pending_sitter_credits = $user_credits-$used_user_credits;
            if($pending_sitter_credits<0){
            $pending_sitter_credits = 0;
            }
            
            $pending_amount = $_n_s_b_d['billingAmount'];
            if (isset($_n_s_b_d['billingAmount']) && $_n_s_b_d['billingAmount'] > 0) {
                $st = PENDING;
                $newArray['status'] = false;
            } else {
                $st = ACTIVE;
                $newArray['status'] = true;
            }
        }
        // Now we need to update used_rewards table to mark how much reward points used to make payment of a particular booking id
        $rewardCount = $this->getUsedRewardCreditsCount($booking_details['booking_id']);
        if ($rewardCount > 0) {
            Yii::$app->db->createCommand()->update('used_rewards', [
                'user_id' => $user['id'],
                'user_credits' => $used_user_credits,
                'user_reward_points' => $used_reward_points,
                'generate_amount' => $generate_amount,
                'status' => $st], 'booking_id = ' . $booking_details['booking_id'])->execute();
        } else {
            $query = new Query;
            $query->createCommand()->insert('used_rewards', [
                'booking_id' => $booking_details['booking_id'],
                'user_id' => $user['id'],
                'user_credits' => $used_user_credits,
                'user_reward_points' => $used_reward_points,
                'generate_amount' => $generate_amount,
                'status' => $st])->execute();
        }

        $newArray['pending_reward_points'] = $pending_reward_points;
        $newArray['pending_sitter_credits'] = $pending_sitter_credits;
        $newArray['pending_booking_amount'] = $pending_amount;
             
        return $newArray;
    }

    public function getUsedRewardCreditsCount($booking_id) {
        $query = new Query;
        $query->select('COUNT(id) as cnt')->from('used_rewards')->where(['booking_id' => $booking_id]);
        $data = $query->createCommand()->queryOne();
        return (isset($data['cnt']) ? $data['cnt'] : 0);
    }

    public function updateUsedRewardStatus($booking_id) {
        Yii::$app->db->createCommand()->update('used_rewards', ['status' => ACTIVE], 'booking_id = ' . $booking_id)->execute();
    }
    


    public function updateUserCredits($userId, $reward_points = 0, $pending_credits = 0) { 
        // As per old code we were only updating sitter credit but we also need to update owner credit because we are letting user to use both owner and sitter credits. 
        // if user is logged in as owner owner_credit( which user will get if borrower pay owner) will be diducted first them sitter credits ( which user will get if owner pay sitter).
        // $pending_credits = 10; //test case
        $userId = Yii::$app->user->getId();
       
        $session = Yii::$app->session;
        $pending_credits = (float) $pending_credits;
        if ($pending_credits == 0) {
            $loggedInuserOwnerCredits = 0;
            $loggedInuserSitterCredits = 0;
			
        } else {
            //$loggedInuserSitterCredits = (float) Yii::$app->user->identity->attributes->sitter_credits;
             $loggedInuserSitterCredits = (float) Yii::$app->user->identity->sitter_credits;
//            $loggedInuserSitterCredits = 30; //test case
           // $loggedInuserOwnerCredits = (float) Yii::$app->user->identity->attributes->owner_credits;
             $loggedInuserOwnerCredits = (float) Yii::$app->user->identity->owner_credits;
//            $loggedInuserOwnerCredits = 50; //test case
            $loggedInuserTotalCredit = $loggedInuserSitterCredits + $loggedInuserOwnerCredits;
            $usedCredits = $loggedInuserTotalCredit - $pending_credits;
            $logged_user = $session->get('loggedinusertype');
            //if ($logged_user == OWNER) {
                if ($loggedInuserOwnerCredits == NULL || $loggedInuserOwnerCredits == 0) {
                    $loggedInuserOwnerCredits = 0;
                    $loggedInuserSitterCredits = $pending_credits;
                } else {
                    if ($loggedInuserOwnerCredits >= $usedCredits) {
                        $loggedInuserOwnerCredits = $loggedInuserOwnerCredits - $usedCredits;
                    } else {
                        $usedCredits = $usedCredits - $loggedInuserOwnerCredits;
                        $loggedInuserOwnerCredits = 0;
                        // now we need to deduct sitter credits
                        $loggedInuserSitterCredits = $loggedInuserSitterCredits - $usedCredits;
                    }
                }
            //} else if ($logged_user == BORROWER) {
//            $where = 'b.pet_owner_id = ' . $userId . ' OR b.pet_renter_id = ' . $userId;
           /* } else {
//            $where = 'b.pet_sitter_id = ' . $userId . ' OR b.pet_owner_id = ' . $userId;
            }*/
        }
        // print_r($reward_points); echo "<br>"; print_r($loggedInuserOwnerCredits); echo "<br>"; print_r($loggedInuserSitterCredits);die;
//        Yii::$app->db->createCommand()->update('user', ['reward_points' => $reward_points, 'sitter_credits' => $pending_credits], 'id = ' . $userId)->execute();
			
        Yii::$app->db->createCommand()->update('user', ['reward_points' => $reward_points, 'owner_credits' => (float)$loggedInuserOwnerCredits, 'sitter_credits' => (float)$loggedInuserSitterCredits], 'id = ' . $userId)->execute();
		
		
		
    }

    #####=update user points

    public function updateUserPoints($booking_details,$pendingRewards) {
   
//        $_n_s_b_d['billingAmount'] is amount after applying reward or/and cridits
        $userId = Yii::$app->user->getId();
        $selectColA = "reward_points";
        $userInfoA = Yii::$app->commonmethod->getUserColumnsData($userId, $selectColA);
        // $booking_details['reward_points'] should be same as payment (after applying credit/rewards)
        $_n_s_b_d = Yii::$app->session->get('_n_s_b_d');
        //$reward_points = $_n_s_b_d['billingAmount'] + (isset($userInfoA['reward_points']) ? $userInfoA['reward_points'] : 0);
         //$reward_points = $_n_s_b_d['billingAmount'] + $pendingRewards;
         
         //new updated code for reward points
         $reward_points = $_n_s_b_d['billingAmount'] + $_n_s_b_d['rewardPointCredits'] + $_n_s_b_d['creditAmount'] + $pendingRewards;
         
        if ($reward_points > 2000) {
            $reward_points = 2000;
        }
 
        Yii::$app->db->createCommand()->update('user', ['reward_points' => $reward_points], 'id = ' . $userId)->execute();
        
        
        $selectColB = "sitter_credits";
       if($booking_details['pet_sitter_id']>0){
        $userInfoB = Yii::$app->commonmethod->getUserColumnsData($booking_details['pet_sitter_id'], $selectColB);
        // Credit amount for sitter will be after deducting admin fee (15% for now) need to make it dynamic
        // If sitter is palcura family member( service days > 50) he will get some discount which will be admin managable (for now it's 1.5%)
        $sitterId = $booking_details['pet_sitter_id'];
        $sitter_booked_days = $this->getBookingDuration($sitterId, SITTER);
        
        $query 	= new Query;
        $query->select('website_fee,family_member_discount,discount')->from('website_settings');
        $settings =  $query->createCommand()->queryOne();
        $adminPercentage = Yii::$app->commonmethod->getWebsiteFee();
		    if ($sitter_booked_days > 50) {
		        $adminPercentage = (float)$adminPercentage - (float)$settings['family_member_discount'];
		    }
		  $adminPercentageAmount = (((float) $booking_details['booking_amount'] * $adminPercentage) / 100);
        $booking_credits = (float) $booking_details['booking_amount'] - $adminPercentageAmount;
        $credit_amount = $booking_credits + (isset($userInfoB['sitter_credits']) ? $userInfoB['sitter_credits'] : 0);
        Yii::$app->db->createCommand()->update('user', ['sitter_credits' => $credit_amount], 'id = ' . $booking_details['pet_sitter_id'])->execute();   
        }else{
        $userInfoB = Yii::$app->commonmethod->getUserColumnsData($booking_details['pet_owner_id'], $selectColB);
        $adminPercentage = Yii::$app->commonmethod->getWebsiteFee();
		$adminPercentageAmount = (((float) $booking_details['booking_amount'] * $adminPercentage) / 100);
        $booking_credits = (float) $booking_details['booking_amount'] - $adminPercentageAmount;
        $credit_amount = $booking_credits + (isset($userInfoB['sitter_credits']) ? $userInfoB['sitter_credits'] : 0);
        Yii::$app->db->createCommand()->update('user', ['sitter_credits' => $credit_amount], 'id = ' . $booking_details['pet_owner_id'])->execute();
        }
       
        // we also need to update  booking_credits, reward_points and admin_fee column in booking table as the are not upto date
        Yii::$app->db->createCommand()->update('booking', [
            'booking_credits' => $_n_s_b_d['billingAmount'] + $_n_s_b_d['rewardPointCredits'] + $_n_s_b_d['creditAmount'] - $adminPercentageAmount,
            'reward_points' => $_n_s_b_d['billingAmount'],
            'admin_fee' => $adminPercentageAmount
                ], 'id = ' . $booking_details['booking_id'])->execute();

        $booking_details['booking_credits'] = $_n_s_b_d['billingAmount'] + $_n_s_b_d['rewardPointCredits'] + $_n_s_b_d['creditAmount'];
        $booking_details['reward_points'] = $_n_s_b_d['billingAmount'];
        $booking_details['admin_fee'] = $adminPercentageAmount;
        return $booking_details;

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

    #####=update booking data

    public function updateBooking($bookingID) {
        //echo 'ID'. $bookingID;
        Yii::$app->db->createCommand()->update('booking', ['status' => ACTIVE, 'payment_status' => ACTIVE], 'id = ' . $bookingID)->execute();
        //~ $booking 					= Booking::findOne(['id' => $bookingID]);
        //~ $booking->status 			= ACTIVE;                                
        //~ $booking->payment_status 	= ACTIVE;
        //~ $booking->save();
    }
     public function updateBookingforcredits($bookingID) {
        //echo 'ID'. $bookingID;
        Yii::$app->db->createCommand()->update('booking', ['status' => ACTIVE, 'payment_status' => ACTIVE,'braintree_payment_status' => ACTIVE], 'id = ' . $bookingID)->execute();
        //~ $booking 					= Booking::findOne(['id' => $bookingID]);
        //~ $booking->status 			= ACTIVE;                                
        //~ $booking->payment_status 	= ACTIVE;
        //~ $booking->save();
    }

    #####= remove booking row

    public function removeBooking($bookingID) {
        $book = Booking::findOne(['id' => $bookingID]);
        $book->delete();
    }

    /**
     * @ Function Name		: paymentSuccessEmail
     * @ Function Params	: 
     * @ Function Purpose 	: email template 
     * @ Function Returns	: boolean true/false
     */
    public function paymentSuccessEmail($booking_details,$emailtype) {
        $booking_type = $booking_details['pet_renter_id'];
        //$owner 				= Yii::$app->user->identity->attributes;
        //$sitter 				= \frontend\models\users\Users::findOne($booking_details['book_sitter_id']);
        $booking_id = $booking_details['booking_id'];
        $booking = \backend\models\booking\Booking::findOne($booking_id);

        $bookingDate = date('m/d/y',strtotime($booking->booking_from_date));
        $datediff = strtotime($booking->booking_to_date) - strtotime($booking->booking_from_date);
      
        $bookingDate .= ' - ' . date('m/d/y',strtotime($booking->booking_to_date));

        $bookingPriceForSitter = (isset($booking->booking_credits) ? $booking->booking_credits : $booking->amount - $booking->admin_fee);
        $bookingPricePaidByOwner = $booking->amount;
        $bookingservice = $booking->booking_services;
        $bookingservicenames = '';
        if(!empty($bookingservice)){
        	$query = new Query;
            $query->select('name')->from('services')->where(['IN', 'id', $bookingservice]);

            $servicesnames = $query->createCommand()->queryAll(); 
            $bookingservicenames = implode(', ', array_column($servicesnames, 'name'));
        
        }
        #####= booking email notifications
        $adminEmail = Yii::$app->commonmethod->getAdminEmailID();
        $this->emailToSitterForBookingReq($bookingDate,$bookingPriceForSitter,$booking,$adminEmail,$booking_type,$bookingservicenames);
        #####= payment notification
        $this->emailToAdmin($booking, $adminEmail, $booking_type, $bookingPricePaidByOwner, $bookingDate,$bookingservicenames);
        if($emailtype ==2){
        $this->emailToOwner($bookingDate, $bookingPricePaidByOwner, $booking, $adminEmail, $booking_type,$bookingservicenames);
        }else{
         $this->emailToOwnerOr($bookingDate, $bookingPricePaidByOwner, $booking, $adminEmail, $booking_type,$bookingservicenames);
        }
    }

    /**
     * @ Function Name		: emailToAdmin
     * @ Function Params	: 
     * @ Function Purpose 	: email to admin 
     * @ Function Returns	: boolean true/false
     */
    public function emailToAdmin($booking, $adminEmail, $booking_type, $bookingPricePaidByOwner, $bookingDate,$bookingservicenames) {
        if ($booking_type > 0) {
            $ownerName = (isset($booking->renter->firstname) ? $booking->renter->firstname : '') . ' ' . (isset($booking->renter->lastname) ? $booking->renter->lastname : '');
            $sitterName = (isset($booking->owner->firstname) ? $booking->owner->firstname : '') . ' ' . (isset($booking->owner->lastname) ? $booking->owner->lastname : '');
        } else {
            $ownerName = (isset($booking->owner->firstname) ? $booking->owner->firstname : '') . ' ' . (isset($booking->owner->lastname) ? $booking->owner->lastname : '');
            $sitterName = (isset($booking->sitter->firstname) ? $booking->sitter->firstname : '') . ' ' . (isset($booking->sitter->lastname) ? $booking->sitter->lastname : '');
        }
        $subject = "Payment has been made successfully";
        $message = '';
        $message .= '<tr>';
        $message .= '<td height="26" style="font-size:15px; font-weight:500; color:#2c2c2c;">Dear Admin,</td>';
        $message .= '</tr>';
        $message .= '<tr>';
        $message .= '<td style="font-size:13px; color:#656565; line-height:18px; padding-bottom:10px;">' . $ownerName . ' has successfully paid for booking of the sitter: ' . $sitterName . '.</td>';
        $message .= '</tr>';
        $message .= '<tr>';
        $message .= '<td height="15"></td>';
        $message .= '</tr>';
        $message .= '<tr><td align="left"><table width="500" border="0" bgcolor="#656565" cellspacing="1" cellpadding="6" style=" color:#656565;">
						<!--tr  bgcolor="#ff8447">
						  <td colspan="2" style="border-top:#203367 solid 0px; font-size:14px; font-weight:400; color:#ffffff; text-transform:capitalize;padding:8px;">Booking Detail</td>
						</tr-->
						<tr  bgcolor="#ffffff">
						  <td width="100" >Booking</td>
						  <td width="270" >' . $booking->name . '</td>
						</tr>					
						<tr  bgcolor="#ffffff">
						  <td>Booking Date</td>
						  <td >' . $bookingDate . '</td>
						</tr>
						<tr  bgcolor="#ffffff">
						  <td>Booking From user</td>
						  <td >' . $ownerName . '</td>
						</tr>
						<tr  bgcolor="#ffffff">
						  <td>Booking To user</td>
						  <td >' . $sitterName . '</td>
						</tr>												
						<tr bgcolor="#ffffff">
						  <td>Booking Price</td>
						  <td >' . CURRENCY_SIGN . $bookingPricePaidByOwner . '</td>
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

    /**
     * @ Function Name		: emailToOwner
     * @ Function Params	: 
     * @ Function Purpose 	: email to owner
     * @ Function Returns	: boolean true/false
     */
	 
	  public function emailToOwner($bookingDate, $bookingPricePaidByOwner, $booking, $adminEmail, $booking_type,$bookingservicenames) {
        if ($booking_type > 0) {
            $nameS = "Renter name";
            $ownerName = (isset($booking->renter->firstname) ? $booking->renter->firstname : '');
            $ownerEmail = (isset($booking->renter->email) ? $booking->renter->email : '');
            $sitterName = (isset($booking->owner->firstname) ? $booking->owner->firstname : '');
            $servicetype = '';
        } else {
            $nameS = "Sitter name";
            $ownerName = (isset($booking->owner->firstname) ? $booking->owner->firstname : '') ;
            $ownerEmail = (isset($booking->owner->email) ? $booking->owner->email : '');
            $sitterName = (isset($booking->sitter->firstname) ? $booking->sitter->firstname : '');
            $servicetype = '<li>Service Type: <span style="font-weight:400; line-height:30px;">' . $bookingservicenames . '</span></li>';
        }
        $subject = "Thank you for submitting your card details!";
        $message = '';                      
          $message .= '<tr>
                            <td height="26" style="font-size:15px; font-weight:600; color:#2c2c2c;  ">Hi ' . $ownerName . ',</td>
                          </tr>
                          <tr>
                            <td style="font-size:13px; color:#656565; line-height:18px; padding-bottom:10px;">Your card information has been captured. You will be charged only on the day of service.</td>
                          </tr>
                          <tr>
                            <td height="5"></td>
                          </tr>
                          <tr>
                            <td align="left">
								<ul style="background-color: #fff3e3; color:#000000; font-size:15px; list-style:none; margin:0; padding:0; font-weight:600; padding: 10px 20px; letter-spacing: 0.3px;">
									<li style="line-height:30px; text-decoration:underline">Itinerary</li>		
									<li>Date (s): <span style="font-style:italic; font-weight:400; line-height:30px;">' . $bookingDate . '</span></li>'; 
									$message.=$servicetype;									
									
									$message.='<li>Price: <span style="font-weight:400; line-height:30px;">' . CURRENCY_SIGN . $bookingPricePaidByOwner . '</span></li>
								</ul>
							</td>
                          </tr>
                          <tr>
                            <td height="15"></td>
                          </tr>
<tr style="color:#656565; font-size:13px; line-height:19px;" >
                            <td>Please click <a href="' . SITE_URL . 'bookings" target="_blank">here</a> to access your account. We will notify you when ' . $sitterName . ' posts activities and photos/videos of your pal.</td>
                          </tr>';                

        $mail = Yii::$app->mailer->compose(['html' => 'layouts/mail'], ['content' => $message, 'subject' => $subject])->setFrom([$adminEmail => 'Palcura'])->setTo($ownerEmail)->setSubject($subject)->send();
        return $mail;
    }
	
    public function emailToOwnerOr($bookingDate, $bookingPricePaidByOwner, $booking, $adminEmail, $booking_type,$bookingservicenames) {
        if ($booking_type > 0) {
            $nameS = "Renter name";
            $ownerName = (isset($booking->renter->firstname) ? $booking->renter->firstname : '');
            $ownerEmail = (isset($booking->renter->email) ? $booking->renter->email : '');
            $sitterName = (isset($booking->owner->firstname) ? $booking->owner->firstname : '');
            $servicetype = '';
        } else {
            $nameS = "Sitter name";
            $ownerName = (isset($booking->owner->firstname) ? $booking->owner->firstname : '') ;
            $ownerEmail = (isset($booking->owner->email) ? $booking->owner->email : '');
            $sitterName = (isset($booking->sitter->firstname) ? $booking->sitter->firstname : '');
            $servicetype = '<li>Service Type: <span style="font-weight:400; line-height:30px;">' . $bookingservicenames . '</span></li>';
        }
        $subject = "Payment has been made successfully";
        $message = '';                      
          $message .= '<tr>
                            <td height="26" style="font-size:15px; font-weight:600; color:#2c2c2c;  ">Hi ' . $ownerName . ',</td>
                          </tr>
                          <tr>
                            <td style="font-size:13px; color:#656565; line-height:18px; padding-bottom:10px;">Your payment for the booking has been done successfully. Please click <a href="'.SITE_URL.'payments" target="_blank">here</a> to access your account. Here are the details:</td>
                          </tr>
                          <tr>
                            <td height="5"></td>
                          </tr>
                          <tr>
                            <td align="left">
								<ul style="background-color: #fff3e3; color:#000000; font-size:15px; list-style:none; margin:0; padding:0; font-weight:600; padding: 10px 20px; letter-spacing: 0.3px;">
									<li style="line-height:30px; text-decoration:underline">Itinerary</li>		
									<li>Date (s): <span style="font-style:italic; font-weight:400; line-height:30px;">' . $bookingDate . '</span></li>'; 
									$message.=$servicetype;									
									
									$message.='<li>Price: <span style="font-weight:400; line-height:30px;">' . CURRENCY_SIGN . $bookingPricePaidByOwner . '</span></li>
								</ul>
							</td>
                          </tr>
                         <tr>
                            <td height="15"></td>
                          </tr>
<tr style="color:#656565; font-size:13px; line-height:19px;" >
                            <td>Please click <a href="' . SITE_URL . 'bookings" target="_blank">here</a> to access your account. </td>
                          </tr>';               

        $mail = Yii::$app->mailer->compose(['html' => 'layouts/mail'], ['content' => $message, 'subject' => $subject])->setFrom([$adminEmail => 'Palcura'])->setTo($ownerEmail)->setSubject($subject)->send();
        return $mail;
    }

    /**
     * @ Function Name		: emailToSitterForBookingReq
     * @ Function Params	: 
     * @ Function Purpose 	: email to sitter 
     * @ Function Returns	: boolean true/false
     */
    public function emailToSitterForBookingReq($bookingDate, $bookingPriceForSitter, $booking, $adminEmail, $booking_type,$bookingservicenames) {
        if ($booking_type > 0) {
            $nameS = "Renter name";
            $ownerName = (isset($booking->renter->firstname) ? $booking->renter->firstname : '');
            $sitterEmail = (isset($booking->owner->email) ? $booking->owner->email : '');
            $sitterName = (isset($booking->owner->firstname) ? $booking->owner->firstname : '') ;
            $headmessage = 'Good news! ' . $ownerName . ' has confirmed and paid for the below:';
            $servicetype = '';
        } else {
            $nameS = "Owner name";
            $ownerName = (isset($booking->owner->firstname) ? $booking->owner->firstname : '');
            $sitterEmail = (isset($booking->sitter->email) ? $booking->sitter->email : '');
            $sitterName = (isset($booking->sitter->firstname) ? $booking->sitter->firstname : '');
            $headmessage = 'Good news! ' . $ownerName . ' has confirmed and paid for the below service:';
            $servicetype = '<li>Service Type: <span style="font-weight:400; line-height:30px;">' . $bookingservicenames . '</span></li>';
        }
        $subject = "Payment recieved from " . $ownerName . "";
        $message = '';
					
		 $message .= '<tr>
                            <td height="5" style="font-size:13px; color:#656565; line-height:5px; padding-bottom:10px;">Hi ' . $sitterName . ',</td>
                          </tr>
                          <tr>
                            <td style="font-size:13px; color:#656565; line-height:18px; padding-bottom:10px;">'.$headmessage.'</td>
                          </tr>
                          <tr>
                            <td height="5"></td>
                          </tr>
                          <tr>
                            <td align="left">
								<ul style="background-color: #fff3e3; color:#000000; font-size:15px; list-style:none; margin:0; padding:0; font-weight:600; padding: 10px 20px; letter-spacing: 0.3px;">
									<li style="line-height:30px; text-decoration:underline">Itinerary</li>									
									<li>Date (s): <span style="font-style:italic; font-weight:400; line-height:30px;">' . $bookingDate . '</span></li>';
									$message.=$servicetype;								
									
									$message.='<li>Your Earnings: <span style="font-weight:400; line-height:30px;">' . CURRENCY_SIGN . $bookingPriceForSitter . '</span></li>
								</ul>
							</td>
                          </tr>
							<tr>
								<td height="15"></td>
							</tr>
							<tr style="color:#656565; font-size:13px; line-height:19px;" >
								<td>Please click <a href="' . SITE_URL . 'bookings" target="_blank">here</a> to access your account. </td>
							</tr>';  			

        $mail = Yii::$app->mailer->compose(['html' => 'layouts/mail'], ['content' => $message, 'subject' => $subject])
                ->setFrom([$adminEmail=>'Palcura'])
                ->setTo($sitterEmail)
                ->setSubject($subject)
                ->send();
        return $mail;
    }

}
