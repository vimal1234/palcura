<?php
namespace backend\models\owners;
use backend\models\owners\Owners;
use yii\base\Model;
use Yii;

/**
* AddOwner
*/
class AddOwner extends Owners {

    public $firstname;
    public $lastname;
    public $email;
    public $phone;
    public $password;
    public $repeat_password;
    public $gender;
    public $dob;
    public $profile_image;
    public $description;
    public $country;
    public $region;
    public $city;
    public $residential_status;
    public $income;
    public $number_of_pets;
    public $house_size;
    public $children;
    public $status;

	/**
	* @inheritdoc
	*/
    public function rules() {
        return [
            [['firstname', 'lastname', 'email', 'password', 'repeat_password', 'gender', 'dob', 'country', 'region', 'city','phone','residential_status','income','number_of_pets','house_size','children'], 'required'],
            ['firstname', 'match', 'pattern' => '/^[a-z ]+$/i', 'message' => 'First Name only accepts alphabets and space.'],
            ['firstname', 'filter', 'filter' => 'trim'],
            [['firstname','lastname'], 'string', 'max' => 40],
            ['lastname', 'match', 'pattern' => '/^[a-z ]+$/i', 'message' => 'Last Name only accepts alphabets and space.'],
            ['lastname', 'filter', 'filter' => 'trim'],
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\backend\models\users\Users', 'message' => 'This email address has already been taken.'],
             ['phone','number'],
            ['password', 'string', 'min' => 8, 'max' => 20],
            ['repeat_password', 'compare', 'compareAttribute' => 'password'],
        ];
    }

	/**
	* @inheritdoc
	*/
    public function attributeLabels() {
        return [
            'firstname' 	=> 'First Name',
            'lastname' 		=> 'Last Name',
            'email' 		=> 'Email',
            'profile_image' => 'Profile Image',
            'descrition' 	=> 'Description',
            'country' 		=> 'Country',
            'region' 		=> 'Region',
            'city' 			=> 'City',
            'nationality' 	=> 'Nationality',
            'residency_status' => 'Residency Status',
            'user_type' 	=> 'User Type',
            'address' 		=> 'Address',
        ];
    }

	/**
	* signup user.
	*
	* @return User|null the saved model or null if saving fails
	*/
    public function signup($postArr) {
        if (!$this->validate()) {
            return null;
        }
        $user = new Owners();
        $user->firstname 		= $this->firstname;
        $user->lastname 		= $this->lastname;
        $user->email 			= $this->email;
        $user->phone 			= $this->phone;
        $user->status 			= ACTIVE;
        $user->user_type 		= OWNER;
        $user->verified_by_admin= VERIFIED;
        $user->profile_image 	= $this->profile_image;
        $user->dob 				= (isset($this->dob) ? date('Y-m-d', strtotime($this->dob)) : '');
        $user->gender 			= $this->gender;
        $user->country 			= $this->country;
        $user->region 			= $this->region;
        $user->city 			= $this->city;
        $user->residential_status = $this->residential_status;
        $user->house_size 		= $this->house_size;
        $user->children 		= $this->children;
        $user->income 			= $this->income;
        $user->number_of_pets 	= $this->number_of_pets;
		$user->date_created 	= date("Y-m-d H:i:s");
        $user->setPassword($this->password);
        $user->generateAuthKey();

        //echo'<pre>'; print_r($user); exit();
        return $user->save() ? $user : null;
    }

	/**
	* @inheritdoc
	*/
    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            $this->password_hash = $this->setPassword($this->password_hash);
            return true;
        } else {
            return false;
        }
    }
}
