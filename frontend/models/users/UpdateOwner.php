<?php
namespace frontend\models\users;
use frontend\models\users\Users;
use frontend\models\UserPet;
use backend\models\owners\Petinformation;
use yii\base\Model;
use Yii;

class UpdateOwner extends Users {
    public $firstname;
    public $lastname;
    public $email;
public $paypal_email;
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
	public $renting_pet;
	//public $profile_completed_owner;
	 //new fields added
    public $pet_name;
    public $picture_of_pet;
    public $care_note;//not mandatory;
    public $pet_id;
	/**
	* @inheritdoc
	*/
    public function rules() {
        return [
            [['firstname', 'lastname','email','registration_type'], 'required','on'=>'update'],
            ['password', 'string', 'min' => 8, 'max' => 20, 'on' => 'update'],
            [['password'], 'required', 'on' => 'update-password'],
            ['repeat_password', 'compare', 'compareAttribute' => 'password', 'skipOnEmpty' => false, 'message' => Yii::t('yii','Repeat Password must be same as Password.'), 'on' => 'update'],
            //[['firstname', 'lastname', 'email'], 'filter', 'filter' => 'trim'],
            [['firstname', 'email'], 'filter', 'filter' => 'trim'],
            ['firstname', 'match', 'pattern' => '/^[a-z ]+$/i', 'message' => Yii::t('yii', 'First Name only accepts alphabets and space.')],
            ['lastname', 'match', 'pattern' => '/^[a-z ]+$/i', 'message' => Yii::t('yii', 'Last Name only accepts alphabets and space.')],
            //[['firstname', 'lastname'], 'string', 'max' => 60, 'on' => 'update'],                     
            [['firstname'], 'string', 'max' => 60, 'on' => 'update'],                        
            ['firstname', 'match', 'pattern' => '/^[a-z ]+$/i', 'message' => 'First Name only accepts alphabets and space.'],
            ['email', 'email'],
['paypal_email', 'email'],
            [['per_day_price'], 'default', 'value'=> 1],
           // [['per_day_price'],'number', 'numberPattern' => '/^\s*[-+]?[0-9]*[.,]?[0-9]+([eE][-+]?[0-9]+)?\s*$/', 'min'=>1,],
            [['email'], 'unique', 'on' => 'update'],
            ['password', 'string', 'min' => 8, 'max' => 20],
            ['repeat_password', 'compare', 'compareAttribute' => 'password'],
            //['address', 'validateAddress'],
 //[['zip_code'], 'validateAddressbyZip'],   
 			[['picture_of_pet'],'safe'],       
             [['pet_parent_type','per_day_price','address','zip_code','paypal_email','country','region','city','pet_name'], 'required',  'when' => function($model) {
				return $model->renting_pet == 1;
            },'whenClient' => "function (attribute, value) { return $('#updateowner-renting_pet').val() == '1'; }"],
            [['pet_type'], 'required',  'when' => function($model) {
				return $model->pet_parent_type == 1;
            },'whenClient' => "function (attribute, value) { return $('#updateowner-pet_parent_type').val() == '1'; }"],
           
        ];
    }
    
    	public function validateOwnerPet(){
		//echo 'model validation';

		if($_POST['UpdateOwner']['pet_parent_type'] && $_POST['UpdateOwner']['renting_pet']=='1'){	
		$postArray = $_POST['UpdateOwner'];
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

$json = json_encode($xml);

		$newarray = json_decode($json,TRUE);
		//echo $cityR['name']; echo "<br>";print_r($newarray['ZipCode']['City']);die;
		if(isset($xml->ZipCode->Error)) {
			$this->addError('zip_code', Yii::t('yii', 'Please enter a valid zipcode.'));
		}
		if($stateR['state_code'] != $newarray['ZipCode']['State']){
		$this->addError('region', Yii::t('yii', 'State does not match with zipcode.'));		
		}
		if(strtoupper($cityR['name']) != $newarray['ZipCode']['City']){
		$this->addError('city', Yii::t('yii', 'City does not match with zipcode.'));		
		}
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
	/*public function validateAddress() {
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
		//echo "<pre>"; print_r($xml); die;
		if(isset($xml->Address->Error)) {
			$this->addError('address', Yii::t('yii', 'Please enter valid address.'));
		} else if(isset($xml->Address->Address2)) {
		} else {
			$this->addError('address', Yii::t('yii', 'Please enter valid address.'));
		}
		
	}*/
	
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
		//$xml = simplexml_load_string($data);
	$array_data = json_decode(json_encode(simplexml_load_string($data)), true);
			if(isset($array_data['Address']['Error'])){
			$this->addError('address', Yii::t('yii', 'Please enter valid address.'));
			}else{
	
			$uspscity = $array_data['Address']['City'];
			$uspsstate = $array_data['Address']['State'];
			$clientsity = strtoupper($city);
			$clientstatecode = $stateR['state_code'];
			$uspszip = $array_data['Address']['Zip5'];
			$clientzip = $zip;
	
				if($clientstatecode != $uspsstate){
				$this->addError('region', Yii::t('yii', 'State does not match with address (hint '.$uspsstate.').'));
				}
	
				if($clientsity != $uspscity){
				$this->addError('city', Yii::t('yii', 'City does not match with address (hint '.$uspscity.').'));
				}
				
				if($clientzip != $uspszip){
				$this->addError('zip_code', Yii::t('yii', 'Zipcode does not match with address (hint '.$uspszip.').'));
				}
			
			}
	
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
            'pet_parent_type' => 'Pet Type',
            'dob'			 => 'Date of Birth',
            'registration_type' => 'Hear About',
            'zip_code'		=> 'Zip Code'
        ];
    }

