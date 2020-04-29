<?php

namespace common\components;

use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\db\Query;

class CommonComponent extends Component {

    public function updateStates($countryID = 0) {
        $states = \common\models\State::find()->where(['country_id' => $countryID])->asArray()->all();
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $states;
    }

    public function updateCities($stateID = 0) {
        $cities = \common\models\City::find()->where(['state_id' => $stateID])->asArray()->all();
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $cities;
    }

   /* public function citiesbyregion($stateId) {
        //$cities = \common\models\City::find()->where(['state_id' => $stateID])->asArray()->all();

        return ArrayHelper::map(\common\models\City::find()->where(['state_id' => $stateId])->orderBy(['cityAlias' => SORT_ASC])->asArray()->all(), 'id', 'cityAlias');
    }*/
 public function citiesbyregion($stateId) {
        //$cities = \common\models\City::find()->where(['state_id' => $stateID])->asArray()->all();

        return ArrayHelper::map(\common\models\City::find()->select(['id','cityAlias as cityName',])->where(['state_id' => $stateId])->orderBy(['cityName' => SORT_ASC])->asArray()->all(), 'id', 'cityName');
    }

    public function residentialStatus($s_type = 0) {
        //if(empty($s_type)) { return '=='; }
        if ($s_type > 0) {
            switch ($s_type) {
                case 1:
                    return "Live with Parents/relatives";
                    break;
                case 2:
                    return "Couch surfing";
                    break;
                case 3:
                    return "Renting with friends";
                    break;
                case 4:
                    return "Renting by myself";
                    break;
                case 5:
                    return "Campus housing";
                    break;
                case 6:
                    return "I own a condo";
                    break;
                case 7:
                    return "I own a house";
                    break;
                default:
                    return "--";
            }
        } else {
            return '--';
            return array("1" => "Live with Parents/relatives", "2" =>
                "Couch surfing", "3" => "Renting with friends", "4" => "Renting by myself",
                "5" => "Campus housing", "6" => "I own a condo", "7" => "I own a house");
        }
    }

    public function residencelists() {
        return array("1" => "Live with Parents/relatives", "2" =>
            "Couch surfing", "3" => "Renting with friends", "4" => "Renting by myself",
            "5" => "Campus housing", "6" => "I own a condo", "7" => "I own a house");
    }

public function getWebsiteSettings() {
        $query = new Query;
		$query->select('*')->from('website_settings');
		return $query->createCommand()->queryOne();		
	}

    public function getStatus($s_type = 0, $all = 0) {
        if ($all > 0) {
            switch ($s_type) {
                case 1:
                    return "Active";
                    break;
                case 2:
                    return "Inactive";
                    break;
                default:
                    echo "--";
            }
        } else {
            return array("1" => "Active", "2" => "Inactive");
        }
    }

    public function getDisbursementStatus($s_type = 0, $all = 0) {
        if ($all > 0) {
            switch ($s_type) {
                case 0:
                    return "Pending";
                    break;
                case 1:
                    return "Transfered";
                    break;
                default:
                    echo "--";
            }
        } else {
            return array("0" => "Pending", "1" => "Transfered");
        }
    }

    public function getUserStatus($s_type = 0, $all = 0) {
        if ($all > 0) {
            switch ($s_type) {
                case 0:
                    return "Pending";
                    break;
                case 1:
                    return "Active";
                    break;
                case 2:
                    return "Inactive";
                    break;
                default:
                    echo "--";
            }
        } else {
            return array("0" => "Pending", "1" => "Active", "2" => "Inactive");
        }
    }

    public function verifyStatus($s_type = 0, $all = 0) {
        if ($all > 0) {
            switch ($s_type) {
                case 0:
                    return "<span class='verifyB'>Pending</span>";
                    break;
                case 1:
                    return "<span class='verifyG'>Verified</span>";
                    break;
                case 2:
                    return "<span class='verifyR'>Rejected</span>";
                    break;
                default:
                    echo "--";
            }
        } else {
            return array("0" => "Pending", "1" => "Verified", "2" => "Rejected");
        }
    }

