<?php
namespace backend\models\dispute;
use backend\models\dispute\Dispute;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
* DisputeSearch represents the model behind the search form about.
*/
class DisputeSearch extends Model {
    public $id;
    public $user_id;
    public $booking_id;
    public $title;
    public $description;
    public $form_type;
    public $verified_by_admin;
    public $status;
    public $date_created;
    public $booking;
    public $user;

    /** @inheritdoc */
    public function rules() {
        return [
         [['id'], 'integer'],
          [['user_id','booking_id','title','description','form_type','verified_by_admin','status','date_created','booking','user'], 'safe'],
        ];
    }

	/**
	* @param $params
	*
	* @return ActiveDataProvider
	*/
    public function search($params) {
		$query = Dispute::find();
		$query->joinWith(['booking','user']);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'  => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        $dataProvider->sort->attributes['booking'] = [
                'asc' => ['booking.name' => SORT_ASC],
                'desc' => ['booking.name' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['user'] = [
                'asc' => ['user.firstname' => SORT_ASC],
                'desc' => ['user.firstname' => SORT_DESC],
        ];
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        if ($this->date_created != "") {
            $date = strtotime($this->date_created);
            $newdate = date('Y-m-d',$date);
            $query->andFilterWhere(['like', 'dispute_resolutions.date_created', $newdate]);
        }

        $query->andFilterWhere(['like', 'booking.name',   $this->booking])
              ->andFilterWhere(['like', 'user.firstname', $this->user])
              ->orFilterWhere(['like',  'user.lastname',  $this->user])
              ->andFilterWhere(['like', 'dispute_resolutions.title', 		  $this->title])
              ->andFilterWhere(['like', 'dispute_resolutions.form_type', 	  $this->form_type])
              ->andFilterWhere(['like', 'dispute_resolutions.verified_by_admin',$this->verified_by_admin])
              ->andFilterWhere(['like', 'dispute_resolutions.status', 		  $this->status]);
        return $dataProvider;
    }
}
