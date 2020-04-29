<?php
namespace common\models;
use yii\base\Model;
use yii\web\UploadedFile;
use yii;

############### Image Manipulation #################
use yii\imagine\Service;
use Imagine\Gd;
use Imagine\Image\Box;
use Imagine\Image\BoxInterface;

class ImageUpload extends Model
{
    /**
     * @var UploadedFile
     */
    public $image;
    public $advertisement_image;
    
    public function rules() {        
        return [
           // [['image'], 'file', 'skipOnEmpty' => true, 'extensions' => 'jpg,jpeg,png,gif', 'maxSize'=>5120000, 'on'=>'update-profile'],
           // [['advertisement_image'], 'file', 'skipOnEmpty' => true, 'extensions' => 'jpg,jpeg,png', 'maxSize'=>5120000, 'on'=>'update-profile'],
        ];
    }

    public function upload() {
        ########## Set the path that the file will be uploaded to
        $uploadPath = Yii::getAlias('@common') .'/uploads/images/';
        if ($this->validate()) {
            $fileNameWithoutExtension = "agrimg_" . time();
            $fileNameWithExtension = $fileNameWithoutExtension . '.' . $this->image->extension;
            $this->image->saveAs($uploadPath . $fileNameWithExtension);
            $thumbnail260x260Name = $uploadPath . $fileNameWithoutExtension . "_260x260" . "." . $this->image->extension;
            return array('originalImage' => $fileNameWithExtension, 'file_extention' => $this->image->extension);
        } else {
            return false;
        }
    }
    
    public function news_image_upload() {
        $uploadPath = Yii::getAlias('@common') .'/uploads/images/';
        if ($this->validate()) {
            $fileNameWithoutExtension = "news_" . time();
            $fileNameWithExtension = $fileNameWithoutExtension . '.' . $this->image->extension;
            $this->image->saveAs($uploadPath . $fileNameWithExtension);
            $thumbnail260x260Name = $uploadPath . $fileNameWithoutExtension . "_260x260" . "." . $this->image->extension;
            return array('originalImage' => $fileNameWithExtension, 'file_extention' => $this->image->extension);
        } else {
            return false;
        }
    }
    
    public function upload_advertisement_image() {
        $uploadPath = Yii::getAlias('@common') .'/uploads/images/';
        if ($this->validate()) {
            $fileNameWithoutExtension = "agrimg_" . time();
            $fileNameWithExtension = $fileNameWithoutExtension . '.' . $this->advertisement_image->extension;
            $this->advertisement_image->saveAs($uploadPath . $fileNameWithExtension);
            return array('originalImage' => $fileNameWithExtension, 'file_extention' => $this->advertisement_image->extension);
        } else {
            return false;
        }
    }
        
}
?>
