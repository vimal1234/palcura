<?php
namespace backend\models\payments;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\payments\Payments;

/**
* Payment represents the model behind the search form about `app\models\Menus`.
*/
class PaymentsSearch extends Payments {
	/**
	* @inheritdoc
	*/
    public $user; 
    public $country; 
    public $booking; 
    public function rules() {
        return [
            [['payment_transaction_id','user_id','booking_id'], 'integer'],
            [['amount', 'trans_date', 'payment_type', 'payment_status','user','trans_id','country','booking'], 'safe'],
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
        $query = Payments::find();
        $query->joinWith(['user','booking']);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['payment_transaction_id' => SORT_DESC]]
        ]);

        $dataProvider->sort->attributes['user'] = [
                'asc' => ['user.firstname' 	=> SORT_ASC],
                'desc' => ['user.firstname' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['booking'] = [
                'asc' => ['booking.name' => SORT_ASC],
                'desc' => ['booking.name' => SORT_DESC],
        ];

        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'payment_transaction_id' 	=> $this->payment_transaction_id,
            'date(trans_date)' 			=> $this->trans_date ? date('Y-m-d', strtotime($this->trans_date)) : '',
        ]);

        $query->andFilterWhere(['like', 'payment_transaction_id', $this->payment_transaction_id])
            ->andFilterWhere(['like', 'trans_date', 	 		  $this->trans_date])
            ->andFilterWhere(['like', 'amount', 	 			  $this->amount])
            ->andFilterWhere(['like', 'booking.admin_fee',		  $this->booking_id])
            ->andFilterWhere(['like', 'payment_transaction.payment_status', 	 	  $this->payment_status])
            ->andFilterWhere(['like', 'payment_type', 			  $this->payment_type])
            ->andFilterWhere(['like', 'trans_id', 				  $this->trans_id])
            ->andFilterWhere(['like', 'user.firstname', 		  $this->user])
            ->orFilterWhere(['like',  'user.lastname', 			  $this->user]);          

        return $dataProvider;
    }
}