	/**
	* @inheritdoc
	*/
    public static function findIdentity($id) {
        return static::findOne(['id' => $id]);
    }

	/**
	* updateRecords.
	*
	* @return User|null the saved model or null if saving fails
	*/
    public function updateRecords($id,$n=0) {
		if (!$this->validate()) {
			return null;
		}

		$attributes = Yii::$app->user->identity->getattributes();
		if($n == 1) {
			$user_type = (isset($attributes['user_type']) && $attributes['user_type'] > 0 ? $attributes['user_type'] : 0);
			if($user_type == SITTER) {
				$ut	= OWNER_SITTER;
			} else if($user_type == BORROWER) {
				$ut	= OWNER_BORROWER;
			} else if($user_type == BORROWER_SITTER) {
				$ut	= ALL_PROFILES;
			} else {
				$ut 	= $user_type;
			}
		}
		
		$userMapLocation		= Yii::$app->commonmethod->getzipcodeparameters($this->zip_code);
        $this->scenario = 'update';
        $user = Users::findOne(['id' => $id]);

		$user->firstname 		= $this->firstname;
		$user->lastname 		= $this->lastname;
		$user->paypal_email 	= $this->paypal_email;
		$user->address 			= $this->address;
		$user->zip_code 		= $this->zip_code;
		if($n == 1) {
			$user->user_type      	= $ut;
		}else{
			$user->user_type		= '4';
		}		
		$user->country 			= (isset($this->country) ? $this->country : 0);
		$user->region 			= $this->region;
		$user->city 			= $this->city;
		$user->renting_pet 		= $this->interested_in_renting;	
		$user->registration_type= $this->registration_type;
		$user->latitude 		= (isset($userMapLocation['lat']) ? $userMapLocation['lat'] : '');
		$user->longitude 		= (isset($userMapLocation['lng']) ? $userMapLocation['lng'] : '');
		$user->profile_completed_owner = 1;
		if($this->profile_image != '') {
			$user->profile_image 	= $this->profile_image;
		}
		

        if($this->password != '') {
            $user->setPassword($this->password);
		}
		
		if($user->save()) {
					$user_id = $user->id;
					$response_counter=0;
					//get pet information
			
					if(($this->interested_in_renting == '1')) {
					$petparentcount	= count($this->pet_parent_type);				
							for($si = 0; $si < $petparentcount; $si++){
							$pet_info 							= new UserPet();
							$pet_info->pet_parent_id			= (isset($this->pet_parent_type[$si]) ? $this->pet_parent_type[$si] : 0);
							$pet_info->type 			    	= (isset($this->pet_type[$si]) ? $this->pet_type[$si] : 0);
							$pet_info->user_id 			    	= $user_id;
							$pet_info->per_day_price 			= (isset($this->per_day_price[$si]) ? $this->per_day_price[$si] : 0);
							$pet_info->care_note				= (isset($this->care_note[$si]) ? $this->care_note[$si] : '');
							$pet_info->picture_of_pet			= (isset($this->picture_of_pet[$si]) ? $this->picture_of_pet[$si] : '');							
							$pet_info->name						= (isset($this->pet_name[$si]) ? $this->pet_name[$si] : '');						
								if($pet_info->save()) {
									$response_counter=1;
								}					
							 }						
					}
					return $user_id;
		}else{
					return null;
		}
		//return $user->save() ? $user : null;
    }
    
