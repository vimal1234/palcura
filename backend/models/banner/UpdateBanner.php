<?php
namespace backend\models\banner;

use yii\base\Model;
use Yii;

/**
 * UpdateBanner
 */
class UpdateBanner extends Banner
{
    public $title;
    public $bannerImage;
    public $description;
    public $dateCreated;
    public $status;
	/**
     * @inheritdoc
     */
    public function rules()
    {
        return [
			 [['title', 'description'], 'required'],
			 ['title', 'string', 'max' => 60],   
			 ['description', 'string', 'max' => 250],			 
        ];
        
    }

    
      /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }
    
    /**
     * update banner.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function updateBanner($id)
    {
        if (!$this->validate()) {
            return null;
        }

        $banner = Banner::findOne(['id' => $id]);
        $banner->title = $this->title;
        $banner->description  = $this->description;
        $banner->status 	    = '1';
        if($this->bannerImage != '' ) 
        $banner->bannerImage 	= $this->bannerImage;

        

        return $banner->save() ? $banner : null;
     }
}
