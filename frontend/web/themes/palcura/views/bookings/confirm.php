<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('yii', 'Accept/Decline');

####= common methods =####
$number_of_pets = Yii::$app->commonmethod->getNumberOfPets();
//$paymentData			=  Yii::$app->session->get('booking_details');
$paymentData = $bookingdata;

####= { $selectCol } use to get specific columns of users. 
$selectCol = "firstname,lastname,reward_points,sitter_credits,owner_credits";
$userInfo = Yii::$app->commonmethod->getUserColumnsData($paymentData['pet_owner_id'], $selectCol);
$servicesName = Yii::$app->commonmethod->getServicesNamenew($paymentData['booking_services']);
$attributes = Yii::$app->user->identity->getattributes();
$session = Yii::$app->session;
$logged_user = $session->get('loggedinusertype');
if ($logged_user == OWNER) {
    $BookedUserName = "Sitter name";
    $user_reward_points = $attributes['reward_points'];
    $user_credits = CURRENCY_SIGN . ($attributes['sitter_credits'] + $attributes['owner_credits']);
    $usercredits2 = $attributes['sitter_credits'] + $attributes['owner_credits'];
} else if ($logged_user == BORROWER) {
    $BookedUserName = "Owner name";
    $user_reward_points = $userInfo['reward_points'];
    $user_credits = CURRENCY_SIGN . ($attributes['sitter_credits'] + $attributes['owner_credits']);
    $usercredits2 = $attributes['sitter_credits'] + $attributes['owner_credits'];
}
$palCuraFee = (isset($websitefees['website_fee']) && $websitefees['website_fee'] > 0 ? $websitefees['website_fee'] : 15);
$allservices = implode(',', $servicesName);

?>

    <div class="col-xs-12">
        <h1><?= $this->title ?></h1>
    </div>
