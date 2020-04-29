<?php
namespace frontend\controllers; 
use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Controller;
use yii\helpers\Url;
use frontend\models\Search;
use yii\data\Pagination;
use yii\db\Query;

class SearchController extends Controller {
    private $limit = 10;
    private $distance = 50;
	public function beforeAction($action) { 
		return true;
	}
	
	/**
	* @ Function Name		: actionSearchGuide
	* @ Function Params		: NA 
	* @ Function Purpose 	: default index function that will be called to display search result 
	* @ Function Returns	: render view
	*/
	public function getzipcodeparameters($zip){
	$url = "https://maps.googleapis.com/maps/api/geocode/json?key=".GOOGLE_MAP_KEY."&address=".urlencode($zip)."&sensor=false";
    $result_string = file_get_contents($url);
    $result = json_decode($result_string, true);
    return $result['results'][0]['geometry']['location'];
	
	}
	
	public function sortresultby($sort_by){
	
		if($sort_by == 1) {
				$orderBy	=	"rating ASC";
			} else if($sort_by == 2) {
				$orderBy	=	"rating DESC";
			} else if($sort_by == 3) {
				//$orderBy	=	"sp.day_price ASC";
				$orderBy	=	"services.price ASC";
			} else if($sort_by == 4) {
				//$orderBy	=	"sp.day_price DESC";
				$orderBy	=	"services.price DESC";
			} else {
				 $orderBy	=	"user.id DESC";
			}			
			return $orderBy;	
	}
	
