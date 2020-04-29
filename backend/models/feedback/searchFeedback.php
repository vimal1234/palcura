<?php

namespace backend\models\feedback;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\feedback\feedback;

/**
 * searchFeedback represents the model behind the search form about `backend\models\page`.
 */
class searchFeedback extends feedback
{
    /**
     * @inheritdoc
     */
    public $userSender;
    public $userReceiver;     
    public function rules()
    {

        return [
            [['id','starrating'], 'integer'],
            [['sender_userid', 'receiver_userid', 'comment', 'starrating', 'date_time', 'status', 'booking_id','userSender','userReceiver'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = feedback::find();
        $query->joinWith(['userSender','userReceiver']);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
		$dataProvider->sort->attributes['userSender'] = [
			'asc' => ['user.usrFirstname' => SORT_ASC],
			'desc' => ['user.usrFirstname' => SORT_DESC],
		];
		$dataProvider->sort->attributes['userReceiver'] = [
			'asc' => ['uto.usrFirstname' => SORT_ASC],
			'desc' => ['uto.usrFirstname' => SORT_DESC],
		];
        $this->load($params);
        if (!$this->validate()) {

            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
        ]);
	
        if ($this->date_time != "") {
            $date = strtotime($this->date_time);
            $newdate = date('Y-m-d',$date);
            $query->andFilterWhere(['like', 'date_time', $newdate]);
        } 
		
        $query->andFilterWhere(['like', 'sender_userid', $this->sender_userid])
            ->andFilterWhere(['like', 'receiver_userid', $this->receiver_userid])
            ->andFilterWhere(['like', 'comment', $this->comment])
            ->andFilterWhere(['like', 'starrating', $this->starrating])
            ->andFilterWhere(['like', 'date_time', $this->date_time])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'booking_id', $this->booking_id])
			->andFilterWhere(['like', 'user.usrFirstname', $this->userSender])
			->orFilterWhere(['like',  'user.usrLastname', $this->userSender])
			->andFilterWhere(['like', 'uto.usrFirstname', $this->userReceiver])
			->orFilterWhere(['like',  'uto.usrLastname', $this->userReceiver]);


        return $dataProvider;
    }
}
