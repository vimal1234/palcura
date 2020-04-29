<?php
namespace backend\models\booking;
use backend\models\booking\Booking;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
* BookingSearch represents the model behind the search form about.
*/
class BookingSearch extends Model {
    public $id;
    public $name;
    public $pet_sitter_id;
    public $pet_owner_id;
    public $pet_renter_id;
    public $booking_from_date;
    public $booking_to_date;
    public $amount;
    public $booking_credits;
    public $admin_fee;
    public $status;
    public $payment_status;
    public $date_created;
    public $sitter;
    public $owner;

    /** @inheritdoc */
    public function rules() {
        return [
         [['id'], 'integer'],
          [['name','pet_sitter_id','pet_owner_id','pet_renter_id','booking_from_date','booking_to_date','amount','admin_fee','status','payment_status','date_created','booking_credits'], 'safe'],
        ];
    }

    /**
    * @param $params
    *
    * @return ActiveDataProvider
    */
    public function search($params) {
		$query = Booking::find()->where('payment_status = :payment_status',[':payment_status' => '1']);
		$query->joinWith(['sitter','owner','renter']);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'  => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $dataProvider->sort->attributes['sitter'] = [
                'asc'  => ['sitter.firstname' => SORT_ASC],
                'desc' => ['sitter.firstname' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['owner'] = [
                'asc'  => ['uname.firstname' => SORT_ASC],
                'desc' => ['uname.firstname' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['renter'] = [
                'asc'  => ['urname.firstname' => SORT_ASC],
                'desc' => ['urname.firstname' => SORT_DESC],
        ];        

        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        if ($this->date_created != "") {
            $date = strtotime($this->date_created);
            $newdate = date('Y-m-d',$date);
            $query->andFilterWhere(['like', 'booking.date_created', $newdate]);
        }
        
        if ($this->booking_from_date != "") {
            $date_from = strtotime($this->booking_from_date);
            $newFromDate = date('Y-m-d',$date_from);
            $query->andFilterWhere(['like', 'booking.booking_from_date', $newFromDate]);
        }
        
        if ($this->booking_to_date != "") {
            $date_to = strtotime($this->booking_to_date);
            $newToDate = date('Y-m-d',$date_to);
            $query->andFilterWhere(['like', 'booking.booking_to_date', $newToDate]);
        }           

        $query->andFilterWhere(['like', 'user.firstname', $this->pet_sitter_id])
              ->andFilterWhere(['like', 'uname.firstname', $this->pet_owner_id])
              ->andFilterWhere(['like', 'urname.firstname', $this->pet_renter_id])
              ->andFilterWhere(['like', 'booking.amount', $this->amount])
              ->andFilterWhere(['like', 'booking.admin_fee', $this->admin_fee])
              ->andFilterWhere(['like', 'booking.status', $this->status]);
        return $dataProvider;
    }
}
