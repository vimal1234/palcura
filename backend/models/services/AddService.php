<?php
namespace backend\models\services;
use backend\models\services\Services;
use yii\base\Model;
use Yii;

/**
 * AddDocumentForm
 */
class AddService extends Model {
	
    public $name;
    public $image;
    public $description;
    public $datetime;
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
    public function attributeLabels() {
        return [
            'name' => 'Name',
            'image' => 'Image',
            'description' => 'Description',
            'date_created' => 'Date',
            'status' => 'Status',
            'pet_type' => 'Pet Type',
        ];
    }
    
    public function addservice() {
        if (!$this->validate()) {
            return null;
        }

        $service 				= new Services();
        $service->name 			= $this->name;
        $service->description   = $this->description;
        $service->pet_type		= implode(',',$this->pet_type);
        $service->status 		= ACTIVE;
        return $service->save() ? $service : null;
    }    
}
