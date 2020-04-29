<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace backend\models\banner;

use backend\models\banner\Banner;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * BannerSearch represents the model behind the search form about User.
 */
class BannerSearch extends Model
{
    /** @var string */
    public $id;
    public $title;
    public $bannerImage;
    public $description;

    /** @var string */
    public $dateCreated;

    public $status;

    /** @inheritdoc */
    public function rules()
    {
        return [
         [['id'], 'integer'],
          [['title', 'bannerImage', 'description', 'dateCreated','status'], 'safe'],
        ];
    }


    /**
     * @param $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {

		$query = Banner::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            
        ]);
        
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        if ($this->dateCreated != "") {
            $date = strtotime($this->dateCreated);
            $newdate = date('Y-m-d',$date);
            $query->andFilterWhere(['like', 'dateCreated', $newdate]);
        }

        $query->andFilterWhere(['like', 'title', $this->title])
                ->andFilterWhere(['like', 'id', $this->id])
                ->andFilterWhere(['like', 'description', $this->description])
                ->andFilterWhere(['like', 'bannerImage', $this->bannerImage])
                ->andFilterWhere(['like', 'status', $this->status]);
              
            
 
        return $dataProvider;
    }
}
