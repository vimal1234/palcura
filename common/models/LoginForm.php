<?php
namespace common\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $email;
    public $password;
    public $rememberMe = true;

    private $_user;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['email', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
			 ['password', 'string','min' => 6, 'max' => 100],
            
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            
            if (!$user) {
                $this->addError('user', Yii::t('yii','Incorrect email or password! Please verify your account (if not verified yet).'));
            }elseif (!$user->validatePassword($this->password)) {
                $this->addError($attribute, Yii::t('yii','Incorrect email or password.'));
            }elseif ($user->delete_status){
            	$this->addError('user', Yii::t('yii','This account is deleted or deactivated. Please contact site admin.'));
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
                if($this->rememberMe == 1){ 
                     setcookie (\Yii::getAlias('@site_title')."_user_email", $this->email, time()+3600*24*4);
                     setcookie (\Yii::getAlias('@site_title')."_user_password", $this->password, time()+3600*24*4);
                }else{
                     setcookie (\Yii::getAlias('@site_title')."_user_email", '');
                     setcookie (\Yii::getAlias('@site_title')."_user_password", '');
                }        
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        } else {
            return false;
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            
            $this->_user = User::findByEmail($this->email);
        }

        return $this->_user;
    }
    
   #####################= login from FB or G PLUS =###################
    public function loginFromSocailNW($email)
    {
        if($this->getSocialUser($email)){
            return Yii::$app->user->login($this->getSocialUser($email), $this->rememberMe ? 3600 * 24 * 30 : 0);
        }
        else{
            return false;
        }
     }

	##################= get user socail sites user =###############
    function getSocialUser($email)
    {
        if ($this->_user === null) {
             $this->_user = User::findByEmail($email);
        }
        return $this->_user;
    }    
}
