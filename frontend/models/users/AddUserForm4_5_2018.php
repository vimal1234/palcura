<?php
namespace frontend\models\users;
use frontend\models\users\Users;
use frontend\models\UserPet;
use frontend\models\common\UserServices;
use backend\models\sitters\Documents;
use backend\models\sitters\Serviceprovider;
use backend\models\owners\Petinformation;
use yii\base\Model;
use Yii;

/**
* AddUserForm form
*/
class AddUserForm extends Model {
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
    
    //new fields added
    public $pet_name;
    public $picture_of_pet;
    public $home_ddress;
    public $care_note;//not mandatory;
        
    public $pitch;
    public $registration_type;
    public $pet_parent_type_sr;
    public $accept_terms;
    public $user_signin_type;
    public $renting_pet;
    
	/**
	* @inheritdoc
	*/
    public function rules() {
        return [
//[['pet_parent_type'], 'validateOwnerPet'],
            [['firstname', 'lastname', 'email', 'password', 'repeat_password', 'dob','user_type', 'zip_code','registration_type'], 'required'],
            [['user_signin_type'],'safe'],
            ['firstname', 'match', 'pattern' => '/^[a-z ]+$/i', 'message' => 'First Name only accepts alphabets and space.'],
            ['firstname', 'filter', 'filter' => 'trim'],
            [['firstname','lastname'], 'string', 'max' => 40],
            ['lastname', 'match', 'pattern' => '/^[a-z ]+$/i', 'message' => 'Last Name only accepts alphabets and space.'],
            ['lastname', 'filter', 'filter' => 'trim'],
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'email','message'=>'Please enter a valid Email Id.'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\frontend\models\users\Users', 'message' => Yii::t('yii','This email address has already been taken.')],
            ['password', 'string', 'min' => 8, 'max' => 20],
            ['repeat_password', 'compare', 'compareAttribute' => 'password'],

             array('accept_terms', 'compare', 'compareValue' => 1, 'message' => Yii::t('yii','You should accept the terms and conditions to register with us.')),
             
             [['reCaptcha'], \yii\recaptcha\ReCaptchaValidator::className(), 'secret' => SECRET_KEY, 'uncheckedMessage' => 'Please verify that you are not a robot.']
        ];
    }

	/**
	* @inheritdoc
	*/
    public function attributeLabels() {
        return [
            'firstname'		 => 'First Name',
            'lastname' 		 => 'Last Name',
            'email' 		 => 'Email',
            'profile_image'  => 'Display Image',
            'descrition' 	 => 'Description',
            'country' 		 => 'Country',
            'region' 		 => 'State/Province',
            'city' 			 => 'City',
            'nationality' 	 => 'Nationality',
            'user_type' 	 => 'User Type',
            'address'		 => 'Address',
            'dob'			 => 'Date of Birth',
            'registration_type' => 'Hear About',
            'pet_parent_type_sr' => 'Pet Type',
            'pet_parent_type'  => 'Pet Type',
            'repeat_password' => 'Confirm password',
            'pet_name' 		 => 'Name of the pet',
            'picture_of_pet' => 'Picture of the pet',
            'home_ddress'	 => 'Home Address',
            'care_note'		 => 'Care Notes',
            'pet_weight_limit' => 'Pet Weight',
            'day_price'		=> 'Price',
'upload_home_images'=> 'House Image',
            
        ];
    }

	/**
	* @inheritdoc
	*/
    public static function findIdentity($id) {
        return static::findOne(['id' => $id]);
    }

	public function validateServices() {
		if(count(array_unique($this->services)) < count($this->services)) {
			$this->addError('services', Yii::t('yii', 'Services name should not be same.'));
		}
	}
	
	public function validateregion(){
		if(empty($this->region) || $this->region == 'prompt'){	
		$this->addError('region', Yii::t('yii', 'State/Province can not be empty.'));	
		}
	}
	public function validatecity(){
	if(empty($this->city) || $this->city == 'prompt'){
	$this->addError('city', Yii::t('yii', 'City can not be empty.'));
	}
	}
	
	public function validatepetweight(){
		if($this->pet_parent_type_sr == 1){
		   if(empty($this->pet_weight_limit)){
		   $this->addError('pet_weight_limit', Yii::t('yii', 'Pet weight can not be empty.'));	   
		   }	
		}	
	}

public function validateOwnerPet(){
	//echo 'model validation';

	if($_POST['AddUserForm']['pet_parent_type'] && $_POST['AddUserForm']['user_type']==OWNER && $_POST['AddUserForm']['interested_in_renting']=='1'){	
	$postArray = $_POST['AddUserForm'];
		foreach($postArray['pet_parent_type'] as $k=>$v){
			if(isset($v) && empty($v)){
				$this->addError('pet_parent_type', Yii::t('yii', 'Select pet type.'));							
			}
			if(isset($postArray['pet_type'][$k]) && empty($postArray['pet_type'][$k]) && $v=='1' ){
				$this->addError('pet_type', Yii::t('yii', 'Select breed type.'));				
			}
			if(isset($postArray['pet_name'][$k]) && empty($postArray['pet_name'][$k])){
				$this->addError('pet_name', Yii::t('yii', 'Enter your pet name.'));				
			}
			if(isset($postArray['per_day_price'][$k]) && empty($postArray['per_day_price'][$k])){
				$this->addError('per_day_price', Yii::t('yii', 'Enter per day price for pet renting.'));				
			}						
		}
	}

}
	