    public function getFormType($s_type = 0, $all = 0) {
        if ($all > 0) {
            switch ($s_type) {
                case 1:
                    return "Suggestions";
                    break;
                case 2:
                    return "Concerns";
                    break;
                case 3:
                    return "Experience";
                    break;
                case 4:
                    return "Questions";
                    break;
                default:
                    echo "--";
            }
        } else {
            return array("1" => "Suggestions", "2" => "Concerns", "3" => "Experience", "4" => "Questions");
        }
    }
	public function getFormType1($s_type = 0, $all = 0) {
        if ($all > 0) {
            switch ($s_type) {
                case 1:
                    return "Pet Owner";
                    break;
                case 2:
                    return "Pet Sitter";
                    break;
                case 3:
                    return "Pet Borrower";
                    break;
                default:
                    echo "--";
            }
        } else {
            return array("1" => "Pet Owner", "2" => "Pet Sitter", "3" => "Pet Borrower");
        }
    }

    public function getHouseSize() {
        return array("1" => "1", "2" => "2", "3" => "3", "4" => "4", "5" => "5"
            , "6" => "6+");
    }

    public function servicesTypes() {
        $query = new Query;
        $query->select('id,name')->from('services')->where(['status' => ACTIVE]);
        $data = $query->createCommand()->queryAll();
        return ArrayHelper::map($data, 'id', 'name');
    }

    public function servicesTypesbyPet() {
        $query = new Query;
        $query->select('*')->from('services');
        $data = $query->createCommand()->queryAll();
        $dogservices = array();
        $catservices = array();
        $otherservices = array();

        if (!empty($data)) {
            foreach ($data as $k => $val) {
                $allowedpet = explode(',', $val['pet_type']);

                if (in_array(1, $allowedpet)) {
                    $dogservices[] = $val;
                }
                if (in_array(2, $allowedpet)) {
                    $catservices[] = $val;
                }
                if (in_array(3, $allowedpet)) {
                    $otherservices[] = $val;
                }
            }
        }
        $dogservices = ArrayHelper::map($dogservices, 'id', 'name');
        $catservices = ArrayHelper::map($catservices, 'id', 'name');
        $otherservices = ArrayHelper::map($otherservices, 'id', 'name');

        $servicesTypes = array();
        $servicesTypes['dogservices'] = $dogservices;
        $servicesTypes['catservices'] = $catservices;
        $servicesTypes['otherservices'] = $otherservices;
        return $servicesTypes;
    }
	public function getUserAdditionalImage($user_id) {
        $query = new Query;
        $query->select('id,name')->from('users_documents')->where(['user_id' => $user_id,'document_type'=>'2','delete_status'=>'0']);
        return $query->createCommand()->queryAll();
		
	}
	public function getUserProfilepics($user_id) {
        $query = new Query;
        $query->select('id,profile_image')->from('user')->where(['id' => $user_id]);
        return $query->createCommand()->queryAll();
    }
    public function getUserDocuments($user_id, $doc_type) {
        $query = new Query;
        $query->select('id,name')->from('users_documents')->where(['user_id' => $user_id, 'document_type' => $doc_type]);
        return $query->createCommand()->queryAll();
    }

    public function getUserActiveDocuments($user_id, $doc_type) {
        $query = new Query;
        $query->select('id,name')->from('users_documents')->where(['user_id' => $user_id, 'document_type' => $doc_type, 'delete_status' => '0']);
        return $query->createCommand()->queryAll();
    }

 public function getUserActiveDocImages($user_id) {
        $query = new Query;
        $query->select('id,name')->from('users_documents')->where(['user_id' => $user_id, 'delete_status' => '0'])->andWhere(['in', 'document_type', [2, 3]]);
        return $query->createCommand()->queryAll();
    }
 public function getUserBookingImages($user_id) {
        $query = new Query;
        $query->select('id,name')->from('booking_images')->where(['user_id' => $user_id, 'delete_status' => '0'])->limit(6);
        return $query->createCommand()->queryAll();
    }

    public function getServiceproviderCnt($user_id) {
        $query = new Query;
        $query->select('id')->from('service_provider_details')->where(['user_id' => $user_id]);
        return $query->createCommand()->queryOne();
    }

    public function getServiceproviderdata($user_id) {
        $query = new Query;
        $query->select('*')->from('service_provider_details')->where(['user_id' => $user_id]);
        return $query->createCommand()->queryOne();
    }

    public function getPetinformation($user_id) {
        $query = new Query;
        $query->select('*')->from('pet_information')->where(['user_id' => $user_id]);
        return $query->createCommand()->queryOne();
    }
    
