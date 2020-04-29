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

class BannerPictureUpload extends Model
{
    /**
     * @var UploadedFile
     */
    public $bannerImage;

    public function rules()
    {        
        return [
			[['bannerImage',],'required','on'=>['update-profile']],
            [['bannerImage'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg', 'maxSize'=>5120000],
        ];
    }
    
    public function upload()
    {
        //Set the path that the file will be uploaded to
        $uploadPath = Yii::getAlias('@common') .'/uploads/banner/';
        if ($this->validate()) {
            
            $fileNameWithoutExtension = $this->bannerImage->baseName . "_" . time();
            $fileNameWithExtension = $fileNameWithoutExtension . '.' . $this->bannerImage->extension;
            
            $this->bannerImage->saveAs($uploadPath . $fileNameWithExtension);
            
            $thumbnail260x260Name = $uploadPath . $fileNameWithoutExtension . "_260x260" . "." . $this->bannerImage->extension;
       
            return array( 'originalImage'=>$fileNameWithExtension );
        } else {
            return false;
        }
    } 
}
?>
