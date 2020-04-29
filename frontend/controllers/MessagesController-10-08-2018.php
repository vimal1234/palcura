<?php
namespace frontend\controllers;
use Yii;
use frontend\models\users\Users;
use frontend\models\messages\Messages;
use frontend\models\common\BookingDiscount;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Controller;
use yii\helpers\Url;
use yii\web\ErrorAction;
use yii\data\Pagination;
use yii\db\Query;

/**
* Messages controller
*/
class MessagesController extends Controller {
	/**
	* @inheritdoc
	*/
    private $limit = LIMIT;

	/**
	* @ Function Name		: actionIndex
	* @ Function Params		: NA 
	* @ Function Purpose 	: default index function that will be called to display messages
	* @ Function Returns	: render view
	*/
    public function actionIndex() {
        if (Yii::$app->user->isGuest) {
            if (Yii::$app->controller->action->id != 'index')
                Yii::$app->session->set('afterlogin', Yii::$app->request->url);

            return Yii::$app->user->loginRequired();
        }

		$userId 	= Yii::$app->user->getId();
		$dataArray 	= array();
		$query 		= new Query;

		####= query one
		$query->select('messages.*,userfrom.firstname as ufrom_fname,userfrom.lastname as ufrom_lname,userto.firstname as uto_fname,userto.lastname as uto_lname')
		->from('messages')
		->join('LEFT JOIN', 'user userfrom', 'userfrom.id = messages.user_from')
		->join('LEFT JOIN', 'user userto', 'userto.id = messages.user_to')
		->where('messages.id IN(SELECT MAX(messages.id) FROM messages WHERE (messages.trashed_by_from_user != '.$userId.' AND '.'messages.trashed_by_to_user != '.$userId.') AND chat_id !=0 AND (messages.user_from = '.$userId.' OR messages.user_to = '.$userId.') GROUP BY messages.chat_id) ORDER BY messages.id DESC');
		
		####= query two
		//~ $query2 = 'SELECT messages.*,userfrom.firstname as ufrom_fname,userfrom.lastname as ufrom_lname,userto.firstname as uto_fname,userto.lastname as uto_lname FROM messages 
		//~ LEFT OUTER JOIN messages m2 ON messages.id = m2.chat_id
		//~ LEFT JOIN `user` `userfrom` ON userfrom.id = messages.user_from
		//~ LEFT JOIN `user` `userto` ON userto.id = messages.user_to 
		//~ WHERE (messages.trashed_by_from_user != '.$userId.' AND messages.trashed_by_to_user != '.$userId.') AND messages.chat_id=0 AND (messages.user_to = '.$userId.' OR messages.user_from = '.$userId.') AND m2.chat_id IS NULL'; 
		//~ $query->union($query2,true);

		$countQuery = clone $query;
		$pages = new Pagination(['totalCount' => $countQuery->count()]);
		$pages->setPageSize(LIMIT);

$newQuery = 'SELECT `messages`.*, `userfrom`.`firstname` AS `ufrom_fname`, `userfrom`.`lastname` AS `ufrom_lname`, `userto`.`firstname` AS `uto_fname`, `userto`.`lastname` AS `uto_lname` FROM `messages` LEFT JOIN `user` `userfrom` ON userfrom.id = messages.user_from LEFT JOIN `user` `userto` ON userto.id = messages.user_to WHERE messages.id IN(SELECT MAX(messages.id) FROM messages WHERE (messages.trashed_by_from_user != '.$userId.' AND messages.trashed_by_to_user != '.$userId.') AND chat_id !=0 AND (messages.user_from = '.$userId.' OR messages.user_to = '.$userId.') GROUP BY messages.chat_id) ORDER BY messages.id DESC';

		//echo $query->createCommand()->getRawSql().' ORDER BY messages.id DESC LIMIT '.$pages->offset.','.LIMIT; die;
		// $messageResult = Yii::$app->db->createCommand($query->createCommand()->getRawSql().'  LIMIT '.$pages->offset.','.LIMIT)->queryAll();

$messageResult = Yii::$app->db->createCommand($newQuery.'  LIMIT '.$pages->offset.','.LIMIT)->queryAll();

		$unread_count 		= $this->getUnreadMessagesCount($userId);
		$dataArray 	 		= array_merge($dataArray, [
		'messages' 		=> $messageResult,
		'unread_count' 	=> $unread_count,
		'pages' 	 	=> $pages,
		]);
		return $this->render('messages', $dataArray);
    }

