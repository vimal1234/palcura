<?php 
namespace frontend\components; 
 //error_reporting(E_ALL);
//ini_set('display_errors', 1);
use Yii;
use yii\base\ErrorException;
use yii\helpers\ArrayHelper;
use yii\base\Component;


require Yii::$app->basePath."/braintree/vendor/autoload.php";

use Braintree\Configuration;
use Braintree\Gateway;
use Braintree\Transaction;
use Braintree\Customer;
use Braintree\Subscription;
use Braintree\MerchantAccount;
use Braintree\CreditCard;
use Braintree\WebhookNotification;
use Braintree\Exception;
use Braintree\Exception\NotFound;



class Braintree extends Component
{
    public $ENV;// "sandbox" or "live"
    
    
    public $MERCHANT_ID;
    
    public $MERCHANT_ACCOUNT_ID;
    
    public $PUBLIC_KEY;
    
    public $PRIVATE_KEY;
    
    public $CSEK;/* Client side encription key */
    
    var $gateway;
    
    public function init(){
	
     //require_once 'braintree-php-2.26.0/lib/Braintree.php';
       
    }
    
    public function __construct()
    {
  
    }
    
    public function setConfig(){

	$this->gateway = new Gateway([
    'environment' => $this->ENV,
    'merchantId' => $this->MERCHANT_ID,
    'publicKey' => $this->PUBLIC_KEY,
    'privateKey' => $this->PRIVATE_KEY
	]);
	return $this->gateway;

	}
    

    public function sale($data)
    {
    
      $gateway = $this->setConfig();
      $result = $gateway->transaction()->sale($data);
      //echo "<pre>"; print_r($result); die;
     // $result = Transaction::sale($data);      
      if ($result->success) {
           
           return(array('success'=>1,
                        'transaction_id'=>$result->transaction->id,
                        'amount' => $result->transaction->amount,
                        'cardtype' => $result->transaction->creditCard['cardType']
                 ));
         	  
      } else if ($result->transaction) {
                //Error processing transaction
          return(array(
                      'success'=>0,
                      'message'=>$result->message,
                      'code'=>$result->transaction->processorResponseCode,
                      'text'=>$result->transaction->processorResponseText
                ));
        
	  
      } else {
                //Validation Errors                              
            return(array(
                      'success'=>0,
                      'message'=>$result->message,
                      'validation_errors'=>$result->errors->deepAll(),
                     
                ));
	 
      }
  
       
    }
    public function createCustomer($data)
    {
       $gateway = $this->setConfig();
       //$result = Customer::create($data);
       $result = $gateway->customer()->create($data);
     
        /* 
        echo "<pre>";
        print_r($result);
        echo "</pre>";
        */
        

	if ($result->success) {
	
	$cardinfo = $result->customer->creditCards[0]->verifications;
	$cvvResponse = $cardinfo[0]['cvvResponseCode'];
	$cardtype = $cardinfo[0]['creditCard']['cardType'];
	
	
	
	     return(array('success'=>1,
                        'customer_id'=>$result->customer->id,
                        'cardtype' => $cardtype,
                        'cvvresponse' => $cvvResponse
                 ));
     
	} else {
                 $errors=$result->errors->deepAll();   
                 if(count($errors)>0&&($errors[0]->code==91609||$errors[0]->message=='Customer ID has already been taken.'))
	       {
	         return(array(
                      'success'=>1,
                      'customer_id'=>$data['id'],
                     
                )); 
	       }

	     return(array(
                      'success'=>0,
                      'validation_errors'=>$result->errors->deepAll(),
                     
                ));
	   
	}
    }
     public function deleteCustomer($id)
    {
       $result = Customer::delete($id);
       
         
        /* 
        echo "<pre>";
        print_r($result);
        echo "</pre>";
        */

	if ($result->success) {
	
	      return($result);
	       /*
	     return(array('success'=>1,
                        'customer_id'=>$result->customer->id
                 ));
                 */
     
	} else {
	     return(array(
                      'success'=>0,
                      'validation_errors'=>$result->errors->deepAll(),
                     
                ));
	   
	}
    }
    
