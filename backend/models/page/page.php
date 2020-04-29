<?php

namespace backend\models\page;

use Yii;
use yii\behaviors\SluggableBehavior;
/**
 * This is the model class for table "page".
 *
 * @property integer $id
 * @property string $pageName
 * @property string $pageTitle
 * @property string $pageType
 * @property string $metaTitle
 * @property string $metaKeyword
 * @property string $metaDescriptions
 * @property string $pageContent
 */
class page extends \yii\db\ActiveRecord
{
    public function behaviors()
	  {
		return [
		    [
		        'class' => SluggableBehavior::className(),
		        'attribute' => 'slug',
		    ],
		];
	  }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'page';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pageType', 'pageContent', 'image'], 'string'],
            [['pageName','pageTitle','pageContent'], 'required'],
            ['pageName', 'match', 'pattern' => '/^[a-z ]+$/i', 'message' => 'Page Name only accepts alphabets and space.'],
            [['pageName', 'pageTitle'], 'string', 'max' => 40],
			[['metaTitle', 'metaKeyword'], 'string', 'max' => 100],
			[['metaDescriptions'], 'string', 'max' => 180]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pageName' => 'Page Name',
            'pageTitle' => 'Page Title',
            'pageType' => 'Page Type',
            'metaTitle' => 'Meta Title',
            'metaKeyword' => 'Meta Keyword',
            'metaDescriptions' => 'Meta Descriptions',
            'pageContent' => 'Page Content',
            'image' => 'Top Image'
        ];
    }
}