	/**
	* validateDates
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
		if($stateR['state_code'] != $xml->Address->State){
		$this->addError('region', Yii::t('yii', 'State does not match with address.'));		
		}
		if(strtoupper($cityR['name']) != $xml->Address->City){
		$this->addError('city', Yii::t('yii', 'City does not match with address.'));		
		}
	}

public function validateAddressbyZip() {

		$stateR			= Yii::$app->commonmethod->regions($this->region);
		$cityR 			= Yii::$app->commonmethod->cities($this->city);
		
		$address		= ''; 
		$city			= (isset($cityR['name']) ? $cityR['name'] : ''); 
		$state			= (isset($stateR['name']) ? $stateR['name'] : '');
		$zip			= (isset($this->zip_code) ? $this->zip_code : '');

		$request_url 	= 'http://production.shippingapis.com/ShippingAPI.dll?API=CityStateLookup&XML='.urlencode('<CityStateLookupRequest USERID="'.USPS_USERNAME.'"><ZipCode><Zip5>'.$zip.'</Zip5></ZipCode></CityStateLookupRequest>');
		$ch			 	= curl_init();
		curl_setopt($ch, CURLOPT_URL, $request_url);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		$data			= curl_exec($ch);
		$info 			= curl_getinfo($ch);
		$err			= curl_error($ch);
		curl_close ($ch);
		$xml = simplexml_load_string($data);

		if(isset($xml->ZipCode->Error)) {
			$this->addError('zip_code', Yii::t('yii', 'Please enter a valid zipcode.'));
		}
		if($stateR['state_code'] != $xml->ZipCode->State){
		$this->addError('region', Yii::t('yii', 'State does not match with zipcode.'));		
		}
		if(strtoupper($cityR['name']) != $xml->ZipCode->City){
		$this->addError('city', Yii::t('yii', 'City does not match with zipcode.'));		
		}
	}

	/**
	* savedata.
	*
	* @return User|null the saved model or null if saving fails
	*/
    public function savedata() {
        if (!$this->validate()) {
            return null;
        }
        $creditdollrs = 0;
		$status = PENDING;
		$userMapLocation		= Yii::$app->commonmethod->getzipcodeparameters($this->zip_code);
		$autoCredits			= Yii::$app->commonmethod->getTeaserEmail($this->email);
		if($autoCredits==1){
		$creditdollrs = 5;
		$status = '1';
		}

        #####= transaction begin
		$connection = \Yii::$app->db;
		$transaction = $connection->beginTransaction();
		try {
			$user = new Users();
			$user->firstname 		= $this->firstname;
			$user->lastname 		= $this->lastname;
			$user->email 			= $this->email;
			$user->status 			= $status;
			$user->user_type      	= $this->user_type; 
			$user->user_signin_type = $this->user_type; 
			$user->dob 				= (isset($this->dob) ? $this->dob : 0);		
			$user->zip_code 		= $this->zip_code;
			$user->user_credits		= 0;
			$user->sitter_credits	= $creditdollrs;
			$user->registration_type= $this->registration_type;
			$user->date_created 	= date("Y-m-d H:i:s");
			$user->setPassword($this->password);
			$user->latitude 		= (isset($userMapLocation['lat']) ? $userMapLocation['lat'] : '');
			$user->longitude 		= (isset($userMapLocation['lng']) ? $userMapLocation['lng'] : '');
			$user->generateAuthKey();
						
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
	* @inheritdoc
	* @param type $insert
	* @return boolean
	*/
    public function beforeSave($insert) {
        if(parent::beforeSave($insert)){
           $this->password_hash = $this->setPassword($this->password_hash);
           return true;
        }else{
           return false;
        }
    }
    
    public function updatesubscription($owner,$sitter,$renter){
    $loggeduser = Yii::$app->user->identity->id;
    $connection = \Yii::$app->db;        
     //update overall rating for reciever          
	 $model = $connection->createCommand('Update user set unsubscribe_owner="'.$owner.'", unsubscribe_renter="'.$renter.'",unsubscribe_sitter="'.$sitter.'" where id="'.$loggeduser.'"');		
			if($model->execute()){				
			 return true;
			} else{
			 return false;
			  }
    
    }
}