	/**
	* becomeanowner
	*
	* @return User|null the saved model or null if saving fails
	*/
	public function sitter_to_owner($id){
		$attributes = Yii::$app->user->identity->getattributes();
		$user_type = (isset($attributes['user_type']) && $attributes['user_type'] > 0 ? $attributes['user_type'] : 0);
		
		
		if($user_type == SITTER) {
			$ut	= OWNER_SITTER;
		} else if($user_type == BORROWER) {
			$ut	= OWNER_BORROWER;
		} else if($user_type == BORROWER_SITTER) {
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
	
    public function becomeanowner($id) {
		if (!$this->validate()) {
			return null;
		}
		$attributes = Yii::$app->user->identity->getattributes();
		$user_type = (isset($attributes['user_type']) && $attributes['user_type'] > 0 ? $attributes['user_type'] : 0);
		if($user_type == SITTER) {
			$ut	= OWNER_SITTER;
		} else if($user_type == BORROWER) {
			$ut	= OWNER_BORROWER;
		} else if($user_type == BORROWER_SITTER) {
			$ut	= ALL_PROFILES;
		} else {
			$ut 	= $user_type;
		}
		$userMapLocation		= Yii::$app->commonmethod->getzipcodeparameters($this->zip_code);
        $this->scenario = 'update';
        $user = Users::findOne(['id' => $id]);
		
		$user->profile_completed_owner = 1;
		$user->firstname 		= $this->firstname;
		$user->lastname 		= $this->lastname;
		$user->paypal_email 			= $this->paypal_email;
		$user->address 			= $this->address;
		$user->zip_code 		= $this->zip_code;
		$user->user_type      	= $ut;
		$user->country 			= (isset($this->country) ? $this->country : 0);
		$user->region 			= $this->region;
		$user->city 			= $this->city;
		$user->renting_pet 		= $this->interested_in_renting;	
		$user->registration_type= $this->registration_type;
		$user->latitude 		= (isset($userMapLocation['lat']) ? $userMapLocation['lat'] : '');
		$user->longitude 		= (isset($userMapLocation['lng']) ? $userMapLocation['lng'] : '');
		if($this->profile_image != '') {
			$user->profile_image 	= $this->profile_image;
		}
	
        if($this->password != '') {
            $user->setPassword($this->password);
		}
		
		if($user->save()) {
					$user_id = $user->id;
					$response_counter=0;
					//get pet information
			
					if(($this->interested_in_renting == '1')) {
					$petinformation	=	Yii::$app->commonmethod->getUserPetsInfo($user_id);
				
						if(isset($petinformation) && !empty($petinformation) ){ 					
						$petparentcount	= count($this->pet_parent_type);
						for($si = 0; $si < $petparentcount; $si++){	
						$pet_id = $this->pet_id[$si];	
							if($this->pet_id[$si] != 0){							
								$pet_info							= UserPet::findOne(['id' => $pet_id]);	
								if(!empty($this->picture_of_pet) && $this->picture_of_pet[$si] != null){
								$pictureofpet = $this->picture_of_pet[$si];
								}else{
								$pictureofpet = $pet_info->picture_of_pet;
								}
								
								if($pet_info != null){		
											
								$pet_info->pet_parent_id			= (isset($this->pet_parent_type[$si]) ? $this->pet_parent_type[$si] : 0);
								$pet_info->type 			    	= (isset($this->pet_type[$si]) ? $this->pet_type[$si] : 0);
								$pet_info->user_id 			    	= $user_id;
								$pet_info->per_day_price 			= (isset($this->per_day_price[$si]) ? $this->per_day_price[$si] : 0);
								$pet_info->care_note				= (isset($this->care_note[$si]) ? $this->care_note[$si] : '');
								$pet_info->picture_of_pet			= $pictureofpet;
								$pet_info->name						= (isset($this->pet_name[$si]) ? $this->pet_name[$si] : '');
					
									if($pet_info->save()) {
										$response_counter=1;
									}
								}
							 }else{
							 
							  	$pet_info 							= new UserPet();
								$pet_info->pet_parent_id			= (isset($this->pet_parent_type[$si]) ? $this->pet_parent_type[$si] : 0);
								$pet_info->type 			    	= (isset($this->pet_type[$si]) ? $this->pet_type[$si] : 0);
								$pet_info->user_id 			    	= $user_id;
								$pet_info->per_day_price 			= (isset($this->per_day_price[$si]) ? $this->per_day_price[$si] : 0);
								$pet_info->care_note				= (isset($this->care_note[$si]) ? $this->care_note[$si] : '');
								$pet_info->picture_of_pet			= (isset($this->picture_of_pet[$si]) ? $this->picture_of_pet[$si] : '');		
								$pet_info->name						= (isset($this->pet_name[$si]) ? $this->pet_name[$si] : '');						
								if($pet_info->save()) {
									$response_counter=1;
								}
							  
							 }	
						 }	
						}else{						
							$petparentcount	= count($this->pet_parent_type);
				
							for($si = 0; $si < $petparentcount; $si++){
							$pet_info 							= new UserPet();
							$pet_info->pet_parent_id			= (isset($this->pet_parent_type[$si]) ? $this->pet_parent_type[$si] : 0);
							$pet_info->type 			    	= (isset($this->pet_type[$si]) ? $this->pet_type[$si] : 0);
							$pet_info->user_id 			    	= $user_id;
							$pet_info->per_day_price 			= (isset($this->per_day_price[$si]) ? $this->per_day_price[$si] : 0);
							$pet_info->care_note				= (isset($this->care_note[$si]) ? $this->care_note[$si] : '');
							$pet_info->picture_of_pet			= (isset($this->picture_of_pet[$si]) ? $this->picture_of_pet[$si] : '');							
							$pet_info->name						= (isset($this->pet_name[$si]) ? $this->pet_name[$si] : '');						
								if($pet_info->save()) {
									$response_counter=1;
								}					
							 }
						}						
					}
					return $user_id;
		}else{
					return null;
		}
		//return $user->save() ? $user : null;
    }    
}
