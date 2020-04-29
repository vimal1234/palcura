<?php

namespace frontend\controllers;

use Yii;
use common\models\Admin;
use yii\db\Query;

class NotificationController extends \yii\web\Controller
{
	public $enableCsrfValidation = false;
   /**
     * Displays actionGetCountries.
     *
     * @return json
     */
    public function actionCustomerFeedbackNotification() {
		####= Admin Email =####
		$fromEmail = $this->getAdminEmailID();

		//$users = \frontend\models\Booking ::find()->asArray()->all();
		$compareDate	=	date('Y-m-d', strtotime("-10 days"));
		$query  = new Query;
		$query->select('booking.booking_id,booking.booked_from_date,booking.booked_to_date,us.usrFirstname,us.usrLastname,us.email')
		->from('booking')
		->join('LEFT JOIN', 'user us', 'us.id = booking.customer_user_id')
		->where("feedback_customer_email_status = '0' AND booked_to_date <= DATE('".$compareDate."')");
		//$q = $query->createCommand()->getRawSql(); print_r($q);
		//DATEADD(day,30,OrderDate)
		$users	= $query->createCommand()->queryAll();
		//print_r($users); exit();

		##################= send notification to customer =##################
		$booking	=	array();
		$subject  ="Feedback Notification";
		if(isset($users) && !empty($users)) {
			foreach($users as $user) {
				$booking[] = $user['booking_id'];
				$message  ='';
				$message .='<tr>';
					$message .='<td height="26" style="font-size:15px; font-weight:500; color:#333333;">Dear '.@$user['usrFirstname'].' '.@$user['usrLastname'].',</td>';
				$message .='</tr>';
				$message .='<tr>';
					$message .='<td style="font-size:13px; color:#585858; line-height:18px; padding-bottom:10px;">We would like to remind you to accomplish the feedback/rating for your booking. Please click <a href="'.SITE_URL.'" target="_blank">here</a> to access your account.</td>';
				$message .='</tr>';
				$message .='<tr>';
					$message .='<td height="15"></td>';
				$message .='</tr>';
				
				$mail = Yii::$app->mailer->compose(['html' => 'layouts/mail'],['content' => $message, 'subject' => $subject])
				->setFrom($fromEmail)
				->setTo(@$user['email'])
				->setSubject($subject)
				->send();
			}
		}
		if(!empty($booking)) {
			$bookIds = implode(",",$booking);
			Yii::$app->db->createCommand()->update('booking', ['feedback_customer_email_status' => '1'], 'booking_id IN ('.$bookIds.')')->execute();
			echo'Done';
		}
    }
    
    public function actionMemberFeedbackNotification() {
		####= Admin Email =####
		$fromEmail = $this->getAdminEmailID();
		
		$compareDate	=	date('Y-m-d', strtotime("-10 days"));
		$query  = new Query;
		$query->select('booking.booking_id,booking.booked_from_date,booking.booked_to_date,us.usrFirstname,us.usrLastname,us.email')
		->from('booking')
		->join('LEFT JOIN', 'user us', 'us.id = booking.guyde_user_id')
		->where("feedback_guyde_email_status = '0' AND booked_to_date <= DATE('".$compareDate."')");
		$users	= $query->createCommand()->queryAll();

		##################= send notification to guyde =##################
		$booking	=	array();
		$subject  ="Feedback Notification";
		if(isset($users) && !empty($users)) {
			foreach($users as $user) {
				$booking[] = $user['booking_id'];
				$message  ='';
				$message .='<tr>';
					$message .='<td height="26" style="font-size:15px; font-weight:500; color:#333333;">Dear '.@$user['usrFirstname'].' '.@$user['usrLastname'].',</td>';
				$message .='</tr>';
				$message .='<tr>';
					$message .='<td style="font-size:13px; color:#585858; line-height:18px; padding-bottom:10px;">We would like to remind you to accomplish the feedback/rating for your booking. Please click <a href="'.SITE_URL.'" target="_blank">here</a> to access your account.</td>';
				$message .='</tr>';
				$message .='<tr>';
					$message .='<td height="15"></td>';
				$message .='</tr>';
				
				$mail = Yii::$app->mailer->compose(['html' => 'layouts/mail'],['content' => $message, 'subject' => $subject])
				->setFrom($fromEmail)
				->setTo(@$user['email'])
				->setSubject($subject)
				->send();
			}
		}
		if(!empty($booking)) {
			$bookIds = implode(",",$booking);
			Yii::$app->db->createCommand()->update('booking', ['feedback_guyde_email_status' => '1'], 'booking_id IN ('.$bookIds.')')->execute();
			echo'Done';
		}
    }
    
