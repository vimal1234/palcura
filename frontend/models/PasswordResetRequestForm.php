<?php
namespace frontend\models;

use common\models\User;
use Yii;
use yii\base\Model;

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
            ['email', 'email','message'=>'Sorry, we do not recognize this email address.'],
            ['email', 'exist',
                'targetClass' => '\common\models\User',
                'message' => Yii::t('yii','Sorry, we do not recognize this email address.')
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
        $user = User::findOne([
           'email' => $this->email,
        ]);

        if (!$user) {
            return false;
        }
        
        if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
            $user->generatePasswordResetToken();
        }

        if (!$user->save()) {
            return false;
        }

        $resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->password_reset_token]);
        $fromEmail = Yii::$app->commonmethod->getAdminEmailID();
        $subject = 'Password reset for PalCura.';
		$message  ='';		
		$message .= '<tr>
                            <td height="5" style="font-size:13px; color:#656565; line-height:5px; padding-bottom:10px;">Hi '.$user->firstname.',</td>
                          </tr>
                          <tr>
                            <td style="font-size:13px; color:#656565; line-height:18px; padding-bottom:10px;">We received a request to reset your PalCura password. Click the link below to reset:</td>
                          </tr>
                          <tr>
                            <td height="5"><a href="'.$resetLink.'">Reset Your Password</a>.</td>
                          </tr>
                          <tr>
                            <td align="left">								
							</td>
                          </tr>
                          <tr>
                            <td height="15"></td>
                          </tr>
                          <tr style="color:#656565; font-size:13px; line-height:19px;" >
                            <td>If you did not make this request or need assistance, you can send us a woof at <a href="hello@palcura.com">hello@palcura.com</a></td>
                          </tr>
<tr>
                            <td height="15"></td>
                          </tr>';

        $mail = Yii::$app->mailer->compose(['html' => 'layouts/mail'], ['content' => $message, 'subject' => $subject])
        ->setFrom([$fromEmail => 'Palcura'])
        ->setTo($this->email)
        ->setSubject($subject)
        ->send();     
        return $mail;
    }
}
