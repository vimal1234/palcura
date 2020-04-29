<?php
namespace frontend\models\users;
use frontend\models\users\Users;
use backend\models\sitters\Documents;
use backend\models\sitters\Serviceprovider;
use backend\models\owners\Petinformation;
use frontend\models\common\UserServices;
use yii\base\Model;
use Yii;

class UpdateSitter extends Users {
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
    public $pet_weight_limit;
    public $residential_status;
    public $pet_type;
    public $pet_parent_type;
    public $per_day_price;
    public $interested_in_renting;
    public $pitch;
    public $registration_type;
    public $pet_parent_type_sr;  
    public $pet_service_id;  
    public $certification;
	/**
	* @inheritdoc
	*/
    public function rules() {
        return [
           //[['firstname', 'lastname','email', 'address', 'zip_code', 'country', 'region', 'city', 'services', 'day_price', 'pitch','pet_parent_type_sr'], 'required','on'=>'update'],
            ['password', 'string', 'min' => 8, 'max' => 20, 'on' => 'update'],
            [['password'], 'required', 'on' => 'update-password'],
            ['repeat_password', 'compare', 'compareAttribute' => 'password', 'skipOnEmpty' => false, 'message' => Yii::t('yii','Repeat Password must be same as Password.'), 'on' => 'update'],
            ['firstname', 'compare', 'compareAttribute' => 'firstname', 'skipOnEmpty' => true, 'message' => Yii::t('yii','Repeat Password must be same as Password.'), 'on' => 'update'],
            //[['firstname', 'lastname', 'email'], 'filter', 'filter' => 'trim'],
            [['firstname', 'email'], 'filter', 'filter' => 'trim'],
            ['firstname', 'match', 'pattern' => '/^[a-z ]+$/i', 'message' => Yii::t('yii', 'First Name only accepts alphabets and space.')],
            ['lastname', 'match', 'pattern' => '/^[a-z ]+$/i', 'message' => Yii::t('yii', 'Last Name only accepts alphabets and space.')],
            //[['firstname', 'lastname'], 'string', 'max' => 60, 'on' => 'update'], 
            // [['day_price'], 'default', 'value'=> 1],
            //[['day_price'],'number', 'numberPattern' => '/^\s*[-+]?[0-9]*[.,]?[0-9]+([eE][-+]?[0-9]+)?\s*$/', 'min'=>1],
           /* [['pet_weight_limit'], 'required',  'when' => function($model) {
				return $model->pet_parent_type_sr == 1;
            },'whenClient' => "function (attribute, value) { return $('#updatesitter-pet_parent_type_sr').val() == '1'; }"],*/                    
           // [['firstname'], 'string', 'max' => 60, 'on' => 'update'],                        
            [['zip_code','country','region','city', 'services', 'day_price', 'pitch','pet_parent_type_sr'], 'string', 'max' => 200, 'on' => 'update'],                        
            //['firstname', 'match', 'pattern' => '/^[a-z ]+$/i', 'message' => 'First Name only accepts alphabets and space.'],
            ['email', 'email'],
            [['email'], 'unique', 'on' => 'update'],
            ['paypal_email', 'email'],
            ['password', 'string', 'min' => 8, 'max' => 20],
            ['repeat_password', 'compare', 'compareAttribute' => 'password'],
           	['address', 'validateAddress'],
			['firstname,lastname,address,zip_code,country,region,city','validatefields','skipOnEmpty' => false, 'skipOnError' => true],
            ['services', 'validateServices','skipOnEmpty' => false],
         
           
            // [['chkusrInterests', 'chkusrLanguage'], 'checkCount'],
        ];
    }
	public function validatefields() 
	{
		if($_POST['UpdateSitter']['firstname'] == '')
		{	
			$this->addError('firstname', Yii::t('yii', 'Please enter firstname.'));	
		}
		if($_POST['UpdateSitter']['lastname'] == '')
		{	
			$this->addError('lastname', Yii::t('yii', 'Please enter lastname.'));	
		}
		if($_POST['UpdateSitter']['address'] == '')
		{	
			$this->addError('address', Yii::t('yii', 'Please enter address.'));	
		}
		if($_POST['UpdateSitter']['zip_code'] == '')
		{	
			$this->addError('zip_code', Yii::t('yii', 'Please enter zipcode.'));	
		}
		if($_POST['UpdateSitter']['country'] == '')
		{	
			$this->addError('country', Yii::t('yii', 'Please select country.'));	
		}
		if($_POST['UpdateSitter']['region'] == '')
		{	
			$this->addError('region', Yii::t('yii', 'Please select region.'));	
		}
		if($_POST['UpdateSitter']['city'] == '')
		{	
			$this->addError('city', Yii::t('yii', 'Please select city.'));	
		}
		if($_POST['UpdateSitter']['pitch'] == '')
		{	
			$this->addError('pitch', Yii::t('yii', 'Please enter your pitch.'));	
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
            'dob'			 => 'Date of Birth',
            'registration_type' => 'Hear About',
            'zip_code'		=> 'Zip/Postal Code',
            'pet_weight_limit' => 'Pet Weight',
            'day_price'			=> 'Price',
            'pet_parent_type_sr' => 'Pet Types',
            'upload_images' 	=> 'Additional images',
			'certification' 	=> 'Do you have any Certifications or Specialties to share? (Optional - Select up to 3) ',
			
        ];
    }