     public function getUserPetsInfo($user_id) {
        $query = new Query;
        $query->select('*')->from('user_pets')->where(['user_id' => $user_id]);
        return $query->createCommand()->queryAll();
    }

    public function getChildrenCount() {
        $arr = array();
        for ($i = 0; $i <= 6; $i++) {
            $arr[$i] = $i;
        }
        return $arr;
    }

    public function getNumberOfPets() {
        $arr = array();
        for ($i = 1; $i <= 10; $i++) {
            $arr[$i] = $i;
        }
        return $arr;
    }

    public function getPlenty() {
        $arr = array();
        for ($i = 1; $i <= 20; $i++) {
            $arr[$i] = $i . "%";
        }
        return $arr;
    }

    public function getIncome() {
        return array("<$25,000" => "<$25,000", "$25,000-$49,999" =>
            "$25,000-$49,999", "$50,000 - $74,999" => "$50,000 - $74,999",
            "$75,000 - $99,999" => "$75,000 - $99,999",
            "$100,000-$149,999" => "$100,000-$149,999", "$150,000+" => "$150,000+");
    }

    public function userTypes() {
        return ArrayHelper::map(\common\models\Usertype::find()->all(), 'id', 'name');
    }

    public function countries($c_id = 0) {
        if ($c_id > 0) {
            return \common\models\Country::find()->select(["name"])->where(['id' => $c_id])->one();
        } else {
            return ArrayHelper::map(\common\models\Country::find()->all(), 'id', 'name');
        }
    }

    public function regions($r_id = 0) {
        if ($r_id > 0) {
            return \common\models\State::find()->select("*")->where(['id' => $r_id])->one();
        } else {
            return ArrayHelper::map(\common\models\State::find()->orderBy(['name' => SORT_ASC])->all(), 'id', 'name');
        }
    }

    public function cities($c_id = 0) {
        if ($c_id > 0) {
            return \common\models\City::find()->select(["*"])->where(['id' => $c_id])->one();
        } else {
            return ArrayHelper::map(\common\models\City::find()->all(), 'id', 'name');
        }
    }

    public function status($v = 0, $n = 0) {
        if ($n == 1) {
            if ($v == '1') {
                echo 'Active';
            } else {
                echo 'Inactive';
            }
        } else {
            if ($v == '1') {
                echo 'Active';
            } else {
                echo 'Inactive';
            }
        }
    }

    public function getPetTypes($r_id = 0) {
        if ($r_id > 0) {
            $query = new Query;
            $query->select('id,name')->from('pet_types')->where(['parent_id' => $r_id, 'delete_status' => '0']);
            $data = $query->createCommand()->queryAll();
            return ArrayHelper::map($data, 'id', 'name');
        } else {
            $query = new Query;
            $query->select('id,name')->from('pet_types')->where(['parent_id' => 0, 'delete_status' => '0']);
            $data = $query->createCommand()->queryAll();
            return ArrayHelper::map($data, 'id', 'name');
        }
    }

    public function getPetTypesSEARCH($r_id = 0) {
        if ($r_id > 0) {
            $query = new Query;
            $query->select('id,name')->from('pet_types')->where(['parent_id' => $r_id, 'delete_status' => '0']);
            $data = $query->createCommand()->queryAll();
            return ArrayHelper::map($data, 'id', 'name');
        } else {
            $query = new Query;
//            $query->select('id,name')->from('pet_types')->where('parent_id = 0 AND id != 10');
            $query->select('id,name')->from('pet_types')->where('parent_id = 0 AND id != 7 AND id != 8 AND id != 9 AND id != 10');
            $data = $query->createCommand()->queryAll();
            return ArrayHelper::map($data, 'id', 'name');
        }
    }
    /*
     * Function to get pat type from pet_id
     */
    public function getPetTypeById($petId) {
        $query = new Query;
        $query->select('name')->from('pet_types')->where(['id' => $petId]);
        $petType = $query->createCommand()->queryAll();
        return (isset($petType) && count($petType) > 0) ? $petType[0]['name'] : '' ;
    }
    
    public function get_booking_care_note($petId, $bookingId){
        $query = new Query;
        $query->select('care_note')->from('booking_care_notes')->where(['user_pet_id' => $petId, 'booking_id' => $bookingId]);
        $careNote = $query->createCommand()->queryAll();
        return (isset($careNote) && count($careNote) > 0) ? $careNote[0]['care_note'] : '' ;
    }