	public function actionUpdateMessagesListing() {
		$userId 	= Yii::$app->user->getId();
		$dataArray 	= array();
		$query 		= new Query;

		####= query one
		$query->select('messages.*,userfrom.firstname as ufrom_fname,userfrom.lastname as ufrom_lname,userto.firstname as uto_fname,userto.lastname as uto_lname')
		->from('messages')
		->join('LEFT JOIN', 'user userfrom', 'userfrom.id = messages.user_from')
		->join('LEFT JOIN', 'user userto', 'userto.id = messages.user_to')
		->where('messages.id IN(SELECT MAX(messages.id) FROM messages WHERE (messages.trashed_by_from_user != '.$userId.' AND '.'messages.trashed_by_to_user != '.$userId.') AND chat_id !=0 AND (messages.user_from = '.$userId.' OR messages.user_to = '.$userId.') GROUP BY messages.chat_id)');
		
		####= query two
		//~ $query2 = 'SELECT messages.*,userfrom.firstname as ufrom_fname,userfrom.lastname as ufrom_lname,userto.firstname as uto_fname,userto.lastname as uto_lname FROM messages 
		//~ LEFT OUTER JOIN messages m2 ON messages.id = m2.chat_id
		//~ LEFT JOIN `user` `userfrom` ON userfrom.id = messages.user_from
		//~ LEFT JOIN `user` `userto` ON userto.id = messages.user_to 
		//~ WHERE (messages.trashed_by_from_user != '.$userId.' AND messages.trashed_by_to_user != '.$userId.') AND messages.chat_id=0 AND (messages.user_to = '.$userId.' OR messages.user_from = '.$userId.') AND m2.chat_id IS NULL ORDER BY messages.id'; 
		//~ $query->union($query2,true);

		$countQuery = clone $query;
		$pages = new Pagination(['totalCount' => $countQuery->count()]);
		$pages->setPageSize(LIMIT);


		$messageResult = Yii::$app->db->createCommand($query->createCommand()->getRawSql().' GROUP BY messages.chat_id LIMIT '.$pages->offset.','.LIMIT)->queryAll();

		$unread_count 		= $this->getUnreadMessagesCount($userId);
		$dataArray 	 		= array_merge($dataArray, [
		'messages' 		=> $messageResult,
		'unread_count' 	=> $unread_count,
		'pages' 	 	=> $pages,
		]);
		
        $this->layout 		= false;
        return $this->render('result', $dataArray);	
	}

	/**
	* @ Function Name		: actionMymessages
	* @ Function Params		: NA
	* @ Function Purpose 	: default index function that will be called to display messages
	* @ Function Returns	: render view
	*/
    public function actionMymessages() {
        return $this->redirect(['messages/index']);
    }

