<?php

namespace backend\models\banner;

use backend\models\banner\Banner;
use yii\base\Model;
use Yii;

/**
 * AddBannerForm
 */
class AddBannerForm extends Model {

    public $title;
    public $description;
    public $dateCreated;
    public $status;
    public $bannerImage;
    

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['title', 'description'], 'required'],
			 ['title', 'string', 'max' => 60],   
			 ['description', 'string', 'max' => 250], 		            
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'title' => 'Title',
            'bannerImage' => 'Image',
            'description' => 'Description',
            'dateCreated' => 'Date',
            'status' => 'Status',
        ];
    }

    /**
     * signup user.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup() {
        if (!$this->validate()) {
            return null;
        }

        ################## Add New Banner ##################
        $banner = new Banner();
        $banner->title = $this->title;
        $banner->description = $this->description;
        $banner->bannerImage = $this->bannerImage;
        return $banner->save() ? $banner : null;
        ##################################################
    }

}