    public function getEmailContent($alias = '') {
        $query = new Query;
        $query->select('message')->from('newsletter_template')->where("code ='" . $alias . "' AND status ='Y'");
        return $query->createCommand()->queryOne();
    }

    public function getAdminEmailID() {
        $modelLink = new \common\models\Admin();
        $AdminEmail = $modelLink->getAdminEmail();
        if (isset($AdminEmail['1']) && !empty($AdminEmail['1'])) {
            $fromEmail = $AdminEmail['1'];
        } else {
            $fromEmail = Yii::$app->params['adminEmail'];
        }
        return $fromEmail;
    }

    public function getMaskEmailAddress($chatID) {
        $query = new Query;
        $query->select('email')->from('random_email_address')->where(['chat_id' => $chatID]);
        $response = $query->createCommand()->queryOne();
        if (isset($response['email']) && !empty($response['email'])) {
            return $response['email'];
        }
    }

    public function getUserColumnsData($userID, $selectCol) {
        if ($userID > 0) {
            if ($selectCol == '' || $selectCol == 0) {
                $selectCol = "*";
            }
            $query = new Query;
            $query->select($selectCol)->from('user')->where(['id' => $userID]);
            return $query->createCommand()->queryOne();
        }
    }

        ####= user services

    public function getUserServices($userID, $type = 0) {
        $query = new Query;
        $query->select('user_services.service_id,user_services.price as service_price,s.name as service_name,s.id as id,user_services.pet_type_id as service_pet_type,user_services.user_id as user_id')->from('user_services')
                ->join('LEFT JOIN', 'services s', 's.id = user_services.service_id')
                ->where('user_services.user_id = ' . $userID);
        $data = $query->createCommand()->queryAll();
        if ($type == true) {
            $data = ArrayHelper::map($data, 'id', 'service_name');
        }
        return $data;
    }
	
	public function getUserServicesdata($userID, $type = 0) {
        $query = new Query;
        $query->select('user_services.service_id,user_services.price as service_price,s.name as service_name,s.id as id,user_services.pet_type_id as service_pet_type,user_services.user_id as user_id')->from('user_services')
                ->join('LEFT JOIN', 'services s', 's.id = user_services.service_id')
                ->where('user_services.id = ' . $userID);
        $data = $query->createCommand()->queryAll();
        if ($type == true) {
            $data = ArrayHelper::map($data, 'id', 'service_name');
        }
        return $data;
    }

    ####= completed user services

    public function getCompletedServices($userID) {
        $query = new Query;
        $query->select('COUNT(id) as cnt')->from('booking')->where(['pet_sitter_id' => $userID, 'completed' => 1]);
        $data = $query->createCommand()->queryOne();
        if (isset($data['cnt']) && !empty($data['cnt'])) {
            return $data['cnt'];
        }
    }

    ####= get user ratings

    public function getUserRatings($userID) {
        $query = new Query;
        $query->select('ROUND(SUM(starrating) / COUNT(id)) as total_rating,COUNT(id) as reviews_count')->from('feedback_rating')->where(['receiver_userid' => $userID]);
        return $query->createCommand()->queryOne();
    }

    ####= generate ratings

    public function generateRatings($ratings) {
        $ratingHTML = '';
        for ($i = 0; $i < 5; $i++) {
            if ($i < $ratings) {
                $ratingHTML .= '<i class="fa fa-star" aria-hidden="true"></i>';
            } else {
                $ratingHTML .= '<i class="fa fa-star greyclr" aria-hidden="true"></i>';
            }
        }
        return $ratingHTML;
    }

    ####= get card information

    public function getCardInformation($userId, $cnt = 0) {
        $query = new Query;
        if ($cnt == true) {
            $query->select('COUNT(id) as cnt')->from('card_information')->where(['card_user_id' => $userId]);
            $response = $query->createCommand()->queryOne();
            return (isset($response['cnt']) ? $response['cnt'] : 0);
        } else {
            $query->select('id,card_holder_name,card_type,card_bank_name,card_number,card_cvv_number,card_exp_month,card_exp_year')->from('card_information')->where(['card_user_id' => $userId]);
            return $query->createCommand()->queryOne();
        }
    }

    ####= get card information

    public function getPetvaccinationdetails($userId) {
        $query = new Query;
        $query->select('*')->from('pet_vaccination_details')->where(['user_id' => $userId]);
        return $query->createCommand()->queryOne();
    }

