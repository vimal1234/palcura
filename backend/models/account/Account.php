<?php
namespace backend\models\account;

use Yii;
use backend\models\users\Users;
use common\models\Currency;


    /**
     * @Payment
     */
class Account extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'accountsetting';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'paypal_email_address','account_holder_name','bank_name','IBAN','account_type'], 'required'],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {   
        return [
            'paypal_email_address' 	=> 'Payment Transaction Id',
            'account_holder_name' 	=> 'Customer Name',
            'bank_name' 			=> 'Booking Id',
            'amount'                => 'Amount',
            'IBAN'                  => 'Transaction ID',
            'account_type'          => 'Date of Transaction',
        ];
    }

    /**
     * @getUser: get users
     */
    public function getUser() {
        return $this->hasOne(Users::className(), ['id' => 'user_id']);
    }

    public function getCurrency() {
        return $this->hasOne(Currency::className(), ['id'=>'accept_currency']);
    }   
}
