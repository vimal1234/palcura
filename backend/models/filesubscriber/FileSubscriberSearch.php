<?php
namespace backend\models\filesubscriber;
use backend\models\filesubscriber\FileSubscriber;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
* FileSubscribersSearch represents the model behind the search form about.
*/
class FileSubscriberSearch extends Model {
    public $id;
    public $email;
    

    /** @inheritdoc */
    public function rules() {
        return [
         [['id'], 'integer'],
          [['email'], 'safe'],
        ];
    }

	/**
	* @param $params
	*
	* @return ActiveDataProvider
	*/
    public function search($params) {
		$query = FileSubscriber::find();
		$dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'  => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $dataProvider->sort->attributes['id'] = [
                'asc'  => ['id' => SORT_ASC],
                'desc' => ['id' => SORT_DESC],
        ];

        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }
        $query->andFilterWhere(['like', 'subscriber.email', $this->email]);
        return $dataProvider;
    }
}
