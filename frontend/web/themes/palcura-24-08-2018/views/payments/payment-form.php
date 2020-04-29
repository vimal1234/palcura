<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('yii', 'Make Payment');
$this->params['breadcrumbs'][] = $this->title;

$booking_details = Yii::$app->session->get('booking_details');

$_n_s_b_d = Yii::$app->session->get('_n_s_b_d');

$price = $booking_details['booking_amount'];
$currency = CURRENCY_SIGN;
$loggedUser = Yii::$app->user->identity;
$cardTypes = Yii::$app->commonmethod->getCardTypes();

$cardnumber = '';
$expMonth = '';
$expYear = '';

if(!empty($data)){
$cardnumber = $data['card_number'];
$expMonth = $data['card_exp_month'];
$expYear  = $data['card_exp_year'];

}
?>
<div class="row">
    <div class="col-xs-12">
        <h1><?= $this->title ?></h1>
    </div>
</div>
</div>
</header>
<section class="contentArea">
    <div class="container">
        <div class="row">
            <?php echo $this->render('//common/sidebar'); ?>
            <div class="col-md-10 col-sm-8 col-xs-12">
                <div class="formContent" style="margin:0;"> 
                    <img class="contact-bg" src="<?php echo WEBSITE_IMAGES_PATH . 'contact-bg.png'; ?>" alt="">
                    <div class="col-md-4 col-sm-4 col-xs-12 pull-right">
                        <div class="formContentSidebar"></div>
                    </div>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <?php if (Yii::$app->session->getFlash('error_mesg')) { ?>
                        <script>
						$(document).ready(function () {
						// Handler for .ready() called.
						$('html, body').animate({
							scrollTop: $('#scrrollhere').offset().top
						}, 'slow');
						});
												
						</script>
                            <div class="col-xs-12" id="scrrollhere">  <div class="alert alert-grey alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span></button>
                                    <i class="glyphicon glyphicon-ok"></i> 
                                    <?php echo Yii::$app->session->getFlash('error_mesg'); ?>
                                </div> </div>
                        <?php } ?>
                        
                        <div class="col-md-12 col-sm-12 col-xs-12" id="braintreeerror" style="display:none;">                       					
					
                            <div class="col-xs-12" id="scrrollhere">  <div class="alert alert-grey alert-dismissible">
                                    <i class="glyphicon glyphicon-remove" ></i><span id="braintreeerrordesc"></span>                           
                                </div> </div>
                       </div>

                        <?php
                        $form = ActiveForm::begin(
                                        ['id' => 'paypal-form', 'method' => 'post',
                                            'options' => ['class' => 'inner'],
                                            'fieldConfig' => [
                                                'template' => "<div class=\"form-group\">\n
													{label}\n<div class=\"controls\">
													{input}<div class=\"col-lg-10\">
													{error}</div></div></div>",
                                                'labelOptions' => [],
                                            ],
                        ]);
                        ?>
                        <input type="hidden" name="PaymentForm[userrewards]" value="<?= (isset($rewardStatus) ? $rewardStatus : 0) ?>" />    
                        <input type="hidden" name="PaymentForm[usercredits]" value="<?= (isset($creditStatus) ? $creditStatus : 0) ?>" />                             
                        <input type="hidden" name="PaymentForm[_n_s_b_d]" value="<?= json_encode($_n_s_b_d) ?>" />                             
                        <?php
                        $items = array(
                            'Visa' => 'Visa',
                            'MasterCard' => 'Master Card',
                            'Discover' => 'Discover',
                            'Amex' => 'American Express',
                        );
                        $items_mm = array();
                        $items_yy = array();
                        for ($i = 1; $i <= 9; $i++) {
                            $items_mm['0' . $i] = '0' . $i;
                        }
                        $items_mm['10'] = 10;
                        $items_mm['11'] = 11;
                        $items_mm['12'] = 12;

                        $start_year = date('Y');
                        for ($i = 0; $i <= 15; $i++) {
                            $yy = $start_year + $i;
                            $items_yy[$yy] = $yy;
                        }
                        ?>

                        <div class="row-block">
                            <div class="col-lg-4 col-md-4 col-sm-4">	
                                <?php //echo $form->field($model, 'fname_oncard')->textInput(['maxlength' => '16']) ?>
                            </div>	                                           
                            <div class="col-lg-4 col-md-4 col-sm-4">
                                <?php
                               /*echo $form->field($model, 'cc_type', ['inputOptions' => [
                                        'class' => "form-control whiteBorder",
                            ]])->dropDownList(
                                        $items, // Flat array ('id'=>'label')
                                        ['prompt' => Yii::t('yii', 'Select Card Type')]    // options
                                )*/
                                ?>
                            </div>	
                            <div class="col-lg-4 col-md-4 col-sm-4">	
                                <?php //echo $form->field($model, 'cc_number')->textInput(['maxlength' => '16', 'autocomplete' => 'off']) ?>
                            </div>	
                        </div>	

                        <div class="row-block">
                            <div class="col-lg-4 col-md-4 col-sm-4">	
                                <?php //echo $form->field($model, 'cvv')->passwordInput(['maxlength' => '16', 'autocomplete' => 'off']) ?>
                            </div>	   
                            <div class="col-lg-4 col-md-4 col-sm-4">	
                                <?php
                               /* echo $form->field($model, 'exp_month', ['inputOptions' => [
                                        'class' => "form-control whiteBorder",
                            ]])->dropDownList(
                                        $items_mm, // Flat array ('id'=>'label')
                                        ['prompt' => Yii::t('yii', 'Select Expiry Month')]    // options
                                )*/
                                ?>
                            </div> 
                            <div class="col-lg-4 col-md-4 col-sm-4">	
                                <?php
                             /* echo  $form->field($model, 'exp_year', ['inputOptions' => [
                                        'class' => "form-control whiteBorder",
                            ]])->dropDownList(
                                        $items_yy, // Flat array ('id'=>'label')
                                        ['prompt' => Yii::t('yii', 'Select Expiry Year')]    // options
                                )*/
                                ?>
                            </div>
                            
                            <!-- custom inputs for braintree start-->
                            <!--form action="<?php echo SITE_URL ;?>payments/directpayment" id="paypal-form" method="post"-->
    <div class="row-block">
    <div class="col-lg-4 col-md-4 col-sm-4">	
   <label class="hosted-fields--label" for="card-number">Card Number<span style="color:red"> *</span></label>
    <div id="card-number" class="hosted-field form-control whiteBorder"></div>
