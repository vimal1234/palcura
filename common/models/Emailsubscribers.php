<?php
namespace common\models;

use Yii;
use yii\base\Model;
use frontend\models\users\Emailsubscriber;

/**
 * Login form
 */
class Emailsubscribers extends Model
{
    public $email;
    private $_user;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['email'], 'required'],  
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'email','message'=>'Please enter a valid Email Address.'],
            // rememberMe must be a boolean value

        ];
    }
  public function savedata() {
       
        #####= transaction begin
		$connection = \Yii::$app->db;
		$transaction = $connection->beginTransaction();
		try {
			$user = new Emailsubscriber();
		
			$user->email = $this->email;
						
				if($user->save()) {
					$user_id = $user->id;
					$response_counter=0;									
					$transaction->commit();
					//return $response_counter ? $user_id : null;
					return $user_id;
				}
			} catch(\Exception $e) {
				$transaction->rollback();
				throw $e;
			}
			return null; 
    }

  

    /**
     * Logs in a user using the provided username and password.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
		  setcookie (\Yii::getAlias('@site_title')."_user_email", '');
                        
        
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
