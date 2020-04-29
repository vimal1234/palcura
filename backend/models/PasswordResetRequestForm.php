<?php
namespace backend\models;

use common\models\Admin;
use Yii;
use yii\base\Model;
use yii\helpers\Html;
/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{
    public $email;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => '\common\models\Admin',
                'filter' => ['status' => Admin::STATUS_ACTIVE],
                'message' => 'There is no user with such email.'
            ],
        ];
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return boolean whether the email was send
     */
    public function sendEmail()
    { 
        /* @var $user User */
        $user = Admin::findOne([
            'status' => Admin::STATUS_ACTIVE,
            'email' => $this->email,
        ]);

        if (!$user) {
            return false;
        }
        
        if (!Admin::isPasswordResetTokenValid($user->password_reset_token)) {
            $user->generatePasswordResetToken();
        }
        
        if (!$user->save()) {
            return false;
        }

		$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->password_reset_token]);

		$subject  ="Password reset for Yii-Admin";
		$message  ='';
		$message .='<tr>';
			$message .='<td height="26" style="font-size:15px; font-weight:500; color:#2c1f14;  ">Dear Admin,</td>';
		$message .='</tr>';
		$message .='<tr>';
			$message .='<td style="font-size:13px; color:#2c1f14; line-height:18px; padding-bottom:10px;">Follow the link to reset your password: ' . Html::a(Html::encode($resetLink), $resetLink) . '</td>';
		$message .='</tr>';
		$message .='<tr>';
			$message .='<td height="5"></td>';
		$message .='</tr>';

		$mail = Yii::$app->mailer->compose(['html' => 'layouts/mail'],['content' => $message, 'subject' => $subject])
		->setFrom([$this->email => 'Yii-Admin'])
		->setTo($this->email)
		->setSubject($subject)
		->send();
		return $mail;
    }
}
