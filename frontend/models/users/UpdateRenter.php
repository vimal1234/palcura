<?php
namespace frontend\models\users;
use frontend\models\users\Users;
use backend\models\owners\Petinformation;
use yii\base\Model;
use Yii;

class UpdateRenter extends Users {
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
    public $address;
    public $country;
    public $region;
    public $city;
    public $nationality;
    public $user_type;
    public $income;
    public $number_of_pets;
    public $house_size;
    public $children;
    public $day_price;
    public $verified_by_admin;
    public $zip_code;
    public $services;
    public $reCaptcha;
    public $pet_weight_limit;
    public $residential_status;
    public $pet_type;
    public $pet_parent_type;
    public $per_day_price;
    public $interested_in_renting;
    public $pitch;
    public $registration_type;
    
	/**
	* @inheritdoc
	*/
    public function rules() {
        return [
            [['firstname', 'lastname','email'], 'required','on'=>'update'],
            ['password', 'string', 'min' => 8, 'max' => 20, 'on' => 'update'],
            [['password'], 'required', 'on' => 'update-password'],
            ['repeat_password', 'compare', 'compareAttribute' => 'password', 'skipOnEmpty' => false, 'message' => Yii::t('yii','Repeat Password must be same as Password.'), 'on' => 'update'],
            [['firstname', 'email'], 'filter', 'filter' => 'trim'],
            ['firstname', 'match', 'pattern' => '/^[a-z ]+$/i', 'message' => Yii::t('yii', 'First Name only accepts alphabets and space.')],
            ['lastname', 'match', 'pattern' => '/^[a-z ]+$/i', 'message' => Yii::t('yii', 'Last Name only accepts alphabets and space.')],
            [['zip_code'],'number'],       
            [['firstname'], 'string', 'max' => 60, 'on' => 'update'],                        
            ['firstname', 'match', 'pattern' => '/^[a-z ]+$/i', 'message' => 'First Name only accepts alphabets and space.'],

            [['email'], 'unique', 'on' => 'update'],
            ['password', 'string', 'min' => 8, 'max' => 20],
            ['repeat_password', 'compare', 'compareAttribute' => 'password'],
        
            //[['residential_status', 'house_size','income','children','number_of_pets'],'safe'],
            // [['chkusrInterests', 'chkusrLanguage'], 'checkCount'],
        ];
    }

    public function checkCount($attribute, $params) {
        // no real check at the moment to be sure that the error is triggered
        if(count(explode(',',$this->$attribute)) <= 5)
            return;

        $attrLabel = '';
        switch($attribute)
        {
            case 'chkusrInterests':
                $attrLabel = 'Interest';
                break;
                
            case 'chkusrLanguage':
                $attrLabel = 'Language';
                break;
        }
        $this->addError($attribute, Yii::t('yii', "You can choose max 5 $attrLabel."));
    }

	/**
	* validateAddress
	* @param N/A
	* @return array
	*/
	public function validateAddress() {
		$stateR			= Yii::$app->commonmethod->regions($this->region);
		$cityR 			= Yii::$app->commonmethod->cities($this->city);
		$address		= (isset($this->address) ? $this->address : ''); 
		$city			= (isset($cityR['name']) ? $cityR['name'] : ''); 
		$state			= (isset($stateR['name']) ? $stateR['name'] : '');
		$zip			= (isset($this->zip_code) ? $this->zip_code : '');

		$request_url 	= 'http://production.shippingapis.com/ShippingAPI.dll?API=Verify&XML='.urlencode('<AddressValidateRequest USERID="'.USPS_USERNAME.'"><Address><Address1></Address1><Address2>'.$address.'</Address2><City>'.$city.'</City><State>'.$state.'</State><Zip5>'.$zip.'</Zip5><Zip4></Zip4></Address></AddressValidateRequest>');
		$ch			 	= curl_init();
		curl_setopt($ch, CURLOPT_URL, $request_url);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		$data			= curl_exec($ch);
		$info 			= curl_getinfo($ch);
		$err			= curl_error($ch);
		curl_close ($ch);
		$xml = simplexml_load_string($data);
		if(isset($xml->Address->Error)) {
			$this->addError('address', Yii::t('yii', 'Please enter valid address.'));
		} else if(isset($xml->Address->Address2)) {
		} else {
			$this->addError('address', Yii::t('yii', 'Please enter valid address.'));
		}
		/*if($stateR['state_code'] != $xml->Address->State){
		$this->addError('region', Yii::t('yii', 'State does not match with address.'));
		
		}
		if(strtoupper($cityR['name']) != $xml->Address->City){
		$this->addError('city', Yii::t('yii', 'City does not match with address.'));		
		}*/
	}
	
