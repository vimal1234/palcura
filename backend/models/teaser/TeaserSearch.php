<?php
namespace backend\models\teaser;
use backend\models\teaser\Teaser;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
* TeaserSearch represents the model behind the search form about.
*/
class TeaserSearch extends Model {
    public $id;
    public $firstname;
    public $lastname;
    public $email;
    public $user_type;
    public $is_registered;
    public $status;
    public $date_created;

    /** @inheritdoc */
    public function rules() {
        return [
         [['id'], 'integer'],
          [['firstname','lastname','email','user_type','is_registered','status','date_created'], 'safe'],
        ];
    }

	/**
	* @param $params
	*
	* @return ActiveDataProvider
	*/
    public function search($params) {
		$query = Teaser::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'  => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        if ($this->date_created != "") {
            $date = strtotime($this->date_created);
            $newdate = date('Y-m-d',$date);
            $query->andFilterWhere(['like', 'date_created', $newdate]);
        }

        $query->andFilterWhere(['like', 'firstname', 	$this->firstname])
              ->andFilterWhere(['like', 'lastname', 	$this->lastname])
              ->andFilterWhere(['like', 'email', 		$this->email])
              ->andFilterWhere(['like', 'user_type', 	$this->user_type])
              ->andFilterWhere(['like', 'is_registered',$this->is_registered])
              ->andFilterWhere(['like', 'status', $this->status]);
        return $dataProvider;
    }
}
