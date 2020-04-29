<?php
namespace backend\models\users;
use backend\models\users\Users;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
* UserSearch represents the model behind the search form about User.
*/
class UserSearch extends Model {

    public $firstname;
    public $lastname;
    public $dob;
    public $gender;
    public $email;
    public $phone;
    public $status;
    public $residency_status;
    public $address;
    public $country;
    public $countryname;
    public $region;
    public $city;
    public $nationality;
    public $user_type;
    public $registration_type;
    public $google_id;
    public $facebook_id;
    public $twitter_id;
    public $linkedin_id;
    public $date_created;

    /** @inheritdoc */
    public function rules() {
        return [
            'fieldsSafe' => [['firstname','lastname', 'email','country','region','city','nationality','date_created','status','countryname'], 'safe'],
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
		$query = Users::find();
		$query->joinWith(['countryname']);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
             'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
		/*
        $dataProvider->sort->attributes['countryname'] = [
                'asc' => ['country.name' => SORT_ASC],
                'desc' => ['country.name' => SORT_DESC],
        ];
		*/
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
              ->andFilterWhere(['like', 'status', $this->status]);
        return $dataProvider;
    }
}