    public function generateToken(){
    $gateway = $this->setConfig();
    $clientToken = $gateway->clientToken()->generate();   
    return $clientToken;
    }
    
    
    public function getCustomerById($customer_id)
    {
		$gateway = $this->setConfig();
		//$customer =  $gateway->customer()->find($customer_id);
		 
       try{
          //$customer = Customer::find($customer_id);         
          $customer =  $gateway->customer()->find($customer_id);
          
         return(array(
                      'success'=>1,
                      'data'=>$customer
                    ));
         
         // return($customer);
         }
         catch(NotFound $e)
         {     
            return(array(
                      'success'=>0,
                      'message'=>'Customer with '.$customer_id." is not found"
                    ));
         }
         
      
      /*
         eg:Array
	      (
		  [success] => 1
		  [customer_id] => 68012283
	      )
       */
     // return($customer);
    }
    public function getPaymentMethodToken($customer_id)
    {
      try{
          $customer = Customer::find($customer_id);
          
         }
         catch(Exception $e)
         {
            return(array(
                      'success'=>0,
                      'message'=>'Customer with '.$customer_id." is not found"
                    ));
         }
       
     
      
      if(isset($customer->creditCards[0]->token))
       {
          return(array(
        
                      'success'=>1,
                      'payment_method_token'=>$customer->creditCards[0]->token
                   ));
                   /*
                     Array
			  (
			      [success] => 1
			      [payment_method_token] => 7nv6bm
			  )
                   */
    
       }
       else
       {
        return(array(
                      'success'=>0,
                      'message'=>'no creditCards found for the customer:'.$customer_id
                    ));
       }
    }
    
    public function updatepaymentMethod($payment_method_token,$nonce){
    $gateway = $this->setConfig();
    $result = $gateway->paymentMethod()->update($payment_method_token, [
    'paymentMethodNonce' => $nonce,
    'options' => [
        'verifyCard' => true
    ]
]);

		if($result->success){

		return(array(
				              'success'=>1,
				              
				         ));

		}else{

		return(array(
				              'success'=>0,
				              'validation_errors'=>$result->errors->deepAll(),
				             
				        ));
		}
   
    
    }
    
    public function createSubscription($payment_method_token)
    {
      $result = Subscription::create(array(
        'paymentMethodToken' => $payment_method_token,
        'planId' => 'fxtb'
      ));
    
    
      if ($result->success) {
      
         return(array(
                      'success'=>1,
                      'subscription_id'=>$result->subscription->id,
                      'subscription_status'=>$result->subscription->status
                 ));
                 /*
                  eg:Array
			  (
			      [success] => 1
			      [subscription_id] => 59btqg
			      [subscription_status] => Active
			  )
                 
                 */
    
       } else {
          return(array(
                      'success'=>0,
                      'validation_errors'=>$result->errors->deepAll(),
                     
                ));
       
        }
    
    }
    
    
    
