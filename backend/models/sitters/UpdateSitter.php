<?php
namespace backend\models\sitters;
use common\models\User;
use yii\base\Model;
use Yii;

/**
* UpdateOwner
*/
class UpdateSitter extends Sitters {
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
            [['firstname', 'lastname', 'email', 'gender', 'dob', 'country', 'region', 'city','phone','residential_status','income','number_of_pets','house_size','children','services_types','day_price'], 'required'],
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
	* updatedata.
	*
	* @return User|null the saved model or null if saving fails
	*/
    public function updatedata($id,$mediaArr='') {
        if (!$this->validate()) {
            return null;
        }

        $user = Sitters::findOne(['id' => $id]); 
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
        $user->day_price	 	= $this->day_price;
        
        if($this->profile_image != '')
            $user->profile_image = $this->profile_image;

        if($this->password != '')
            $user->setPassword($this->password);

		if($user->save()) {
			##### 1=Document, 2=Image
			$user_id = $id;
			if(!empty($mediaArr)) {
				$response_counter=0;
				if(isset($mediaArr['upload_documents']) && !empty($mediaArr['upload_documents'])) {
					$Identity_documents	=	Yii::$app->commonmethod->getUserDocuments($user_id,ID_DOCUMENTS);
					if(isset($Identity_documents) && !empty($Identity_documents)) {
						for($i = 0; $i < LIMIT_USER_DOCUMENTS; $i++) {
							$data1 					= new Documents();
							$data1 					= Documents::findOne(['id' => $Identity_documents[$i]['id']]); 
							$data1->name 			= (isset($mediaArr['upload_documents'][$i]) ? $mediaArr['upload_documents'][$i] : '');
							$data1->delete_status	= (isset($mediaArr['upload_documents'][$i]) ? '0' : REMOVE);
							if($data1->save()) {
								$response_counter=1;
							}
						}
					} else {
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
					}
				}

				if(isset($mediaArr['upload_images']) && !empty($mediaArr['upload_images'])) {
					$user_images	=	Yii::$app->commonmethod->getUserDocuments($user_id,USER_IMAGES);
					if(isset($user_images) && !empty($user_images)) {
						for($i=0; $i < LIMIT_USER_IMAGES; $i++) {
							$data2 					= Documents::findOne(['id' => $user_images[$i]['id']]); 
							$data2->name 			= (isset($mediaArr['upload_images'][$i]) ? $mediaArr['upload_images'][$i] : '');
							$data2->delete_status	= (isset($mediaArr['upload_images'][$i]) ? '0' : REMOVE);
							if($data2->save()) {
								$response_counter=1;
							}
						}
					} else {
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
					}
				}
				return $response_counter ? $response_counter : null;
			}
			return $user_id;
		}
    }
}
