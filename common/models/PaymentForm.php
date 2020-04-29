<?php

namespace common\models;

use Yii;
use yii\base\Model;

/**
 *  Class PaymentForm is the model for Payment Form(Debit/Credit card).
 */
class PaymentForm extends Model
{
    public $fname_oncard;
    public $lname_oncard;
    public $company_name;
    public $billing_zipcode;
    public $cc_type;
    public $cc_number;
    public $exp_month;
    public $exp_year;
    public $cvv;
  
    /**
	* @ Function Name		: rules
	* @ Function Params		: NA 
	* @ Function Purpose 	: define the validations rules to apply on submitted form data
	* @ Function Returns	: array
	*/
    public function rules()
    {
        return [
            // name, email, subject and body are required
            /*[['fname_oncard', 'cc_type', 'cc_number', 'exp_month', 'exp_year', 'cvv'], 'required'],
            ['company_name','string']*/
        ];
    }

    /**
	* @ Function Name		: attributeLabels
	* @ Function Params		: NA 
	* @ Function Purpose 	: defing the custom label for fields
	* @ Function Returns	: array
	*/
    public function attributeLabels()
    {
        return [
            'fname_oncard' => 'Name On Card',
            'lname_oncard' => 'Last Name On Card',
            'cc_type' => 'Card Type',
            'cc_number' => 'Card Number',
            'exp_month' => 'Expiry Month',
            'exp_year' => 'Expiry Year',
            'cvv' => 'CVV',
        ];
    }

}
