<?php
namespace backend\models\feedback;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\feedback\feedback;

/**
 * FeedbackSearch represents the model behind the search form about `backend\models\page`.
 */
class FeedbackSearch extends Feedback {
    /**
     * @inheritdoc
     */
    public $userSender;
    public $userReceiver;
    public function rules() {
        return [
            [['id','starrating'], 'integer'],
            [['sender_userid', 'receiver_userid', 'comment', 'starrating', 'date_time', 'status','userSender','userReceiver'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios() {
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params) {
		$query = feedback::find();
		$query->joinWith(['userSender','userReceiver']);

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
			'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
		]);

		$dataProvider->sort->attributes['userSender'] = [
			'asc' => ['user.firstname' => SORT_ASC],
			'desc' => ['user.firstname' => SORT_DESC],
		];

		//~ $dataProvider->sort->attributes['userReceiver'] = [
			//~ 'asc' => ['user.firstname' => SORT_ASC],
			//~ 'desc' => ['user.firstname' => SORT_DESC],
		//~ ];

		$dataProvider->sort->attributes['userReceiver'] = [
			'asc' => ['uto.firstname' => SORT_ASC],
			'desc' => ['uto.firstname' => SORT_DESC],
		];
		
		$this->load($params);
		if (!$this->validate()) {
			return $dataProvider;
		}

		if ($this->date_time != "") {
			$date = strtotime($this->date_time);
			$newdate = date('Y-m-d',$date);
			$query->andFilterWhere(['like', 'date_time', $newdate]);
		}

		$query->andFilterWhere(['like', 	'sender_userid', $this->sender_userid])
		->andFilterWhere(['like', 		'receiver_userid', $this->receiver_userid])
		->andFilterWhere(['like', 		'comment', $this->comment])
		->andFilterWhere(['like', 		'starrating', $this->starrating])
		->andFilterWhere(['like', 		'date_time', $this->date_time])
		->andFilterWhere(['like', 		'feedback_rating.status', $this->status])
		->andFilterWhere(['like', 		'user.firstname', $this->userSender])
		->orFilterWhere(['like',  		'user.lastname', $this->userSender])
		->andFilterWhere(['like', 'uto.firstname', $this->userReceiver])
		->orFilterWhere(['like',  'uto.lastname', $this->userReceiver]);
		return $dataProvider;
    }
}