    public function strsublen_complete($str, $start, $length, $extra = 0) {
        $convert_string = "";
        if ($str != '' || $str != 0) {
            if (strlen($str) > $length) {
                if ($extra == 0) {
                    $convert_string = substr($str, $start, $length);
                } else {
                    $convert_string = substr($str, $start, $length - 2) . "..";
                }
            } else {
                $convert_string = substr($str, $start, $length);
            }
        }
        return $convert_string;
    }

    ####= calculate services price

    public function calculateServicesPrice($servicesArr, $day = 1, $user_id = 0) {

        $query = new Query;
        $query->select('SUM(price)*' . $day . ' as services_price')->from('user_services')->where(['IN', 'service_id', $servicesArr])->andWhere(['user_id' => $user_id]);
        $data = $query->createCommand()->queryOne();
        return (isset($data['services_price']) ? $data['services_price'] : 0);
    }

    ####= get website charges for the services

    public function getWebsiteFee() {
        return 15;
    }

    public function getBookingImages($bookingId) {
        $query = new Query;
        $query->select('id,name,media_type')->from('booking_images')->where(['booking_id' => $bookingId, 'delete_status' => '0']);
        return $query->createCommand()->queryAll();
    }

    public function getDatepickerDate() {
        $cDate = date(DATEPICKER_FORMAT_PHP);
        return date(DATEPICKER_FORMAT_PHP, strtotime($cDate . ADD_DAYS_PHP));
    }

    public function getLatitudeAndLongitude($address) {
        $location = array();
        $prepAddr = urlencode($address);
        $request_url = "https://maps.googleapis.com/maps/api/geocode/json?address=" . $prepAddr . "&sensor=false&key=" . GOOGLE_MAP_KEY;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $request_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($ch);
        $info = curl_getinfo($ch);
        $err = curl_error($ch);
        curl_close($ch);
        $jsondata = json_decode($data);
        $location['lat'] = (isset($jsondata->{'results'}[0]->{'geometry'}->{'location'}->{'lat'}) ? $jsondata->{'results'}[0]->{'geometry'}->{'location'}->{'lat'} : '');
        $location['lng'] = (isset($jsondata->{'results'}[0]->{'geometry'}->{'location'}->{'lng'}) ? $jsondata->{'results'}[0]->{'geometry'}->{'location'}->{'lng'} : '');
        return $location;
    }

    public function uspsAddressVerify($addressArr) {
        $stateR = $this->regions($addressArr['state']);
        $cityR = $this->cities($addressArr['city']);

        $address = (isset($addressArr['address']) ? $addressArr['address'] : '');
        $city = (isset($cityR['name']) ? $cityR['name'] : '');
        $state = (isset($stateR['name']) ? $stateR['name'] : '');

        $request_url = 'http://production.shippingapis.com/ShippingAPI.dll?API=Verify&XML=' . urlencode('<AddressValidateRequest USERID="' . USPS_USERNAME . '"><Address><Address1></Address1><Address2>' . $address . '</Address2><City>' . $city . '</City><State>' . $state . '</State><Zip5></Zip5><Zip4></Zip4></Address></AddressValidateRequest>');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $request_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($ch);
        $info = curl_getinfo($ch);
        $err = curl_error($ch);
        curl_close($ch);
        $xml = simplexml_load_string($data);
        if (isset($xml->Address->Error)) {
            //$xml2 = (array) $xml->Address;
            return array("status" => "failed", "error" => "Invalid address");
        } else if (isset($xml->Address->Address2)) {
            $xml2 = (array) $xml->Address;
            $vAddress = (isset($xml2['Address2']) ? $xml2['Address2'] : '');
            $vCity = (isset($xml2['City']) ? $xml2['City'] : '');
            $vState = (isset($xml2['State']) ? $xml2['State'] : '');
            $vZip5 = (isset($xml2['Zip5']) ? $xml2['Zip5'] : '');
            $vZip4 = (isset($xml2['Zip4']) ? $xml2['Zip4'] : '');
            return array("status" => "success", "address" => $vAddress, "city" => $vCity, "state" => $vState, "zip5" => $vZip5, "zip4" => $vZip4);
        } else {
            return array("status" => "failed", "error" => "Invalid address");
        }
    }

