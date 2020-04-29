<?php
namespace backend\models\sitters;

use yii\base\Model;
use yii\web\UploadedFile;
use yii;

############### Image Manipulation #################
use yii\imagine\Image;
use Imagine\Gd;
use Imagine\Image\Box;
use Imagine\Image\BoxInterface;

class UserProfilePictureUpload extends Model
{
    /**
     * @var UploadedFile
     */
    public $usrProfileImage;
    public $profile_image;

    public function rules()
    {        
        return [
            [['profile_image'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpeg, jpg', 'maxSize'=>1024*1024*5, "tooBig" => "The file \"{file}\" is too big. Its size cannot exceed  5 MB.", 'on'=>'update-profile'],//size in bytes    
             [['profile_image'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpeg, jpg', 'maxSize'=>1024*1024*5, "tooBig" => "The file \"{file}\" is too big. Its size cannot exceed  5 MB.",'on'=>'signup'],        
        ];
    }
     public function attributeLabels() {
        return ['profile_image'		 => 'Profile Image'];
        
        }
    
    public function upload()
    {
        //Set the path that the file will be uploaded to
        $uploadPath = Yii::getAlias('@common') .'/uploads/profile/';
        if ($this->validate()) {         
            ###CHECK FOR TRYING TO GET PROPERTY OF NON OBJECT ERROR FOR OPTIONAL FILE UPLOADS WHEN NOT UPLOADING THE FILE######
            if(empty($this->profile_image) )
            {
                return false;
            }
            
            $fileNameWithoutExtension = $this->profile_image->baseName . "_" . time();
            $fileNameWithExtension = $fileNameWithoutExtension . '.' . $this->profile_image->extension;
            
            $this->profile_image->saveAs($uploadPath . $fileNameWithExtension);
            
            $thumbnail260x260Name = $uploadPath . $fileNameWithoutExtension . "_260x260" . "." . $this->profile_image->extension;
            return array( 'originalImage'=>$fileNameWithExtension );
        } else {
            return false;
        }
    } 
}
?>
