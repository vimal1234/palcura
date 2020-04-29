<?php
namespace backend\models\userservices;
use backend\models\userservices\UserService;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
* UserServiceSearch represents the model behind the search form about User.
*/
class UserServiceSearch extends Model {
    public $id;
    public $user_id;
    public $service_id;
    public $price;
    public $date_created;
    public $status;
    public $service;    
    public $user;    

    /** @inheritdoc */
    public function rules() {
        return [
         [['id'], 'integer'],
          [['service','user','service_id','user_id','price','date_created','status'], 'safe'],
        ];
    }

    /**
    * @param $params
    *
    * @return ActiveDataProvider
    */
    public function search($params,$d=0) {
		$query = UserServices::find()->where(['user_id' => $d]);
		$query->joinWith(['service','user']);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]  
        ]);
        $dataProvider->sort->attributes['service'] = [
                'asc' => ['services.name' => SORT_ASC],
                'desc' => ['services.name' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['user'] = [
                'asc' => ['user.firstname' => SORT_ASC],
                'desc' => ['user.firstname' => SORT_DESC],
        ];
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        //~ $this->load($params);
        //~ if (!$this->validate()) {
            //~ return $dataProvider;
        //~ }
        if ($this->date_created != "") {
            $date = strtotime($this->date_created);
            $newdate = date('Y-m-d',$date);
            $query->andFilterWhere(['like', 'user_services.date_created', $newdate]);
        }

        $query->andFilterWhere(['like',   'services.name', $this->service])
              ->andFilterWhere(['like',   'user.firstname', $this->user])
              ->orFilterWhere(['like',    'user.lastname', $this->user])
              ->andFilterWhere(['like',   'user_services.price', $this->price])              
              ->andFilterWhere(['like',   'user_services.status', $this->status]);
        return $dataProvider;
    }
}
