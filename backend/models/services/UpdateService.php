<?php
namespace backend\models\services;
use yii\base\Model;
use Yii;

/**
* UpdateService
*/
class UpdateService extends Services {
    public $name;
    public $description;
    public $date_created;
    public $status;
	public $pet_type;
	/**
    * @inheritdoc
    */
    public function rules() {
        return [
			 [['name','description','pet_type'], 'required'], 
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
	public function updateService($id) {
        if (!$this->validate()) {
            return null;
        }
        $service 				= Services::findOne(['id' => $id]);
        $service->name 			= $this->name;
        $service->description   = $this->description;
        $service->pet_type		= implode(',',$this->pet_type);
        return $service->save() ? $service : null;
	}
}