	/**
	* @ Function Name		: actionSearchMessages
	* @ Function Params		: NA
	* @ Function Purpose 	: function works for searching messages
	* @ Function Returns	: render view
	*/
	public function actionSearchMessages() {
        $userId 	= Yii::$app->user->getId();
        $dataArray 	= array();
        $query 		= new Query;
		$post 		= Yii::$app->request->post();
		$where		= '';
		if(isset($post['message']['search_value']) && !empty($post['message']['search_value'])) {
			$newArr = explode(" ",$post['message']['search_value']);
			if(isset($newArr[1]) && !empty($newArr[1])) {
				$where = "AND (messages.message LIKE '%".$post['message']['search_value']."%' OR (userto.firstname LIKE '%".$newArr[0]."%' AND userto.lastname LIKE '%".$newArr[1]."%'))";
			} else {
				$where = "AND (messages.message LIKE '%".$post['message']['search_value']."%' OR userto.firstname LIKE '%".$post['message']['search_value']."%' OR userto.lastname LIKE '%".$post['message']['search_value']."%')";
			}
		}

		
		$query->select('messages.*,userfrom.firstname as ufrom_fname,userfrom.lastname as ufrom_lname,userto.firstname as uto_fname,userto.lastname as uto_lname')
		->from('messages')
		->join('LEFT JOIN', 'user userfrom', 'userfrom.id = messages.user_from')
		->join('LEFT JOIN', 'user userto', 'userto.id = messages.user_to')
		->where('messages.id IN(SELECT MAX(messages.id) FROM messages WHERE (messages.trashed_by_from_user != '.$userId.' AND '.'messages.trashed_by_to_user != '.$userId.') AND chat_id !=0 AND (messages.user_from = '.$userId.' OR messages.user_to = '.$userId.') '.$where.' AND chat_id !=0 AND (messages.user_from = '.$userId.' OR messages.user_to = '.$userId.') GROUP BY messages.chat_id)');
		
		$countQuery = clone $query;
		$pages = new Pagination(['totalCount' => $countQuery->count()]);
		$pages->setPageSize(LIMIT);

$newQuery = 'SELECT `messages`.*, `userfrom`.`firstname` AS `ufrom_fname`, `userfrom`.`lastname` AS `ufrom_lname`, `userto`.`firstname` AS `uto_fname`, `userto`.`lastname` AS `uto_lname` FROM `messages` LEFT JOIN `user` `userfrom` ON userfrom.id = messages.user_from LEFT JOIN `user` `userto` ON userto.id = messages.user_to WHERE messages.id IN(SELECT MAX(messages.id) FROM messages WHERE (messages.trashed_by_from_user != '.$userId.' AND messages.trashed_by_to_user != '.$userId.') AND chat_id !=0 AND (messages.user_from = '.$userId.' OR messages.user_to = '.$userId.') '.$where.' AND chat_id !=0 AND (messages.user_from = '.$userId.' OR messages.user_to = '.$userId.') GROUP BY messages.chat_id)';

		$messageResult = Yii::$app->db->createCommand($newQuery.' GROUP BY messages.chat_id LIMIT '.$pages->offset.','.LIMIT)->queryAll();
		
		$unread_count 		= $this->getUnreadMessagesCount($userId);
		$dataArray 			= array_merge($dataArray, [
			'messages' 		=> $messageResult,
			'unread_count' 	=> $unread_count,
			'pages' 		=> $pages,
		]);
        $this->layout 		= false;
        return $this->render('result', $dataArray);
	}

	/**
	* @ Function Name		: getUnreadMessagesCount
	* @ Function Params		: $userId
	* @ Function Purpose 	: function uses to get unread messages count
	* @ Function Returns	: render view
	*/
	public function getUnreadMessagesCount($userId=0) {
        $query 		= new Query;
		$query->select('COUNT(id) as unread_cnt')->from('messages')->where(['user_to' => $userId, 'is_read' => 0]);
		$messageCnt = $query->createCommand()->queryOne();
		return (isset($messageCnt['unread_cnt']) ? $messageCnt['unread_cnt'] : 0);
	}

	/**
	* @ Function Name		: actionRemoveConversation
	* @ Function Params		: NA
	* @ Function Purpose 	: function uses to remove users conversations
	* @ Function Returns	: render view
	*/
	public function actionRemoveConversation() {
		$postData = Yii::$app->request->post();
		if(isset($postData['chatIds']) && !empty($postData['chatIds'])) {
			$userId 	= Yii::$app->user->getId();
			$connection = \Yii::$app->db;
			foreach($postData['chatIds'] as $chat_id) {
				$response = $this->getThreadID($chat_id);
				if($response == 0) {
					$connection->createCommand()->update('messages', ['trashed_by_to_user' => $userId], ['chat_id' => $chat_id])->execute();
					$connection->createCommand()->update('messages', ['trashed_by_to_user' => $userId], ['id' => $chat_id])->execute();
				} else {
					$connection->createCommand()->update('messages', ['trashed_by_from_user' => $userId], ['chat_id' => $chat_id])->execute();
					$connection->createCommand()->update('messages', ['trashed_by_from_user' => $userId], ['id' => $chat_id])->execute();
				}
			}
		}
	}

