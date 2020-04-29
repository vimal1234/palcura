<?php
namespace backend\models\banner;

use yii\base\Model;
use yii\web\UploadedFile;
use yii;

############### Image Manipulation #################
use yii\imagine\Image;
use Imagine\Gd;
use Imagine\Image\Box;
use Imagine\Image\BoxInterface;

class BannerVideoUpload extends Model
{
    /**
     * @var UploadedFile
     */
    public $bannerVideo;

    public function rules()
    {        
        return [
            [['bannerVideo'], 'file', 'skipOnEmpty' => true, 'extensions' => 'mp4', 'maxSize'=>1024*1024*25, 'on'=>'update-video'],###size in bytes
            [['bannerVideo'], 'file', 'skipOnEmpty' => true, 'extensions' => 'mp4', 'maxSize'=>1024*1024*25, 'on'=>'update-banner'],###size in bytes
        ];
    }
    
    public function upload()
    {
        //Set the path that the file will be uploaded to
        $uploadPath = Yii::getAlias('@common') .'/uploads/banner/';
        if ($this->validate()) {
            
            $fileNameWithoutExtension = $this->bannerVideo->baseName . "_" . time();
            $fileNameWithExtension = $fileNameWithoutExtension . '.' . $this->bannerVideo->extension;
            
            $this->bannerVideo->saveAs($uploadPath . $fileNameWithExtension);
                        
            return $fileNameWithExtension;
        } else {
            return false;
        }
    } 
}
?>