    public function actionPetsitter() {
		
		$session 				= Yii::$app->session;
		$session->remove('booking_details');
		$logged_user 			= $session->get('loggedinusertype');
		
        $model 			= new Search();
        $queryParams 	= Yii::$app->request->queryParams;  
        $limit 			= $this->limit;
        $searchfor 		= array();
        $searchString 	= '';
		$sort_by 		= 1;
		$orderBy	=	"rating ASC";
		
		if(Yii::$app->request->isPost){		
			$post = Yii::$app->request->post();
			if(!empty($post)) {				
				$searchfor = $post['Search'];
								
			}
		}
		
		$logginUserId 	= Yii::$app->user->getId();
		if($logginUserId=='' || $logginUserId==0){
			$session->set('searchrequest',1);
			$session->set('searchrequestdata',$searchfor);
			return $this->redirect(['site/signin']);
		}
		
		$zipaddress 	= $this->getzipcodeparameters(Yii::$app->user->identity->zip_code);
		if(($logged_user != OWNER) && $searchfor['searchcategory']=='lovingpet') {
		Yii::$app->session->setFlash('item', Yii::t('yii','Please switch/subscribe to your owner profile to search.'));
			return $this->redirect(['site/home']);
		}

if(($logged_user == OWNER && Yii::$app->user->identity->unsubscribe_owner == 2) && $searchfor['searchcategory']=='lovingpet') {
		Yii::$app->session->setFlash('item', Yii::t('yii','Your owner profile is deactivated. Please subscribe to your owner profile to search.'));
			return $this->redirect(['site/home']);
		}

		
		if(($logged_user != OWNER || Yii::$app->user->identity->unsubscribe_owner == 2) && $searchfor['searchcategory']=='borrow') {
		Yii::$app->session->setFlash('item', Yii::t('yii','Please switch/subscribe to your borrower profile to search.'));
			return $this->redirect(['site/home']);
		}
		
		
		
		$chksearchrequest = $session->get('searchrequest');
		if($chksearchrequest==1){
			$getsercharray = $session->get('searchrequestdata');
			$searchfor = $getsercharray;
			$session->remove('searchrequestdata');
			$session->remove('searchrequest');
		}
		
		if(!empty($searchfor)) { 
		if($searchfor['searchcategory']=='borrow'){
				Yii::$app->session->setFlash('item', Yii::t('yii','Please switch/subscribe to your borrower profile to search.'));
				return $this->redirect(['site/home']);
				}
			$session->set('searchrequestdata',$searchfor);
			$model->selected_pal 	= $searchfor['selected_pal'];
			$model->pet_weight   	= (isset($searchfor['pet_weight']) ? $searchfor['pet_weight'] : '');
			$model->zip          	= $searchfor['zip'];
			$model->service_type 	= $searchfor['service_type'];
			$model->no_of_pals   	= (isset($searchfor['no_of_pals']) ? $searchfor['no_of_pals'] : 1);
			$model->date_from    	= $searchfor['date_from'];
			$model->date_to      	= $searchfor['date_to'];

			####= search pets on the behaif of selected values
            $searchByPalType 		= (isset($searchfor['selected_pal']) ? $searchfor['selected_pal'] : '');
            $searchByPetWeight 		= (isset($searchfor['pet_weight']) ? $searchfor['pet_weight'] : '');
            $searchByZipCode 		= (isset($searchfor['zip']) ? $searchfor['zip'] : '');
            $searchByServices 		= (isset($searchfor['service_type']) ? $searchfor['service_type'] : '');
            $searchByDateFrom 		= (isset($searchfor['date_from']) ? $searchfor['date_from'] : '');
            $searchByDateTo 		= (isset($searchfor['date_to']) ? $searchfor['date_to'] : '');

			$session 				= Yii::$app->session;
			$logged_user 			= $session->get('loggedinusertype');
			
			##### calculate distance
			$fLat 					= (isset($searchfor['u_latitude']) ? $searchfor['u_latitude'] : '');
			$fLon 					= (isset($searchfor['u_longitude']) ? $searchfor['u_longitude'] : '');
			if($fLat == "" || $fLon == "") {
				$attributes 			= Yii::$app->user->identity->getattributes();
				$fLat 					= (isset($attributes['latitude']) ? $attributes['latitude'] : '');
				$fLon 					= (isset($attributes['longitude']) ? $attributes['longitude'] : '');
			}
			
			$distance 				= $this->distance;
			$userIDs 				= '';
			$user_IDs 				= '';
			if(!empty($searchByZipCode)) {		   
			   $zipaddress = $this->getzipcodeparameters($searchByZipCode);	
			   $ziplat =   $zipaddress['lat']; 
			   $ziplng =   $zipaddress['lng'];
			   
			   $query ="SELECT *,ACOS( SIN( RADIANS( `latitude` ) ) * SIN( RADIANS( $ziplat ) ) + COS( RADIANS( `latitude` ) )* COS( RADIANS( $ziplat )) * COS( RADIANS( `longitude` ) - RADIANS( $ziplng )) ) * 3964 AS `distance` FROM `user` WHERE ACOS( SIN( RADIANS( `latitude` ) ) * SIN( RADIANS( $ziplat ) ) + COS( RADIANS( `latitude` ) ) * COS( RADIANS( $ziplat )) * COS( RADIANS( `longitude` ) - RADIANS( $ziplng )) ) * 3964 < $distance ORDER BY `distance`";
			   //$query = 
				$zipCodesArr = Yii::$app->db->createCommand($query)->queryAll();
				foreach ($zipCodesArr as $row)
				{ 
				   $userIDs = $userIDs.(isset($row['id']) && $row['id'] > 0 ? $row['id']."," : '');
				}
				if(isset($userIDs) && count($userIDs)>0)
				{
					$user_IDs = trim($userIDs, ",");
				}
			}elseif($fLat != "" && $fLon != "") {
				$query ="SELECT *,ACOS( SIN( RADIANS( `latitude` ) ) * SIN( RADIANS( $fLat ) ) + COS( RADIANS( `latitude` ) )* COS( RADIANS( $fLat )) * COS( RADIANS( `longitude` ) - RADIANS( $fLon )) ) * 3964 AS `distance` FROM `user` WHERE ACOS( SIN( RADIANS( `latitude` ) ) * SIN( RADIANS( $fLat ) ) + COS( RADIANS( `latitude` ) ) * COS( RADIANS( $fLat )) * COS( RADIANS( `longitude` ) - RADIANS( $fLon )) ) * 3964 < $distance ORDER BY `distance`";
				$zipCodesArr = Yii::$app->db->createCommand($query)->queryAll();
				
				
				foreach ($zipCodesArr as $row)
				{ 
				   $userIDs = $userIDs.(isset($row['id']) && $row['id'] > 0 ? $row['id']."," : '');
				}
				if(isset($userIDs) && count($userIDs)>0)
				{
					$user_IDs = trim($userIDs, ",");
				}
			}
			
			#### default condition
			$WHERE		=	"";
			$WHERE 	=	" user.status = '".ACTIVE."' AND (user.user_type = '".SITTER."' OR user.user_type = '".OWNER_SITTER."' OR user.user_type = '".ALL_PROFILES."' OR user.user_type = '".BORROWER_SITTER."') AND user.unsubscribe_sitter='1' AND user.verified_by_admin='1'";

			if(isset($user_IDs) && trim($user_IDs) != "") {
				$WHERE .= ' AND user.id IN('.$user_IDs.')';
			}elseif(!empty($searchByZipCode)){
			 	$WHERE .= " AND (user.zip_code LIKE '%$searchByZipCode%' OR user.address LIKE '%$searchByZipCode%')";
			    $zipaddress = $this->getzipcodeparameters($searchByZipCode);
			}

            if(!empty($searchByPalType)) {
				if($searchByPalType == 7) {
					$WHERE .= ' AND (sp.pet_type_id = 1 OR sp.pet_type_id = 2 OR sp.pet_type_id = 7)';
				} else if($searchByPalType == 8) {
					$WHERE .= ' AND (sp.pet_type_id = 2 OR sp.pet_type_id = 3 OR sp.pet_type_id = 8)';
				} else if($searchByPalType == 9) {
					$WHERE .= ' AND (sp.pet_type_id = 1 OR sp.pet_type_id = 3 OR sp.pet_type_id = 10)';
				} else if($searchByPalType == 10) {
					//$WHERE .= ' AND sp.pet_type_id = '.$searchByPalType;
					$WHERE .= ' AND (sp.pet_type_id = 1 OR sp.pet_type_id = 3 OR sp.pet_type_id = 10 OR sp.pet_type_id = 7 OR sp.pet_type_id = 9 OR sp.pet_type_id = 8 OR sp.pet_type_id = 2)';
				} else if($searchByPalType == 1) {
				$WHERE .= ' AND (sp.pet_type_id = 1 OR sp.pet_type_id = 7 OR sp.pet_type_id = 9 OR sp.pet_type_id = 10)';
			
				} else if($searchByPalType == 2) {
				$WHERE .= ' AND (sp.pet_type_id = 2 OR sp.pet_type_id = 7 OR sp.pet_type_id = 8 OR sp.pet_type_id = 10)';
			
				}else if($searchByPalType == 3) {
				$WHERE .= ' AND (sp.pet_type_id = 3 OR sp.pet_type_id = 9 OR sp.pet_type_id = 8 OR sp.pet_type_id = 10)';
			
				}else {	
					$WHERE .= ' AND sp.pet_type_id = '.$searchByPalType;
				}
			}else{
				$WHERE .= ' AND (sp.pet_type_id = 1 OR sp.pet_type_id = 3 OR sp.pet_type_id = 10 OR sp.pet_type_id = 7 OR sp.pet_type_id = 9 OR sp.pet_type_id = 8 OR sp.pet_type_id = 2)';
			}

            if(!empty($searchByPetWeight)) {			
				$WHERE .= ' AND sp.pet_weight_limit  LIKE "%'.$searchByPetWeight.'%"';
			}

			/*if(!empty($searchByZipCode)) {
			   $WHERE .= " AND (user.zip_code LIKE '%$searchByZipCode%' OR user.address LIKE '%$searchByZipCode%')";
			   $zipaddress = $this->getzipcodeparameters($searchByZipCode);
			}*/

			if(!empty($searchByServices)) {
			   $WHERE .= ' AND services.service_id= '.$searchByServices;
			}

			if(!empty($searchByDateFrom) && !empty($searchByDateTo)) {
				$searchArr = array();
				while (strtotime($searchByDateFrom) <= strtotime($searchByDateTo)) {
					$searchArr[] = $searchByDateFrom;
					$searchByDateFrom = date(DATEPICKER_FORMAT_PHP, strtotime("+1 day", strtotime($searchByDateFrom)));
				}
				$WHERE .= " AND una.dates NOT REGEXP(REPLACE('".implode(",",$searchArr)."',',','|'))";
			} else if(!empty($searchByDateFrom)) {
				$searchByDateTo = $searchByDateFrom;
				$searchArr = array();
				while (strtotime($searchByDateFrom) <= strtotime($searchByDateTo)) {
					$searchArr[] = $searchByDateFrom;
					$searchByDateFrom = date(DATEPICKER_FORMAT_PHP, strtotime("+1 day", strtotime($searchByDateFrom)));
				}
				$WHERE .= " AND una.dates NOT REGEXP(REPLACE('".implode(",",$searchArr)."',',','|'))";
			}

			$WHERE .= " AND user.id != ".$logginUserId;

			$sort_by	=	(isset($post['filter']['sort_by']) ? $post['filter']['sort_by'] : 0);
			if($sort_by == 1) {
				$orderBy	=	"rating ASC";
			} else if($sort_by == 2) {
				$orderBy	=	"rating DESC";
			} else if($sort_by == 3) {
				//$orderBy	=	"sp.day_price ASC";
				$orderBy	=	"services.price ASC";
			} else if($sort_by == 4) {
				//$orderBy	=	"sp.day_price DESC";
				$orderBy	=	"services.price DESC";
			} else {
				$orderBy	=	"user.id DESC";
			}

			//COUNT(b.id) as completed_services
			$query = new Query;
            $query->select('user.id,user.firstname,user.lastname,user.profile_image,user.zip_code,user.country,user.address,user.region,user.city,user.latitude,user.longitude,sp.pet_weight_limit,sp.day_price,services.service_id,user.description as user_description,sp.pitch,user.user_average_rating as rating,cns.name as u_country_name,ct.name as u_city_name,services.price')
                    ->from('user')->distinct()
                    ->join('LEFT JOIN', 'user_services services', 'user.id = services.user_id')                     
                    ->join('LEFT JOIN', 'service_provider_details as sp', 'user.id = sp.user_id')  
                    //->join('LEFT JOIN', 'booking as b', 'user.id = b.pet_sitter_id AND b.completed = 1')
                    ->join('LEFT JOIN', 'user_unavailability as una', 'user.id = una.user_id')
                    ->join('LEFT JOIN', 'countries as cns', 'user.country = cns.id')
                    ->join('LEFT JOIN', 'cities as ct', 'user.city = ct.id')
                    ->where($WHERE); 
					
			Yii::$app->session->set('searchWhere', $WHERE);
		
		}

		#####= Pagination Query
		if (!Yii::$app->request->isPost && isset($queryParams['page']) && isset($queryParams['per-page'])) {
            $WHERE = Yii::$app->session->get('searchWhere');           
			$query = new Query;
            $query->select('user.id,user.firstname,user.lastname,user.profile_image,user.address,user.zip_code,user.country,user.region,user.city,user.latitude,user.longitude,user.description as user_description,sp.pet_weight_limit,sp.day_price,sp.pitch,services.service_id,user.user_average_rating as rating,cns.name as u_country_name,ct.name as u_city_name,services.price')
                    ->from('user')->distinct()
                    ->join('LEFT JOIN', 'user_services services', 'user.id = services.user_id')                                       
                    ->join('LEFT JOIN', 'service_provider_details as sp', 'user.id = sp.user_id')                
                    //->join('LEFT JOIN', 'booking as b', 'user.id = b.pet_sitter_id AND b.completed = 1')
                    ->join('LEFT JOIN', 'user_unavailability as una', 'user.id = una.user_id')
                    ->join('LEFT JOIN', 'countries as cns', 'user.country = cns.id')
                    ->join('LEFT JOIN', 'cities as ct', 'user.city = ct.id')
                    ->where($WHERE);
        } elseif(!Yii::$app->request->isPost && empty($queryParams)) {
			//$WHERE = " user.status = '1' AND (user.user_type = '".SITTER."' OR user.user_type = '".OWNER_SITTER."') AND user.id != ".$logginUserId;
			$WHERE = Yii::$app->session->get('searchWhere');
			$query = new Query;
            $query->select('user.id,user.firstname,user.lastname,user.profile_image,user.address,user.zip_code,user.country,user.region,user.city,user.latitude,user.longitude,user.description as user_description,sp.pet_weight_limit,sp.day_price,sp.pitch,services.service_id,user.user_average_rating as rating,cns.name as u_country_name,ct.name as u_city_name,services.price')
                    ->from('user')->distinct()
                    ->join('LEFT JOIN', 'user_services services', 'user.id = services.user_id')                                       
                    ->join('LEFT JOIN', 'service_provider_details as sp', 'user.id = sp.user_id')
                    //->join('LEFT JOIN', 'booking as b', 'user.id = b.pet_sitter_id AND b.completed = 1')
                    ->join('LEFT JOIN', 'user_unavailability as una', 'user.id = una.user_id')
                    ->join('LEFT JOIN', 'countries as cns', 'user.country = cns.id')
                    ->join('LEFT JOIN', 'cities as ct', 'user.city = ct.id')                    
                    ->where($WHERE); 
                    $sort_by =  Yii::$app->session->get('sortby');
					$orderBy = $this->sortresultby($sort_by);  
					Yii::$app->session->set('searchWhere', $WHERE);
        }

        if(isset($query)) {
			$countQuery = clone $query;			
			$pages 		= new Pagination(['totalCount' => $countQuery->count()]);			
			$query->orderBy($orderBy)
				  ->offset($pages->offset)
				  ->limit($limit);
			$searchResult = $query->createCommand()->queryAll();
			if(isset($searchResult[0]['id']) && empty($searchResult[0]['id'])) {
				$searchResult = array();
			} //echo $searchResult = $query->createCommand()->getRawSql(); exit();
			$pages->setPageSize($limit);
			
			$dataArray  = array(          
				'searchResult' => $searchResult,
				'pages' => $pages,
				'model' => $model,
				'sort_by' => $sort_by,
				'zipaddress' => $zipaddress,
			);
		}
		
        return $this->render('petsitter', $dataArray);
    }
    
