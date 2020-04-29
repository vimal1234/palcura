<?php
namespace backend\models\coupon;
use Yii;

/**
 * This is the model class for table "coupons".
 *
 */
class Coupon extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'coupons';
    }

}
