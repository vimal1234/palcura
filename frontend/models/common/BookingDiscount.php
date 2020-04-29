<?php
namespace frontend\models\common;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
* BookingDiscount model
*/
class BookingDiscount extends \yii\db\ActiveRecord {
	public $sitter_id;	
	public $owner_id;	
	public $discount;	
	public $minimum_price;	
	public $till_date;

	/**
	* @inheritdoc
	*/
    public static function tableName() {
        return 'booking_discount';
    }

	/**
	* @inheritdoc
	*/
    public function behaviors() {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'datetime',
                'value' => new Expression('NOW()'),
            ],
        ];
    }

	/**
	* @inheritdoc
	*/
	public function rules() {
		return [				
				[['owner_id','sitter_id'], 'integer'],
				[['owner_id','sitter_id','till_date','date_created','status','minimum_price','discount'], 'safe'],
				[['minimum_price'], 'number', 'min' => 500],
				[['minimum_price'], 'number', 'max' => 2000],
				[['discount'], 'number'],
		];
	}
	
	public function attributeLabels() {
        return [
            'discount' 	=> Yii::t('yii','Final Price'),
            
        ];
    }
}
