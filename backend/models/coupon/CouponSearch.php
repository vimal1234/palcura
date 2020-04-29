<?php
namespace backend\models\coupon;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\coupon\Coupon;

/**
 * FeedbackSearch represents the model behind the search form about `backend\models\page`.
 */
class CouponSearch extends Coupon {
    /**
     * @inheritdoc
     */
    public $coupon_name;
   
    public function rules() {
        return [
           'fieldsSafe' => [['coupon_name','coupon_code'], 'safe'], 
        ];
    }

  
    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params) {
		$query = Coupon::find();
		//$query->joinWith(['userSender','userReceiver']);

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
			'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
		]);

		$this->load($params);
		if (!$this->validate()) {
			return $dataProvider;
		}
		 $query->andFilterWhere(['like', 'coupon_name', $this->coupon_name])
              ->andFilterWhere(['like', 'coupon_code', $this->coupon_code]);	
		return $dataProvider;
    }
}
