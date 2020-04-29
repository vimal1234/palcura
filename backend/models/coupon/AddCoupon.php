<?php

namespace backend\models\coupon;

use backend\models\coupon\Coupon;
use yii\base\Model;
use Yii;

/**
 * AddMemberForm
 */
class AddCoupon extends Coupon { 
   
   	public $coupon_name;
   	public $coupon_code;
   	public $coupon_valid_date;
   	public $coupon_description;
   	public $owner_list;
   	public $renter_list;
    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['coupon_name','coupon_code','coupon_valid_date','coupon_description','owner_list'
                ], 'required'],
           [['renter_list'],'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'coupon_name' 			=> 'Name',
            'coupon_code' 			=> 'Coupon Code',
            'coupon_description'	=> 'Description',
           	'owner_list'			=> 'Owners',
           	'renter_list'			=> 'Renters',
           	'coupon_valid_date'		=> 'Validity Date',
        ];
    }

    /**
     * signup user.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function addcoupon() {
        if (!$this->validate()) {
            return null;
        }
		if(!empty($this->renter_list) && count($this->renter_list)>0){
		$renterarray = implode(',',$this->renter_list);
		}else{
		$renterarray = '';
		}
        $coupon = new Coupon();
        $coupon->coupon_name 			= $this->coupon_name;
        $coupon->coupon_code 			= $this->coupon_code;
        $coupon->status 				= '1';
        $coupon->coupon_description 	= $this->coupon_description;
        $coupon->coupon_valid_date 		= (isset($this->coupon_valid_date) ? date('Y-m-d', strtotime($this->coupon_valid_date)) : '');
        $coupon->owner_list 			= implode(',',$this->owner_list);
        $coupon->renter_list 			= $renterarray;

        return $coupon->save() ? $coupon : null;
    }

}
