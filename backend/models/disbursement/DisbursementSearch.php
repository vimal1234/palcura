<?php
namespace backend\models\disbursement;
use backend\models\disbursement\Disbursement;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
* DisbursementSearch represents the model behind the search form about.
*/
class DisbursementSearch extends Model {
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
          [['user_id','booking_id','status','date_created','user','booking'], 'safe'],
        ];
    }

	/**
	* @param $params
	*
	* @return ActiveDataProvider
	*/
    public function search($params) {
		$query = Disbursement::find();
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
            $query->andFilterWhere(['like', 'payment_disbursements.date_created', $newdate]);
        }

        $query->andFilterWhere(['like', 'booking.name',   $this->booking])
              ->andFilterWhere(['like', 'user.firstname', $this->user])
               ->andFilterWhere(['like', 'booking.booking_credits', $this->booking->booking_credits])
              ->orFilterWhere(['like',  'user.lastname',  $this->user])
              ->andFilterWhere(['like', 'payment_disbursements.status', $this->status]);
        return $dataProvider;
    }
}
