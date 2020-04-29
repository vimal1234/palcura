<?php
namespace backend\models;
use Yii;
use yii\base\Model;
use common\models\Admin;

/**
 * Password update
 */
class PasswordUpdate extends Model
{
    public $email;
	public $phone_number;
	public $whatsapp_number;
    public $old_password;
    public $new_password;
    public $repeat_password;
    private $_user;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
			[['email','phone_number','whatsapp_number'], 'required'],
			['email', 'filter', 'filter' => 'trim'],
			['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['new_password', 'string', 'min' => 6, 'max' => 15],
            [['phone_number','whatsapp_number'], 'string', 'min' => 10, 'max' => 15],
            ['repeat_password','compare','compareAttribute'=>'new_password'],
            ['old_password', 'validateOldPassword'],
        ];
    }
    
    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser() {
		$adminid = Yii::$app->user->identity->id;
        if ($this->_user === null) {
            $this->_user = Admin::findIdentity($adminid);
        }
        return $this->_user;
    }

     /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validateOldPassword($attribute, $params) {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
			
            if (!$user || !$user->validatePassword($this->old_password)) {
				 $this->addError($attribute, 'Incorrect old password.');
            }else{
				return true;
			}
			
        }
    }    
	/**
	* Password update
	*
	* @return User|null the saved model or null if saving fails
	*/
	public function updateProfile() {
        if (!$this->validate()) {
            return false;
        }
		$user = $this->getUser();
		$user->email 				= $this->email;
		$user->phone_number 		= $this->phone_number;
		$user->whatsapp_number 		= $this->whatsapp_number;
        if($this->new_password 		!=''){
			$user->password_hash 	= Yii::$app->security->generatePasswordHash($this->new_password); 
		}
		return $user->save(false) ? true : false;
    }
}
