<?php
namespace backend\models\services;
use backend\models\services\Service;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
* ServiceSearch represents the model behind the search form about User.
*/
class ServiceSearch extends Model
{
    public $id;
    public $name;
    public $description;
    public $date_created;
    public $status;

    /** @inheritdoc */
    public function rules() {
        return [
         [['id'], 'integer'],
          [['name','description','date_created','status'], 'safe'],
        ];
    }

    /**
    * @param $params
    *
    * @return ActiveDataProvider
    */
    public function search($params) {
		$query = Services::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]  
        ]);
        
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        if ($this->date_created != "") {
            $date = strtotime($this->date_created);
            $newdate = date('Y-m-d',$date);
            $query->andFilterWhere(['like', 'date_created', $newdate]);
        }

        $query->andFilterWhere(['like', 'name', $this->name])
                ->andFilterWhere(['like', 'description', $this->description])
                ->andFilterWhere(['like', 'status', $this->status]);
        return $dataProvider;
    }
}
