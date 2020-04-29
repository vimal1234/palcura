<?php
namespace backend\models\petvaccinations;
use backend\models\petvaccinations\Petvaccination;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
* PetvaccinationSearch represents the model behind the search form about User.
*/
class PetvaccinationSearch extends Model {
    public $id;
    public $user_id;
    public $pet_name;
    public $emergency_contact;
    public $start_date;
    public $end_date;
    public $status;    
    public $date_created;
    public $notification_flag;
    public $user;

    /** @inheritdoc */
    public function rules() {
        return [
         [['id'], 'integer'],
          [['user','user_id','pet_name','emergency_contact','start_date','end_date','date_created','notification_flag','status'], 'safe'],
        ];
    }

    /**
    * @param $params
    *
    * @return ActiveDataProvider
    */
    public function search($params,$d=0) {
		$query = Petvaccinations::find()->where(['user_id' => $d]);
		$query->joinWith(['user']);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]  
        ]);

        $dataProvider->sort->attributes['user'] = [
                'asc' => ['user.firstname' => SORT_ASC],
                'desc' => ['user.firstname' => SORT_DESC],
        ];

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        if ($this->date_created != "") {
            $date = strtotime($this->date_created);
            $newdate = date('Y-m-d',$date);
            $query->andFilterWhere(['like', 'pet_vaccination_details.date_created', $newdate]);
        }

        $query->andFilterWhere(['like',   'user.firstname', $this->user])
              ->orFilterWhere(['like',    'user.lastname', $this->user])
              ->andFilterWhere(['like',   'pet_vaccination_details.pet_name', $this->pet_name])              
              ->andFilterWhere(['like',   'pet_vaccination_details.emergency_contact', $this->emergency_contact])              
              //->andFilterWhere(['like',   'pet_vaccination_details.start_date', $this->start_date])              
             // ->andFilterWhere(['like',   'pet_vaccination_details.end_date', $this->end_date])              
              ->andFilterWhere(['like',   'pet_vaccination_details.status', $this->status]);
        return $dataProvider;
    }
}
