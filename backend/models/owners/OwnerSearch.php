<?php
namespace backend\models\owners;
use backend\models\owners\Owners;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
* OwnerSearch represents the model behind the search form about User.
*/
class OwnerSearch extends Model {
    public $firstname;
    public $lastname;
    public $dob;
    public $gender;
    public $email;
    public $phone;
    public $status;
    public $residential_status;
    public $verified_by_admin;
    public $verification_badge;
    public $number_of_pets;
    public $address;
    public $country;
    public $countryname;
    public $region;
    public $city;
    public $date_created;

    /** @inheritdoc */
    public function rules() {
        return [
            'fieldsSafe' => [['firstname','lastname', 'email','country','region','city','number_of_pets','date_created','status','countryname','residential_status','verified_by_admin','verification_badge'], 'safe'],
            'createdDefault' => ['date_created', 'default', 'value' => null],
          
        ];
    }

    /** @inheritdoc */
    public function attributeLabels() {
        return [
            'fullname'        => Yii::t('user', 'Fullname'),
            'usrFirstname'    => Yii::t('user', 'First name'),
            'usrLastname'     => Yii::t('user', 'Last name'),
            'username'        => Yii::t('user', 'Username'),
            'email'           => Yii::t('user', 'Email'),
            'created_at'      => Yii::t('user', 'Registration time'),
        ];
    }

	/**
	* @param $params
	*
	* @return ActiveDataProvider
	*/
    public function search($params) {
		//$query = Owners::find()->where('user_type = :user_type AND delete_status = :delete_status',[':user_type' => OWNER,':delete_status' => '0'])->orWhere('user_type = :user_type AND delete_status = :delete_status',[':user_type' => OWNER_SITTER,':delete_status' => '0']);
$query = Owners::find()->where("(user_type = '".OWNER."' OR user_type = '".OWNER_SITTER."' OR user_type = '".OWNER_BORROWER."' OR user_type = '".ALL_PROFILES."') ");
		$query->joinWith(['countryname']);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
             'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        if ($this->date_created !== null) {
            $date 	 = strtotime($this->date_created);
            $newdate = date('Y-m-d',$date);
            $query->andFilterWhere(['like', 'date_created', $newdate]);
        }

        $query->andFilterWhere(['like', 'firstname', $this->firstname])
              ->andFilterWhere(['like', 'lastname', $this->lastname])
              ->andFilterWhere(['like', 'email', $this->email])
              ->andFilterWhere(['like', 'name', $this->country])
              ->andFilterWhere(['like', 'number_of_pets', $this->number_of_pets])
              ->andFilterWhere(['like', 'residential_status', $this->residential_status])
              ->andFilterWhere(['like', 'verified_by_admin', $this->verified_by_admin])
              ->andFilterWhere(['like', 'status', $this->status]);
        return $dataProvider;
    }
}