	/**
	* @ Function Name		: actionFilter
	* @ Function Params		: NA 
	* @ Function Purpose 	: filter search result 
	* @ Function Returns	: render view
	*/
    public function actionFilter() {
		$session 				= Yii::$app->session;
		$logged_user 			= $session->get('loggedinusertype');
		if($logged_user != OWNER || Yii::$app->user->identity->unsubscribe_owner == 2) {
		 Yii::$app->session->setFlash('item', Yii::t('yii','Please switch/subscribe to your owner or borrower profile to search.'));
		 return $this->redirect(['site/home']);
		}
		$zipaddress 	= $this->getzipcodeparameters(Yii::$app->user->identity->zip_code);
        $queryParams 	= Yii::$app->request->queryParams;
        $limit 			= $this->limit;
        $post 			= Yii::$app->request->post();
        $sort_by		= 1;
        $orderBy	=	"rating ASC";
        $logginUserId 	= Yii::$app->user->getId();
        if(isset($post['filter']) && !empty($post['filter'])) {
			$searchFilter 	= $post['filter'];
			$session->set('searchrequestdata',$searchFilter);
			####= filter by
            $searchByAmount 		= (isset($searchFilter['amount']) ? $searchFilter['amount'] : ''); 
            $searchByPalType 		= (isset($searchFilter['selected_pal']) ? $searchFilter['selected_pal'] : '');
            $searchByPetWeight 		= (isset($searchFilter['pet_weight']) ? $searchFilter['pet_weight'] : '');
            $searchByZipCode 		= (isset($searchFilter['zip']) ? $searchFilter['zip'] : '');
            $searchByServices 		= (isset($searchFilter['service_type']) ? $searchFilter['service_type'] : '');
            $searchByNumOfPets 		= (isset($searchFilter['no_of_pals']) ? $searchFilter['no_of_pals'] : 0);
            $searchByDateFrom 		= (isset($searchFilter['date_from']) ? $searchFilter['date_from'] : '');
            $searchByDateTo 		= (isset($searchFilter['date_to']) ? $searchFilter['date_to'] : '');

			##### calculate distance
			 $fLat 					= (isset($searchFilter['u_latitude']) ? $searchFilter['u_latitude'] : '');
			$fLon 					= (isset($searchFilter['u_longitude']) ? $searchFilter['u_longitude'] : '');
			
			if($fLat == "" || $fLon == "") {
				$attributes 			= Yii::$app->user->identity->getattributes();
				 $fLat 					= (isset($attributes['latitude']) ? $attributes['latitude'] : '');
				 $fLon 					= (isset($attributes['longitude']) ? $attributes['longitude'] : '');
			}
			$distance 				= $this->distance;
			$userIDs 				= '';
			$user_IDs 				= '';
			
			if(!empty($searchByZipCode)) {	   
			   $zipaddress = $this->getzipcodeparameters($searchByZipCode);	
			   $ziplat =   $zipaddress['lat']; 
			   $ziplng =   $zipaddress['lng'];
			   
			   $query ="SELECT *,ACOS( SIN( RADIANS( `latitude` ) ) * SIN( RADIANS( $ziplat ) ) + COS( RADIANS( `latitude` ) )* COS( RADIANS( $ziplat )) * COS( RADIANS( `longitude` ) - RADIANS( $ziplng )) ) * 3964 AS `distance` FROM `user` WHERE ACOS( SIN( RADIANS( `latitude` ) ) * SIN( RADIANS( $ziplat ) ) + COS( RADIANS( `latitude` ) ) * COS( RADIANS( $ziplat )) * COS( RADIANS( `longitude` ) - RADIANS( $ziplng )) ) * 3964 < $distance ORDER BY `distance`";
			   //$query = 
				$zipCodesArr = Yii::$app->db->createCommand($query)->queryAll();
				foreach ($zipCodesArr as $row)
				{ 
				   $userIDs = $userIDs.(isset($row['id']) && $row['id'] > 0 ? $row['id']."," : '');
				}
				if(isset($userIDs) && count($userIDs)>0)
				{
					$user_IDs = trim($userIDs, ",");
				}
			}elseif($fLat != "" && $fLon != "") {
				$query ="SELECT *,ACOS( SIN( RADIANS( `latitude` ) ) * SIN( RADIANS( $fLat ) ) + COS( RADIANS( `latitude` ) )* COS( RADIANS( $fLat )) * COS( RADIANS( `longitude` ) - RADIANS( $fLon )) ) * 3964 AS `distance` FROM `user` WHERE ACOS( SIN( RADIANS( `latitude` ) ) * SIN( RADIANS( $fLat ) ) + COS( RADIANS( `latitude` ) ) * COS( RADIANS( $fLat )) * COS( RADIANS( `longitude` ) - RADIANS( $fLon )) ) * 3964 < $distance ORDER BY `distance`";
				$zipCodesArr = Yii::$app->db->createCommand($query)->queryAll();
				foreach ($zipCodesArr as $row)
				{ 
				   $userIDs = $userIDs.(isset($row['id']) && $row['id'] > 0 ? $row['id']."," : '');
				}
				if(isset($userIDs) && count($userIDs)>0)
				{
					$user_IDs = trim($userIDs, ",");
				}
			}
			#### default condition					
			$WHERE		=	"";
			$WHERE 		.=	" user.status = '1' AND (user.user_type = '".SITTER."' OR user.user_type = '".OWNER_SITTER."' OR user.user_type = '".ALL_PROFILES."' OR user.user_type = '".BORROWER_SITTER."' ) AND user.unsubscribe_sitter='1' AND user.verified_by_admin='1'";

			if(isset($user_IDs) && trim($user_IDs) != "") {
				$WHERE .= ' AND user.id IN('.$user_IDs.')';
			}elseif(!empty($searchByZipCode)){
			 	$WHERE .= " AND (user.zip_code LIKE '%$searchByZipCode%' OR user.address LIKE '%$searchByZipCode%')";
			    $zipaddress = $this->getzipcodeparameters($searchByZipCode);
			}

            if(!empty($searchByAmount)) {
				$amtVal		=	explode(" - ",str_replace("$","",$searchByAmount));				
				$startPrice = (isset($amtVal[0]) ? $amtVal[0] : 0);
				$endPrice 	= (isset($amtVal[1]) ? $amtVal[1] : 0);
				$WHERE .= ' AND (services.price >= '.$startPrice.' AND services.price <= '.$endPrice.')';
			}

            if(!empty($searchByPalType)) {
				if($searchByPalType == 7) {
					$WHERE .= ' AND (sp.pet_type_id = 1 OR sp.pet_type_id = 2 OR sp.pet_type_id = 7)';
				} else if($searchByPalType == 8) {
					$WHERE .= ' AND (sp.pet_type_id = 2 OR sp.pet_type_id = 3 OR sp.pet_type_id = 8)';
				} else if($searchByPalType == 9) {
					$WHERE .= ' AND (sp.pet_type_id = 1 OR sp.pet_type_id = 3 OR sp.pet_type_id = 10)';
				} else if($searchByPalType == 10) {
					//$WHERE .= ' AND sp.pet_type_id = '.$searchByPalType;
					$WHERE .= ' AND (sp.pet_type_id = 1 OR sp.pet_type_id = 3 OR sp.pet_type_id = 10 OR sp.pet_type_id = 7 OR sp.pet_type_id = 9 OR sp.pet_type_id = 8 OR sp.pet_type_id = 2)';
				}else if($searchByPalType == 1) {
				$WHERE .= ' AND (sp.pet_type_id = 1 OR sp.pet_type_id = 7 OR sp.pet_type_id = 9 OR sp.pet_type_id = 10)';
			
				} else if($searchByPalType == 2) {
				$WHERE .= ' AND (sp.pet_type_id = 2 OR sp.pet_type_id = 7 OR sp.pet_type_id = 8 OR sp.pet_type_id = 10)';
			
				}else if($searchByPalType == 3) {
				$WHERE .= ' AND (sp.pet_type_id = 3 OR sp.pet_type_id = 9 OR sp.pet_type_id = 8 OR sp.pet_type_id = 10)';
			
				} else {	
					$WHERE .= ' AND sp.pet_type_id = '.$searchByPalType;
				}
			}else{
				$WHERE .= ' AND (sp.pet_type_id = 1 OR sp.pet_type_id = 3 OR sp.pet_type_id = 10 OR sp.pet_type_id = 7 OR sp.pet_type_id = 9 OR sp.pet_type_id = 8 OR sp.pet_type_id = 2)';
			}

            if(!empty($searchByPetWeight)) {			
				$WHERE .= ' AND sp.pet_weight_limit LIKE "%'.$searchByPetWeight.'%"';
			}
			
			if(!empty($searchByServices)) {
			   $WHERE .= ' AND services.service_id= '.$searchByServices;
			}
			
			/*if(!empty($searchByNumOfPets)) {
			   $WHERE .= ' AND user.number_of_pets >= '.$searchByNumOfPets;
			}*/

			if(!empty($searchByDateFrom) && !empty($searchByDateTo)) {
				//date_default_timezone_set('UTC');
				$searchArr = array();
				while (strtotime($searchByDateFrom) <= strtotime($searchByDateTo)) {
					$searchArr[] = $searchByDateFrom;
					$searchByDateFrom = date(DATEPICKER_FORMAT_PHP, strtotime("+1 day", strtotime($searchByDateFrom)));
				}
				$WHERE .= " AND una.dates NOT REGEXP(REPLACE('".implode(",",$searchArr)."',',','|'))";
			} else if(!empty($searchByDateFrom)) {
				$searchByDateTo = $searchByDateFrom;
				$searchArr = array();
				while (strtotime($searchByDateFrom) <= strtotime($searchByDateTo)) {
					$searchArr[] = $searchByDateFrom;
					$searchByDateFrom = date(DATEPICKER_FORMAT_PHP, strtotime("+1 day", strtotime($searchByDateFrom)));
				}
				$WHERE .= " AND una.dates NOT REGEXP(REPLACE('".implode(",",$searchArr)."',',','|'))";
			}

			$WHERE .= " AND user.id != ".$logginUserId;	
			
			$sort_by	=	(isset($post['filter']['sort_by']) ? $post['filter']['sort_by'] : 0);
			Yii::$app->session->set('sortby', $sort_by);
			if($sort_by == 1) {
				$orderBy	=	"rating ASC";
			} else if($sort_by == 2) {
				$orderBy	=	"rating DESC";
			} else if($sort_by == 3) {
				//$orderBy	=	"sp.day_price ASC";
				$orderBy	=	"services.price ASC";
			} else if($sort_by == 4) {
				//$orderBy	=	"sp.day_price DESC";
				$orderBy	=	"services.price DESC";
			} else {
				$orderBy	=	"user.id DESC";
			}
			
			$query = new Query;
            $query->select('user.id,user.firstname,user.lastname,user.profile_image,user.address,user.zip_code,user.country,user.region,user.city,user.latitude,user.longitude,sp.pet_weight_limit,sp.day_price,services.service_id,user.description as user_description,sp.pitch,user.user_average_rating as rating,cns.name as u_country_name,ct.name as u_city_name,services.price')
                    ->from('user')->distinct()
                    ->join('LEFT JOIN', 'user_services services', 'user.id = services.user_id')                     
                    ->join('LEFT JOIN', 'service_provider_details as sp', 'user.id = sp.user_id') 
                    //->join('LEFT JOIN', 'booking as b', 'user.id = b.pet_sitter_id AND b.completed = 1')
                    ->join('LEFT JOIN', 'user_unavailability as una', 'user.id = una.user_id')
                    ->join('LEFT JOIN', 'countries as cns', 'user.country = cns.id')
                    ->join('LEFT JOIN', 'cities as ct', 'user.city = ct.id')                                               
                    ->where($WHERE);         
			Yii::$app->session->set('searchWhere', $WHERE);
        } elseif (!Yii::$app->request->isPost && isset($queryParams['page']) && isset($queryParams['per-page'])) {
            $WHERE = Yii::$app->session->get('searchWhere');           
			$query = new Query;
            $query->select('user.id,user.firstname,user.lastname,user.profile_image,user.address,user.zip_code,user.country,user.region,user.city,user.latitude,user.longitude,sp.pet_weight_limit,sp.day_price,services.service_id,user.description as user_description,sp.pitch,user.user_average_rating as rating,cns.name as u_country_name,ct.name as u_city_name,services.price')
                    ->from('user')->distinct()
                    ->join('LEFT JOIN', 'user_services services', 'user.id = services.user_id')                                       
                    ->join('LEFT JOIN', 'service_provider_details as sp', 'user.id = sp.user_id') 
                    //->join('LEFT JOIN', 'booking as b', 'user.id = b.pet_sitter_id AND b.completed = 1')
                    ->join('LEFT JOIN', 'user_unavailability as una', 'user.id = una.user_id')
                    ->join('LEFT JOIN', 'countries as cns', 'user.country = cns.id')
                    ->join('LEFT JOIN', 'cities as ct', 'user.city = ct.id')                             
                    ->where($WHERE);
        } else {
			$WHERE = " user.status = '1' AND (user.user_type = '".SITTER."' OR user.user_type = '".OWNER_SITTER."' OR user.user_type = '".ALL_PROFILES."' OR user.user_type = '".BORROWER_SITTER."') AND user.unsubscribe_sitter='1' AND user.verified_by_admin='1' AND user.id != ".$logginUserId;
			$query = new Query;
				$query->select('user.id,user.firstname,user.lastname,user.profile_image,user.zip_code,user.country,user.address,user.region,user.city,user.latitude,user.longitude,sp.pet_weight_limit,sp.day_price,services.service_id,user.description as user_description,sp.pitch,user.user_average_rating as rating,cns.name as u_country_name,ct.name as u_city_name,services.price')
						->from('user')->distinct()
						->join('LEFT JOIN', 'user_services services', 'user.id = services.user_id')                                       
						->join('LEFT JOIN', 'service_provider_details as sp', 'user.id = sp.user_id')    
						//->join('LEFT JOIN', 'booking as b', 'user.id = b.pet_sitter_id AND b.completed = 1')
						->join('LEFT JOIN', 'user_unavailability as una', 'user.id = una.user_id')
						->join('LEFT JOIN', 'countries as cns', 'user.country = cns.id')
						->join('LEFT JOIN', 'cities as ct', 'user.city = ct.id')						 
						->where($WHERE);   
			Yii::$app->session->set('searchWhere', $WHERE);
        }

		$countQuery = clone $query;	
		$pages 		= new Pagination(['totalCount' => $countQuery->count()]);			
		$query->orderBy($orderBy)
			  ->offset($pages->offset)
		      ->limit($limit);    
		$searchResult = $query->createCommand()->queryAll();
		if(isset($searchResult[0]['id']) && empty($searchResult[0]['id'])) {
			$searchResult = array();
		}
		//echo $searchResult = $query->createCommand()->getRawSql(); exit();
		$pages->setPageSize($limit);		
		$this->layout = false;
		
		return $this->render('searchlisting',[
			'searchResult' => $searchResult,
			'pages' => $pages,
			'sort_by' => $sort_by,
			'zipaddress' => $zipaddress
		]);
    }
    
    
	/**
	* @ Function Name		: actionSearchGuide
	* @ Function Params		: NA 
	* @ Function Purpose 	: default index function that will be called to display search result 
	* @ Function Returns	: render view
	*/
    public function actionPetrenter() {
    
		$session 				= Yii::$app->session;
		$session->remove('booking_details');
		$logged_user 			= $session->get('loggedinusertype');
		if($logged_user != RENTER || Yii::$app->user->identity->unsubscribe_renter == 2) {
		Yii::$app->session->setFlash('item', Yii::t('yii','Please switch/subscribe to your owner or borrower profile to search.'));
			return $this->redirect(['site/home']);
		}
		
		/*
		renter will be able to search only after verified by admin
		*/
		$verificationstatus = Yii::$app->user->identity->verified_by_admin;
		if($verificationstatus != 1){
		Yii::$app->session->setFlash('item', Yii::t('yii','your profile is still pending for verification by admin. please contact us for more details.'));
			return $this->redirect(['site/home']);
		}
		
        $model 			= new Search();
        $queryParams 	= Yii::$app->request->queryParams;  
        $limit 			= $this->limit;
        $searchfor 		= array();
        $searchString 	= '';
		$sort_by		= 1;
		$orderBy	=	"rating ASC";
		$logginUserId 	= Yii::$app->user->getId();
		if(Yii::$app->request->isPost){		
			$post = Yii::$app->request->post();
			if(!empty($post)) {				
				$searchfor = $post['Search'];						
			}
		}
		$zipaddress 	= $this->getzipcodeparameters(Yii::$app->user->identity->zip_code);
		//check if search data is set in session
		$chksearchrequest = $session->get('searchrequest');
		if($chksearchrequest==1){
			$getsercharray = $session->get('searchrequestdata');
			$searchfor = $getsercharray;
			$session->remove('searchrequestdata');
			$session->remove('searchrequest');
		}
		
		if(!empty($searchfor)) {
		if($searchfor['searchcategory']=='lovingpet'){
				Yii::$app->session->setFlash('item', Yii::t('yii','Please switch/subscribe to your owner profile to search.'));
				return $this->redirect(['site/home']);
				}

if(Yii::$app->user->identity->unsubscribe_renter == 2){
Yii::$app->session->setFlash('item', Yii::t('yii','Your borrower profile is deactivated. Please subscribe to your borrower profile to search.'));
			return $this->redirect(['site/home']);
}

			$session->set('searchrequestdata',$searchfor);
			$model->selected_pal 	= $searchfor['selected_pal'];
			$model->pet_weight   	= $searchfor['pet_weight'];
			$model->zip          	= $searchfor['zip'];
			//$model->service_type 	= $searchfor['service_type'];
			$model->no_of_pals   	= (isset($searchfor['no_of_pals']) ? $searchfor['no_of_pals'] : 0);
			$model->date_from    	= $searchfor['date_from'];
			$model->date_to      	= $searchfor['date_to'];

			####= search pets on the behaif of selected values
            $searchByPalType 		= (isset($searchfor['selected_pal']) ? $searchfor['selected_pal'] : '');
            $searchByPetWeight 		= (isset($searchfor['pet_weight']) ? $searchfor['pet_weight'] : '');
            $searchByZipCode 		= (isset($searchfor['zip']) ? $searchfor['zip'] : '');
          // $searchByServices 		= (isset($searchfor['service_type']) ? $searchfor['service_type'] : '');
            $searchByDateFrom 		= (isset($searchfor['date_from']) ? $searchfor['date_from'] : '');
            $searchByDateTo 		= (isset($searchfor['date_to']) ? $searchfor['date_to'] : '');

			$session 				= Yii::$app->session;
			$logged_user 			= $session->get('loggedinusertype');
			
			##### calculate distance
			$fLat 					= (isset($searchfor['u_latitude']) ? $searchfor['u_latitude'] : '');
			$fLon 					= (isset($searchfor['u_longitude']) ? $searchfor['u_longitude'] : '');
			if($fLat == "" || $fLon == "") {
				$attributes 			= Yii::$app->user->identity->getattributes();
				$fLat 					= (isset($attributes['latitude']) ? $attributes['latitude'] : '');
				$fLon 					= (isset($attributes['longitude']) ? $attributes['longitude'] : '');
			}
			$distance 				= $this->distance;
			$userIDs 				= '';
			$user_IDs 				= '';
			if(!empty($searchByZipCode)) {			   
			   $zipaddress = $this->getzipcodeparameters($searchByZipCode);	
			   $ziplat =   $zipaddress['lat']; 
			   $ziplng =   $zipaddress['lng'];
			   
			   $query ="SELECT *,ACOS( SIN( RADIANS( `latitude` ) ) * SIN( RADIANS( $ziplat ) ) + COS( RADIANS( `latitude` ) )* COS( RADIANS( $ziplat )) * COS( RADIANS( `longitude` ) - RADIANS( $ziplng )) ) * 3964 AS `distance` FROM `user` WHERE ACOS( SIN( RADIANS( `latitude` ) ) * SIN( RADIANS( $ziplat ) ) + COS( RADIANS( `latitude` ) ) * COS( RADIANS( $ziplat )) * COS( RADIANS( `longitude` ) - RADIANS( $ziplng )) ) * 3964 < $distance ORDER BY `distance`";
			   //$query = 
				$zipCodesArr = Yii::$app->db->createCommand($query)->queryAll();
				foreach ($zipCodesArr as $row)
				{ 
				   $userIDs = $userIDs.(isset($row['id']) && $row['id'] > 0 ? $row['id']."," : '');
				}
				if(isset($userIDs) && count($userIDs)>0)
				{
					$user_IDs = trim($userIDs, ",");
				}
			}elseif($fLat != "" && $fLon != "") {
				$query ="SELECT *,ACOS( SIN( RADIANS( `latitude` ) ) * SIN( RADIANS( $fLat ) ) + COS( RADIANS( `latitude` ) )* COS( RADIANS( $fLat )) * COS( RADIANS( `longitude` ) - RADIANS( $fLon )) ) * 3964 AS `distance` FROM `user` WHERE ACOS( SIN( RADIANS( `latitude` ) ) * SIN( RADIANS( $fLat ) ) + COS( RADIANS( `latitude` ) ) * COS( RADIANS( $fLat )) * COS( RADIANS( `longitude` ) - RADIANS( $fLon )) ) * 3964 < $distance ORDER BY `distance`";
				$zipCodesArr = Yii::$app->db->createCommand($query)->queryAll();
				foreach ($zipCodesArr as $row)
				{ 
				   $userIDs = $userIDs.(isset($row['id']) && $row['id'] > 0 ? $row['id']."," : '');
				}
				if(isset($userIDs) && count($userIDs)>0)
				{
					$user_IDs = trim($userIDs, ",");
				}
			}
			
			#### default condition
			$WHERE		=	"";
			$WHERE 	=	" user.status = '".ACTIVE."' AND pi.interested_in_renting='1' AND (user.user_type = '".OWNER."' OR user.user_type = '".OWNER_SITTER."'  OR user.user_type = '".ALL_PROFILES."'  OR user.user_type = '".OWNER_BORROWER."' ) AND user.unsubscribe_owner='1' AND user.profile_completed_owner='1'";

			if(isset($user_IDs) && trim($user_IDs) != "") {
				$WHERE .= ' AND user.id IN('.$user_IDs.')';
			}elseif(!empty($searchByZipCode)){
			 	$WHERE .= " AND (user.zip_code LIKE '%$searchByZipCode%' OR user.address LIKE '%$searchByZipCode%')";
			    $zipaddress = $this->getzipcodeparameters($searchByZipCode);
			}

            if(!empty($searchByPalType)) {
				if($searchByPalType == 7) {
					$WHERE .= ' AND (pi.pet_parent_id = 1 OR pi.pet_parent_id = 2 OR pi.pet_parent_id = 7)';
				} else if($searchByPalType == 8) {
					$WHERE .= ' AND (pi.pet_parent_id = 2 OR pi.pet_parent_id = 3 OR pi.pet_parent_id = 8)';
				} else if($searchByPalType == 9) {
					$WHERE .= ' AND (pi.pet_parent_id = 1 OR pi.pet_parent_id = 3 OR pi.pet_parent_id = 10)';
				} else if($searchByPalType == 10) {
					//$WHERE .= ' AND sp.pet_type_id = '.$searchByPalType;
				} else {	
					$WHERE .= ' AND pi.pet_parent_id = '.$searchByPalType;
				}		
			}else{
				$WHERE .= ' AND (pi.pet_parent_id = 1 OR pi.pet_parent_id = 3 OR pi.pet_parent_id = 2)';
			}

            //~ if(!empty($searchByPetWeight)) {			
				//~ $WHERE .= ' AND sp.pet_weight_limit <= '.$searchByPetWeight;
			//~ }

			/*if(!empty($searchByZipCode)) {
			   $WHERE .= " AND (user.zip_code LIKE '%$searchByZipCode%' OR user.address LIKE '%$searchByZipCode%')";
			   $zipaddress = $this->getzipcodeparameters($searchByZipCode);
			}*/

			//~ if(!empty($searchByServices)) {
			   //~ $WHERE .= ' AND services.service_id= '.$searchByServices;
			//~ }

			//~ if(!empty($searchByDateFrom) && !empty($searchByDateTo)) {
				//~ $searchArr = array();
				//~ while (strtotime($searchByDateFrom) <= strtotime($searchByDateTo)) {
					//~ $searchArr[] = $searchByDateFrom;
					//~ $searchByDateFrom = date(DATEPICKER_FORMAT_PHP, strtotime("+1 day", strtotime($searchByDateFrom)));
				//~ }
				//~ $WHERE .= " AND una.dates NOT REGEXP(REPLACE('".implode(",",$searchArr)."',',','|'))";
			//~ } else if(!empty($searchByDateFrom)) {
				//~ $searchByDateTo = $searchByDateFrom;
				//~ $searchArr = array();
				//~ while (strtotime($searchByDateFrom) <= strtotime($searchByDateTo)) {
					//~ $searchArr[] = $searchByDateFrom;
					//~ $searchByDateFrom = date(DATEPICKER_FORMAT_PHP, strtotime("+1 day", strtotime($searchByDateFrom)));
				//~ }
				//~ $WHERE .= " AND una.dates NOT REGEXP(REPLACE('".implode(",",$searchArr)."',',','|'))";
			//~ }

			$logginUserId 	= Yii::$app->user->getId();
			$WHERE .= ' AND user.id != '.$logginUserId;			

			$sort_by	=	(isset($post['filter']['sort_by']) ? $post['filter']['sort_by'] : 0);
			if($sort_by == 1) {
				$orderBy	=	"rating ASC";
			} else if($sort_by == 2) {
				$orderBy	=	"rating DESC";
			} else if($sort_by == 3) {
				$orderBy	=	"pi.per_day_price ASC";
			} else if($sort_by == 4) {
				$orderBy	=	"pi.per_day_price DESC";
			} else {
				$orderBy	=	"user.id DESC";
			}

			//COUNT(b.id) as completed_services
			$query = new Query;
            $query->select('user.id,user.firstname,user.lastname,user.profile_image,user.zip_code,user.country,user.address,user.region,user.city,user.latitude,user.longitude,pi.per_day_price,user.description as user_description,user.user_average_rating as rating,user.total_rating as t_rating,cns.name as u_country_name,ct.name as u_city_name')
                    ->from('user')->distinct()
                   // ->join('LEFT JOIN', 'user_services services', 'user.id = services.user_id')                     
                    ->join('LEFT JOIN', 'pet_information as pi', 'user.id = pi.user_id')  
                    // ->join('LEFT JOIN', 'booking as b', 'user.id = b.pet_sitter_id AND b.completed = 1')
                    // ->join('LEFT JOIN', 'user_unavailability as una', 'user.id = una.user_id')
					->join('LEFT JOIN', 'countries as cns', 'user.country = cns.id')
					->join('LEFT JOIN', 'cities as ct', 'user.city = ct.id')                    
                    ->where($WHERE);  
			Yii::$app->session->set('searchWhere', $WHERE);
		}

		#####= Pagination Query
		if (!Yii::$app->request->isPost && isset($queryParams['page']) && isset($queryParams['per-page'])) {
            $WHERE = Yii::$app->session->get('searchWhere');           
			$query = new Query;
            $query->select('user.id,user.firstname,user.lastname,user.profile_image,user.zip_code,user.country,user.address,user.region,user.city,user.latitude,user.longitude,pi.per_day_price,user.description as user_description,user.user_average_rating as rating,user.total_rating as t_rating,cns.name as u_country_name,ct.name as u_city_name')
                    ->from('user')->distinct()
                   // ->join('LEFT JOIN', 'user_services services', 'user.id = services.user_id')                                       
                    ->join('LEFT JOIN', 'pet_information as pi', 'user.id = pi.user_id')               
                   // ->join('LEFT JOIN', 'booking as b', 'user.id = b.pet_sitter_id AND b.completed = 1')
                    //->join('LEFT JOIN', 'user_unavailability as una', 'user.id = una.user_id')     
					->join('LEFT JOIN', 'countries as cns', 'user.country = cns.id')
					->join('LEFT JOIN', 'cities as ct', 'user.city = ct.id')                    
                    ->where($WHERE);
        } elseif(!Yii::$app->request->isPost && empty($queryParams)) {
			$WHERE = " user.status = '1' AND pi.interested_in_renting='1' AND  (user.user_type = '".OWNER."' OR user.user_type = '".OWNER_SITTER."' OR user.user_type = '".ALL_PROFILES."'  OR user.user_type = '".OWNER_BORROWER."'  ) AND user.unsubscribe_owner='1' AND user.profile_completed_owner='1' AND user.id != ".$logginUserId;
			$query = new Query;
            $query->select('user.id,user.firstname,user.lastname,user.profile_image,user.zip_code,user.country,user.address,user.region,user.city,user.latitude,user.longitude,pi.per_day_price,user.description as user_description,user.user_average_rating as rating,user.total_rating as t_rating,cns.name as u_country_name,ct.name as u_city_name')
                    ->from('user')->distinct()
                   // ->join('LEFT JOIN', 'user_services services', 'user.id = services.user_id')                                       
                     ->join('LEFT JOIN', 'pet_information as pi', 'user.id = pi.user_id')  
                 //   ->join('LEFT JOIN', 'booking as b', 'user.id = b.pet_sitter_id AND b.completed = 1')
                    //->join('LEFT JOIN', 'user_unavailability as una', 'user.id = una.user_id')
					->join('LEFT JOIN', 'countries as cns', 'user.country = cns.id')
					->join('LEFT JOIN', 'cities as ct', 'user.city = ct.id')                    
                    ->where($WHERE);   
					Yii::$app->session->set('searchWhere', $WHERE);
        }

        if(isset($query)) {
			$countQuery = clone $query;			
			$pages 		= new Pagination(['totalCount' => $countQuery->count()]);			
			$query->orderBy("user.id DESC")
				  ->offset($pages->offset)
				  ->limit($limit);
			$searchResult = $query->createCommand()->queryAll();
			if(isset($searchResult[0]['id']) && empty($searchResult[0]['id'])) {
				$searchResult = array();
			}
			$pages->setPageSize($limit);	
			$dataArray  = array(          
				'searchResult' => $searchResult,
				'pages' => $pages,
				'model' => $model,
				'sort_by' => $sort_by,
				'zipaddress' => $zipaddress
			);
		}
        return $this->render('petrenter', $dataArray);
    }
    