    public function getTeaserEmail($emailID) {
        $query = new Query;
        //$query->select('COUNT(userid) as cnt')->from('teaser_details')->where(['email' => $emailID, 'amount_credited' => '1', 'status' => '1']);
        $query->select('COUNT(userid) as cnt')->from('teaser_details')->where("email = '" . $emailID . "' AND amount_credited = '1' AND (status = '1' OR status = '2')");
        $response = $query->createCommand()->queryOne();
        return (isset($response['cnt']) ? $response['cnt'] : 0);
    }

    public function adminDiscount($userType, $booked_days) {
        switch ($userType) {
            case OWNER:
                if ($booked_days > 50) {
                    return 15;
                } else if ($booked_days > 10) {
                    return 10;
                } else {
                    return 0;
                }
                break;
            case SITTER:
                if ($booked_days > 50) {
                    return 15;
                } else if ($booked_days > 10) {
                    return 10;
                } else {
                    return 0;
                }
                break;
            case BORROWER:
                if ($booked_days > 50) {
                    return 15;
                } else if ($booked_days > 10) {
                    return 10;
                } else {
                    return 0;
                }
                break;
            default:
                echo 0;
        }
    }

    public function getServicesName($servicesArr) {
        $query = new Query;
        $query->select('id,name')->from('services')->where(['IN', 'id', $servicesArr]);
        $data = $query->createCommand()->queryAll();
        $data = ArrayHelper::map($data, 'id', 'name');
        return $data;
    }

    public function getServicesNamenew($servicesArr) {
        $query = new Query;
        $servicesArr = explode(',', $servicesArr);
        $query->select('id,name')->from('services')->where(['IN', 'id', $servicesArr]);
        $data = $query->createCommand()->queryAll();
        $data = ArrayHelper::map($data, 'id', 'name');
        return $data;
    }

    public function getCardTypes() {
        $data = array('Visa' => 'Visa', 'MasterCard' => 'Master Card', 'Discover' => 'Discover', 'Amex' => 'American Express');
        //$data = ArrayHelper::map($data, 'id', 'name');	
        return $data;
    }

    public function isPalcuraFamilyMember($userId) {
        $query = new Query;
        $query->select('id')->from('palcura_family_member')->where(['user_id' => $userId]);
        $data = $query->createCommand()->queryOne();
        return (isset($data['id']) ? $data['id'] : 0);
    }

    public function getOwnerPrice($userId) {
        $query = new Query;
        $query->select('per_day_price')->from('pet_information')->where(['user_id' => $userId]);
        $data = $query->createCommand()->queryOne();
        return (isset($data['per_day_price']) ? $data['per_day_price'] : 0);
    }

    public function getUnreadMessagesCount($userId = 0) {
        $query = new Query;
        $query->select('COUNT(id) as unread_cnt')->from('messages')->where(['user_to' => $userId, 'is_read' => 0]);
        $messageCnt = $query->createCommand()->queryOne();
        return (isset($messageCnt['unread_cnt']) ? $messageCnt['unread_cnt'] : 0);
    }

    public function getFinalSitterDiscount($userId = 0, $sitterId = 0, $min_amt = 0) {
        $compareDate = date('Y-m-d');
        $query = new Query;
        $query->select('id')->from('booking_discount')->where('owner_id = ' . $userId . ' AND sitter_id = ' . $sitterId . ' AND minimum_price <= ' . $min_amt . ' AND till_date <= DATE("' . $compareDate . '")');
        $data = $query->createCommand()->queryOne();
        return (isset($data['id']) ? $data['id'] : 0);
    }

    public function getBookingReminder($userId) {

        $currentdate = date('Y-m-d');
        $query = new Query;
        $query->select('*')->from('booking')->Where('(pet_owner_id = ' . $userId . ' OR pet_renter_id = ' . $userId . ') AND booking_from_date > "' . $currentdate . '" AND payment_status = "1"')->orderBy('booking_from_date ASC')->limit(1);
        return $query->createCommand()->queryAll();
    }

    public function getSitterbookings($userID) {
        $currentDate = date("Y-m-d");
        $query = new Query;
        $query->select('*')->from('booking')->select('id,name,pet_sitter_id,pet_owner_id,status,booking_from_date')->where('pet_sitter_id = ' . $userID . ' AND booking_from_date <= "' . $currentDate . '" AND booking_to_date >= "' . $currentDate . '" AND payment_status = "1"')->orderBy(['booking_from_date' => SORT_ASC]);
        return $query->createCommand()->queryAll();
    }

