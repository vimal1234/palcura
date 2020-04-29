<?php
namespace backend\models\usertype;

use yii\base\Model;
use Yii;

/**
 * UpdateUsertype
 */
class UpdateUsertype extends Usertype
{
    public $name;
    public $description;
    public $datetime;
    public $status;

	/**
    * @inheritdoc
    */
    public function rules() {
        return [
			 [['name','description'], 'required'], 
			 ['name', 'string', 'max' => 60],   
			 ['description', 'string', 'max' => 250], 					            
        ];
    }
    
    /**
    * @inheritdoc
    */
    public static function findIdentity($id) {
        return static::findOne(['id' => $id]);
    }
    
    /**
    * update service.
    *
    * @return User|null the saved model or null if saving fails
    */
	public function updatedata($id) {
        if (!$this->validate()) {
            return null;
        }
        $data 				 = Usertype::findOne(['id' => $id]);
        $data->name 		 = $this->name;
        $data->description   = $this->description;
        return $data->save() ? $data : null;
	}
}