	/**
	* @ Function Name		: actionFilter
	* @ Function Params		: NA 
	* @ Function Purpose 	: filter search result 
	* @ Function Returns	: render view
	*/
    public function actionFilterRenter() {
		$session 				= Yii::$app->session;
		$logged_user 			= $session->get('loggedinusertype');
		$zipaddress 	= $this->getzipcodeparameters(Yii::$app->user->identity->zip_code);
		if($logged_user != RENTER || Yii::$app->user->identity->unsubscribe_renter == 2) {
		Yii::$app->session->setFlash('item', Yii::t('yii','Please switch/subscribe to your owner or borrower profile to search.'));
		 return $this->redirect(['site/home']);
		}
		
		/*
		renter will be able to search only after verified by admin
		*/
		$verificationstatus = Yii::$app->user->identity->verified_by_admin;
		if($verificationstatus != 1){
		Yii::$app->session->setFlash('item', Yii::t('yii','your profile is still pending for verification by admin. please contact us for more details.'));
			return $this->redirect(['site/home']);
		}
		
        $queryParams 	= Yii::$app->request->queryParams;
        $limit 			= $this->limit;
        $post 			= Yii::$app->request->post();
        $sort_by		= 1;
        $orderBy	=	"rating ASC";
        $logginUserId 	= Yii::$app->user->getId();
        if(isset($post['filter']) && !empty($post['filter'])) {
			$searchFilter 	= $post['filter'];
			$session->set('searchrequestdata',$searchFilter);
			####= filter by
           // $searchByAmount 		= (isset($searchFilter['amount']) ? $searchFilter['amount'] : ''); 
            $searchByPalType 		= (isset($searchFilter['selected_pal']) ? $searchFilter['selected_pal'] : '');
            $searchByPetWeight 		= (isset($searchFilter['pet_weight']) ? $searchFilter['pet_weight'] : '');
            $searchByZipCode 		= (isset($searchFilter['zip']) ? $searchFilter['zip'] : '');
           // $searchByServices 		= (isset($searchFilter['service_type']) ? $searchFilter['service_type'] : '');
            $searchByNumOfPets 		= (isset($searchFilter['no_of_pals']) ? $searchFilter['no_of_pals'] : '');
            $searchByDateFrom 		= (isset($searchFilter['date_from']) ? $searchFilter['date_from'] : '');
            $searchByDateTo 		= (isset($searchFilter['date_to']) ? $searchFilter['date_to'] : '');
            
			##### calculate distance
			$fLat 					= (isset($searchFilter['u_latitude']) ? $searchFilter['u_latitude'] : '');
			$fLon 					= (isset($searchFilter['u_longitude']) ? $searchFilter['u_longitude'] : '');
			if($fLat == "" || $fLon == "") {
				$attributes 			= Yii::$app->user->identity->getattributes();
				$fLat 					= (isset($attributes['latitude']) ? $attributes['latitude'] : '');
				$fLon 					= (isset($attributes['longitude']) ? $attributes['longitude'] : '');
			}
			$distance 				= $this->distance;
			$userIDs 				= '';
			$user_IDs 				= '';
			if(!empty($searchByZipCode)) {			   
			   $zipaddress = $this->getzipcodeparameters($searchByZipCode);	
			   $ziplat =   $zipaddress['lat']; 
			   $ziplng =   $zipaddress['lng'];
			   
			   $query ="SELECT *,ACOS( SIN( RADIANS( `latitude` ) ) * SIN( RADIANS( $ziplat ) ) + COS( RADIANS( `latitude` ) )* COS( RADIANS( $ziplat )) * COS( RADIANS( `longitude` ) - RADIANS( $ziplng )) ) * 3964 AS `distance` FROM `user` WHERE ACOS( SIN( RADIANS( `latitude` ) ) * SIN( RADIANS( $ziplat ) ) + COS( RADIANS( `latitude` ) ) * COS( RADIANS( $ziplat )) * COS( RADIANS( `longitude` ) - RADIANS( $ziplng )) ) * 3964 < $distance ORDER BY `distance`";
			   //$query = 
				$zipCodesArr = Yii::$app->db->createCommand($query)->queryAll();
				foreach ($zipCodesArr as $row)
				{ 
				   $userIDs = $userIDs.(isset($row['id']) && $row['id'] > 0 ? $row['id']."," : '');
				}
				if(isset($userIDs) && count($userIDs)>0)
				{
					$user_IDs = trim($userIDs, ",");
				}
			}elseif($fLat != "" && $fLon != "") {
				$query ="SELECT *,ACOS( SIN( RADIANS( `latitude` ) ) * SIN( RADIANS( $fLat ) ) + COS( RADIANS( `latitude` ) )* COS( RADIANS( $fLat )) * COS( RADIANS( `longitude` ) - RADIANS( $fLon )) ) * 3964 AS `distance` FROM `user` WHERE ACOS( SIN( RADIANS( `latitude` ) ) * SIN( RADIANS( $fLat ) ) + COS( RADIANS( `latitude` ) ) * COS( RADIANS( $fLat )) * COS( RADIANS( `longitude` ) - RADIANS( $fLon )) ) * 3964 < $distance ORDER BY `distance`";
				$zipCodesArr = Yii::$app->db->createCommand($query)->queryAll();
				foreach ($zipCodesArr as $row)
				{ 
				   $userIDs = $userIDs.(isset($row['id']) && $row['id'] > 0 ? $row['id']."," : '');
				}
				if(isset($userIDs) && count($userIDs)>0)
				{
					$user_IDs = trim($userIDs, ",");
				}
			}
			
			#### default condition					
			$WHERE		=	"";
			$WHERE 		=	" user.status = '1' AND pi.interested_in_renting='1' AND  (user.user_type = '".OWNER."' OR user.user_type = '".OWNER_SITTER."' OR user.user_type = '".ALL_PROFILES."'  OR user.user_type = '".OWNER_BORROWER."'  ) AND user.unsubscribe_owner='1' AND user.profile_completed_owner='1'";

			if(isset($user_IDs) && trim($user_IDs) != "") {
				$WHERE .= ' AND user.id IN('.$user_IDs.')';
			}elseif(!empty($searchByZipCode)){
			 	$WHERE .= " AND (user.zip_code LIKE '%$searchByZipCode%' OR user.address LIKE '%$searchByZipCode%')";
			    $zipaddress = $this->getzipcodeparameters($searchByZipCode);
			}

            if(!empty($searchByPalType)) {
				if($searchByPalType == 7) {
					$WHERE .= ' AND (pi.pet_parent_id = 1 OR pi.pet_parent_id = 2 OR pi.pet_parent_id = 7)';
				} else if($searchByPalType == 8) {
					$WHERE .= ' AND (pi.pet_parent_id = 2 OR pi.pet_parent_id = 3 OR pi.pet_parent_id = 8)';
				} else if($searchByPalType == 9) {
					$WHERE .= ' AND (pi.pet_parent_id = 1 OR pi.pet_parent_id = 3 OR pi.pet_parent_id = 10)';
				} else if($searchByPalType == 10) {
					//$WHERE .= ' AND sp.pet_type_id = '.$searchByPalType;
				} else {	
					$WHERE .= ' AND pi.pet_parent_id = '.$searchByPalType;
				}
			}else{
				$WHERE .= ' AND (pi.pet_parent_id = 1 OR pi.pet_parent_id = 3 OR pi.pet_parent_id = 2)';
			}

            //~ if(!empty($searchByPetWeight)) {			
				//~ $WHERE .= ' AND sp.pet_weight_limit <= '.$searchByPetWeight;
			//~ }

			/*if(!empty($searchByZipCode)) {
			   $WHERE .= " AND (user.zip_code LIKE '%$searchByZipCode%' OR user.address LIKE '%$searchByZipCode%')";
			   $zipaddress = $this->getzipcodeparameters($searchByZipCode);
			}*/
			
			//~ if(!empty($searchByNumOfPets)) {
			   //~ $WHERE .= ' AND user.number_of_pets <= '.$searchByNumOfPets;
			//~ }

			//~ if(!empty($searchByDateFrom) && !empty($searchByDateTo)) {
				//~ //date_default_timezone_set('UTC');
				//~ $searchArr = array();
				//~ while (strtotime($searchByDateFrom) <= strtotime($searchByDateTo)) {
					//~ $searchArr[] = $searchByDateFrom;
					//~ $searchByDateFrom = date(DATEPICKER_FORMAT_PHP, strtotime("+1 day", strtotime($searchByDateFrom)));
				//~ }
				//~ $WHERE .= " AND una.dates NOT REGEXP(REPLACE('".implode(",",$searchArr)."',',','|'))";
			//~ } else if(!empty($searchByDateFrom)) {
				//~ $searchByDateTo = $searchByDateFrom;
				//~ $searchArr = array();
				//~ while (strtotime($searchByDateFrom) <= strtotime($searchByDateTo)) {
					//~ $searchArr[] = $searchByDateFrom;
					//~ $searchByDateFrom = date(DATEPICKER_FORMAT_PHP, strtotime("+1 day", strtotime($searchByDateFrom)));
				//~ }
				//~ $WHERE .= " AND una.dates NOT REGEXP(REPLACE('".implode(",",$searchArr)."',',','|'))";
			//~ }

			$WHERE .= ' AND user.id != '.$logginUserId;
			
			$sort_by	=	(isset($post['filter']['sort_by']) ? $post['filter']['sort_by'] : 0);
			if($sort_by == 1) {
				$orderBy	=	"rating ASC";
			} else if($sort_by == 2) {
				$orderBy	=	"rating DESC";
			} else if($sort_by == 3) {
				$orderBy	=	"pi.per_day_price ASC";
			} else if($sort_by == 4) {
				$orderBy	=	"pi.per_day_price DESC";
			} else {
				$orderBy	=	"user.id DESC";
			}
			
			$query = new Query;
            $query->select('user.id,user.firstname,user.lastname,user.profile_image,user.zip_code,user.country,user.address,user.region,user.city,user.latitude,user.longitude,pi.per_day_price,user.description as user_description,user.user_average_rating as rating,user.total_rating,user.total_rating as t_rating,cns.name as u_country_name,ct.name as u_city_name')
                    ->from('user')->distinct()
                    //->join('LEFT JOIN', 'user_services services', 'user.id = services.user_id')                     
                     ->join('LEFT JOIN', 'pet_information as pi', 'user.id = pi.user_id')   
                   // ->join('LEFT JOIN', 'booking as b', 'user.id = b.pet_sitter_id AND b.completed = 1')
                    //->join('LEFT JOIN', 'user_unavailability as una', 'user.id = una.user_id')     
						->join('LEFT JOIN', 'countries as cns', 'user.country = cns.id')
						->join('LEFT JOIN', 'cities as ct', 'user.city = ct.id')                                          
                    ->where($WHERE);         
			Yii::$app->session->set('searchWhere', $WHERE);
        } elseif (!Yii::$app->request->isPost && isset($queryParams['page']) && isset($queryParams['per-page'])) {
            $WHERE = Yii::$app->session->get('searchWhere');           
			$query = new Query;
            $query->select('user.id,user.firstname,user.lastname,user.profile_image,user.zip_code,user.country,user.address,user.region,user.city,user.latitude,user.longitude,pi.per_day_price,user.description as user_description,user.user_average_rating as rating,user.total_rating as t_rating,cns.name as u_country_name,ct.name as u_city_name')
                    ->from('user')->distinct()
                    //->join('LEFT JOIN', 'user_services services', 'user.id = services.user_id')                                       
                 ->join('LEFT JOIN', 'pet_information as pi', 'user.id = pi.user_id')  
                   // ->join('LEFT JOIN', 'booking as b', 'user.id = b.pet_sitter_id AND b.completed = 1')
                    //->join('LEFT JOIN', 'user_unavailability as una', 'user.id = una.user_id') 
						->join('LEFT JOIN', 'countries as cns', 'user.country = cns.id')
						->join('LEFT JOIN', 'cities as ct', 'user.city = ct.id')                            
                    ->where($WHERE);
        } else {
			$WHERE = " user.status = '1' AND pi.interested_in_renting='1' AND (user.user_type = '".OWNER."' OR user.user_type = '".OWNER_SITTER."' OR user.user_type = '".ALL_PROFILES."'  OR user.user_type = '".OWNER_BORROWER."' ) AND user.unsubscribe_owner='1' AND user.profile_completed_owner='1' AND user.id != ".$logginUserId;
			$query = new Query;
				$query->select('user.id,user.firstname,user.lastname,user.profile_image,user.zip_code,user.country,user.address,user.region,user.city,user.latitude,user.longitude,pi.per_day_price,user.description as user_description,user.user_average_rating as rating,user.total_rating as t_rating,cns.name as u_country_name,ct.name as u_city_name')
						->from('user')->distinct()
						//->join('LEFT JOIN', 'user_services services', 'user.id = services.user_id')                                       
						 ->join('LEFT JOIN', 'pet_information as pi', 'user.id = pi.user_id')  
						//->join('LEFT JOIN', 'booking as b', 'user.id = b.pet_sitter_id AND b.completed = 1')
						//->join('LEFT JOIN', 'user_unavailability as una', 'user.id = una.user_id') 
						->join('LEFT JOIN', 'countries as cns', 'user.country = cns.id')
						->join('LEFT JOIN', 'cities as ct', 'user.city = ct.id')						
						->where($WHERE);   
			Yii::$app->session->set('searchWhere', $WHERE);
        }

		$countQuery = clone $query;	
		$pages 		= new Pagination(['totalCount' => $countQuery->count()]);			
		$query->orderBy($orderBy)
			  ->offset($pages->offset)
		      ->limit($limit);    
		$searchResult = $query->createCommand()->queryAll();
		if(isset($searchResult[0]['id']) && empty($searchResult[0]['id'])) {
			$searchResult = array();
		}
		//echo $searchResult = $query->createCommand()->getRawSql(); exit();
		$pages->setPageSize($limit);		
		$this->layout = false;      
		return $this->render('searchlistingrenter',[
			'searchResult' => $searchResult,
			'pages' => $pages,
			'sort_by' => $sort_by,
			'zipaddress' => $zipaddress
		]);
    }
}