</div>
<div class="col-lg-4 col-md-4 col-sm-4">
    <label class="hosted-fields--label" for="expiration-date">Expiration Date<span style="color:red"> *</span></label>
    <div id="expiration-date" class="hosted-field form-control whiteBorder"></div>
</div>
<div class="col-lg-4 col-md-4 col-sm-4">
    <label class="hosted-fields--label" for="cvv">CVV<span style="color:red"> *</span></label>
    <div id="cvv" class="hosted-field form-control whiteBorder"></div>
</div>
    <!--label class="hosted-fields--label" for="postal-code">Postal Code</label-->
    <div id="postal-code" class="hosted-field"></div>
    </div>
    <input type="hidden" name="nocetoken" id="noncetoken" />
	<div class="row-block">
    <div class="col-md-12 col-xs-12 col-lg-12" style="float:left;padding: 13px 15px;">
                                <?php echo Html::submitButton(Yii::t('yii', 'Submit'), ['class' => 'btn btn-primary orangebtn pull-right', 'name' => 'paynow-button', 'id' => 'paynow-button']) ?>
                            </div>
	</div>						
    </form>
                            <!-- custom inputs for braintree end-->
                
                        </div>

                        <div class="row-block">
                            <div class="col-lg-4 col-md-4 col-sm-4">	
                                <img src="<?php echo Url::home(); ?>frontend/web/themes/palcura/images/vmadlogo.png"/>
                            </div>	    
                            <div class="col-lg-4 col-md-4 col-sm-4">	
                            </div>	    

                            <div class="col-lg-4 col-md-4 col-sm-4" style="text-align:right;">
                                <h4 style="float:left;padding: 13px 0px;" class="pull-right">
                                    <?php echo Yii::t('yii', 'Total Due') ?>: <?php echo $currency; ?><span class="payPrice"><?php  //echo round($price);   ?></span><span class="payPrice"><?php echo (isset($_n_s_b_d['billingAmount'])) ? $_n_s_b_d['billingAmount'] : $price; ?></span> 
                                </h4>
                                <!--<p class="secureserver"><?php // echo Yii::t('yii', 'secure server')  ?><img src="<?php // echo Url::home();  ?>frontend/web/themes/palcura/images/locksmall.png"/></p>-->
                            </div>	
                            <div class="col-md-12 col-xs-12 col-lg-12">
                                <?php //Html::submitButton(Yii::t('yii', 'Submit'), ['class' => 'btn btn-primary orangebtn pull-right', 'name' => 'paynow-button', 'id' => 'paynow-button']) ?>
                            </div>
                        </div>
                        <div class="row-block">

                        </div>
                        <!--?php ActiveForm::end(); ?-->
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--script src="https://js.braintreegateway.com/js/braintree-2.32.1.min.js"></script-->
<script src="https://js.braintreegateway.com/web/3.31.0/js/client.js"></script>
<script src="https://js.braintreegateway.com/web/3.31.0/js/hosted-fields.js"></script>
    <script>
    /*braintree.setup("<?php echo Yii::$app->braintree->generateToken() ?>", "custom", {
        id: "paypal-form",
        hostedFields: {
          number: {
            selector: "#card-number"
          },
          cvv: {
            selector: "#cvv"
          },
          expirationDate: {
            selector: "#expiration-date"
          }
        }
      });*/
      // This handler does the magic


      var form = document.querySelector('#paypal-form');
      var submit = document.querySelector('#paynow-button');
      
      var cardnumber = "<?php echo $cardnumber ?>";
	  var expMonth = "<?php echo $expMonth ?>";
	  if(expMonth<10){
	  expMonth = 0+expMonth;
	  }
	  var expYear  = "<?php echo $expYear ?>";

      braintree.client.create({
        authorization: "<?php echo Yii::$app->braintree->generateToken() ?>"
      }, function (clientErr, clientInstance) {
        if (clientErr) {
          console.error(clientErr);
          return;
        }

        // This example shows Hosted Fields, but you can also use this
        // client instance to create additional components here, such as
        // PayPal or Data Collector.

        braintree.hostedFields.create({
          client: clientInstance,
          styles: {
            'input': {
              'font-size': '14px'
            },
            'input.invalid': {
              'color': 'red'
            },
            'input.valid': {
              'color': 'green'
            }
          },
          fields: {
            number: {
              selector: '#card-number',
              //placeholder: 'card number'
              prefill:cardnumber
            },
            cvv: {
              selector: '#cvv',
              //placeholder: 'cvv'     
            },
            expirationDate: {
              selector: '#expiration-date',
              prefill: expMonth+'/'+expYear
            }
             
          }
        }, function (hostedFieldsErr, hostedFieldsInstance) {
          if (hostedFieldsErr) {
            console.error(hostedFieldsErr);
            return;
          }

          submit.removeAttribute('disabled');

          form.addEventListener('submit', function (event) {
            event.preventDefault();

            hostedFieldsInstance.tokenize(function (tokenizeErr, payload) {
              if (tokenizeErr) {
$('#braintreeerror').hide();
                //console.error(tokenizeErr);
                var errorcode = tokenizeErr.code;
                //console.log(errorcode);
                if(errorcode =='HOSTED_FIELDS_FIELDS_EMPTY'){
                $('#braintreeerrordesc').empty();
                $('#braintreeerror').show();
                $('#braintreeerrordesc').text('Please enter card details below.');
                }
                if(errorcode=='HOSTED_FIELDS_FIELDS_INVALID' || errorcode=='HOSTED_FIELDS_FAILED_TOKENIZATION' || errorcode=='HOSTED_FIELDS_TOKENIZATION_FAIL_ON_DUPLICATE' || errorcode== 'HOSTED_FIELDS_TOKENIZATION_CVV_VERIFICATION_FAILED' || errorcode=='HOSTED_FIELDS_TOKENIZATION_NETWORK_ERROR'){
                $('#braintreeerrordesc').empty();
                $('#braintreeerror').show();
                $('#braintreeerrordesc').text(' Card details seems to be invalid. Please enter correct details.');
                }
               //console.log(errorcode);
                return;
              }
				$('#noncetoken').val(payload.nonce);
				
              // If this was a real integration, this is where you would
              // send the nonce to your server.
              $( "#paypal-form" ).submit();
              //console.log('Got a nonce: ' + payload.nonce);
            });
          }, false);
        });
      });
    </script>
    
