<?php
namespace frontend\models\users;

use yii\base\Model;
use yii\web\UploadedFile;
use yii;

############### Image Manipulation #################
use yii\imagine\Image;
use Imagine\Gd;
use Imagine\Image\Box;
use Imagine\Image\BoxInterface;

class UserIdDocumentUpload extends Model
{
    /**
     * @var UploadedFile
     */
    public $usrIdDocument;

    public function rules()
    {        
        return [            
            [['usrIdDocument'], 'file', 'skipOnEmpty' => true, 'extensions' => 'pdf, doc, docx, txt', 'maxSize'=>1024*1024*5, "tooBig" => "The file \"{file}\" is too big. Its size cannot exceed  5 MB.", 'on'=>'update-profile'],//size in bytes
        ];
    }   
    
       /**
     * @inheritdoc
     */
	 public function attributeLabels() {
		return [
			 'usrIdDocument'      => Yii::t('yii','Uploaded ID documents'),
		 ];
	 }
	 
    public function uploadfile()
    {
        //Set the path that the file will be uploaded to
        $uploadPath = Yii::getAlias('@common') .'/uploads/documents/';
        if ($this->validate()) {

            $fileNameWithoutExtension = $this->usrIdDocument->baseName . "_" . time();
            $fileNameWithExtension = $fileNameWithoutExtension . '.' . $this->usrIdDocument->extension;
            $this->usrIdDocument->saveAs($uploadPath . $fileNameWithExtension);
            return $fileNameWithExtension;
            
        } else {
            return false;
        }
    }    
}
?>