    ###############= Cancel booking after 72 hours =###############  
    public function actionCancelBookingRequest() {
		####= Admin Email =####
		$fromEmail = $this->getAdminEmailID();
		$compareDate	=	date('Y-m-d H:i:s', strtotime("-3 days"));

		$query  = new Query;
		$query->select('booking.*,us.usrFirstname,us.usrLastname,us.email,usg.usrFirstname as uf,usg.usrLastname as ul,usg.email as ue,cc.currency_sign')
		->from('booking')
		->join('LEFT JOIN', 'user us', 'us.id = booking.customer_user_id')
		->join('LEFT JOIN', 'user usg', 'usg.id = booking.guyde_user_id')	
		->join('LEFT JOIN', 'payment_transaction pt', 'pt.booking_id = booking.booking_id')
		->join('LEFT JOIN', 'currencies cc', 'cc.currency_name = pt.currency')
		->where("booking_status = '0' AND booked_on_date <= '".$compareDate."'")
		->groupBy("booking.booking_id");
		$users	= $query->createCommand()->queryAll();
		//echo"<pre>"; print_r($users); exit();
		##################= send notification to guyde =##################
		$booking	=	array();
		$subject  ="Booking request has been cancelled.";
		if(isset($users) && !empty($users)) {
			foreach($users as $user) {
				$booking[] = $user['booking_id'];
				
				$bookingPrice = $user['booking_price'] - ($user['admin_fee']+$user['service_fee']);
				$bookingDate = $user['booked_from_date'];
				$datediff = strtotime($user['booked_to_date']) - strtotime($user['booked_from_date']); 
				if($datediff > 0)
					$bookingDate .= ' - '.$user['booked_to_date'];
				
				#### customer notification
				$message  ='';
				$message .='<tr>';
					$message .='<td height="26" style="font-size:15px; font-weight:500; color:#333333;">Dear '.@$user['usrFirstname'].' '.@$user['usrLastname'].',</td>';
				$message .='</tr>';
				$message .='<tr>';
					$message .='<td style="font-size:13px; color:#585858; line-height:18px; padding-bottom:10px;">Your booking request has been cancelled due to delay response from insider. Payment will be credited to your account within 2-3 days. Please click <a href="'.SITE_URL.'" target="_blank">here</a> to access your account.</td>';
				$message .='</tr>';
				$message .='<tr>';
					$message .='<td height="15"></td>';
				$message .='</tr>';
				$message .='<tr><td align="left"><table width="500" border="0" bgcolor="#2c1f14" cellspacing="1" cellpadding="6" style=" color:#585858;">
						<tr  bgcolor="#333333">
						  <td colspan="2" style="border-top:#333333 solid 0px; font-size:14px; font-weight:400; color:#ffffff; text-transform:capitalize;   padding:8px;">Booking Detail</td>
						</tr>
						<tr  bgcolor="#ffffff">
						  <td width="100" >Booking Location</td>
						  <td width="270" >'.$user['booking_destination'].'</td>
						</tr>
						<tr  bgcolor="#ffffff">
						  <td>Booking Date</td>
						  <td >'.$bookingDate.'</td>
						</tr>';

					if(isset($user['no_of_hours']) && $user['no_of_hours'] > 0) {	
						$message .='<tr  bgcolor="#ffffff">
										<td>Number of Hours</td>
										<td >'.$user['no_of_hours'].'</td>
									</tr>';
					} else {
						$message .='<tr  bgcolor="#ffffff">
										<td>Number of Days</td>
										<td >'. (isset($user['no_of_days']) ? $user['no_of_days'] : '') .'</td>
									</tr>';
					}

					$message .='<tr  bgcolor="#ffffff">
						  <td>Booking Price</td>
						  <td >'.$user['currency_sign'].''.$user['booking_price'].'</td>
						</tr>						
						<tr  bgcolor="#ffffff">
						  <td>Number of Travelers</td>
						  <td >'. (isset($user['no_of_travellers']) ? $user['no_of_travellers'] : 2).'</td>
						</tr>						
					  </table></td>
					</tr>';				
				
				$mail = Yii::$app->mailer->compose(['html' => 'layouts/mail'],['content' => $message, 'subject' => $subject])
				->setFrom($fromEmail)
				->setTo(@$user['email'])
				->setSubject($subject)
				->send();
				
				#### insider notification
				$message2  ='';
				$message2 .='<tr>';
					$message2 .='<td height="26" style="font-size:15px; font-weight:500; color:#333333;">Dear '.@$user['uf'].' '.@$user['ul'].',</td>';
				$message2 .='</tr>';
				$message2 .='<tr>';
					$message2 .='<td style="font-size:13px; color:#585858; line-height:18px; padding-bottom:10px;">The booking request has expired. Please click <a href="'.SITE_URL.'" target="_blank">here</a> to access your account.</td>';
				$message2 .='</tr>';
				$message2 .='<tr>';
					$message2 .='<td height="15"></td>';
				$message2 .='</tr>';
				$message2 .='<tr><td align="left"><table width="500" border="0" bgcolor="#2c1f14" cellspacing="1" cellpadding="6" style=" color:#585858;">
						<tr  bgcolor="#333333">
						  <td colspan="2" style="border-top:#333333 solid 0px; font-size:14px; font-weight:400; color:#ffffff; text-transform:capitalize;   padding:8px;">Booking Detail</td>
						</tr>
						<tr  bgcolor="#ffffff">
						  <td width="100" >Booking Location</td>
						  <td width="270" >'.$user['booking_destination'].'</td>
						</tr>
						<tr  bgcolor="#ffffff">
						  <td>Booking Date</td>
						  <td >'.$bookingDate.'</td>
						</tr>';

					if(isset($user['no_of_hours']) && $user['no_of_hours'] > 0) {	
						$message2 .='<tr  bgcolor="#ffffff">
										<td>Number of Hours</td>
										<td >'.$user['no_of_hours'].'</td>
									</tr>';
					} else {
						$message2 .='<tr  bgcolor="#ffffff">
										<td>Number of Days</td>
										<td >'. (isset($user['no_of_days']) ? $user['no_of_days'] : '') .'</td>
									</tr>';
					}

					$message2 .='<tr  bgcolor="#ffffff">
						  <td>Booking Price</td>
						  <td >'.$user['currency_sign'].''.$bookingPrice.'</td>
						</tr>						
						<tr  bgcolor="#ffffff">
						  <td>Number of Travelers</td>
						  <td >'. (isset($user['no_of_travellers']) ? $user['no_of_travellers'] : 2).'</td>
						</tr>						
					  </table></td>
					</tr>';						
				
				$mail = Yii::$app->mailer->compose(['html' => 'layouts/mail'],['content' => $message2, 'subject' => $subject])
				->setFrom($fromEmail)
				->setTo(@$user['ue'])
				->setSubject($subject)
				->send();
				#### Message notification
				$this->cancelBookingMessage($user,1);
				$this->cancelBookingMessage($user,2);				
						
			}
		}
		if(!empty($booking)) {
			$bookIds = implode(",",$booking);
			Yii::$app->db->createCommand()->update('booking', ['booking_status' => '2'], 'booking_id IN ('.$bookIds.')')->execute();
			echo'Done';
		} 
    }