	/**
	* @inheritdoc
	*/
    public static function findIdentity($id) {
        return static::findOne(['id' => $id]);
    }
	public static function update_profile_id($id) {
		
       	#####= transaction begin
		$connection = \Yii::$app->db;
		$transaction = $connection->beginTransaction();
		
			$user = Users::findOne(['id' => $id]);
			$user->profile_completed= 1;
			$user->save();
			$transaction->commit();
    }
	
	
	/*public function validateServices() {
	//$this->addError('services', Yii::t('yii', 'Services name can not be same for selected pet.'));
		//if(count(array_unique($this->services)) < count($this->services)) {
			//$this->addError('services', Yii::t('yii', 'Services name should not be same.'));
		//}
						
		$petparentselected = $this->pet_parent_type_sr;
		$petweightlimit = $_POST['UpdateSitter']['pet_weight_limit'];
		$petservice = $this->services;
		$petweightselected = $petweightlimit;
		if(!empty($petweightlimit)){
		$petweightselected = array_values($petweightlimit);
		}
		$servicekeys = array();
		foreach($petparentselected as $k=>$v){	
	if(isset($v) && empty($petweightselected[$k]) && $v=='1' ){
					$this->addError('pet_weight_limit', Yii::t('yii', 'Select sizes of pet based on pet type.'));				
				}	
		
		$servicekeys = array_keys($petparentselected, $v);
		//if same pet selected for more than one time
			if(count($servicekeys) > 1){ 
				$servicevalue = $petservice[$servicekeys[0]]; 				
				$serviceselectedkeys = array_keys($petservice, $servicevalue);				
					if(count($serviceselectedkeys)>1){
					$this->addError('services', Yii::t('yii', 'Please select a different service for similar pet types.'));
					}	
			}
		}
		
	}*/

