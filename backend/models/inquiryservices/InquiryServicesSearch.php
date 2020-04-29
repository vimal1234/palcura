<?php
namespace backend\models\inquiryservices;
use backend\models\inquiryservices\InquiryServices;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
* WebsiteQueriesSearch represents the model behind the search form about.
*/
class InquiryServicesSearch extends Model {
    public $id;
    public $user_id;
    public $title;
	public $name;
	public $email;
    public $status;
    public $reviewed_by_admin;
    public $date_created;
    public $username;

    /** @inheritdoc */
    public function rules() {
        return [
         [['id'], 'integer'],
          [['user_id','title','name','email','status','reviewed_by_admin','date_created'], 'safe'],
        ];
    }

	/**
	* @param $params
	*
	* @return ActiveDataProvider
	*/
    public function search($params) {
		$query = InquiryServices::find();
		$query->joinWith(['username']);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'  => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $dataProvider->sort->attributes['username'] = [
                'asc'  => ['username.firstname' => SORT_ASC],
                'desc' => ['username.firstname' => SORT_DESC],
        ];

        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        if ($this->date_created != "") {
            $date = strtotime($this->date_created);
            $newdate = date('Y-m-d',$date);
            $query->andFilterWhere(['like', 'website_queriesnear.date_created', $newdate]);
        }
 
        $query->andFilterWhere(['like', 'user.firstname', $this->user_id])
              ->andFilterWhere(['like', 'website_queriesnear.title', $this->title])
 ->andFilterWhere(['like', 'website_queriesnear.name', $this->name])
 ->andFilterWhere(['like', 'website_queriesnear.email', $this->email])
              ->andFilterWhere(['like', 'website_queriesnear.reviewed_by_admin', $this->reviewed_by_admin])
              ->andFilterWhere(['like', 'website_queriesnear.status', $this->status]);
        return $dataProvider;
    }
}
