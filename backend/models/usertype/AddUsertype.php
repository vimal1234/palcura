<?php
namespace backend\models\usertype;
use backend\models\usertype\Usertype;
use yii\base\Model;
use Yii;

/**
 * AddUsertype
 */
class AddUsertype extends Model {
	
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
    public function attributeLabels() {
        return [
            'name' => 'Name',
            'description' => 'Description',
            'datetime' => 'Date',
            'status' => 'Status',
        ];
    }
    
    public function savedata() {
        if (!$this->validate()) {
            return null;
        }

        $data 				= new Usertype();
        $data->name 		= $this->name;
        $data->description  = $this->description;
        $data->status 		= $this->status;
        return $data->save() ? $data : null;
    }    
}