    public function createSubMerchant($data)
    {
      $result = MerchantAccount::create($data);
      
      if ($result->success) {
      
         return(array(
                      'success'=>1,
                      'sub_merchant_id'=>$result->merchantAccount->id,
                      'status'=>$result->merchantAccount->status,
                      'currency_code'=>$result->merchantAccount->currencyIsoCode
                 ));
                 /*
                   eg:Array
			    (
				[success] => 1
				[sub_merchant_id] => jane_doe_instant5
				[status] => pending
				[currency_code] => USD
			    )
			    /*  firstname=>approve_me
			    Array
			      (
				  [success] => 1
				  [sub_merchant_id] => approve_me_doe_instant
				  [status] => pending
				  [currency_code] => USD
			      )
                 */
    
       } else {
         return(array(
                      'success'=>0,
                      'errors'=>$result->errors->deepAll(),
                     
                ));
                /* eg:
                  Array
		      (
			  [success] => 0
			  [errors] => Array
			      (
				  [0] => Braintree_Error_Validation Object
				      (
					  [_attribute:Braintree_Error_Validation:private] => mobilePhone
					  [_code:Braintree_Error_Validation:private] => 82683
					  [_message:Braintree_Error_Validation:private] => Funding mobile phone is invalid.
				      )

			      )

		      )

                */
       
        }
    
    }
     public function updateSubMerchant($sub_merchant_id,$data)
    {
      $result = MerchantAccount::update($sub_merchant_id,$data);
      
      if ($result->success) {
      
         return(array(
                      'success'=>1,
                      'sub_merchant_id'=>$result->merchantAccount->id,
                      'status'=>$result->merchantAccount->status,
                      'currency_code'=>$result->merchantAccount->currencyIsoCode
                 ));
                 /*
                   eg:Array
			    (
				[success] => 1
				[sub_merchant_id] => jane_doe_instant5
				[status] => pending
				[currency_code] => USD
			    )
			    /*  firstname=>approve_me
			    Array
			      (
				  [success] => 1
				  [sub_merchant_id] => approve_me_doe_instant
				  [status] => pending
				  [currency_code] => USD
			      )
                 */
    
       } else {
         return(array(
                      'success'=>0,
                      'errors'=>$result->errors->deepAll(),
                     
                ));
                /* eg:
                  Array
		      (
			  [success] => 0
			  [errors] => Array
			      (
				  [0] => Braintree_Error_Validation Object
				      (
					  [_attribute:Braintree_Error_Validation:private] => mobilePhone
					  [_code:Braintree_Error_Validation:private] => 82683
					  [_message:Braintree_Error_Validation:private] => Funding mobile phone is invalid.
				      )

			      )

		      )

                */
       
        }
    
    }
    public function transactToSubMerchant($data)
    {
      $result = Transaction::sale($data);
      
      /*
      echo "<pre>";
      print_r($result);
      echo "</pre>";
      */
      if ($result->success) {
      
         return(array(
                      'success'=>1,
                      'transaction_id'=>$result->transaction->id,
                      'amount'=>$result->transaction->amount,
                      'status'=>$result->transaction->status,
                      'type'=>$result->transaction->type,
                      'service_fee'=>$result->transaction->serviceFeeAmount,
                      'currency_code'=>$result->transaction->currencyIsoCode,
                      'escrow_status'=>$result->transaction->escrowStatus,
                   
                 ));
                 /*
                  eg:Array
			(
			    [success] => 1
			    [transaction_id] => 4x7wdg
			    [amount] => 100.00
			    [status] => submitted_for_settlement
			    [currency_code] => USD
			)
                 
                 */
    
       } else {
          return(array(
                      'success'=>0,
                      'transaction_errors'=>$result->errors->deepAll(),
                     
                ));
       
        }
 
    }
    
    public function releaseFromEscrow($transaction_id)
    {
    
         $result = Transaction::releaseFromEscrow($transaction_id);
        
         if ($result->success)
         {
          return($result);
         }
         else
         {
          return(array(
                      'success'=>0,
                      'errors'=>$result->errors->deepAll(),
                     
                ));
         }
    }
    public function getTransactionById($transaction_id)
    {

      
       try{
          $transaction = Transaction::find($transaction_id);
           /*
           echo "<pre>";
	      print_r( $transaction);
	      echo "</pre>";
	      */
          return(array(
                        'transaction_id'=>$transaction->id,
                        'amount'=>$transaction->amount,
                        'currency_code'=>$transaction->currencyIsoCode,
                        'type'=>$transaction->type,
                        'escrow_status'=>$transaction->escrowStatus,
                        'service_fee'=>$transaction->serviceFeeAmount
                      ));
         }
         catch(Exception $e)
         {
            return(array(
                      'success'=>0,
                      'message'=>'Customer with '.$transaction_id." is not found"
                    ));
         }
         
      
      /*
         eg:Array
	      (
		  [success] => 1
		  [customer_id] => 68012283
	      )
       */
   
    }
    public function createCard($data)
    {
    $gateway = $this->setConfig();
      // $result = CreditCard::create($data);
 	$result = $gateway->creditCard()->create($data);
	if ($result->success) {
	
	     return(array('success'=>1,
                        'payment_method_token'=>$result->creditCard->token
                 ));
     
	} else {
	     return(array(
                      'success'=>0,
                      'validation_errors'=>$result->errors->deepAll(),
                     
                ));
	   
	}
    }
    public function cloneTransaction($transaction_id,$amount)
    {
      $result = Transaction::cloneTransaction($transaction_id, array(
	  'amount' => $amount,
	  
	  'options' => array(
	    'submitForSettlement' => true,
	    
	  )
	));
	
	
	
      if ($result->success) {
	      
	  
	     return(array('success'=>1,
                           'transaction_id'=>$result->transaction->id,
                           'amount'=>$result->transaction->amount,
                           'currency_code'=>$result->transaction->currencyIsoCode,
                           'type'=>$result->transaction->type,
                           'escrow_status'=>$result->transaction->escrowStatus,
                           'service_fee'=>$result->transaction->serviceFeeAmount
                 ));
     
	} else {
	    
	
	    
	      if(count($result->errors->deepAll())>0)
	      {
		return(array(
			  'success'=>0,
			  'transaction_clone_errors'=>$result->errors->deepAll(),
			
		    ));
              } 
              else
              {
                return(array(
			  'success'=>0,
			  'transaction_clone_errors'=>$result->message,
			
		    ));
              }
	   
	}
    
    }
    public function getMasterAccountId()
    {
     return($this->MERCHANT_ACCOUNT_ID);
    }
    
