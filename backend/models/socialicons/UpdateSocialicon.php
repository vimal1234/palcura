<?php
namespace backend\models\socialicons;
use yii\base\Model;
use Yii;

/**
* UpdateSocialicon
*/
class UpdateSocialicon extends Socialicons {
    public $name;
    public $image;
    public $url;
    public $description;
    public $date_created;
    public $status;

	/**
    * @inheritdoc
    */
    public function rules() {
        return [
            [['name','description','url'], 'required'],
			 ['name', 'string', 'max' => 60],   
			 ['description', 'string', 'max' => 250],            
			 ['url', 'url', 'defaultScheme' => 'https'],            
        ];
    }
    
    /**
    * @inheritdoc
    */
    public static function findIdentity($id) {
        return static::findOne(['id' => $id]);
    }
    
    /**
    * update socialicon.
    *
    * @return User|null the saved model or null if saving fails
    */
	public function updateSocialicon($id) {
        if (!$this->validate()) {
            return null;
        }
        $data	 				= Socialicons::findOne(['id' => $id]);
        //$data->image   			= $this->image;        
        $data->name 			= $this->name;
        $data->url   			= $this->url;
        $data->description   	= $this->description;
        return $data->save() ? $data : null;
	}
}
