<?php
namespace frontend\models;
use yii\db\Query;
use Yii;

/**
* Contactus model
*
*/
class Contactus extends \yii\db\ActiveRecord {
	
	public $reCaptcha;
	/**
	* @inheritdoc
	*/
	public static function tableName() {
		return 'website_queries';
	}

	/**
	* @inheritdoc
	*/
	public function rules() {
		return [
			[['name','email', 'description','subject'], 'required'],
			[['phone'],'safe'],
		    [['reCaptcha'], \yii\recaptcha\ReCaptchaValidator::className(), 'secret' => SECRET_KEY, 'uncheckedMessage' => 'Please verify that you are not a robot.']
		];
	}

	/**
	* @inheritdoc
	*/
    public function attributeLabels() {
        return [
            'name' 	=> Yii::t('yii','Name'),
            'email' 	=> Yii::t('yii','Email address'),
            'phone' 	=> Yii::t('yii',''),
            'subject' 	=> Yii::t('yii','Subject'),
            'description' 	=> Yii::t('yii','Message'),
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
		$model 			= new Contactus;
		$model->name		= $this->name;
		$model->email 		= $this->email;
		$model->phone 		= $this->phone;
		$model->description = $this->description;
		$model->subject 	= $this->subject;		
		if($model->save()) { 
			return true;
		} else {
			return false;
		}
	}
}
?>