</div>
</header>
<section class="contentArea">
    <div class="container">
        <div class="row">
            <?php echo $this->render('//common/sidebar'); ?>
            <div class="col-md-10 col-sm-12 col-xs-12">
                <div class="formContent" style="margin:0;"> 
                    <img class="contact-bg" src="<?php echo WEBSITE_IMAGES_PATH . 'contact-bg.png'; ?>" alt="">
                    <div class="col-md-4 col-sm-4 col-xs-12 pull-right">
                        <div class="formContentSidebar"></div>
                    </div>

                    <div class="col-md-12 col-sm-8 col-xs-12">
                        <div id="msgbox" style="display:none;">
                        </div>	
                        <!--?php
                                //$form = ActiveForm::begin(
                                                //[ 'id' => 'paypal-now', 'method' => 'post','action' => Url::to(['booking/confirm']),
                                                //	'options' => ['class' => 'inner'],
                                                        //'fieldConfig' => [
                                                        //	'template' => "",
                                                        //	'labelOptions' => [],
                                                        //],
                                //]);
                        ?-->
                        <?php
                        $form = ActiveForm::begin([
                                    'id' => 'frmSignupUser',
                                    //'action' => Url::to(['bookings/confirm']),
                                    'options' => [
                                        'enctype' => 'multipart/form-data',
                                        'tag' => 'span',
                                    ],
                                    'fieldConfig' => [
                                        'template' => "<div class=\"form-group\">\n
					{label}\n
					{input}\n
					<div class=\"col-lg-10\">
					{error} {hint}
					</div>
					</div>",
                                        'labelOptions' => [],
                                        'options' => [
                                            'tag' => 'span',
                                            'class' => '',
                                        ],
                                    ],
                        ]);
                        //echo $form->field($model, 'sitter_id')->hiddenInput(['value'=> '1'])->label(false);
                        echo Html::hiddenInput('owner_id', $paymentData['pet_owner_id']);
                        echo Html::hiddenInput('renter_id', $paymentData['pet_renter_id']);
                        echo Html::hiddenInput('sitter_id', $paymentData['pet_sitter_id']);
                        echo Html::hiddenInput('booking_from_date', $paymentData['booking_from_date']);
                        echo Html::hiddenInput('booking_to_date', $paymentData['booking_to_date']);
                        echo Html::hiddenInput('services', $allservices);
                        //echo Html::hiddenInput('chat_id', $threadID);
                        ?>
                        <!--input type="hidden" name="reward_status" id="reward_status" value="" /> 
                        <input type="hidden" name="credit_amount" id="credit_amount" value="" /--> 
                        <input type="hidden" name="final_price" id="final_amount" value="<?= ($paymentData['amount']) ?>" /> 
                        <div class="col-md-6 col-sm-6 col-xs-12 paymentSection singleLine">
                            <div class="form-group">
                                <label>Booking from date :</label>
                                <div class="bookingNameExt"> <?= date('m/d/y', strtotime($paymentData['booking_from_date'])) ?></div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12 paymentSection singleLine">
                            <div class="form-group">
                                <label>Booking to date :</label>
                                <div class="bookingNameExt"> <?= date('m/d/y', strtotime($paymentData['booking_to_date'])) ?></div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12 paymentSection singleLine">
                            <div class="form-group ">
                                <label>Booking name :</label>
                                <div class="bookingNameExt"><?= $paymentData['name'] ?></div>
                            </div>
                        </div>  
                        <div class="col-md-6 col-sm-6 col-xs-12 paymentSection singleLine">
                            <div class="form-group ">
                                <label>Owner Name :</label>
                                <div class="bookingNameExt"><?= $userInfo['firstname'] . " " . $userInfo['lastname'] ?></div>
                            </div>
                        </div> 
                        <?php if (isset($servicesName) && !empty($servicesName)) { ?>
                            <div class="col-md-12 col-sm-12 col-xs-12 paymentSection singleLine">
                                <div class="form-group">
                                    <label>Booking Services :</label>
                                    <ol>
                                        <?php
                                        $i = 1;
                                        foreach ($servicesName as $row) {
                                            echo'<li>' . $i . '. ' . $row . '</li>';
                                            $i++;
                                        }
                                        ?>
                                    </ol>
                                </div>
                            </div>   
                        <?php } ?>

                        <!-- Booking description  -->
                        <div class="col-md-12 col-sm-12 col-xs-12 paymentSection">
                            <label>Booking description :</label>
                            <p class="booking_description"> 
                                <?php echo nl2br($paymentData['description']); ?>
                            </p>
                        </div>

                        <!-- /Booking description  -->



                        <!---  Pet Detail Section  --->
                        <?php
                        if ($logged_user == SITTER) {
//                            echo count($pets);
//                            die;
                            if (isset($pets) && count($pets) > 0) {
                                ?>
                                <div class="col-md-6 col-sm-6 col-xs-12 paymentSection singleLine">
                                    <div class="form-group ">
                                        <label>Number of pets :</label>
                                        <div class="bookingNameExt"><?= count($pets) ?></div>
                                    </div>
                                </div> 
                                <div class="col-md-12 col-sm-12 col-lg-12 pet_details_section" style="margin-bottom: 30px; float: left; width: 100%;">
                                    <span> Pets Details: </span>
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th> # </th>
                                                <th> Pet Type </th>
                                                <th> Name </th>
                                                <th> Care note </th>
                                            </tr>
                                        </thead>
                                        </tbody>
                                        <?php
                                        foreach ($pets as $key => $pet) {
                                            ?>
                                            <tr>
                                                <td> <?= $key + 1 ?> </td>
                                                <td> <?= $pet->getPetType() ?> </td>
                                                <td> <?= $pet->name ?> </td>
                                                <td> <?= $pet->get_care_note($paymentData['id']) ?> </td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                        </tbody>
                                    </table>
                                </div>
                                <?php
                            }
                        }
						
                        ?>
                        <!---  /Pet Detail Section  --->

                        <!--div class="col-md-12 col-sm-12 col-xs-12 textareaField">
                          <div class="form-group">
                            <label>Booking Description.*</label>
                                            <div class="bookingDescription">Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</div>
                          </div>
                        </div--> 
                        <!--div class="col-md-12 col-sm-12 col-xs-12 paymentSection paymentbtn">
                                      <div class="form-group">
                                        <label><i class="fa fa-asterisk paymentIcon"></i> Reward points :</label>
                                              <div class="rewardExt"><?php //echo $user_reward_points; ?></div>
                                              <div class="rewardBtn"><a href="javascript:void(0);" id="rewardPoints">Use Points</a></div>
                                      </div>
                              </div>
                              <div class="col-md-9 col-sm-9 col-xs-12 paymentSection paymentbtn">
                                      <div class="form-group">
                                        <label><i class="fa fa-credit-card paymentIcon"></i> Credit amount:</label>
                                        <div class="rewardExt"><?php //echo $user_credits; ?></div>
                                              <div class="rewardBtn"><a href="javascript:void(0);" id="creditsPoints">Use Credit</a></div>
                                      </div>
                              </div-->
                        <!--div class="row-block">
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                        <?php
                        //echo $form->field($model, 'minimum_price', ['inputOptions' => [
                        //'class' => "form-control",
                        //]])->textInput(['maxlength' => 5, 'autofocus' => false])->label('Estimated booking price <span>*</span>');
                        ?>
                                        </div>
                                </div--->

                        <div class="row-block">
                            <div class="col-md-6 col-sm-6 col-xs-12">
<div class="row">
<div class="col-md-6 col-sm-6 col-xs-12">
<?php

                                echo $form->field($model, 'discount', ['inputOptions' => [
                                        'class' => "form-control",
                                        'data-orgprice' => $paymentData['amount']
                            ]])->textInput(['autofocus' => true])->label('Change price : <span></span>');
						
							?>
</div>
<div class="col-md-6 col-sm-6 col-xs-12">
<div class="form-group">
                                <div class="">

                                    <button type="button" class="orangeBtn updatepricebtn" id="finalprice">Update price</button></br>		
                                </div>
                            </div>
</div>
</div>
                                
                            </div>
<div class="col-md-6 col-sm-6 col-xs-12 paymentbutton" style="text-align:right;padding-top:25px">
                            <div class="paymentSection ">
                                <label class="totalAmount">Original price: </label>
                                <div class="rewardExt" >
									
                                    <?= CURRENCY_SIGN ?><b id="estimatedprice"><?php echo $paymentData['amount']; ?></b>
                                </div>
                        </div>			
                        <div class="paymentSection ">
                                <label class="totalAmount">Final Price: </label>
                                <div class="rewardExt">
                                    <?= CURRENCY_SIGN ?><b id="totalfinalprice"><?php echo ($paymentData['amount']) ?></b>
                                </div>
                        </div>
                        </div>
                        </div>

                        

                        
                        <div class="col-md-6 col-sm-6 col-xs-12 paymentbutton"> </div>                
                        <div class="col-md-12 col-sm-12 col-xs-12 paymentbutton">
                            <div class="captcha">
                                <div class="btnSubmit">
                                    <button type="submit" class="orangeBtn">Confirm</button></br>
                                </div>
                                <div class="btnSubmit" style="margin-right:20px;">
                                    <button type="button" class="orangeBtn" id="rejectbooking">Decline</button>
                                </div>
                            </div>
                        </div>
						
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    $('document').ready(function () {
        $('#finalprice').on('click', function () {
            var newinputamount = $('#bookingdiscount-discount').val();
            var originalAmount = $('#bookingdiscount-discount').data('orgprice');
            if (newinputamount == "") {
                return false;
            }
            if (parseFloat(newinputamount) >= 0) {
                if (parseFloat(originalAmount) < parseFloat(newinputamount)) {
                    alert("Cannot be more than Estimated price.");
                    return false;
                }
                $('#bookingdiscount-discount').val(parseFloat(newinputamount));
                $('#totalfinalprice').html(parseFloat(newinputamount));
                $('#final_amount').val(parseFloat(newinputamount));
            } else {
                alert("Please enter a valid amount.");
            }
        });

        $('#rejectbooking').on('click', function () {
            $.ajax({
                url: '<?php echo Url::to(['bookings/rejectbooking']); ?>',
                type: 'post',
                data: {'bookingid': '<?php echo $paymentData["id"]; ?>', 'renter_id': '<?php echo $paymentData["pet_renter_id"]; ?>', 'sitter_id': '<?php echo $paymentData["pet_sitter_id"]; ?>', 'owner_id': '<?php echo $paymentData["pet_owner_id"]; ?>', 'from_date': '<?php echo $paymentData["booking_from_date"]; ?>', 'to_date': '<?php echo $paymentData["booking_to_date"]; ?>', 'booking_name': '<?php echo $paymentData["name"]; ?>'},
                success: function (response) {
                    if (response == true) {
                        console.log('booking rejected');
                        window.location.href = '<?php echo SITE_URL; ?>' + 'bookings/index';
                    } else {
                        console.log('error updating booking');
                        return false;
                    }


                }
            });
        });

    });


</script>

