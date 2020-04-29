<?php
namespace frontend\models;
use frontend\models\users\Users;
use yii\base\Model;
use Yii;
use yii\db\Query;

class Vaccinationdetails extends \yii\db\ActiveRecord {
    public $vaccination_doc;
    public $pet_name;
    public $vaccination_validity;
    public $my_pet_weight;
    public $my_pet_age;
    public $my_pet_sex;
    public $emergency_contact;

/**
	* @inheritdoc
	*/
	public static function tableName() {
		return 'pet_vaccination_details';
	}
	/**
	* @inheritdoc
	*/
    public function rules() {
        return [
           [['pet_name','vaccination_doc','vaccination_validity','my_pet_weight','my_pet_age','my_pet_sex','emergency_contact'],'safe'],
           [['pet_name','vaccination_validity'],'required','on'=>'upf'],
        ];
    }
    

	/**
	* @inheritdoc
	*/
    public function attributeLabels() {
        return [
            'vaccination_doc'		=> 'Vaccination Document',
            'vaccination_validity'	=> 'Vaccination Expiration date',
            'pet_name'	 => 'Pet Name',
            'my_pet_weight'	 => 'Pet Weight',
            'my_pet_age'	 => 'Pet Age',
            'my_pet_sex'	 => 'Sex', 
            'emergency_contact'=> 'Emergency Contact'                        	                    		
        ];
    }
    
    public function saveVacc($data,$vaccinationdoc){
    $userid = Yii::$app->user->identity->id;
     $connection = \Yii::$app->db;  
     if(!empty($data['vaccination_validity'])) { 
     $data['vaccination_validity'] = date('Y-m-d',strtotime($data['vaccination_validity']));
     }else{
     $data['vaccination_validity'] = date('Y-m-d');
     }
		$model = $connection->createCommand('Insert into pet_vaccination_details (vaccination_doc, user_id, vaccination_validity) values("'.$vaccinationdoc.'","'.$userid.'","'.$data['vaccination_validity'].'")');
		//$data = $model->queryAll();
			if($model->execute()){
			 return true;
			}else{
			return false;
			}  
    }
    
    /*public function updateVacc($data,$vaccid,$docname){
    $userid = Yii::$app->user->identity->id;
    $data['vaccination_validity'] = date('Y-m-d',strtotime($data['vaccination_validity']));
   	$connection = \Yii::$app->db;    
		$model = $connection->createCommand('Update pet_vaccination_details set vaccination_doc="'.$docname.'",vaccination_validity="'.$data['vaccination_validity'].'",pet_name="'.$data['pet_name'].'",my_pet_age="'.$data['my_pet_age'].'",my_pet_weight="'.$data['my_pet_weight'].'",my_pet_sex="'.$data['my_pet_sex'].'" where id="'.$vaccid.'"');
		$data = $model->execute();
		if(count($data>0)){		
		return true;
		}else{		
		return false;
		}
    }*/
    
    public function updateVacc($data,$vaccid,$docname){
    $userid = Yii::$app->user->identity->id;
    if(!empty($data['vaccination_validity'])) { 
     $data['vaccination_validity'] = date('Y-m-d',strtotime($data['vaccination_validity']));
     }else{
     $data['vaccination_validity'] = date('Y-m-d');
     }
   	$connection = \Yii::$app->db;    
		$model = $connection->createCommand('Update pet_vaccination_details set vaccination_doc="'.$docname.'",vaccination_validity="'.$data['vaccination_validity'].'" where id="'.$vaccid.'"');
		$data = $model->execute();
		if(count($data>0)){		
		return true;
		}else{		
		return false;
		}
    }
    
}
