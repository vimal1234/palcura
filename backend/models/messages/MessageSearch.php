<?php
namespace backend\models\messages;
use backend\models\messages\Messages;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
* MessagesSearch represents the model behind the search form about.
*/
class MessageSearch extends Model {
    public $id;
    public $title;
    public $message;
    public $user_from;
    public $user_to;
    public $removed_from;
    public $status;
    public $date_created;
    public $userfrom;
    public $userto;

    /** @inheritdoc */
    public function rules() {
        return [
         [['id'], 'integer'],
          [['title','message','user_from','user_to','removed_from','status','date_created'], 'safe'],
        ];
    }

    /**
    * @param $params
    *
    * @return ActiveDataProvider
    */
    public function search($params) {
		$query = Messages::find();
		$query->joinWith(['userfrom','userto']);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'  => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $dataProvider->sort->attributes['userfrom'] = [
                'asc'  => ['userfrom.firstname' => SORT_ASC],
                'desc' => ['userfrom.firstname' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['owner'] = [
                'asc'  => ['userto.firstname' => SORT_ASC],
                'desc' => ['userto.firstname' => SORT_DESC],
        ];

        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        if ($this->date_created != "") {
            $date = strtotime($this->date_created);
            $newdate = date('Y-m-d',$date);
            $query->andFilterWhere(['like', 'messages.date_created', $newdate]);
        }

        $query->andFilterWhere(['like', 'user.firstname', $this->user_from])
              ->andFilterWhere(['like', 'muserto.firstname', $this->user_to])
              ->andFilterWhere(['like', 'messages.title', $this->title])
              ->andFilterWhere(['like', 'messages.message', $this->message])
              ->andFilterWhere(['like', 'messages.status', $this->status]);
        return $dataProvider;
    }
}