	/**
	* @ Function Name		: getThreadID
	* @ Function Params		: chat id
	* @ Function Purpose 	: function uses to verify thread id
	* @ Function Returns	: render view
	*/
	public function getThreadID($chat_id=0) {
		$userId 	= Yii::$app->user->getId();
        $query 		= new Query;		
		$query->select('COUNT(id) as cnt')->from('messages')->where(['id' => $chat_id, 'user_from' => $userId]);
		$response   = $query->createCommand()->queryOne();
		return (isset($response['cnt']) ? $response['cnt'] : 0);
	}
	
	/**
	* @ Function Name		: actionUserMessaging
	* @ Function Params		: chat id
	* @ Function Purpose 	: function gets chat messages 
	* @ Function Returns	: render view
	*/	
	public function actionUserMessaging($chat_id=0) {
		$chat_id = Yii::$app->request->get('id');
		if($chat_id == 0)
			return $this->redirect(['messages/index']);

		####= user messaging
		$dataArray 	= array();	
		$this->markReadStatus($chat_id);
		$conversationDetails = $this->getChatHistory($chat_id);
		$model   = new BookingDiscount();
		$dataArray 	 		= array_merge($dataArray, [
			'messages' 			=> $conversationDetails,
			'threadID' 			=> $chat_id,
			'model' 	 		=> $model,
		]);
		return $this->render('user-messaging', $dataArray);
	}

	public function actionBookingdiscount() {
		$userId 	= Yii::$app->user->getId();
		$model  	 = new BookingDiscount();
        if (($model->load(Yii::$app->request->post()) && $model->validate())) {
			$postArr 		= Yii::$app->request->post();
			$till_date 		= Yii::$app->commonmethod->getDatepickerDate();
			$response 		= $this->verifyThreadID($postArr['chat_id']);
			$owner_id 		= (isset($response) ? $response : 0);
			$sitter_id 		= $userId;
			$minimum_price 	= (isset($postArr['BookingDiscount']['minimum_price']) ? $postArr['BookingDiscount']['minimum_price'] : 0);
			$discount		= (isset($postArr['BookingDiscount']['discount']) ? $postArr['BookingDiscount']['discount'] : 0);

			$query = new Query;
			$query->select('id')->from('booking_discount')->where(['owner_id' => $owner_id, 'sitter_id' => $sitter_id]); 
			$data  = $query->createCommand()->queryOne();
			if($owner_id > 0) {
				if(isset($data['id']) && $data['id'] > 0) {
					$connection = \Yii::$app->db;
					$connection->createCommand()->update('booking_discount', ['owner_id' => $owner_id, 'sitter_id' => $sitter_id, 'minimum_price' => $minimum_price, 'discount' => $discount, 'till_date' => $till_date, 'status' => PENDING], ['id' => $data['id']])->execute();	
				} else {
					$query = new Query;
					$query->createCommand()->insert('booking_discount', ['owner_id' => $owner_id, 'sitter_id' => $sitter_id, 'minimum_price' => $minimum_price, 'discount' => $discount, 'till_date' => $till_date, 'status' => PENDING])->execute();
				}
				Yii::$app->session->setFlash('item', 'The final price discount has been updated successfully.');
			} else {
				Yii::$app->session->setFlash('item', 'We were unable to process your request. Please try again later.');
			}
			return $this->redirect(['messages/user-messaging/'.$postArr['chat_id']]);
		}
		return $this->redirect(['messages/index']);
	}

