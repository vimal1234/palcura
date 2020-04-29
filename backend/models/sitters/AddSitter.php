<?php
namespace backend\models\sitters;
use backend\models\sitters\Sitters;
use backend\models\sitters\Documents;
use yii\base\Model;
use Yii;

/**
* AddSitter
*/
class AddSitter extends Sitters {

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
    public $services_types;
    public $day_price;    
    public $status;

    ####### media files	
    public $upload_documents;
    public $upload_images;
	/**
	* @inheritdoc
	*/
    public function rules() {
        return [
            [['firstname', 'lastname', 'email', 'password', 'repeat_password', 'gender', 'dob', 'country', 'region', 'city','phone','residential_status','income','number_of_pets','house_size','children','services_types','day_price'], 'required'],
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
            [['day_price'],'number','max' => 1000],
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
	* savedata.
	*
	* @return User|null the saved model or null if saving fails
	*/
    public function savedata($mediaArr='') {
        if (!$this->validate()) {
            return null;
        }

        ###############= transaction begin =###############
		$connection = \Yii::$app->db;
		$transaction = $connection->beginTransaction();
		try {
			$user = new Sitters();
			$user->firstname 		= $this->firstname;
			$user->lastname 		= $this->lastname;
			$user->email 			= $this->email;
			$user->phone 			= $this->phone;
			$user->status 			= ACTIVE;
			$user->user_type 		= SITTER;
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
			$user->day_price	 	= $this->day_price;
			$user->date_created 	= date("Y-m-d H:i:s");
			$user->setPassword($this->password);
			$user->generateAuthKey();
			if($user->save()) {
				##### 1=Document, 2=Image, 3=home images
				$user_id = $user->id;
				$response_counter=0;
				for($i = 0; $i < LIMIT_USER_DOCUMENTS; $i++) {
					$data1 					= new Documents();
					$data1->name 			= (isset($mediaArr['upload_documents'][$i]) ? $mediaArr['upload_documents'][$i] : '');
					$data1->document_type	= ID_DOCUMENTS;
					$data1->user_id		 	= $user_id;
					$data1->delete_status	= (isset($mediaArr['upload_documents'][$i]) ? '0' : REMOVE);
					if($data1->save()) {
						$response_counter=1;
					}
				}

				for($i=0; $i < LIMIT_USER_IMAGES; $i++) {
					$data2 					= new Documents();
					$data2->name 			= (isset($mediaArr['upload_images'][$i]) ? $mediaArr['upload_images'][$i] : '');
					$data2->document_type	= USER_IMAGES;
					$data2->delete_status	= (isset($mediaArr['upload_images'][$i]) ? '0' : REMOVE);
					$data2->user_id 		= $user_id;
					if($data2->save()) {
						$response_counter=1;
					}
				}
				
				for($i=0; $i < LIMIT_HOME_IMAGES; $i++) {
					$data3 					= new Documents();
					$data3->name 			= (isset($mediaArr['upload_home_images'][$i]) ? $mediaArr['upload_home_images'][$i] : '');
					$data3->document_type	= HOME_IMAGES;
					$data3->delete_status	= (isset($mediaArr['upload_home_images'][$i]) ? '0' : REMOVE);
					$data3->user_id 		= $user_id;
					if($data3->save()) {
						$response_counter=1;
					}
				}
				
			$transaction->commit();
			return $response_counter ? $response_counter : null;
			}
		} catch(\Exception $e) {
			$transaction->rollback();
			//throw $e;
		}
		return null;			
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