    public function verifyWebHookNotification()
    {
      if(isset($_GET["bt_challenge"])) {
      
       echo(WebhookNotification::verify($_GET["bt_challenge"]));
      
      }
      else
      {
        return(false);
      }
    }
    
    public function parseWebHookNotification()
    {
       if(
	    isset($_POST["bt_signature"]) &&
	    isset($_POST["bt_payload"])
	) {
	    $webhookNotification = WebhookNotification::parse(
		$_POST["bt_signature"], $_POST["bt_payload"]
	    );

	    return($webhookNotification); 
	   /*  
	    $message =
		"[Webhook Received " . $webhookNotification->timestamp->format('Y-m-d H:i:s') . "] "
		. "Kind: " . $webhookNotification->kind . " | "
		. "Subscription: " . $webhookNotification->subscription->id . "\n";

	    file_put_contents("/tmp/webhook.log", $message, FILE_APPEND);
	  */  
	}
	else
	  return(false);	       
    }
    
    public function partialRefundAcustomer($transactionid,$refundAmount,$transactionvoid,$amountdeducted){
	$refundAmount = number_format((float)$refundAmount, 2, '.', '');
	$amountdeducted = number_format((float)$amountdeducted, 2, '.', '');
    $gateway = $this->setConfig();
    
    $transactionres = $gateway->transaction()->find($transactionid);  

    if(isset($transactionres->id) && !empty($transactionres->id)){
  
		if($transactionres->status == 'submitted_for_settlement' ){
			
			if($transactionvoid == true){
			$result = $gateway->transaction()->void($transactionid);
			$reftransactionid = $transactionid;
			//$reftransactionid = $result->transaction->id;
			//$amount = $result->transaction->amount;
			}elseif($transactionvoid == false){
					
			 $result = $gateway->transaction()->void($transactionid);
			 //$reftransactionid = $result->transaction->id;
			 
			 $paymdata=array(
                'amount' => $amountdeducted,               
				 'customerId' => Yii::$app->user->identity->braintree_customer_id,			 
                 'options' => [
					'submitForSettlement' => true
			  ]                                         	
               );
			 $gateway->transaction()->sale($paymdata);
			 //$reftransactionid = $result->transaction->amount;
			 $reftransactionid = $transactionid;
			}			
			if ($result->success) {			
			   return(array('success'=>1,'transaction_id' => $reftransactionid,'status'=>'voided','amount'=> $refundAmount
		                  
		             ));
			}else{
			  return(array(
		                  'success'=>0,
		                		                 
		            ));
			}		
		}elseif($transactionres->status == 'settled' || $transactionres->status == 'Settling'){
			$result = $gateway->transaction()->refund($transactionid, $refundAmount);
					//echo "<pre>"; print_r($result); die;
			if ($result->success) {
			$reftransactionid = $result->transaction->id; 
			$amount = $result->transaction->amount; 
			 return(array('success'=>1,'transaction_id' => $reftransactionid,'amount'=> $amount,'status'=>'refunded' 		                  
		             ));
		 
			} else {
			 return(array('success'=>0));		   
			}		
		 }
       }else{    
			return(array( 'success'=>0));
		}
    
    }
    
}  
?>
