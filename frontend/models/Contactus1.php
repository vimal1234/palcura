<?php
namespace frontend\models;
use yii\db\Query;
use Yii;

/**
* Contactus model
*
*/
class Contactus1 extends \yii\db\ActiveRecord {
	
	//public $reCaptcha;
	public $accept_terms;
	/**
	* @inheritdoc
	*/
	public static function tableName() {
		return 'website_queriesnear';
	}

	/**
	* @inheritdoc
	*/
	public function rules() {
		return [
			[['zip_code','email','subject'], 'required'],
			[['name'],'safe'],
			 //array('accept_terms', 'compare', 'compareValue' => 1, 'message' => Yii::t('yii','You should accept the terms and conditions to register with us.')),
		    //[['reCaptcha'], \yii\recaptcha\ReCaptchaValidator::className(), 'secret' => SECRET_KEY, 'uncheckedMessage' => 'Please verify that you are not a robot.']
		];
	}

	/**
	* @inheritdoc
	*/
    public function attributeLabels() {
        return [
            'name' 	=> Yii::t('yii','Name'),
            'email' 	=> Yii::t('yii','Email address'),
            'zip_code' 	=> Yii::t('yii','zip code'),
            'subject' 	=> Yii::t('yii','Subject'),
        ];
    }

    public function saveContactInfo() {

	
		//~ $query = new Query;
		//~ $query->createCommand()->insert('website_queries', ['name' => $this->name, 'email' => $this->email, 'phone' => $this->phone, 'description' => $this->description, 'subject' => $this->subject])->execute();
		//~ $id = Yii::$app->db->getLastInsertID();
		//~ if($id){
			//~ return true;
		//~ } else {
			//~ return false;
		//~ }		
		$model 			= new Contactus1;
		$model->name		= $this->name;
		$model->email 		= $this->email;
		$model->zip_code 	= $this->zip_code;
		$model->subject 	= $this->subject;	
	
		
		
		if($model->save()) { 
			return true;
		} else {
			return false;
		}
	}
}
?>
