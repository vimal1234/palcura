<?php

namespace frontend\models\messages;

use Yii;
use yii\db\Query;
use yii\data\Pagination;
/**
 * User model
 *
 * @property integer $id
 * @property string $countries
 */
class Messages extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    #public $message_id;
	private $limit = 10;
    public static function tableName() {
        return 'messages';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['message', 'user_to'], 'required'],# 'subject', 
            ['message', 'string', 'max' => 500],
            ['chat_id', 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'message_id' => Yii::t('yii', 'message'),
            'message' => Yii::t('yii', 'Description'),
            'subject' => Yii::t('yii', 'Message subject'),
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id) {
        return static::findOne(['message_id' => $id]);
    }

    /**
     * Display: replyMessage
     * Param: int $threadId messages.thread_id
     */
    public function replyMessage($threadId = 0) {
        if (!$this->validate()) {
            return null;
        }

        $messages = new Messages();
        $messages->user_from = Yii::$app->user->getId();
        $messages->user_to = $this->user_to;
        $messages->message = $this->message;
        $messages->booking_id = (isset($this->booking_id) ? $this->booking_id : 0);
        $messages->subject = $this->subject;
        $messages->date_created = date(DATETIME_FORMAT);
        $messages->is_trashed = '0';
        $messages->is_read = '0';
        $messages->status = '1';

		$threadId	=	$this->checkTHreadID(Yii::$app->user->getId(),$this->user_to);
        $messages->thread_id = $threadId;
        return $messages->save() ? $messages : null;
    }
    
    public function replyMessageToAdmin($threadId = 0) {
        if (!$this->validate()) {
            return null;
        }

        $messages = new Messages();
        $messages->user_from = Yii::$app->user->getId();
        $messages->user_to = $this->user_to;
        $messages->message = $this->message;
        $messages->booking_id = 0;
        $messages->subject = $this->subject;
        $messages->date_created = date(DATETIME_FORMAT);
        $messages->is_trashed = '0';
        $messages->is_read = '0';
        $messages->status = '1';
        $messages->send_status = '1';

		$threadId	=	$this->checkTHreadID(Yii::$app->user->getId(),$this->user_to);
        $messages->thread_id = $threadId;
        return $messages->save() ? $messages : null;
    }
    
    public function getThreadInfo($threadId) 
    {
        $sql = "
            Select m.message_id, m.message, m.subject,
            uf.usrFirstname Sender, uf.id, 
            ut.usrFirstname Receiver, ut.id 
            FROM messages m 
            LEFT JOIN user uf ON uf.id=m.user_from
            LEFT JOIN user ut ON ut.id=m.user_to 
            WHERE m.thread_id=:threadId || m.message_id=:threadId
            ORDER BY m.date_created ASC";

        $threadData = Messages::findOne($threadId);
        
        if(empty($threadData) )
        {
            return false;
        }
        
        if($threadData->thread_id !== 0)
        {            
            $threadParentData =  Messages::findOne($threadData->thread_id);
            
            if(empty($threadParentData) )
                return false;
            
            $threadId = $threadParentData->message_id;
        }
			$userId = Yii::$app->user->getId();

/*        $messages = Messages::find()
            ->select('messages.message_id, messages.message, messages.subject, messages.date_created,messages.booking_id,messages.user_from,messages.user_to,messages.booking_request,messages.send_status,
                        uf.usrFirstname sender_fname, uf.usrLastname sender_lname, uf.id,
                        ut.usrFirstname receiver_fname, ut.usrLastname receiver_lname, ut.id,bk.booking_id as pbk,bk.booking_status')
            ->leftJoin('user uf', 'uf.id=messages.user_from')
            ->leftJoin('user ut', 'ut.id=messages.user_to')
            ->leftJoin('booking bk', 'bk.booking_id=messages.booking_id')
            ->where('messages.thread_id = :threadId AND messages.is_trashed != :userId AND messages.is_trashed_by_from_user != :userId', [':threadId' => $threadId,':userId' => $userId])
            ->orWhere('messages.message_id = :threadId AND messages.is_trashed != :userId AND messages.is_trashed_by_from_user != :userId', [':threadId' => $threadId,':userId' => $userId])
            ->orderBy('messages.message_id DESC')
            ->asArray()
            ->limit(20)
            ->all();
*/

			$query = new Query;
            $query->select('messages.message_id, messages.message, messages.subject, messages.date_created,messages.booking_id,messages.user_from,messages.user_to,messages.booking_request,messages.send_status,
                        uf.usrFirstname sender_fname, uf.usrLastname sender_lname, uf.id,
                        ut.usrFirstname receiver_fname, ut.usrLastname receiver_lname, ut.id,bk.booking_id as pbk,bk.booking_status')
            ->from('messages')
            ->join('LEFT JOIN', 'user uf', 'uf.id = messages.user_from')
            ->join('LEFT JOIN', 'user ut', 'ut.id=messages.user_to')
            ->join('LEFT JOIN', 'booking bk', 'bk.booking_id=messages.booking_id')
            ->where('messages.thread_id = :threadId AND messages.is_trashed != :userId AND messages.is_trashed_by_from_user != :userId', [':threadId' => $threadId,':userId' => $userId])
            ->orWhere('messages.message_id = :threadId AND messages.is_trashed != :userId AND messages.is_trashed_by_from_user != :userId', [':threadId' => $threadId,':userId' => $userId])
            ->orderBy('messages.message_id DESC');
                    
			$countQuery = clone $query;
			$pages = new Pagination(['totalCount' => $countQuery->count()]);
			$pages->setPageSize($this->limit);
			$query->offset($pages->offset)->limit($this->limit);
			$messageResult = $query->createCommand()->queryAll();
					
			$arr_details	=	array('threadDetails' => $messageResult, 'pages' => $pages);
			return $arr_details;
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
					//$threadID = (isset($response['thread_id']) ? $response['thread_id'] : 0);	
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
}

?>
