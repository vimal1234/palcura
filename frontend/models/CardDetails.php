<?php
namespace frontend\models;
use Yii;
use yii\db\Query;

/**
* This is the model class for table "card_information".
*
* @property integer $id
*/
class CardDetails extends \yii\db\ActiveRecord {

	/**
	* @ Function Name		: tableName
	* @ Function Params	: NA
	* @ Function Purpose 	: get table name
	* @ Function Returns	: String
	*/
    public static function tableName() {
        return 'card_information';
    }

	/**
	* @ Function Name		: rules
	* @ Function Params	: NA 
	* @ Function Purpose 	: define the validations rules to apply on submitted form data
	* @ Function Returns	: array
	*/
    public function rules() {
        return [
            [['card_holder_name','card_bank_name','card_number','card_cvv_number','card_exp_month','card_exp_year'], 'required'],
        ];
    }

	/**
	* savedata.
	*
	* @return Cards|null the saved model or null if saving fails
	*/
	public function savedata() {
		if (!$this->validate()) {
			return null;
		}

		$data = new CardDetails();
		$data->card_holder_name 	= $this->card_holder_name;
		$data->card_bank_name 		= $this->card_bank_name;
		$data->card_number 			= $this->card_number;
		$data->card_cvv_number 		= $this->card_cvv_number;
		$data->card_exp_month 		= $this->card_exp_month;
		$data->card_exp_year 		= $this->card_exp_year;
		$data->card_user_id 		= Yii::$app->user->getId();
		return $data->save();
	}
	
	/**
	* updatedata.
	*
	* @return Cards|null the saved model or null if saving fails
	*/
	public function updatedata() {
		if (!$this->validate()) {
			return null;
		}

        $this->scenario 			= 'update';
        $data 						= CardDetails::findOne(['card_user_id' => $id]);
		$data->card_holder_name 	= $this->card_holder_name;
		$data->card_bank_name 		= $this->card_bank_name;
		$data->card_number 			= $this->card_number;
		$data->card_cvv_number 		= $this->card_cvv_number;
		$data->card_exp_month 		= $this->card_exp_month;
		$data->card_exp_year 		= $this->card_exp_year;
		return $data->save();
	}
}