	/**
	* @inheritdoc
	*/
    public function attributeLabels() {
        return [
            'firstname'		 => 'First Name',
            'lastname' 		 => 'Last Name',
            'email' 		 => 'Email',
            'profile_image'  => 'Profile Image',
            'descrition' 	 => 'Description',
            'country' 		 => 'Country',
            'region' 		 => 'State/Province',
            'city' 			 => 'City',
            'nationality' 	 => 'Nationality',
            'user_type' 	 => 'User Type',
            'address'		 => 'Address',
            'dob'			 => 'Date of Birth',
            'registration_type' => 'Hear About',
        ];
    }

	/**
	* @inheritdoc
	*/
    public static function findIdentity($id) {
        return static::findOne(['id' => $id]);
    }

	/**
	* becomeMember.
	*
	* @return User|null the saved model or null if saving fails
	*/
	public function sitter_to_borrower($id){
		$attributes = Yii::$app->user->identity->getattributes();
		$user_type = (isset($attributes['user_type']) && $attributes['user_type'] > 0 ? $attributes['user_type'] : 0);

		if($user_type == OWNER) {
			$ut	= OWNER_BORROWER;
		} else if($user_type == SITTER) {
			$ut	= BORROWER_SITTER;
		} else if($user_type == OWNER_SITTER) {
			$ut	= ALL_PROFILES;
		} else {
			$ut = $user_type;
		}
		$user = Users::findOne(['id' => $id]);
		$user->user_type = $ut;
		$user->profile_completed_owner = 1;
		
		if($user->save()){
			return $user->user_type;
		}else{
			return 0;
		}
		
	}
	
    public function updateRecords($id) {
		if (!$this->validate()) {
			return null;
		}
        $this->scenario = 'update';
        $userMapLocation		= Yii::$app->commonmethod->getzipcodeparameters($this->zip_code);
        $user = Users::findOne(['id' => $id]);
		$user->firstname 		= $this->firstname;
		$user->lastname 		= $this->lastname;
		$user->zip_code 		= $this->zip_code;
		$user->latitude 		= (isset($userMapLocation['lat']) ? $userMapLocation['lat'] : '');
		$user->longitude 		= (isset($userMapLocation['lng']) ? $userMapLocation['lng'] : '');
		$user->profile_completed_borrower	= 1 ;
		if($this->profile_image != '') {
			$user->profile_image 	= $this->profile_image;
		}

        if($this->password != '') {
            $user->setPassword($this->password);
		}
		return $user->save() ? $user : null;
    }
    
    public function becomeaborrower($id) {
		if (!$this->validate()) {
			return null;
		}
		$attributes = Yii::$app->user->identity->getattributes();
		$user_type = (isset($attributes['user_type']) && $attributes['user_type'] > 0 ? $attributes['user_type'] : 0);
		if($user_type == OWNER) {
			$new_user_type	= OWNER_BORROWER;
		} else if($user_type == SITTER) {
			$new_user_type	= BORROWER_SITTER;
		} else if($user_type == OWNER_SITTER) {
			$new_user_type	= ALL_PROFILES;
		} else {
			$new_user_type 	= $user_type;
		}
		$userMapLocation		= Yii::$app->commonmethod->getzipcodeparameters($this->zip_code);
        $this->scenario = 'update';
        $user = Users::findOne(['id' => $id]);
		$user->zip_code 		= $this->zip_code;
		$user->firstname 		= $this->firstname;
		$user->lastname 		= $this->lastname;
		$user->user_type 		= $new_user_type;
		$user->latitude 		= (isset($userMapLocation['lat']) ? $userMapLocation['lat'] : '');
		$user->longitude 		= (isset($userMapLocation['lng']) ? $userMapLocation['lng'] : '');
		$user->profile_completed_borrower	= 1 ;
		if($this->profile_image != '') {
			$user->profile_image 	= $this->profile_image;
		}

        if($this->password != '') {
            $user->setPassword($this->password);
		}
		return $user->save() ? $user : null;
    }
    
      
    
}
