<?php
namespace backend\models\page;

use yii\base\Model;
use yii\web\UploadedFile;
use yii;

############### Image Manipulation #################
use yii\imagine\Image;
use Imagine\Gd;
use Imagine\Image\Box;
use Imagine\Image\BoxInterface;

class ImageUpload extends Model
{
    /**
     * @var UploadedFile
     */
    public $imageUpload;

    public function rules()
    {        
        return [            
            [['imageUpload'], 'file', 'skipOnEmpty' => true, 'extensions' => 'jpeg, jpg, png', 'maxSize'=>1024*1024*5, "tooBig" => "The file \"{file}\" is too big. Its size cannot exceed  5 MB.", 'on'=>'update-profile'],//size in bytes
        ];
    }   
    
    public function uploadfile()
    {
        //Set the path that the file will be uploaded to
        $uploadPath = Yii::getAlias('@common') .'/uploads/page/';

        if ($this->validate()) {

            ###CHECK FOR TRYING TO GET PROPERTY OF NON OBJECT ERROR FOR OPTIONAL FILE UPLOADS WHEN NOT UPLOADING THE FILE######
            if(empty($this->imageUpload) )
            {
                return false;
            }
            
            $fileNameWithoutExtension = $this->imageUpload->baseName . "_" . time();
            $fileNameWithExtension = $fileNameWithoutExtension . '.' . $this->imageUpload->extension;
            $this->imageUpload->saveAs($uploadPath . $fileNameWithExtension);
            return $fileNameWithExtension;
            
        } else {
            return false;
        }
    }    
}
?>
