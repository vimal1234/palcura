<?php

namespace frontend\models;

use yii\base\Model;
use yii\web\UploadedFile;
use yii;

############### Image Manipulation #################
use yii\imagine\Document;
use Imagine\Gd;
use Imagine\Image\Box;
use Imagine\Image\BoxInterface;

class Uploads extends Model {

    /**
     * @var UploadedFile[]
     */
    public $bookingid;
    public $upload_documents;
    public $upload_images;
    public $upload_home_images;
    public $upload_booking_images;
    public $vaccination_doc;
  	public $picture_of_pet;
public $renting_a_pet;

    public function rules() {
        return [
           // [['upload_documents'], 'file', 'skipOnEmpty' => true, 'checkExtensionByMimeType' => false, 'maxSize' => 1024 * 1024 * 5, 'on' => 'upb', 'maxFiles' => 4],
            [['upload_images'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png,jpg,jpeg', 'maxSize' => 1024 * 1024 * 5, 'on' => 'upc', 'maxFiles' => 100],
            [['upload_home_images'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png,jpg,jpeg', 'maxSize' => 1024 * 1024 * 5, 'on' => 'upd', 'maxFiles' => 4],
            [['upload_booking_images'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png,jpg,jpeg,mp4,mov', 'maxSize' => 1024 * 1024 * 100, 'on' => 'upbooking', 'maxFiles' => 4],
            [['vaccination_doc'], 'file', 'skipOnEmpty' => true, 'extensions' => 'doc,docx,ppt,pdf,txt,png,jpg,jpeg', 'maxSize' => 1024 * 1024 * 10, 'on' => 'upf', 'maxFiles' => 1],
            
            //[['upload_documents'], 'file', 'skipOnEmpty'	 => false, 'extensions' => 'doc,docx,ppt,pdf,txt,png,jpg,jpeg', 'maxSize'	=>1024 * 1024 * 5, 'on'=>'upg', 'maxFiles' => 3],
           // [['upload_documents'], 'file','skipOnEmpty' => true,'on'=>'upg', 'maxFiles' => 1],
            [['upload_home_images'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png,jpg,jpeg', 'maxSize' => 1024 * 1024 * 5, 'on' => 'uph', 'maxFiles' => 4],
             [['picture_of_pet'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png,jpg,jpeg', 'maxSize' => 1024 * 1024 * 5, 'on' => 'upk', 'maxFiles' => 4],
            // [['picture_of_pet'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png,jpg,jpeg', 'maxSize' => 1024 * 1024 * 5, 'on' => 'uppetpic', 'maxFiles' => 4],
			//['upload_documents', 'verifydoc','skipOnEmpty' => false],
			['picture_of_pet', 'verifypetpic'],
			['upload_home_images', 'verifyhouseimages','skipOnEmpty' => true],
			
        ];
    }

	
	public function verifydoc()
	{ 
		$id = Yii::$app->user->getId();
		$docinformation	= Yii::$app->commonmethod->getUserActiveDocuments($id,1);
		//if(empty($docinformation) ||count($docinformation) < 2){
//print_r($_FILES['Uploads']['name']['upload_documents'][0]);

		$houseimage = $_FILES['Uploads']['name']['upload_documents'];
		 //print_r($houseimage);
		 //exit;
		if($houseimage[0]==''){
		//echo "sasas";
		//exit;
		$this->addError('upload_documents', Yii::t('yii', 'Driver’s License or Photo ID required.'));
		}
		//}	
	}
    /*
      public function upload() {
      ########## Set the path that the file will be uploaded to
      $uploadPath = Yii::getAlias('@common') .'/uploads/properties/';
      if ($this->validate()) {
      $fileNameWithoutExtension = "agariv_" . time();
      $fileNameWithExtension = $fileNameWithoutExtension . '.' . $this->upload_video->extension;
      $this->upload_video->saveAs($uploadPath . $fileNameWithoutExtension . '.' . $this->upload_video->extension);
      return array('originalImage' => $fileNameWithExtension, 'file_extention' => $this->upload_video->extension);
      } else {
      return false;
      }
      }
     */
	
public function verifypetpic(){
    $id 			= Yii::$app->user->getId();
    $petinformation	=	Yii::$app->commonmethod->getUserPetsInfo($id);
    
    $rentingpet = $_POST['Uploads']['renting_a_pet'];

		if($rentingpet == 1 && empty($petinformation)){
		$petpic = $_FILES['Uploads']['name']['picture_of_pet'];
	   
			foreach($petpic as $k=>$v){
			if(isset($v) && empty($v)){
					$this->addError('picture_of_pet', Yii::t('yii', 'Please select a file.'));							
				}
			}
		}
    }

	public function verifyhouseimages(){
     
      $id = Yii::$app->user->getId();
      $docinformation	=	Yii::$app->commonmethod->getUserActiveDocuments($id,3);
		  if(empty($docinformation) ||count($docinformation) < 1){
		  $houseimage = $_FILES['Uploads']['name']['upload_home_images'];
		 
				if($houseimage[0]==''){
					$this->addError('upload_home_images', Yii::t('yii', 'Minimum two inside house pictures are required.'));
				}
		   }	
   	 }

    public function uploadB($nwArr = 0) {
		
        ########## Set the path that the file will be uploaded to
        $uploadPath = Yii::getAlias('@common') . '/uploads/documents/';
        //if ($this->validate()) {
            $returnArr = array();
            $i=1;
            foreach ($this->upload_documents as $file) {
                $fileNameWithoutExtension = $i . time();
                $fileNameWithExtension = $fileNameWithoutExtension . '.' . $file->extension;
                if ($file->saveAs($uploadPath . $fileNameWithExtension)) {
                    $returnArr[] = $fileNameWithExtension;
                }
                $i++;
            }
            return array('originalImage' => $returnArr);
        //} else {
          //  return false;
        //}
    }

    public function uploadC($nwArr = 0) {
        ########## Set the path that the file will be uploaded to
        $uploadPath = Yii::getAlias('@common') . '/uploads/images/';
        if ($this->validate()) {
            $returnArr = array();
            $i = 1;
            foreach ($this->upload_images as $file) {
                $fileNameWithoutExtension = $i . time();
                $fileNameWithExtension = $fileNameWithoutExtension . '.' . $file->extension;
                if ($file->saveAs($uploadPath . $fileNameWithExtension)) {
                    $returnArr[] = $fileNameWithExtension;
                }
                $i++;
            }
            return array('originalImage' => $returnArr);
        } else {
            return false;
        }
    }

    public function uploadD($nwArr = 0) {
        ########## Set the path that the file will be uploaded to
        $uploadPath = Yii::getAlias('@common') . '/uploads/images/';
        if ($this->validate()) {
            $returnArr = array();
            $i = 1;
            foreach ($this->upload_home_images as $file) {
                $fileNameWithoutExtension = $i . time();
                $fileNameWithExtension = $fileNameWithoutExtension . '.' . $file->extension;
                if ($file->saveAs($uploadPath . $fileNameWithExtension)) {
                    $returnArr[] = $fileNameWithExtension;
                }
                $i++;
            }
            return array('originalImage' => $returnArr);
        } else {
            return false;
        }
    }

    public function uploadBookings($nwArr = 0) {
        ########## Set the path that the file will be uploaded to
        $uploadPath = Yii::getAlias('@common') . '/uploads/bookings/';
        if ($this->validate()) {
            $returnArr = array();
            $i = 1;
            foreach ($this->upload_booking_images as $file) {
                $fileNameWithoutExtension = $i . time();
                $fileNameWithExtension = $fileNameWithoutExtension . '.' . $file->extension;
                if ($file->saveAs($uploadPath . $fileNameWithExtension)) {
                    $returnArr[] = $fileNameWithExtension;
                }
                $i++;
            }
            return array('originalImage' => $returnArr);
        } else {
            return false;
        }
    }

    public function uploadF() {
        ########## Set the path that the file will be uploaded to
        $uploadPath = Yii::getAlias('@common') . '/uploads/documents/';
        if ($this->validate()) {

            $fileNameWithoutExtension = $this->vaccination_doc->baseName . "_" . time();
            $fileNameWithExtension = $fileNameWithoutExtension . '.' . $this->vaccination_doc->extension;
            $this->vaccination_doc->saveAs($uploadPath . $fileNameWithExtension);
            $thumbnail260x260Name = $uploadPath . $fileNameWithoutExtension . "_260x260" . "." . $this->vaccination_doc->extension;

            return array('docname' => $fileNameWithExtension);
        } else {
            echo "ascgbb";
            die;
            return false;
        }
    }
    
     /*public function uploadK($nwArr = 0) {
        ########## Set the path that the file will be uploaded to
        $uploadPath = Yii::getAlias('@common') . '/uploads/petimages/';
        
        if ($this->validate()) {
            $returnArr = array();
            $i = 1;
            foreach ($this->picture_of_pet as $file) { 
              
                $fileNameWithoutExtension = $i . time();
                $fileNameWithExtension = $fileNameWithoutExtension . '.' . $file->extension;
                if ($file->saveAs($uploadPath . $fileNameWithExtension)) {
                    $returnArr[] = $fileNameWithExtension;
                }
                $i++;
            }
      die;
            return array('originalImage' => $returnArr);
        } else {
            return false;
        }
    }*/
    public function uploadK($nwArr = 0) {
        ########## Set the path that the file will be uploaded to
        $uploadPath = Yii::getAlias('@common') . '/uploads/petimages/';
        
        if ($this->validate()) {
        $returnArr = array();
        $petpics = $_FILES['Uploads'];
        $allpics = $petpics['size']['picture_of_pet'];
        $i = 0;
        $imagename = null;  
          foreach($allpics as $va){
		      if($va > 0){
		          
		      $target_dir = $uploadPath;
		      $name = $petpics['name']['picture_of_pet'][$i];
		       
		      $tmp_name = $petpics['tmp_name']['picture_of_pet'][$i];
		                
		        $temp = explode(".", $name);
				$newfilename = uniqid(). '.' . end($temp);        
				$target_file = $target_dir . $newfilename;
				if (move_uploaded_file($tmp_name, $target_file)) {
				 $imagename =  $newfilename;
				}

		      }else{
		       $imagename = null;        
		      }
          $returnArr[] = $imagename;
          $i++;
          }   
            return array('originalImage' => $returnArr);
        } else {
            return false;
        }
    }
    

}

?>
