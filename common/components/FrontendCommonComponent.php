<?php
namespace common\components;
use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\db\Query;

class FrontendCommonComponent extends Component {

	public function frontendbanner() {
        return \backend\models\banner\Banner::find()->where(['status' => '1'])->asArray()->all();
	}

	public function membershipPlans() {
		return \backend\models\memberships\Package::find()->where(['status' => '1'])->asArray()->all();
	}
}
