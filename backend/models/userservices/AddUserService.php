<?php
namespace backend\models\userservices;
use backend\models\userservices\UserServices;
use yii\base\Model;
use Yii;

/**
* AddUserService
*/
class AddUserService extends Model {

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
    public function attributeLabels() {
        return [
            'user_id' 	=> 'User',
            'service_id'=> 'Service Name',
            'price' 	=> 'Price',
        ];
    }

	/**
	* @inheritdoc
	*/    
    public function addservice() {
        if (!$this->validate()) {
            return null;
        }

        $data 				= new UserServices();
        $data->user_id		= $this->user_id;
        $data->service_id	= $this->service_id;
        $data->price   		= $this->price;
        $data->status 		= ACTIVE;
        return $data->save() ? $data : null;
    }
}