    ###############= Booking reminder before 120 hours =###############  
    public function actionBookingReminders() {
		####= Admin Email =####
		$fromEmail = $this->getAdminEmailID();
		$compareDate	=	date('Y-m-d H:i:s', strtotime("-5 days"));

		$query  = new Query;
		$query->select('booking.booking_id,booking.booked_from_date,booking.booked_to_date,us.usrFirstname,us.usrLastname,us.email')
		->from('booking')
		->join('LEFT JOIN', 'user us', 'us.id = booking.guyde_user_id')
		->where("booking_status = '0' AND booked_on_date <= '".$compareDate."'");
		$users	= $query->createCommand()->queryAll();
		
		//echo"<pre>"; print_r($users); exit();

		##################= send notification to guyde =##################
		$booking	=	array();
		$subject  ="Booking reminder.";
		if(isset($users) && !empty($users)) {
			foreach($users as $user) {
				$booking[] = $user['booking_id'];
				$message  ='';
				$message .='<tr>';
					$message .='<td height="26" style="font-size:15px; font-weight:500; color:#333333;">Dear '.@$user['usrFirstname'].' '.@$user['usrLastname'].',</td>';
				$message .='</tr>';
				$message .='<tr>';
					$message .='<td style="font-size:13px; color:#585858; line-height:18px; padding-bottom:10px;">This is reminder message for a  booking request. Please Accept/Decline booking to use further action. After 48 hours booking will be cancelled. Please click <a href="'.SITE_URL.'" target="_blank">here</a> to access your account.</td>';
				$message .='</tr>';
				$message .='<tr>';
					$message .='<td height="15"></td>';
				$message .='</tr>';
				
				$mail = Yii::$app->mailer->compose(['html' => 'layouts/mail'],['content' => $message, 'subject' => $subject])
				->setFrom($fromEmail)
				->setTo(@$user['email'])
				->setSubject($subject)
				->send();
			}
		}
    }
 
 
    /**
     * @ Function Name		: cancelBookingMessage
     * @ Function Params	: booking details {array}
     * @ Function Purpose 	: send booking request message
     * @ Function Returns	: return
     */
    public function cancelBookingMessage($booking_details,$res) {

        $thread_id = $this->checkTHreadID($booking_details['customer_user_id'],$booking_details['guyde_user_id']);
        #### 1=CUSTOMER, 2=INSIDER
        if($res == 1) {
			$from 	= $booking_details['customer_user_id'];
			$to 	= $booking_details['guyde_user_id'];
			$message="The booking request has expired.";
		} else {
			$from 	= $booking_details['guyde_user_id'];
			$to 	= $booking_details['customer_user_id'];
			$message="The booking has been cancelled by Insider. The Insider didn't answer and that you should book another Insider.";
		}
        
        Yii::$app->db->createCommand()
            ->insert('messages', ['user_from' => $from,'user_to' => $to,'message' => $message,'booking_id' => $booking_details['booking_id'],'status' => '1','booking_request' => '1', 'thread_id' => $thread_id])->execute();
    }
 
