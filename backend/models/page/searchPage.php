<?php

namespace backend\models\page;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\page\page;

/**
 * searchPage represents the model behind the search form about `backend\models\page`.
 */
class searchPage extends page
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['pageName', 'pageTitle', 'pageType', 'metaTitle', 'metaKeyword', 'metaDescriptions', 'pageContent','pageDateCreated','status'], 'safe'],
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
        $query = page::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
        ]);
	
		  if ($this->pageDateCreated != "") {
            $date = strtotime($this->pageDateCreated);
			$newdate = date('Y-m-d',$date);
            $query->andFilterWhere(['like', 'pageDateCreated', $newdate]);
        } 
		
        $query->andFilterWhere(['like', 'pageName', $this->pageName])
            ->andFilterWhere(['like', 'pageTitle', $this->pageTitle])
            ->andFilterWhere(['like', 'pageType', $this->pageType])
            ->andFilterWhere(['like', 'metaTitle', $this->metaTitle])
            ->andFilterWhere(['like', 'metaKeyword', $this->metaKeyword])
            ->andFilterWhere(['like', 'metaDescriptions', $this->metaDescriptions])
            ->andFilterWhere(['like', 'pageContent', $this->pageContent])
            ->andFilterWhere(['=', 'status', $this->status]);
			return $dataProvider;
    }
}
