<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\Admin;

/**
* ContactForm is the model behind the contact form.
*/
class Calender extends Model {

    public $unavailabilty;

	/**
	* @inheritdoc
	*/
    public function rules() {
        return [
            [['unavailabilty'], 'required'],
        ];
    }

	/**
	* @inheritdoc
	*/
	public function attributeLabels() {
		return [
			'unavailabilty' => Yii::t('yii','Date'),          
		];
	}
}