     public function checkTHreadID($userfrom,$userto) {
			$query = new Query;
			$query->select('COUNT(message_id) as cnt')
					->from('messages')
					->where('user_from = '.$userfrom.' AND user_to = '.$userto.' || user_from = '.$userto.' AND user_to = '.$userfrom);
			$msg_cnt = $query->createCommand()->queryOne();
			$threadID	=	0;
			if(isset($msg_cnt['cnt']) && $msg_cnt['cnt'] >= 1) {
				if($msg_cnt['cnt'] == 1) {
					$query->select('message_id')
							->from('messages')
							->where('user_from = '.$userfrom.' AND user_to = '.$userto.' || user_from = '.$userto.' AND user_to = '.$userfrom);
					$response = $query->createCommand()->queryOne();
					$threadID = $response['message_id'];
				} else {
					$query->select('thread_id')
							->from('messages')
							->where('user_from = '.$userfrom.' AND user_to = '.$userto.' || user_from = '.$userto.' AND user_to = '.$userfrom.' AND thread_id != 0')->orderBy('message_id DESC');
					$response = $query->createCommand()->queryOne();

					if(isset($response['thread_id']) && $response['thread_id'] > 0) {
						$threadID = $response['thread_id'];
					} else {
						$query->select('message_id')
								->from('messages')
								->where('user_from = '.$userfrom.' AND user_to = '.$userto.' || user_from = '.$userto.' AND user_to = '.$userfrom)->orderBy('message_id ASC');
						$response = $query->createCommand()->queryOne();					
						$threadID = (isset($response['message_id']) ? $response['message_id'] : 0);						
					}									
				}
			}
			return $threadID;
	} 
    
     /**
     * get Admin Email
     * @param 
     * @return string
     */
	public function getAdminEmailID() {
		$modelLink = new Admin();
		$AdminEmail  = $modelLink->getAdminEmail();
		if(isset($AdminEmail['1']) && !empty($AdminEmail['1'])) {
			$fromEmail = $AdminEmail['1'];
		} else {
			$fromEmail = ADMIN_EMAIL_ADDRESS;
		}
		return $fromEmail;
	}    
}
