<?php
namespace backend\models\renters;
use common\models\User;
use yii\base\Model;
use Yii;

/**
* UpdateRenter
*/
class UpdateRenter extends Renters {
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
            [['firstname', 'lastname', 'email', 'gender', 'dob', 'country', 'region', 'city','phone','residential_status','income','number_of_pets','house_size','children'], 'required'],
            ['firstname', 'match', 'pattern' => '/^[a-z ]+$/i', 'message' => 'First Name only accepts alphabets and space.'],
            ['firstname', 'filter', 'filter' => 'trim'],
            [['firstname','lastname'], 'string', 'max' => 40],
            ['lastname', 'match', 'pattern' => '/^[a-z ]+$/i', 'message' => 'Last Name only accepts alphabets and space.'],
            ['lastname', 'filter', 'filter' => 'trim'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique'],
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
            'user_type' 	=> 'User Type',
            'address' 		=> 'Address',
        ];
    }

	/**
	* @inheritdoc
	*/
    public static function findIdentity($id) {
        return static::findOne(['id' => $id]);
    }

	/**
	* update member.
	*
	* @return User|null the saved model or null if saving fails
	*/
    public function updateUser($id) {
        if (!$this->validate()) {
            return null;
        }

        $user = Renters::findOne(['id' => $id]); 
        $user->firstname 		= $this->firstname;
        $user->lastname 		= $this->lastname;
        $user->email 			= $this->email;
        $user->phone 			= $this->phone;
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
        
        if($this->profile_image != '')
            $user->profile_image = $this->profile_image;

        if($this->password != '')
            $user->setPassword($this->password);

		//echo'<pre>'; print_r($user); exit();
        return $user->save() ? $user : null;
    }
}
