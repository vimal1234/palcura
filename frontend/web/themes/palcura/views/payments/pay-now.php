<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('yii', 'Pay Now');

####= common methods =####
$number_of_pets = Yii::$app->commonmethod->getNumberOfPets();
//$paymentData			=  Yii::$app->session->get('booking_details');
$paymentData = $bookingdata;

####= { $selectCol } use to get specific columns of users. 
$selectCol = "firstname,lastname,reward_points,sitter_credits,owner_credits";
$userInfo = Yii::$app->commonmethod->getUserColumnsData($paymentData['pet_sitter_id'], $selectCol);

$servicesName = Yii::$app->commonmethod->getServicesNamenew($paymentData['booking_services']);
$attributes = Yii::$app->user->identity->getattributes();
$session = Yii::$app->session;
$logged_user = $session->get('loggedinusertype');



if ($logged_user == BORROWER) {
    $userInfo = Yii::$app->commonmethod->getUserColumnsData($paymentData['pet_owner_id'], $selectCol);
}

if ($logged_user == OWNER) {
    $BookedUserName = "Sitter name";
    $user_reward_points = number_format($attributes['reward_points'],2);
    $totalUserCredits = number_format($attributes['sitter_credits'] + $attributes['owner_credits'],2);
    $user_credits = CURRENCY_SIGN . ($totalUserCredits);
    $usercredits2 = $attributes['sitter_credits'] + $attributes['owner_credits'];
    
} else if ($logged_user == BORROWER) {
    $BookedUserName = "Owner name";
    $user_reward_points = number_format($attributes['reward_points'],2);
    $totalUserCredits = number_format($attributes['sitter_credits'] + $attributes['owner_credits'],2);
    $user_credits = CURRENCY_SIGN . ($totalUserCredits);
    $usercredits2 = $attributes['sitter_credits'] + $attributes['owner_credits'];
}
    
    $usableCredit = Yii::$app->commonmethod->availableCreditPointsforUse();
    $showUsableCredit = CURRENCY_SIGN . ($usableCredit);
    
    $usablePoints = Yii::$app->commonmethod->availableRewardPointsforUse();

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
            <div class="col-md-10 col-sm-12 col-xs-12">
                <div class="formContent" style="margin:0;"> 
                    <img class="contact-bg" src="<?php echo WEBSITE_IMAGES_PATH . 'contact-bg.png'; ?>" alt="">
                    <div class="col-md-4 col-sm-4 col-xs-12 pull-right">
                        <div class="formContentSidebar"></div>
                    </div>

                    <div class="col-md-12 col-sm-8 col-xs-12">
                        <div id="msgbox" style="display:none;">
                        </div>	
                        <?php

                        $form = ActiveForm::begin(
                                        ['id' => 'paypal-now', 'method' => 'post', 'action' => Url::to(['payments/directpayment']),
                                            'options' => ['class' => 'inner'],
                                            'fieldConfig' => [
                                                'template' => "",
                                                'labelOptions' => [],
                                            ],
                        ]);
                        ?>
                        <input type="hidden" name="reward_status" id="reward_status" value="" /> 
                        <input type="hidden" name="credit_amount" id="credit_amount" value="" /> 
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
                        <div class="col-md-12 col-sm-12 col-xs-12" style="padding:0px;">
                            <div class="col-md-6 col-sm-6 col-xs-12 paymentSection singleLine">
                                <div class="form-group ">
                                    <label>Booking name :</label>
                                    <div class="bookingNameExt"><?= $paymentData['name'] ?></div>
                                </div>
                            </div>  
                            <div class="col-md-6 col-sm-6 col-xs-12 paymentSection singleLine">
                                <div class="form-group ">
                                    <label><?= $BookedUserName ?> :</label>
                                    <div class="bookingNameExt"><?= $userInfo['firstname'] . " " . $userInfo['lastname'] ?></div>
                                </div>
                            </div> 
                        </div>
                        <?php if (isset($servicesName) && !empty($servicesName)) { ?>
                            <div class="col-md-6 col-sm-6 col-xs-12 paymentSection singleLine">
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
                                        <!--
                                                                                <li>1. Over Night Care</li>
                                                                                <li>2. House Sitting</li>
                                                                                <li>3. Day Care</li>
                                                                                <li>4. Walking</li>
                                        -->
                                    </ol>
                                </div>
                            </div>   
                        <?php } ?>


                        <!---  Pet Detail Section  --->
                        <?php
                        if ($logged_user == OWNER) {
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
                        <div class="col-md-7 col-sm-12 col-xs-12">
                            <div class="col-md-12 col-sm-12 col-xs-12 paymentSection paymentbtn">
                                <div class="form-group">
                                    <label><i class="fa fa-asterisk paymentIcon"></i> Reward points :</label>
                                     <!--div class="rewardExt _available_reward_points" style="background-color: yellow;border-radius:5px;"><?= $user_reward_points ?></div-->
                                     <!--div class="rewardExt _remaining_reward_points" style="background-color: #9999ff;border-radius:5px;"><?= $usablePoints ?></div-->
                                     <div class="rewardExt _remaining_reward_points"><?= $usablePoints ?></div>
                                    <div class="rewardBtn"><a href="javascript:void(0);" id="rewardPoints">Use Points</a></div>
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12 paymentSection paymentbtn">
                                <div class="form-group">
                                    <label><i class="fa fa-credit-card paymentIcon"></i> Credit amount:</label>
                                    <!--div class="rewardExt _available_credit_amount" style="background-color: yellow;border-radius:5px;"><?= $user_credits ?></div-->
                                    <!--div class="rewardExt _remaining_credit_amount" style="background-color: #9999ff;border-radius:5px;"><?= $showUsableCredit ?></div-->
                                    <div class="rewardExt _remaining_credit_amount"><?= $showUsableCredit ?></div>
                                    <div class="rewardBtn"><a href="javascript:void(0);" id="creditsPoints">Use Credit</a></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5 col-sm-12 col-xs-12 paymentSection ">
                            <div class="col-md-12 col-sm-12 col-xs-12" style="padding: 10px 0;">
                                <div class="col-md-9 col-sm-9 col-xs-9">
                                    <label class="totalAmount">Total Amount :</label>
                                </div>
                                <div class="col-md-3 col-sm-3 col-xs-3 totalAmount">
                                    <?= CURRENCY_SIGN . ($paymentData['amount']) ?>
                                </div>
                            </div>
                            <style>
                                ._disabled{
                                    display: none;
                                }
                            </style>
                            <div class="col-md-12 col-sm-12 col-xs-12 _disabled" style="padding: 10px 0;">
                                <div class="col-md-9 col-sm-9 col-xs-9 totalAmount"> Reward Point discount <i class="fa fa-close _remove_reward_points" style="cursor:pointer;color:red;"> </i></div>
                                <div class="col-md-3 col-sm-3 col-xs-3 totalAmount">-<?= CURRENCY_SIGN ?><span class="_reward_point_discount">0</span> </div>
                            </div>

                            <div class="col-md-12 col-sm-12 col-xs-12 _disabled" style="padding: 10px 0;">
                                <div class="col-md-9 col-sm-9 col-xs-9 totalAmount"> Credit Amount discount <i class="fa fa-close _remove_credit_amount" style="cursor:pointer;color:red;"> </i></div>
                                <div class="col-md-3 col-sm-3 col-xs-3 totalAmount">-<?= CURRENCY_SIGN ?><span class="_credit_amount_discount">0</span> </div>
                            </div>

                            <div class="col-md-12 col-sm-12 col-xs-12 _disabled" style="border-top: 1px solid #000000; margin: 10px 0; padding: 10px 0;">
                                <div class="col-md-9 col-sm-9 col-xs-9 totalAmount"> Final Amount:</div>
                                <div class="col-md-3 col-sm-3 col-xs-3 totalAmount"> <?= CURRENCY_SIGN ?><span class="_discounted_amount"><?= (round($paymentData['amount'],2)) ?></span></div>
                            </div>
                            <input type='hidden' value='' name='_n_s_b_d' class="_n_s_b_d">
                        </div>
						<div class="col-md-12 col-sm-12 col-xs-12 paymentbutton">
						<strong>Note:Above points/rewards are from completed booking only.</strong>
						</div>
                        <div class="col-md-12 col-sm-12 col-xs-12 paymentbutton">
                            <div class="captcha">
                                <div class="btnSubmit">
                                    <button type="submit" class="orangeBtn">Pay	</button>
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

// Class to manage payments, reward points and credit amount
    var managePayment = function (billingAmount, rewardPoints, creditAmount) {
        // selectors for reward point and credit amount which are hidden
        this.rewardPointInput = $('#reward_status');
        this.creditAmountInput = $('#credit_amount');
        this.rewardPointDiscountField = $('._reward_point_discount');
        this.creditAmountDiscountField = $('._credit_amount_discount');
        this.discountedAmountField = $('._discounted_amount');
        this.remainingRewardPointsField = $('._remaining_reward_points');
        this.remainingCreditAmountField = $('._remaining_credit_amount');
        this.notSecureBillingDetails = $('._n_s_b_d');
//        this.removeRewardPoints = $('._remove_reward_points');
//        this.removeCreditAmount = $('._remove_credit_amount');
        // ewardUnitCount means how many reward point make a unit for now its 500
        this.rewardUnitCount = 500;
        // rewardUnitPrice means how much credit (money) one reward unit provides
        this.rewardUnitPrice = 5;
        this.orgBillingAmount = parseFloat(billingAmount);
        this.rewardPoints = parseFloat(rewardPoints);
        this.creditAmount = parseFloat(creditAmount);

        // Function to check if user want to use rewardPoints
        this.isRewardPointSelected = function () {
            return (this.rewardPointInput.val() == 1) ? true : false;
        };
        // Function to check if user want to use created amount
        this.isCreditAmountSelected = function () {
            return (this.creditAmountInput.val() == 1) ? true : false;
        };
        // Use reward points and then credit amount
        this.applyRewardsAndThenCredits = function () {
            if (this.isRewardPointSelected()) {
                var rewardPointCredits = this.getRewardPointCredits();
                if (this.orgBillingAmount < rewardPointCredits) {
                    var canUseRewardCredit = (Math.floor(this.orgBillingAmount / this.rewardUnitPrice) * this.rewardUnitPrice);
                    rewardPointCredits = canUseRewardCredit;
                }
                var remainingRewardPoints = this.rewardPoints - ((rewardPointCredits / this.rewardUnitPrice) * this.rewardUnitCount);
                var newBillingAmount = this.orgBillingAmount - rewardPointCredits;
            } else {
                var newBillingAmount = this.orgBillingAmount;
                var rewardPointCredits = 0;
                var remainingRewardPoints = this.rewardPoints;
            }

            if (this.isCreditAmountSelected()) {
                if (newBillingAmount == 0) {
                    this.creditAmountInput.val(0);
                    alert('Can not use credit amount.');
                    return false;
                } else {
                    // Please keep these line in same sequence
                    if (newBillingAmount > this.creditAmount) {
                        var newBillingAmount = newBillingAmount - this.creditAmount;
                        var creditAmount = this.creditAmount;
                        var remainingCreditAmount = 0;
                    } else {
                        // Please keep these line in same sequence
                        var creditAmount = newBillingAmount;
                        var remainingCreditAmount = this.creditAmount - newBillingAmount;
                        var newBillingAmount = 0;
                    }
                }
            } else {
                var creditAmount = 0;
                var remainingCreditAmount = this.creditAmount;
            }
            this.updateDom(newBillingAmount, rewardPointCredits, remainingRewardPoints, creditAmount, remainingCreditAmount);
        };

        // Use credit amount and then reward points
        this.applyCreditsAndThenRewards = function () {
            if (this.isCreditAmountSelected()) {
                // Please keep these line in same sequence
                if (this.orgBillingAmount > this.creditAmount) {
                    var newBillingAmount = this.orgBillingAmount - this.creditAmount;
                    var creditAmount = this.creditAmount;
                    var remainingCreditAmount = 0;
                } else {
                    // Please keep these line in same sequence
                    var creditAmount = this.orgBillingAmount;
                    var remainingCreditAmount = this.creditAmount - this.orgBillingAmount;
                    var newBillingAmount = 0;
                }
            } else {
                var creditAmount = 0;
                var remainingCreditAmount = this.creditAmount;
                var newBillingAmount = this.orgBillingAmount;
            }

            if (this.isRewardPointSelected()) {
                if (newBillingAmount == 0) {
                    this.rewardPointInput.val(0);
                    alert('Can not use reward points.');
                    return false;
                } else {
                    var rewardPointCredits = this.getRewardPointCredits();
                    if (newBillingAmount < rewardPointCredits) {
                        var canUseRewardCredit = (Math.floor(newBillingAmount / this.rewardUnitPrice) * this.rewardUnitPrice);
                        rewardPointCredits = canUseRewardCredit;
                    }
                    var remainingRewardPoints = this.rewardPoints - ((rewardPointCredits / this.rewardUnitPrice) * this.rewardUnitCount);
                    newBillingAmount = newBillingAmount - rewardPointCredits;
                }
            } else {
                var newBillingAmount = newBillingAmount;
                var rewardPointCredits = 0;
                var remainingRewardPoints = this.rewardPoints;
            }
            this.updateDom(newBillingAmount, rewardPointCredits, remainingRewardPoints, creditAmount, remainingCreditAmount);
        };

        this.removeRewardPoints = function () {
            if (!this.isRewardPointSelected()) {
                return false;
            }
            this.rewardPointInput.val(0);
            this.applyCreditsAndThenRewards();
        };

        this.removeCreditAmount = function () {
            if (!this.isCreditAmountSelected()) {
                return false;
            }
            this.creditAmountInput.val(0);
            this.applyRewardsAndThenCredits();
        };


        this.updateDom = function (billingAmount, rewardPointCredits, remainingRewardPoints, creditAmount, remainingCreditAmount) {
        //round value of price
       		remainingCreditAmount =  remainingCreditAmount.toFixed(2);
       		creditAmount  = creditAmount.toFixed(2);
       		remainingRewardPoints = remainingRewardPoints.toFixed(2);
       		rewardPointCredits = rewardPointCredits.toFixed(2);
       		billingAmount =  billingAmount.toFixed(2);
       		
            console.log("billingAmount " + billingAmount);
            console.log("rewardPointCredits " + rewardPointCredits);
            console.log("remainingRewardPoints " + remainingRewardPoints);
            console.log("creditAmount " + creditAmount);
            console.log("remainingCreditAmount " + remainingCreditAmount);
            this.remainingRewardPointsField.text(remainingRewardPoints);
            this.remainingCreditAmountField.text('$'+remainingCreditAmount);
            this.rewardPointDiscountField.text(rewardPointCredits);
            this.creditAmountDiscountField.text(creditAmount);
            this.discountedAmountField.text(billingAmount);
            if (creditAmount > 0 || rewardPointCredits > 0) {
                if (rewardPointCredits > 0) {
                    this.rewardPointDiscountField.parent().parent().removeClass('_disabled');
                } else {
                    this.rewardPointDiscountField.parent().parent().addClass('_disabled');
                }
                if (creditAmount > 0) {
                    this.creditAmountDiscountField.parent().parent().removeClass('_disabled');
                } else {
                    this.creditAmountDiscountField.parent().parent().addClass('_disabled');
                }
                this.discountedAmountField.parent().parent().removeClass('_disabled');
            } else {
                this.discountedAmountField.parent().parent().addClass('_disabled');
                this.rewardPointDiscountField.parent().parent().addClass('_disabled');
                this.creditAmountDiscountField.parent().parent().addClass('_disabled');
            }
            // Passing not secure data to php through form post using hidden field
            var nSecureData = {
                billingAmount: billingAmount,
                rewardPointCredits: rewardPointCredits,
                remainingRewardPoints: remainingRewardPoints,
                creditAmount: creditAmount,
                remainingCreditAmount: remainingCreditAmount
            };
            this.notSecureBillingDetails.val(JSON.stringify(nSecureData));
        };

        this.getRewardPointCredits = function () {
            var rewardUnits = Math.floor(this.rewardPoints / this.rewardUnitCount);
            return this.rewardPointCredits = rewardUnits * this.rewardUnitPrice;
        };
    };

    $(function () {
<?php
// test cases
//$usercredits2 = 12;
//$paymentData['amount'] = 22;
?>
        var payNowObj = new managePayment(parseFloat(<?= ($paymentData['amount']) ?>), parseFloat(<?= $usablePoints ?>), parseFloat(<?= $usableCredit ?>));
        payNowObj.applyRewardsAndThenCredits();
        $('._remove_reward_points').click(function () {
            payNowObj.removeRewardPoints();
        });

        $('._remove_credit_amount').click(function () {
            payNowObj.removeCreditAmount();
        });

        $("#rewardPoints").click(function () {
            if ($('#reward_status').val() == 1) {
                return false;
            }
            $('#reward_status').val(1);
            var usrPoints = "<?= $usablePoints ?>";
            $("#msgbox").css("display", "block");
            if (usrPoints >= 500) {
                if (payNowObj.isCreditAmountSelected()) {
                    payNowObj.applyCreditsAndThenRewards();
                } else {
                    payNowObj.applyRewardsAndThenCredits();
                }
                $("#msgbox").html('<div class="alert alert-grey alert-dismissible" > <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span> </button> <i class="fa fa-check"></i>Reward points have been applied successfully.</div>');
            } else {
                $("#msgbox").html('<div class="alert alert-grey alert-dismissible" > <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span> </button> <i class="fa fa-remove"></i>Minimum 500 reward points are required.</div>');
            }

        });

        $("#creditsPoints").click(function () {
            if ($('#credit_amount').val() == 1) {
                return false;
            }
            $('#credit_amount').val(1);
            var usrCredits = "<?= $usableCredit ?>";
            $("#msgbox").css("display", "block");
            if (usrCredits > 0) {
                if (payNowObj.isRewardPointSelected()) {
                    payNowObj.applyRewardsAndThenCredits();
                } else {
                    payNowObj.applyCreditsAndThenRewards();
                }
                $("#msgbox").html('<div class="alert alert-grey alert-dismissible" > <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span> </button> <i class="fa fa-check"></i>Credit points have been applied successfully.</div>');
            } else {
                $("#msgbox").html('<div class="alert alert-grey alert-dismissible" > <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span> </button> <i class="fa fa-remove"></i>Insufficent balance .</div>');
            }


        });
        $(".close2").click(function () {
            $("#msgbox").css("display", "none");
        });

    });
</script>
