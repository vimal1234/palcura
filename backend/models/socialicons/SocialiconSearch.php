<?php
namespace backend\models\socialicons;
use backend\models\socialicons\Socialicon;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
* SocialiconSearch represents the model behind the search form about User.
*/
class SocialiconSearch extends Model {
    public $id;
    public $name;
    public $url;
    public $description;
    public $date_created;
    public $status;

    /** @inheritdoc */
    public function rules() {
        return [
         [['id'], 'integer'],
          [['name','url','description','date_created','status'], 'safe'],
        ];
    }

    /**
    * @param $params
    *
    * @return ActiveDataProvider
    */
    public function search($params) {
		$query = Socialicons::find();
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

        $query->andFilterWhere(['like', 	'name', 		$this->name])
                ->andFilterWhere(['like', 	'url', 			$this->url])
                ->andFilterWhere(['like', 	'description', 	$this->description])
                ->andFilterWhere(['like', 	'status', 		$this->status]);
        return $dataProvider;
    }
}