	public function getChatHistory($chat_id) {
		$userId 	= Yii::$app->user->getId();
		$query 		= new Query;	
		$query->select('messages.*,userfrom.firstname as ufrom_fname,userfrom.lastname as ufrom_lname,userto.firstname as uto_fname,userto.lastname as uto_lname')->from('messages')->join('LEFT JOIN', 'user userfrom', 'userfrom.id = messages.user_from')->join('LEFT JOIN', 'user userto', 'userto.id = messages.user_to')->where('(messages.chat_id = ' . $chat_id . ' OR messages.id = ' . $chat_id . ') AND (messages.trashed_by_from_user !='.$userId.' AND messages.trashed_by_to_user !='.$userId.') AND (messages.user_from = '.$userId.' OR messages.user_to = '.$userId.') AND messages.status = "1"  ORDER BY messages.id ASC');
		
		//$countQuery = clone $query;
		//$pages 		= new Pagination(['totalCount' => $countQuery->count()]);
		//$pages->setPageSize(LIMIT);
		//$query->offset($pages->offset)->limit(LIMIT);
		return $query->createCommand()->queryAll();
	}

	/**
	* @ Function Name		: actionLiveMessaging
	* @ Function Params		: chat id
	* @ Function Purpose 	: function gets chat messages 
	* @ Function Returns	: render view
	*/	
	public function actionLiveMessaging() {
		$post 		= Yii::$app->request->post();
		if(isset($post['livechatting']['chat_id']) && $post['livechatting']['chat_id'] > 0) {
			$chat_id = $post['livechatting']['chat_id'];
		}

		if($chat_id <= 0) {
			return false;
		}

		####= user messaging		
		$dataArray 	= array();	
		$this->markReadStatus($chat_id);
		$conversationDetails = $this->getChatHistory($chat_id);
		$dataArray 	 		= array_merge($dataArray, [
			'messages' 			=> $conversationDetails,
			'threadID' 			=> $chat_id,
		]);
        $this->layout 		= false;
        return $this->render('chatting-result', $dataArray);
	}
	
	/**
	* @ Function Name		: messageSending
	* @ Function Params		: post Arr
	* @ Function Purpose 	: function uses to send messaging
	* @ Function Returns	: NA
	*/
	public function actionMessagesending() {
		$post 		= Yii::$app->request->post();
		$userId 	= Yii::$app->user->getId();
		if(isset($post['postData']['chat_id']) && !empty($post['postData']['chat_id'])) {
			$response = $this->verifyThreadID($post['postData']['chat_id']);
			if($response > 0) {
				Yii::$app->db->createCommand()->insert('messages', ['chat_id' => $post['postData']['chat_id'], 'user_from' => $userId, 'user_to' => $response, 'message' => $post['postData']['message'], 'status' => '1'])->execute();
				$insert_id = Yii::$app->db->getLastInsertID();
				if($insert_id > 0) {
					$this->newMessageEmailNotification($post['postData'],$response,$post['postData']['message']);
					$responseArr = $this->getNewMessages($insert_id,$post['postData']['chat_id']);
					if(isset($responseArr) && !empty($responseArr)) {
						$str = '';
						$count = 0;
						foreach($responseArr as $m_row) {
						$count++;
							if($userId == $m_row['user_from']) {
								$cl = 'yellowBox';
							} else {
								$cl = '';
							}
							$str .='<div class="whiteBox '.$cl.'">';
								$str .='<p>'.$m_row['message'].'</p>';
								$str .='<ul>';
									$str .='<li><a id="newdate'.$count.'"></a><span>|</span></li>';
									$str .='<li><a id="lblTime'.$count.'"></a></li>';
								$str .='</ul>';
								$str .='<p class="cleintname">'.$m_row['ufrom_fname'].' '.$m_row['ufrom_lname'].'</p>';
							$str .='</div>
							<script>
							var dateFromDb = '.strtotime($m_row['date_created']).';
							var monthNames = [
							"January", "February", "March",
							"April", "May", "June", "July",
							"August", "September", "October",
							"November", "December"
						 	 ];
						 	 var Messagenow = new Date(dateFromDb * 1000);
						 	 Messagenow.setMinutes(Messagenow.getMinutes());
							 var day = Messagenow.getDate();
							 var monthIndex = Messagenow.getMonth();
							 var year = Messagenow.getFullYear();
							 var msgdate =  monthNames[monthIndex] + " " + day + " " + year;
							 $("a#newdate'.$count.'").text(msgdate);
							 DisplayCurrentTime(Messagenow);
							  
					 	function DisplayCurrentTime(Msgdate) {
							var date = Msgdate;
							var hours = date.getHours() > 12 ? date.getHours() - 12 : date.getHours();       
							var am_pm = date.getHours() >= 12 ? "PM" : "AM";
							hours = hours < 10 ? "0" + hours : hours;
							var minutes = date.getMinutes() < 10 ? "0" + date.getMinutes() : date.getMinutes();
							var seconds = date.getSeconds() < 10 ? "0" + date.getSeconds() : date.getSeconds();
							time = hours + ":" + minutes + " " + am_pm;
							$("a#lblTime'.$count.'").text(time);
							}
							</script>
							';
						}
						return json_encode(array("status" => 'success', "response" => $str));
					}
				}
			}
		}
	}

