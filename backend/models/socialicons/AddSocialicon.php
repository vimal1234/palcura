<?php
namespace backend\models\socialicons;
use backend\models\socialicons\Socialicons;
use yii\base\Model;
use Yii;

/**
 * AddDocumentForm
 */
class AddSocialicon extends Model {
	
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
    public function attributeLabels() {
        return [
            'name' 			=> 'Name',
            'image' 		=> 'Image',
            'description' 	=> 'Description',
            'url' 			=> 'Url',
            'date_created' 	=> 'Date',
            'status' 		=> 'Status',
        ];
    }
    
    public function addsocialicon() {
        if (!$this->validate()) {
            return null;
        }

        $data 					= new Socialicons();
        //$data->image   			= $this->image;        
        $data->name 			= $this->name;
        $data->url   			= $this->url;
        $data->description   	= $this->description;
        $data->status 			= ACTIVE;
        return $data->save() ? $data : null;
    }    
}
