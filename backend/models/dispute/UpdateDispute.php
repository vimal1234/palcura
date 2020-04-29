<?php
namespace backend\models\dispute;
use yii\base\Model;
use Yii;

/**
* UpdateDispute
*/
class UpdateDispute extends Dispute {
    public $title;
    public $form_type;
    public $description;
    public $admin_comment;
    public $paid_charges;
    public $verified_by_admin;
    public $status;
    public $date_created;

	/**
    * @inheritdoc
    */
    public function rules() {
        return [
			 [['title','admin_comment','verified_by_admin','paid_charges'], 'required'], 
			 ['title', 'string', 'max' => 60],   
			 ['admin_comment', 'string', 'max' => 250], 					            
        ];
    }
    
    /**
    * @inheritdoc
    */
    public static function findIdentity($id) {
        return static::findOne(['id' => $id]);
    }

    /**
    * update dispute.
    *
    * @return User|null the saved model or null if saving fails
    */
	public function updatedata($id) {
        if (!$this->validate()) {
            return null;
        }
        $data 						= Dispute::findOne(['id' => $id]);
        $data->title 				= $this->title;
        $data->admin_comment  		= $this->admin_comment;
        $data->verified_by_admin  	= $this->verified_by_admin;
        $data->paid_charges  		= $this->paid_charges;
        return $data->save() ? $data : null;
	}
}
