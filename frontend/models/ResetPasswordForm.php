<?php
namespace frontend\models;

use common\models\User;
use yii\base\InvalidParamException;
use yii\base\Model;
use Yii;

/**
 * Password reset form
 */
class ResetPasswordForm extends Model
{
    public $password;
    public $repeat_password;
    

    /**
     * @var \common\models\User
     */
    private $_user;


    /**
     * Creates a form model given a token.
     *
     * @param  string                          $token
     * @param  array                           $config name-value pairs that will be used to initialize the object properties
     * @throws \yii\base\InvalidParamException if token is empty or not valid
     */
    public function __construct($token, $config = []) {
        if (empty($token) || !is_string($token)) {
            throw new InvalidParamException(Yii::t('yii','Password reset token cannot be blank.'));
        }
        $this->_user = User::findByPasswordResetToken($token);
        if (!$this->_user) {
            throw new InvalidParamException(Yii::t('yii','Wrong password reset token.'));
        }
        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['password', 'repeat_password'], 'required'],
            ['password', 'string','min' => 6, 'max' => 100],
            ['repeat_password','compare','compareAttribute'=>'password'],
        ];
    }

    /**
     * Resets password.
     *
     * @return boolean if password was reset.
     */
    public function resetPassword() {
        $user = $this->_user;       
       
        $user->setPassword($this->password);
        $user->removePasswordResetToken();
         $firstname = $user->firstname;
        $lastname = $user->lastname;
        $email = $user->email;
        
        $this->afterresetemail($firstname,$lastname,$email);
        return $user->save(false);
    }
    
    public function afterresetemail($firstname,$lastname,$email){
    
     $fromEmail = Yii::$app->commonmethod->getAdminEmailID();
        $subject = 'Your password has been successfully reset';
		$message  ='';
		
		$message .= '<tr>
                            <td height="5" style="font-size:13px; color:#656565; line-height:5px; padding-bottom:10px;">Hi '.$firstname.',</td>
                          </tr>
                          <tr>
<tr>
                            <td height="15"></td>
                          </tr>
                            <td style="font-size:13px; color:#656565; line-height:18px; padding-bottom:10px;">You have successfully reset your password.</td>
                          </tr>
<tr>
                            <td height="15"></td>
                          </tr>';

        $mail = Yii::$app->mailer->compose(['html' => 'layouts/mail'], ['content' => $message, 'subject' => $subject])
        ->setFrom([$fromEmail => 'Palcura'])
        ->setTo($email)
        ->setSubject($subject)
        ->send();     
        return $mail;
    
    }
}