	public function validateServices() {
	//$this->addError('services', Yii::t('yii', 'Services name can not be same for selected pet.'));
		/*if(count(array_unique($this->services)) < count($this->services)) {
			$this->addError('services', Yii::t('yii', 'Services name should not be same.'));
		}*/
		
		$petparentselected = $_POST['UpdateSitter']['pet_parent_type_sr'];		
		$petweightlimit = $_POST['UpdateSitter']['pet_weight_limit'];
		$day_price = $_POST['UpdateSitter']['day_price'];
			
		$petservice = $_POST['UpdateSitter']['services'];
		$petweightselected = $petweightlimit;
		if(!empty($petweightlimit)){
		$petweightselected = array_values($petweightlimit);
		}
		
		$servicekeys = array();
		if(!empty($petparentselected)){
		foreach($petparentselected as $k=>$v){		
		if(isset($v) && empty($petweightselected[$k]) && $v=='1' ){
					$this->addError('pet_weight_limit', Yii::t('yii', 'Select size of pet based on pet type.'));				
				}								
		$servicekeys = array_keys($petparentselected, $v);
		
		//if same pet selected for more than one time
			if(count($servicekeys) > 1){
			$cnt = 0;
			$skeysvalue = array();
			
			foreach($servicekeys as $l=>$m){			
			$skeysvalue[] =  $petservice[$m];			
			} 
			
			
			if(count(array_unique($skeysvalue)) < count($skeysvalue)) {
			$this->addError('services', Yii::t('yii', 'Please select a different service for similar pet types.'));
			}
			
			//echo "<pre>"; print_r($skeys); echo "---";			
				//$servicevalue = $petservice[$servicekeys[0]]; 				
				//$serviceselectedkeys = array_keys($petservice, $servicevalue);								
					/*if(count($serviceselectedkeys)>1){	
					$this->addError('services', Yii::t('yii', 'Please select a different service for similar pet types.'));
					}*/				
			}
			
		}
		foreach($day_price as $k=>$v){
			if(empty($day_price[$k])){
				$this->addError('day_price', Yii::t('yii', 'Please enter price per day.'));
			}
		}
		
		}
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
	* updateRecords.
	*
	* @return User|null the saved model or null if saving fails
	*/
    public function updateRecords($id,$mediaArr,$service_data) 
	{
        /* if (!$this->validate()) {
            return null;
        } */

		//die("under developing phage...");
		$userMapLocation		= Yii::$app->commonmethod->getLatitudeAndLongitude($this->address);
		####= usps verification
		$addressArr 			= array("address" => $this->address, "city" => $this->city, "state" => $this->region);
		//~ $uspsResponse			= Yii::$app->commonmethod->uspsAddressVerify($addressArr);
		//~ $verifiedAddress		= (isset($uspsResponse['address']) ? $uspsResponse['address'] : '');
		$verifiedAddress		= (isset($this->address) ? $this->address : '');		
        #####= transaction begin
		$connection = \Yii::$app->db;
		$transaction = $connection->beginTransaction();
		

		try {

			$this->scenario 		= 'update';			
			$user 					= Users::findOne(['id' => $id]);
			
			$user->firstname 		= $this->firstname;
			$user->lastname 		= $this->lastname;
			$user->email 			= $this->email;
			$user->paypal_email = $this->paypal_email;					
			$user->country 			= (isset($_POST['UpdateSitter']['country']) ? $_POST['UpdateSitter']['country'] : 0);
			$user->region 			= $_POST['UpdateSitter']['region'];
			$user->city 			= $_POST['UpdateSitter']['city'];
			$user->address 			= $_POST['UpdateSitter']['address'];
			$user->zip_code 		= $this->zip_code;
			$user->latitude 		= (isset($userMapLocation['lat']) ? $userMapLocation['lat'] : '');
			$user->longitude 		= (isset($userMapLocation['lng']) ? $userMapLocation['lng'] : '');
			//$user->profile_completed= 0;	
			$user->certification  = $this->certification;
			
			
			if($this->profile_image != '') {
				$user->profile_image 	= $this->profile_image;
			}

			if($this->password != '') {
				$user->setPassword($this->password);
			}
				$user->save();
				$transaction->commit();
				$transaction = $connection->beginTransaction();
				if($user->save()) 
				{
					$user_id 			= $id;
					/*if(!empty($this->pet_weight_limit) ){
					$weightlimit = implode(',',$this->pet_weight_limit);
					}else{
					$weightlimit = null;
					}*/
					
					$response_counter	= 0;
					$service_provider	=	Yii::$app->commonmethod->getServiceproviderCnt($user_id);
					
					if(isset($service_provider['id']) && !empty($service_provider['id'])) {
						$sp_detail 							= Serviceprovider::findOne(['user_id' => $user_id]);
						$sp_detail->day_price 			    = array_sum($this->day_price);
						$sp_detail->pitch 					= $this->pitch;
						//$sp_detail->pet_weight_limit 		= $weightlimit;
						//$sp_detail->pet_type_id 			= $this->pet_parent_type_sr;
						if($sp_detail->save()) {
							$response_counter=1;
						}
					} else {
						$sp_detail 							= new Serviceprovider();
						$sp_detail->user_id 			    = $user_id;
						$sp_detail->day_price 			    = array_sum($this->day_price);
						$sp_detail->pitch 					= $this->pitch;
						//$sp_detail->pet_weight_limit 		= $weightlimit;
						if($sp_detail->save()) {
							$response_counter=1;
						}							
					}

					##### 1=Document, 2=Image, 3=home images
					$response_counter=0;
					if(isset($mediaArr['upload_documents']) && !empty($mediaArr['upload_documents']))
					{
						
						$connection->createCommand('DELETE FROM users_documents WHERE user_id="'.$id.'"AND document_type=1')
        ->execute();
						
						$Identity_documents	=	Yii::$app->commonmethod->getUserDocuments($user_id,ID_DOCUMENTS);				
						if(isset($Identity_documents) && !empty($Identity_documents)) {
							for($i = 0; $i < count($mediaArr['upload_documents']); $i++) {
								$data1 					= new Documents();
								//$data1 					= Documents::findOne(['id' => $Identity_documents[$i]['id']]); 
								$data1->name 			= (isset($mediaArr['upload_documents'][$i]) ? $mediaArr['upload_documents'][$i] : '');
								$data1->document_type	= ID_DOCUMENTS;
								$data1->user_id		 	= $user_id;
								$data1->delete_status	= (isset($mediaArr['upload_documents'][$i]) ? '0' : REMOVE);
								if($data1->save()) {
									$response_counter=1;
								}
							}
						} else {
							//echo LIMIT_USER_DOCUMENTS;
							for($i = 0; $i < LIMIT_USER_DOCUMENTS; $i++) {
								$data1 					= new Documents();
								$data1->name 			= (isset($mediaArr['upload_documents'][$i]) ? $mediaArr['upload_documents'][$i] : '');
								$data1->document_type	= ID_DOCUMENTS;
								$data1->user_id		 	= $user_id;
								$data1->delete_status	= (isset($mediaArr['upload_documents'][$i]) ? '0' : REMOVE);
								//print_r($data1);
								if($data1->save()) {
									$response_counter=1;
								}
							}
						}
					}
					
					//update services record
					if($service_data==1) {
					$uscount=0;
					
					if(isset($this->services) && !empty($this->services)) {
				
						$UserServices =	Yii::$app->commonmethod->getUserServices($user_id);
						
						
						if(isset($UserServices) && !empty($UserServices)) {
						
							for($i = 0; $i < count($this->services); $i++) {
								//$data1 					= new UserServices();
									
									$weightlimit = 	$this->calculatepetweight($this->pet_weight_limit, $i);	
									
									$pet_service_id = $this->pet_service_id[$i];
									//$data1 	= UserServices::findOne(['service_id' => $this->services[$i],'user_id' => $user_id]);																		
									if($pet_service_id>0){
									$data1 	= UserServices::findOne(['id' => $pet_service_id]);								
									$data1->price = (isset($this->day_price[$i]) ? $this->day_price[$i] : '');
									$data1->pet_weight_limit = $weightlimit;
									$data1->service_id	= (isset($this->services[$i]) ? $this->services[$i] : 0);	
									$data1->pet_type_id = $this->pet_parent_type_sr[$i];	
									
										if($data1->save()) {
											$uscount=1;
											
										}
									}else{
										
									$userservice_data 				= new UserServices();
									$userservice_data->service_id	= (isset($this->services[$i]) ? $this->services[$i] : 0);
									$userservice_data->user_id		= $user_id;
									$userservice_data->price		= (isset($this->day_price[$i]) && $this->day_price[$i]!='' ? $this->day_price[$i] : 10);
									$userservice_data->status		= ACTIVE;
									$userservice_data->pet_weight_limit = $weightlimit;
									$userservice_data->pet_type_id = $this->pet_parent_type_sr[$i];
									
										if($userservice_data->save()){
										$uscount=1;
										
										}
										
									}
							}
						} 
						else 
						{
							
									
							if(isset($this->services) && !empty($this->services)) 
							{
								$uscount	= count($this->services);
								
								for($si = 0; $si < $uscount; $si++) 
								{
									$weightlimit = 	$this->calculatepetweight($this->pet_weight_limit, $si);
					
									$userservice_data 				= new UserServices();
									
									$userservice_data->service_id	= (isset($this->services[$si]) ? $this->services[$si] : 0);
									$userservice_data->user_id		= $user_id;
									
									$userservice_data->price		= (isset($this->day_price[$si]) && $this->day_price[$si]!='' ? $this->day_price[$si] : 10);
									
					
									$userservice_data->status		= ACTIVE;
									
									$userservice_data->pet_weight_limit = $weightlimit;
									
									$userservice_data->pet_type_id = $this->pet_parent_type_sr[$si];
									
									 
									$userservice_data->save();
								}
							}
						}
					}
					}
					if(isset($mediaArr['upload_images']) && !empty($mediaArr['upload_images'])) {
						$user_images	=	Yii::$app->commonmethod->getUserDocuments($user_id,USER_IMAGES);
						if(isset($user_images) && !empty($user_images)) {
						
							for($i=0; $i < count($mediaArr['upload_images']	); $i++) {
								$data2 					= new Documents();
								//$data2 					= Documents::findOne(['id' => $user_images[$i]['id']]); 
								$data2->name 			= (isset($mediaArr['upload_images'][$i]) ? $mediaArr['upload_images'][$i] : '');
								$data2->document_type	= USER_IMAGES;
								$data2->user_id 		= $user_id;
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
					
					if(isset($mediaArr['upload_home_images']) && !empty($mediaArr['upload_home_images'])) 
					{
						
						$user_home_images	=	Yii::$app->commonmethod->getUserDocuments($user_id,HOME_IMAGES);
						
						if(isset($user_home_images) && !empty($user_home_images)) 
						{
							
							for($i=0; $i < count($mediaArr['upload_home_images']); $i++) {
								$data3 					= new Documents();
								$data3->name 			= (isset($mediaArr['upload_home_images'][$i]) ? $mediaArr['upload_home_images'][$i] : '');
								$data3->document_type	= HOME_IMAGES;
								$data3->delete_status	= (isset($mediaArr['upload_home_images'][$i]) ? '0' : REMOVE);
								$data3->user_id 		= $user_id;
								if($data3->name!='') {
								if($data3->save()) 
								{
									$response_counter=1;
								}
								}
							}
						} else {
							for($i=0; $i < LIMIT_HOME_IMAGES; $i++) {
								$data3 					= new Documents();
								$data3->name 			= (isset($mediaArr['upload_home_images'][$i]) ? $mediaArr['upload_home_images'][$i] : '');
								$data3->document_type	= HOME_IMAGES;
								$data3->delete_status	= (isset($mediaArr['upload_home_images'][$i]) ? '0' : REMOVE);
								$data3->user_id 		= $user_id;
								if($data3->name!='') {
								if($data3->save()) {
									$response_counter=1;
								}
								}
							}
						}
					}
					
					$transaction->commit();
					
					return $user_id;
				}
				$transaction->commit();
		} catch(\Exception $e) {
			$transaction->commit();
			//throw $e;
		}
		return null;
	}
	
	public function calculatepetweight($petweight, $i){
	$weightlimit = null;
	$petweightselected = array_values($petweight);												
			if(!empty($petweightselected[$i]) ){
				if(count($petweightselected[$i]) > 1){
				$weightlimit = implode(',',$petweightselected[$i]);
				}else{
				$weightlimit = $petweightselected[$i][0];
				}
			}			
			return $weightlimit;	
	}
	
	/**
	* becomeASitter.
	*
	* @return User|null the saved model or null if saving fails
	*/
    public function becomeASitter($id,$mediaArr) {
        if (!$this->validate()) {
            return null;
        }

		$attributes = Yii::$app->user->identity->getattributes();
		$userMapLocation		= Yii::$app->commonmethod->getLatitudeAndLongitude($this->address);
		$user_type = (isset($attributes['user_type']) && $attributes['user_type'] > 0 ? $attributes['user_type'] : 0);
		if($user_type == OWNER) {
			$ut	= OWNER_SITTER;
		} else if($user_type == BORROWER) {
			$ut	= BORROWER_SITTER;
		} else if($user_type == OWNER_BORROWER) {
			$ut	= ALL_PROFILES;
		} else {
			$ut = $user_type;
		}
		

		$verifiedAddress		= (isset($this->address) ? $this->address : '');	
        #####= transaction begin
		$connection = \Yii::$app->db;
		$transaction = $connection->beginTransaction();
		try {
			$this->scenario 		= 'update';
			$user 					= Users::findOne(['id' => $id]);
			$user->firstname 		= $this->firstname;
			$user->lastname 		= $this->lastname;
			$user->email 			= $this->email;
			$user->paypal_email= $this->paypal_email;
			$user->address 			= $verifiedAddress;
			$user->user_type      		= $ut;
			$user->country 			= (isset($this->country) ? $this->country : 0);
			$user->region 			= $this->region;
			$user->city 			= $this->city;				
			$user->zip_code 		= $this->zip_code;
			$user->latitude 		= (isset($userMapLocation['lat']) ? $userMapLocation['lat'] : '');
			$user->longitude 		= (isset($userMapLocation['lng']) ? $userMapLocation['lng'] : '');		
			$user->profile_completed	= 1 ;	
			if($this->profile_image != '') {
				$user->profile_image 	= $this->profile_image;
			}
			
			if($this->password != '') {
				$user->setPassword($this->password);
			}


				if($user->save()) {  
					$user_id 			= $id;
					$response_counter	= 0;
					$service_provider	=	Yii::$app->commonmethod->getServiceproviderCnt($user_id);
					
					
			  	/*if(!empty($this->pet_weight_limit)){
				$weightlimit = implode(',',$this->pet_weight_limit);
				}else{
				$weightlimit = null;
				}*/
					
					if(isset($service_provider['id']) && !empty($service_provider['id'])) {
						
						$sp_detail 							= Serviceprovider::findOne(['user_id' => $user_id]); 
						$sp_detail->day_price 			    = array_sum($this->day_price);
						$sp_detail->pitch 					= $this->pitch;
						//$sp_detail->pet_weight_limit 		= $weightlimit;
						//$sp_detail->pet_type_id 			= $this->pet_parent_type_sr;
						if($sp_detail->save()) {
							$response_counter=1;
						}
					} else { 

						$sp_detail 							= new Serviceprovider();
						$sp_detail->user_id 			    = $user_id;
						$sp_detail->day_price 			    = array_sum($this->day_price);
						$sp_detail->pitch 					= $this->pitch;
						//$sp_detail->pet_weight_limit 		= $weightlimit;
						//$sp_detail->pet_type_id 			= $this->pet_parent_type_sr;
						if($sp_detail->save()) {
						
							$response_counter=1;
						}							
					}

					##### 1=Document, 2=Image, 3=home images
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
					
					//update services record
					$uscount=0;
					if(isset($this->services) && !empty($this->services)) {
					
						$UserServices =	Yii::$app->commonmethod->getUserServices($user_id);					
						if(isset($UserServices) && !empty($UserServices)) {
						
							for($i = 0; $i < count($this->services); $i++) {
								//$data1 					= new UserServices();
								$data1 					= UserServices::findOne(['service_id' => $this->services[$i],'user_id' => $user_id]);
									if(!empty($data1)){
									$data1->price 			= (isset($this->day_price[$i]) ? $this->day_price[$i] : '');							
										if($data1->save()) {
											$uscount=1;
										}
									}else{
									$userservice_data 				= new UserServices();
									$userservice_data->service_id	= (isset($this->services[$i]) ? $this->services[$i] : 0);
									$userservice_data->user_id		= $user_id;
									$userservice_data->price		= (isset($this->day_price[$i]) ? $this->day_price[$i] : 10);
									$userservice_data->status		= ACTIVE;
									$userservice_data->save();
									}
							}
						} else {

							
							if(isset($this->services) && !empty($this->services)) {
							$uscount	= count($this->services);
							
							for($si = 0; $si< $uscount; $si++) {
							
								if(!empty($this->pet_weight_limit) ){
									
										$weightlimit = 	$this->calculatepetweight($this->pet_weight_limit, $si);
								}else{
									$weightlimit = null;
								}
								
								
								
								$userservice_data = new UserServices();
								
								$userservice_data->service_id	= (isset($this->services[$si]) ? $this->services[$si] : 0);
								$userservice_data->user_id		= $user_id;
								$userservice_data->price		= (isset($this->day_price[$si]) ? $this->day_price[$si] : 10);
								$userservice_data->status		= ACTIVE;
								$userservice_data->pet_weight_limit = $weightlimit;
								$userservice_data->pet_type_id = $this->pet_parent_type_sr[$si];
								$userservice_data->save();


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
					
					if(isset($mediaArr['upload_home_images']) && !empty($mediaArr['upload_home_images'])) {
						$user_home_images	=	Yii::$app->commonmethod->getUserDocuments($user_id,HOME_IMAGES);
						if(isset($user_home_images) && !empty($user_home_images)) {
							for($i=0; $i < LIMIT_USER_IMAGES; $i++) {
								$data3 					= Documents::findOne(['id' => $user_home_images[$i]['id']]); 
								$data3->name 			= (isset($mediaArr['upload_home_images'][$i]) ? $mediaArr['upload_home_images'][$i] : '');
								$data3->delete_status	= (isset($mediaArr['upload_home_images'][$i]) ? '0' : REMOVE);
								if($data3->save()) {
									$response_counter=1;
								}
							}
						} else {
							
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
							
						}
					}
					
					$transaction->commit();
					return $user_id;
				}
		} catch(\Exception $e) {
			$transaction->rollback();
			//throw $e;
		}
		return null;
	}	
	
	public function getuserdocuments($id,$doctype){
	$data = array();
		$connection = \Yii::$app->db;
		$model = $connection->createCommand('SELECT * FROM users_documents where document_type = '.$doctype.' AND user_id='.$id );
		$data = $model->queryAll();
		return $data;
	}
}
