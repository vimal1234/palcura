<?php
namespace backend\models\sitters;

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
    public $upload_documents;
    public $upload_images;
    public $upload_home_images;
    public $upload_booking_images;
    public $imageuploadtype;
    public $picture_of_pet;
    public $rentinpetselecttype;
    
    public function rules() {
        return [
            [['upload_documents','upload_home_images','upload_booking_images','imageuploadtype'], 'required',  'when' => function($model) {
				return $model->imageuploadtype == SITTER;
            },'whenClient' => "function (attribute, value) { return $('#uploads-imageuploadtype').val() == '2'; }"],        
             [['upload_documents'], 'file', 'skipOnEmpty'	 => true, 'checkExtensionByMimeType'=>false, 'maxSize'	=>1024 * 1024 * 5, 'on'=>'upb', 'maxFiles' => 3],
			 [['upload_images'], 'file', 'skipOnEmpty' 		 => true, 'extensions' => 'png,jpg,jpeg', 'maxSize'			=>1024 * 1024 * 5, 'on'=>'upc','maxFiles' => 4],			 
			 [['upload_home_images'], 'file', 'skipOnEmpty'  => true, 'extensions' => 'png,jpg,jpeg', 'maxSize'			=>1024 * 1024 * 5, 'on'=>'upd','maxFiles' => 2],
			 [['upload_booking_images'], 'file', 'skipOnEmpty' 		 => true, 'extensions' => 'png,jpg,jpeg', 'maxSize'			=>1024 * 1024 * 5, 'on'=>'upbooking','maxFiles' => 4],
			 [['picture_of_pet'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png,jpg,jpeg', 'maxSize' => 1024 * 1024 * 5, 'on' => 'upk', 'maxFiles' => 4],
			  [['picture_of_pet'], 'required',  'when' => function($model) {
				return $model->rentinpetselecttype == 1;
            },'whenClient' => "function (attribute, value) { return $('#uploads-rentinpetselecttype').val() == '1'; }"], 
			 
			  			 			 
        ];
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

public function attributeLabels() {
        return [
        'upload_home_images'=> 'House Image',
             ];
    }
 
    public function uploadB($nwArr=0) {
        ########## Set the path that the file will be uploaded to
        $uploadPath = Yii::getAlias('@common') .'/uploads/documents/';
        if ($this->validate()) {
			$returnArr = array();
			$i=1;		
            foreach ($this->upload_documents as $file) {
					$fileNameWithoutExtension = $i.time();
					$fileNameWithExtension = $fileNameWithoutExtension . '.' . $file->extension;
					if($file->saveAs($uploadPath . $fileNameWithExtension)) {
						$returnArr[] = $fileNameWithExtension;
					}
					$i++;		
            }
			return array('originalImage' => $returnArr);
        } else {
            return false;
        }
    }

    public function uploadC($nwArr=0) {
        ########## Set the path that the file will be uploaded to
        $uploadPath = Yii::getAlias('@common') .'/uploads/images/';
        if ($this->validate()) {
			$returnArr = array();
			$i=1;
            foreach ($this->upload_images as $file) {
					$fileNameWithoutExtension = $i.time();
					$fileNameWithExtension = $fileNameWithoutExtension . '.' . $file->extension;
					if($file->saveAs($uploadPath . $fileNameWithExtension)) {
						$returnArr[] = $fileNameWithExtension;
					}
					$i++;
            }
			return array('originalImage' => $returnArr);
        } else {
            return false;
        }
    }
    
    public function uploadD($nwArr=0) {
        ########## Set the path that the file will be uploaded to
        $uploadPath = Yii::getAlias('@common') .'/uploads/images/';
        if ($this->validate()) {
			$returnArr = array();
			$i=1;
            foreach ($this->upload_home_images as $file) {
					$fileNameWithoutExtension = $i.time();
					$fileNameWithExtension = $fileNameWithoutExtension . '.' . $file->extension;
					if($file->saveAs($uploadPath . $fileNameWithExtension)) {
						$returnArr[] = $fileNameWithExtension;
					}
					$i++;
            }
			return array('originalImage' => $returnArr);
        } else {
            return false;
        }
    }
    
    public function uploadK($nwArr=0) {
        ########## Set the path that the file will be uploaded to
        $uploadPath = Yii::getAlias('@common') .'/uploads/images/';
        if ($this->validate()) {
			$returnArr = array();
			$i=1;
            foreach ($this->picture_of_pet as $file) {
					$fileNameWithoutExtension = $i.time();
					$fileNameWithExtension = $fileNameWithoutExtension . '.' . $file->extension;
					if($file->saveAs($uploadPath . $fileNameWithExtension)) {
						$returnArr[] = $fileNameWithExtension;
					}
					$i++;
            }
			return array('originalImage' => $returnArr);
        } else {
            return false;
        }
    }
    
    public function uploadBookings($nwArr=0) {
        ########## Set the path that the file will be uploaded to
        $uploadPath = Yii::getAlias('@common') .'/uploads/bookings/';
        if ($this->validate()) {
			$returnArr = array();
			$i=1;
            foreach ($this->upload_booking_images as $file) {
					$fileNameWithoutExtension = $i.time();
					$fileNameWithExtension = $fileNameWithoutExtension . '.' . $file->extension;
					if($file->saveAs($uploadPath . $fileNameWithExtension)) {
						$returnArr[] = $fileNameWithExtension;
					}
					$i++;
            }
			return array('originalImage' => $returnArr);
        } else {
            return false;
        }
    }    
}
?>