	public function newMessageEmailNotification($data,$user_to,$textmessage) {
		$fromEmail	= Yii::$app->commonmethod->getMaskEmailAddress($data['chat_id']);
		####= { $selectCol } use to get specific columns of users. 
		$selectCol  = "id,email,firstname,lastname";
		$userInfo	= Yii::$app->commonmethod->getUserColumnsData($user_to,$selectCol);
		$uname		= (isset($userInfo['firstname']) ? $userInfo['firstname'] : '') ;
		$emailTo	= (isset($userInfo['email']) ? $userInfo['email'] : '');
		$user 		= 	Yii::$app->user->identity->attributes;
		$fromname	=  (isset($user['firstname']) ? $user['firstname'] : '');

		if(isset($fromEmail) && !empty($fromEmail)) {
			$subject  = "New message received from ".$fromname."";
			$message  = '';
						
              $message .= '<tr>
                            <td style="font-size:13px; color:#656565; line-height:13px; padding-bottom:10px;" align="center">'.$fromname.' says</td>
                          </tr>                     
                          <tr>
                            <td height="0"></td>
                          </tr>
                          <tr style="color:#656565; font-size:13px; line-height:19px;" align="center" >
                            <td>"'.$textmessage.'"</td>
                          </tr>
                          <tr>
                            <td height="5"></td>
                          </tr>
                          <tr>
                            <td>
                              <p style="color:#656565; font-size:13px; line-height:19px; margin:0 0 12px 0; font-style:italic;">Respond to '.$fromname.' by replying directly to this email or access your PalCura inbox <a href="'.SITE_URL.'messages/'.'">here. </p></td>
                          </tr>';
                                      
			Yii::$app->mailer->compose(['html' => 'layouts/messagemailtemp.php'], ['content' => $message, 'subject' => $subject])
					->setTo($emailTo)
					->setFrom([$fromEmail => 'PalCura'])
					->setSubject($subject)
					->setTextBody($message)
					->send();
			}
	}

	public function verifyThreadID($threadID) {
		$query 	  = new Query;	
		$query->select('id,user_from,user_to')->from('messages')->where(['id' => $threadID]);
		$response = $query->createCommand()->queryOne();
		if(isset($response['id']) && !empty($response['id'])) {
			$userId 	= Yii::$app->user->getId();
			if($userId == $response['user_from']) {
				return $response['user_to'];
			} else if($userId == $response['user_to']) {
				return $response['user_from'];
			} else {
				return false;
			}
		}
	}
	
	public function getNewMessages($message_id,$thread_id) {
		$query 	  = new Query;	
		$query->select('messages.*,userfrom.firstname as ufrom_fname,userfrom.lastname as ufrom_lname,userto.firstname as uto_fname,userto.lastname as uto_lname')
		->from('messages')
		->join('LEFT JOIN', 'user userfrom', 'userfrom.id = messages.user_from')
		->join('LEFT JOIN', 'user userto', 'userto.id = messages.user_to')
		->where('(messages.chat_id = ' . $thread_id . ' OR messages.id = ' . $thread_id . ') AND  messages.id >= '.$message_id.'  ORDER BY messages.id ASC');
		return $query->createCommand()->queryAll();
	}

