<?php
namespace backend\models\settings;
use Yii;
use yii\base\Model;

/**
* AddSetting
*/
class AddSetting extends Model {

    public $website_fee;
    public $family_member_discount;
    public $discount;
    public $google_analytics;
public $facebook_pixel;
    public $status;

	/**
	* @inheritdoc
	*/
    public function rules() {
        return [
            [['website_fee','family_member_discount'], 'required'],  
            [['website_fee','family_member_discount'], 'number', 'numberPattern' => '/^\s*[-+]?[0-9]*[.,]?[0-9]+([eE][-+]?[0-9]+)?\s*$/'],
            [['website_fee'], 'number', 'max' => 20],  
            [['family_member_discount'], 'number', 'max' => 10],  
            [['discount'], 'number', 'max' => 10],  
        ];
    }

	/**
	* @inheritdoc
	*/
    public function attributeLabels() {
        return [
            'website_fee' 	=> 'Website fee',
            'family_member_discount' 	=> 'Family member discount',
            'discount' 		=> 'discount',
            'date_created' 	=> 'Date',
            'status' 		=> 'Status',
'facebook_pixel'=> 'Facebook Pixel'
        ];
    }
}
