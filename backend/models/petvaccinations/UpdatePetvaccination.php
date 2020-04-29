<?php
namespace backend\models\petvaccinations;
use yii\base\Model;
use Yii;

/**
* UpdatePetvaccination
*/
class UpdatePetvaccination extends Petvaccinations {

    public $user_id;
    public $service_id;
    public $price;

	/**
    * @inheritdoc
    */
    public function rules() {
        return [
            [['service_id','user_id','price'], 'required'],
			 ['price', 'number', 'max' => 1000], 					            
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
	public function updatePetvaccination($id) {
        if (!$this->validate()) {
            return null;
        }
        $data 				= Petvaccinations::findOne(['id' => $id]);
        $data->user_id		= $this->user_id;
        $data->service_id	= $this->service_id;
        $data->price   		= $this->price;
        return $data->save() ? $data : null;
	}
}
