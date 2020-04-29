<?php
namespace frontend\models;
use Yii;
use yii\db\Query;

/**
* This is the model class for table "card_information".
*
* @property integer $id
*/
class Account extends \yii\db\ActiveRecord {

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
            [['card_holder_name','card_number','card_type','card_exp_month','card_exp_year'], 'required'],
            [['card_holder_name','card_bank_name'], 'string', 'max' => 40],
            [['card_bank_name'],'safe'],
            [['card_number','card_cvv_number'], 'number'],
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

		$data = new Account();
		$data->card_holder_name 	= $this->card_holder_name;
		$data->card_bank_name 		= $this->card_bank_name;
		$data->card_number 		= $this->card_number;
		$data->card_type 		= $this->card_type;
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
	public function updatedata($id) {
		if (!$this->validate()) {
			return null;
		}

        $this->scenario 			= 'update';
        $data 						= Account::findOne(['card_user_id' => $id]);
		$data->card_holder_name 	= $this->card_holder_name;
		$data->card_bank_name 		= $this->card_bank_name;
		$data->card_number 		= $this->card_number;
		$data->card_type 		= $this->card_type;
		$data->card_exp_month 		= $this->card_exp_month;
		$data->card_exp_year 		= $this->card_exp_year;
		return $data->save();
	}

	/**
	* getOwner
	* @param N/A
	* @return array
	*/     
    public function getOwner()
    {
        return $this->hasOne(\backend\models\users\Users::className(), ['id' => 'card_user_id']);
    }
}

?>
