<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Booking;

/**
 * BookingSearch represents the model behind the search form about `frontend\models\Booking`.
 */
class BookingSearch extends Booking
{
   # public $fullName;
       
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['booking_id', 'no_of_travellers', 'customer_user_id', 'guyde_user_id'], 'integer'],
            [['booked_from_date', 'booked_to_date', 'booking_destination', 'booking_status',
                'customerPaymentStatus', 'adminPaymentStatus', 'booked_on_date',
                 ], 'safe'],#'fullName',
            [['no_of_hours', 'no_of_days', 'booking_price'], 'number'],
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
        $query = Booking::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        
        /**
        * Setup your sorting attributes
        * Note: This is setup before the $this->load($params) 
        * statement below
        */
//       $dataProvider->setSort([
//           'attributes' => [
//               'booking_id',
////               'customer.fullName' => [
////                   'asc' => ['usrFirstname' => SORT_ASC, 'usrLastname' => SORT_ASC],
////                   'desc' => ['usrFirstname' => SORT_DESC, 'usrLastname' => SORT_DESC],
////                   #'label' => 'Full Name',
////                   'default' => SORT_ASC
////               ],
//               'booked_from_date',
//               'booked_to_date'
//           ]
//       ]);
        
        
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'booking_status' => '1',
            'booking_id' => $this->booking_id,
            'booked_from_date' => $this->booked_from_date,
            'booked_to_date' => $this->booked_to_date,
            'no_of_hours' => $this->no_of_hours,
            'no_of_days' => $this->no_of_days,
            'no_of_travellers' => $this->no_of_travellers,
            'customer_user_id' => $this->customer_user_id,
            'guyde_user_id' => $this->guyde_user_id,
            'booking_price' => $this->booking_price,
            'booked_on_date' => $this->booked_on_date,
        ]);

        $query->andFilterWhere(['like', 'booking_destination', $this->booking_destination])
            ->andFilterWhere(['like', 'booking_status', $this->booking_status])
            ->andFilterWhere(['like', 'customerPaymentStatus', $this->customerPaymentStatus])
            ->andFilterWhere(['like', 'adminPaymentStatus', $this->adminPaymentStatus]);

//        $query->andFilterWhere(['like', 'guyde.id', $this->guyde_user_id]) 
//            ->andFilterWhere(['like', 'customer.id', $this->customer_user_id]);
        
//        $query->andWhere('usrFirstname LIKE "%' . $this->customer['fullName'] . '%" ' .
//            'OR usrLastname LIKE "%' . $this->customer['fullName']  . '%"'
//        );
        
        return $dataProvider;
    }
}
