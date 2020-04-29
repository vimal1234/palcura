<?php

namespace frontend\models;
use yii\db\Query;

use Yii;

/**
 * This is the model class for table "banner".
 *
 * @property integer $id
 */
class bannerImages extends \yii\db\ActiveRecord{



    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'banner';
    }
 
   /**
     * Get CMS page by id
     * @param integer $id
     * @return array
     */
    public function getBannerImage() {
        
        $query = new Query;
        $query->select('bannerImage')
            ->from('banner')
            ->where(['status'=>'1']);
        
        $rows = $query->all();
        $command = $query->createCommand();

        $rows = $command->queryAll();
        return $rows;            
    }
}