	/**
	* @ Function Name		: markReadStatus
	* @ Function Params	: message_id {int}
	* @ Function Purpose 	: mark read status
	* @ Function Returns	: return
	*/
	public function markReadStatus($chat_id = 0) {
		$userId = Yii::$app->user->getId();
		if($chat_id > 0) {
			Yii::$app->db->createCommand()->update('messages', ['is_read' => 1], 'id = ' .$chat_id. ' OR chat_id = ' .$chat_id)->execute();
		}
	}

	/**
	* @ Function Name		: actionBookingMessaging
	* @ Function Params		: chat id
	* @ Function Purpose 	: function gets chat messages 
	* @ Function Returns	: render view
	*/	
	public function actionBookingMessaging($user_id=0) {
		$model   = new Messages();
		$logginUserId 	= Yii::$app->user->getId();
		$user_id = Yii::$app->request->get('id');
		if($user_id == 0)
			return $this->redirect(['messages/index']);

		$chat_id	= $this->getThreadByUserID($user_id);
		if(isset($chat_id) && !empty($chat_id)) {
			return $this->redirect(['messages/user-messaging/'.$chat_id]);
		}

		if ($model->load(Yii::$app->request->post()) && $model->validate()) {
			$data = Yii::$app->request->post('Messages');
			$model->message		=	(isset($data['message']) ? $data['message'] : '');
			$model->user_from	=	$logginUserId;
			$model->user_to		=	(isset($data['user_to']) ? $data['user_to'] : '');
			$model->status		=	ACTIVE;
            if($model->save()) {
				$chatId = Yii::$app->db->getLastInsertID();
				$this->createRandomEmail($chatId);
				$this->updateThread($chatId);
				$data2 = array();
				$data2['chat_id'] = $chatId;
				$this->newMessageEmailNotification($data2,$data['user_to'],$data['message']);				
                //Yii::$app->session->setFlash('item', Yii::t('yii','Your message has been sent successfully.'));
				//return $this->redirect(['users/view-user-details/'.$data['user_to']]);
				return $this->redirect(['messages/user-messaging/'.$chatId]);
            } else {
                Yii::$app->session->setFlash('item', Yii::t('yii','Please enter valid values for all the fields.'));
            }
		}

		####= booking messaging
		$dataArray 			= array();
		$dataArray 	 		= array_merge($dataArray, [
			'model' 		=> $model,
			'book_user_id' 	=> $user_id,
		]);
		return $this->render('booking-message', $dataArray);
	}
	
	public function updateThread($id) {
		Yii::$app->db->createCommand()->update('messages', ['chat_id' => $id], 'id = ' . $id)->execute();		
	}

	public function createRandomEmail($chatId) {
		$query 	  = new Query;	
		$query->select('chat_id')->from('random_email_address')->where(['chat_id' => $chatId]);
		$response   = $query->createCommand()->queryOne();
		if(isset($response['chat_id']) && $response['chat_id'] > 0) {
			
		} else {
			$email		 = 'palcurauser'.mt_rand().'@palcura.com';
			$insertItems = array('chat_id'=> $chatId, 'email'=> $email);
			Yii::$app->db->createCommand()->insert('random_email_address', $insertItems)->execute();
		}
	}	
	
	public function getThreadByUserID($user_id) {
        $query 		= new Query;
	$cuserId = Yii::$app->user->getId();		
	$whereCase	= '(user_from = '.$user_id.' AND user_to = '.$cuserId.') OR (user_from = '.$cuserId.' AND user_to = '.$user_id.')';
		$query->select('chat_id')->from('messages')->where($whereCase)->orderBy(['id' => SORT_DESC]);
		$response   = $query->createCommand()->queryOne();
		$chatID = (isset($response['chat_id']) ? $response['chat_id'] : 0);
		if($chatID == 0) { 
			$query->select('id')->from('messages')->where($whereCase)->orderBy(['id' => SORT_ASC]);
			$response   = $query->createCommand()->queryOne();
			$chatID = (isset($response['id']) ? $response['id'] : 0);
		}
		return $chatID;
	} 
 }