    public function getSitterupcomingbookings($userId) {
        $currentdate = date('Y-m-d');
        $query = new Query;
        $query->select('*')->from('booking')->Where(['pet_sitter_id' => $userId, 'payment_status' => '1'])->andWhere('booking_from_date>"' . $currentdate . '"')->orderBy('booking_from_date ASC')->limit(1);
        return $query->createCommand()->queryAll();
    }
    
    public function availableCreditPointsforUse(){
    $totalbookingcredits = 0;
    $currentdate = date('Y-m-d');
    $userId = Yii::$app->user->identity->id;
    $query = new Query;
    $query->select('booking_credits,id')->from('booking')->Where(['pet_sitter_id' => $userId, 'payment_status' => '1','booking_status' => '1'])->andWhere('booking_to_date>="' . $currentdate . '"');
    $bookingdata = $query->createCommand()->queryAll();
    if(!empty($bookingdata)){
    $booking_credits = 0;
	foreach ($bookingdata as $item) {
		$booking_credits += $item['booking_credits'];
	}
	$totalbookingcredits = $booking_credits;
	}
	$myownercredits = Yii::$app->user->identity->owner_credits;
	$mysittercredits = Yii::$app->user->identity->sitter_credits;
	$totalUserCredits = $myownercredits+$mysittercredits;	
	$usableCredit = $totalUserCredits-$totalbookingcredits;
    $usableCredit = round($usableCredit,2);
    if($usableCredit >0){
    $usableCredit = $usableCredit;
    }else{
    $usableCredit = 0;
    }
	return $usableCredit;
    
    }
    
    public function availableRewardPointsforUse(){
    $totalbookingPoints = 0;
	$booking_points=null;
    $currentdate = date('Y-m-d');
    $userId = Yii::$app->user->identity->id;
    $query = new Query;
    $query->select('amount,id')->from('booking')->Where(['pet_owner_id' => $userId])->orWhere(['pet_renter_id' => $userId])->andWhere(['payment_status' => '1','booking_status' => '1'])->andWhere('booking_to_date>="' . $currentdate . '"');
    $bookingdata = $query->createCommand()->queryAll();
   
    if(!empty($bookingdata)){
    $reward_points = 0;
	foreach ($bookingdata as $item) {
		
		$booking_points += $item['amount'];

	}
	$totalbookingPoints = $booking_points;
	}
	$totalUserPoints = Yii::$app->user->identity->reward_points;
	$usablePoints = $totalUserPoints-$totalbookingPoints;
    $usablePoints = round($usablePoints,2);
    if($usablePoints >0){
    $usablePoints = $usablePoints;
    }else{
    $usablePoints = 0;
    }
	return $usablePoints;
    
    }

 public function getServiceproviderPetWdata($user_id) {
        $query = new Query;
        $query->select('pet_weight_limit')->from('service_provider_details')->where(['user_id' => $user_id]);
        return $query->createCommand()->queryOne();
    }
    
  public function getzipcodeparameters($zip){
	$url = "https://maps.googleapis.com/maps/api/geocode/json?key=".GOOGLE_MAP_KEY."&address=".urlencode($zip)."&sensor=false";
    $result_string = file_get_contents($url);
    $result = json_decode($result_string, true);
	
	if(!empty($result['results']))
	{
		$res = $result['results'][0]['geometry']['location'];
	}
	else
	{
		$res = '';
	}	
    //return ;
	return $res;
	}  
	public function getUserPets($userId) {
        $query = new Query;
        $query->select('*')->from('user_pets')->where(['user_id' => $userId]);
        $data = $query->createCommand()->queryAll();
        return $data;
    }
    
    public function getUserPetsVaccinatiopn($userId) {
        $query = new Query;
        $query->select('vaccination_doc,vaccination_validity')->from('pet_vaccination_details')->where(['user_id' => $userId]);
        $data = $query->createCommand()->queryOne();
       
        return $data;
    }

  public function verifyBookinguser($userId, $bookingid) { 
        $query = new Query;
		$query->select('id')->from('booking')->where(['pet_owner_id' => $userId])->orWhere(['pet_renter_id' => $userId])->andWhere(['id' => $bookingid]);
		$data =  $query->createCommand()->queryOne();	
		if(isset($data['id']) && !empty($data['id'])){
		return true;
		}else{
		return false;
		}
	}

}
