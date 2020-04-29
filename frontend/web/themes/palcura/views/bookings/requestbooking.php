<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('yii', 'Request Booking');


####= common methods =####
$number_of_pets = Yii::$app->commonmethod->getNumberOfPets();
$paymentData = Yii::$app->session->get('booking_details');



####= { $selectCol } use to get specific columns of users. 
$selectCol = "firstname,lastname,reward_points,sitter_credits,owner_credits";
$userInfo = Yii::$app->commonmethod->getUserColumnsData($paymentData['pet_sitter_id'], $selectCol);
$servicesName = Yii::$app->commonmethod->getServicesName($paymentData['services']);
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
$session = Yii::$app->session;
$loggedingusertype = $session->get('loggedinusertype');
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
			
			<?php if (Yii::$app->session->getFlash('items')): ?>
						
						<script>
						$(document).ready(function () {
						// Handler for .ready() called.
						$('html, body').animate({
							scrollTop: $('#scrrollhere').offset().top
						}, 'slow');
						});
												
						</script>
							<div class="col-md-10 col-sm-12 col-xs-12" id="scrrollhere">
								<div class="alert alert-grey alert-dismissible">
									<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span>
									</button>
									<?php echo Yii::$app->session->getFlash('items'); ?>
								</div>
							</div>
						<?php endif; ?>
            <div class="col-md-10 col-sm-12 col-xs-12">
                <?php if ($loggedingusertype == OWNER) { ?>
                    <a class="head" href="<?php echo Url::to(['search/petsitter']) ?>" style="margin:0 0 0 25px;"> Back to search results</a>
                <?php } elseif ($loggedingusertype == RENTER) { ?>
                    <a class="head" href="<?php echo Url::to(['search/petrenter']) ?>" style="margin:0 0 0 25px;"> Back to search results</a>
                <?php } ?>
				<div id="editbook" style="visibility:hidden;"></div>
                <div class="formContent" style="margin:0;"> 
					
                    <img class="contact-bg" src="<?php echo WEBSITE_IMAGES_PATH . 'contact-bg.png'; ?>" alt="">
                    <!--div class="col-md-4 col-sm-4 col-xs-12 pull-right">
                        <div class="formContentSidebar"></div>
                    </div-->
					
					
						
					
                    <div class="col-md-12 col-sm-8 col-xs-12">
                        <div id="msgbox" style="display:none;">
                        </div>	
                        <?php
                        $form = ActiveForm::begin(
                                        ['id' => 'paypal-now', 'method' => 'post', 'action' => Url::to(['bookings/requestbooking']),
                                            'options' => ['class' => 'inner'],
                                            'fieldConfig' => [
                                                'template' => "",
                                                'labelOptions' => [],
                                            ],
                        ]);
                        ?>
						
                        <input type="hidden" name="booking_request" id="booking_request" value="1" /> 

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
                                <div class="bookingNameExt"><?= $paymentData['booking_name'] ?></div>
                            </div>
                        </div>  
                        <div class="col-md-6 col-sm-6 col-xs-12 paymentSection singleLine">
                            <div class="form-group ">
                                <label><?= $BookedUserName ?> :</label>
                                <div class="bookingNameExt"><?= $userInfo['firstname'] . " " . $userInfo['lastname'] ?></div>
                            </div>
                        </div> 
                        <?php if (isset($servicesName) && !empty($servicesName)) { ?>
                            <div class="col-md-6 col-sm-6 col-xs-6 paymentSection singleLine">
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
//                        echo "<pre>";
//                        print_r($paymentData['pets']);
//                        die;
                        if (isset($paymentData['pets']) && count($paymentData['pets']) > 0) {
                            ?>
                            <div class="col-md-6 col-sm-6 col-xs-12 paymentSection singleLine">
                                <div class="form-group ">
                                    <label>Number of pets :</label>
                                    <div class="bookingNameExt"><?= count($paymentData['pets']) ?></div>
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
                                    foreach ($paymentData['pets'] as $key => $pet) {
                                        ?>
                                        <tr>
                                            <td> <?= $key + 1 ?> </td>
                                            <td> <?= $pet['type_name'] ?> </td>
                                            <td> <?= $pet['name'] ?> </td>
                                            <td> <?= $pet['care_note'] ?> </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php
                        }
                        ?>
                        <!---  /Pet Detail Section  --->


                        
                        <div class="col-md-12 col-sm-12 col-xs-12 paymentSection ">
                            <div class="form-group">
                                <label class="totalAmount">Total Amount :</label>
                                <div class="rewardExt">
                                    <?= CURRENCY_SIGN . $paymentData['booking_amount'] ?>
<?php if(isset($paymentData['pets']) && count($paymentData['pets'])>=1){ ?>
                                    <small>(<?= CURRENCY_SIGN. ($paymentData['booking_amount']/count($paymentData['pets'])) ?>/pet)</small>
<?php } ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 col-sm-12 col-xs-12 paymentbutton">
                            <div class="captcha ctaForm">
<!--
                                <div class="btnSubmit">
                                    <button type="submit" class="orangeBtn">Request</button>              
                                </div>
							
                                <div class="btnSubmit btnTwo">
                                    <a  class="orangeBtn" href="<?php echo Url::to(['bookings/book-now']); ?>" >Edit</a>             
                                </div>-->
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
$('document').ready(function(){			
			var desiredHeight = $(window).height() - 650;	
			$('html, body').animate({
							scrollTop: $('#editbook').offset().top-desiredHeight
						}, 'slow');	
	
});
</script>
