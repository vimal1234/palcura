<?php
namespace backend\models\users;
use common\models\User;
use yii\base\Model;
use Yii;

/**
 * UpdateUser
 */
class UpdateUser extends User
{
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
    public $nationality;
    public $residency_status;
    public $user_type;
    public $status;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['firstname', 'lastname','email', 'gender', 'dob', 'residency_status', 'country', 'region', 'city', 'nationality','phone', 'user_type'
                ], 'required'],
            ['firstname', 'match', 'pattern' => '/^[a-z ]+$/i', 'message' => 'First Name only accepts alphabets and space.'],
            ['firstname', 'filter', 'filter' => 'trim'],
            [['firstname','lastname','residency_status'], 'string', 'max' => 40],
            ['lastname', 'match', 'pattern' => '/^[a-z ]+$/i', 'message' => 'Last Name only accepts alphabets and space.'],
            ['lastname', 'filter', 'filter' => 'trim'],
            ['email', 'string', 'max' => 255],
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
            'firstname' => 'First Name',
            'lastname' => 'Last Name',
            'email' => 'Email',
            'profile_image' => 'Profile Image',
            'descrition' => 'Description',
            'country' => 'Country',
            'region' => 'Region',
            'city' => 'City',
            'nationality' => 'Nationality',
            'residency_status' => 'Residency Status',
            'user_type' => 'User Type',
            'address' => 'Address',
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

        $user = Users::findOne(['id' => $id]);        
        $user->firstname 		= $this->firstname;
        $user->lastname 		= $this->lastname;
        $user->email 			= $this->email;
        $user->phone 			= $this->phone;
        $user->dob 				= (isset($this->dob) ? date('Y-m-d', strtotime($this->dob)) : '');
        $user->gender 			= $this->gender;
        $user->country 			= $this->country;
        $user->region 			= $this->region;
        $user->city 			= $this->city;
        $user->nationality		= $this->nationality;
        $user->user_type 		= $this->user_type;
        $user->residency_status = $this->residency_status;
        if($this->profile_image != '')
            $user->profile_image = $this->profile_image;     

        if($this->password != '')
            $user->setPassword($this->password);

        return $user->save() ? $user : null;
    }
}
