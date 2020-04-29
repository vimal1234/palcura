<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\Admin;

/**
 * ContactForm is the model behind the contact form.
 */
class Connectadmin extends Model
{
    /**
     * @inheritdoc
     */
    public $title; 
    public $description;
    public $form_type;
    public $booking_id;
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['title','description','form_type'], 'required'],
          	[['booking_id'],'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                
        ];
    }
    
    public function savedata(){
    $userid = Yii::$app->user->identity->id;
    	$connection = \Yii::$app->db;   		   	 		
		$model = $connection->createCommand('Insert into dispute_resolutions(user_id,booking_id,title,description,form_type) values("'.$userid.'","'.$this->booking_id.'","'.$this->title.'","'.$this->description.'","'.$this->form_type.'")');		
		if($model->execute()){
		 return true;
		}else{
		 return false;
		}         
    
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     *
     * @param  array  $emailData various email data required to send email
     * @return boolean whether the email was sent
     */
    public function sendEmail($emailData)
    {
		$toEmail = $this->getAdminEmailID();
        $fullName = ucwords($emailData['fname'] . ' ' . $emailData['lname']);
        
        $subject  ="New enquiry received from ". ucwords($emailData['fname']); 
		$message  ='';
		$message .='<tr>';
			$message .='<td height="26" style="font-size:15px; font-weight:500; color:#333333;">Dear MyGuyde Support,</td>';
		$message .='</tr>';
		$message .='<tr>';
			$message .='<td style="font-size:13px; color:#585858; line-height:18px; padding-bottom:10px;">New contact us enquiry has been received from '. $fullName .'. Below are the details:</td>';
		$message .='</tr>';
		$message .='<tr>';
			$message .='<td height="5"></td>';
		$message .='</tr>';
		$message .='<tr>';
			$message .='<td align="left">';
				$message .='<table width="100%" border="0" bgcolor="#2c1f14" cellspacing="1" cellpadding="6" style=" color:#585858;">';
					$message .='<tr  bgcolor="#333333">';
						$message .='<td colspan="2" style="border-top:#333333 solid 0px; font-size:14px; font-weight:400; color:#ffffff; text-transform:capitalize;   padding:8px;">Contact details</td>';
					$message .='</tr>';
					$message .='<tr  bgcolor="#ffffff">';
						$message .='<td width="100" >Name</td>';
						$message .='<td width="270" >' . @$fullName . '</td>';
					$message .='</tr>';
					$message .='<tr  bgcolor="#ffffff">';
						$message .='<td>Email</td>';
						$message .='<td >' . @$emailData['email'] . '</td>';
					$message .='</tr>';
				
					$message .='<tr  bgcolor="#ffffff">';
						$message .='<td>Country</td>';
						$message .='<td >' . @$emailData['country'] . '</td>';
					$message .='</tr>';
					
					$message .='<tr  bgcolor="#ffffff">';
						$message .='<td>Message</td>';
						$message .='<td >' . @$emailData['message'] . '</td>';
					$message .='</tr>';															
				$message .='</table>';
			$message .='</td>';
		$message .='</tr>';
		$message .='<tr>';
			$message .='<td height="15"></td>';
		$message .='</tr>';
      
        return Yii::$app->mailer->compose(['html' => 'layouts/mail'], ['content' => $message, 'subject' => $subject])
            ->setTo($toEmail)
            ->setFrom([$emailData['email'] => $fullName ])
            ->setSubject($subject)
            ->setTextBody($message)
            ->send();
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
			Yii::$app->params['adminEmailTitle'];
		}
		return $fromEmail;
	}
}
