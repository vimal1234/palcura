<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('yii', 'Book Now');
####= common methods =####
$session = Yii::$app->session;
$loggedingusertype = $session->get('loggedinusertype');
$booking_data = $session->get('booking_data');

//lets set data for edit booking
$OLdBookingName = '';
$OldBookingDesc = '';
$oldBiookingFromdate = '';
$oldBookigntodate = '';

if(isset($requestedBookigData) && !empty($requestedBookigData)){
$OLdBookingName = $requestedBookigData['booking_name'];
$OldBookingDesc = $requestedBookigData['description'];
$oldBiookingFromdate = date('m/d/y',strtotime($requestedBookigData['booking_from_date']));
$oldBookigntodate = date('m/d/y',strtotime($requestedBookigData['booking_to_date']));

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
            <div class="col-md-10 col-sm-12 col-xs-12">
                <div class="backsrch">
                    <?php if ($loggedingusertype == RENTER) { ?>
                        <a class="head" href="<?php echo Url::to(['search/petrenter']) ?>" style="margin:0 0 0 25px;"> < Back to search results</a>
                    <?php } ?>
                </div>
                <div class="formContent" style="margin:0;"> 
                    <img class="contact-bg" src="<?php echo WEBSITE_IMAGES_PATH . 'contact-bg.png'; ?>" alt="">
<!--                    <div class="col-md-4 col-sm-4 col-xs-12 pull-right">
                        <div class="formContentSidebar"></div>
                    </div>-->
                    <div class="col-md-12 col-sm-8 col-xs-12">
                        <?php if (Yii::$app->session->getFlash('item')): ?>
                        <script>
						$(document).ready(function () {
						// Handler for .ready() called.
						$('html, body').animate({
							scrollTop: $('#scrrollhere').offset().top
						}, 'slow');
						});
												
						</script>
                            <div class="alert alert-grey alert-dismissible" id="scrrollhere">
                                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span>
                                </button>
                                <i class="fa fa-check"></i> <?php echo Yii::$app->session->getFlash('item'); ?>
                            </div>
                        <?php endif; ?>
                        <?php
                        $form = ActiveForm::begin([
                                    'id' => 'editProfile-form',
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
                        ?>
                        <div class="hidden">
                            <?php
                            echo $form->field($model, 'booking_type')->hiddenInput(['value' => RENTER])->label(false);
                            ?>
                        </div>
                        <div class="row-block">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php
                                echo $form->field($model, 'name', ['inputOptions' => [
                                        'class' => "form-control",
                                         'value' => (isset($OLdBookingName)?$OLdBookingName:'')
                            ]])->textInput(['autofocus' => false])->label('Booking name *<p>(Enter a short title for the booking so that you can remember)</p>');
                                ?>
                            </div>
                        </div>						
                        <div class="row-block">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php
                                echo $form->field($model, 'booking_from_date', ['inputOptions' => [
                                        'class' => "form-control",
                                        'value' => (isset($oldBiookingFromdate) && !empty($oldBiookingFromdate)?$oldBiookingFromdate:$date_from)
                            ]])->textInput(['class' => 'datepicker', 'id' => 'booking_from_date', 'readOnly' => true, 'autofocus' => false])->label('Booking from date *');
                                ?>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php
                                echo $form->field($model, 'booking_to_date', ['inputOptions' => [
                                        'class' => "form-control",
                                        'value' => (isset($oldBookigntodate) && !empty($oldBookigntodate)?$oldBookigntodate:$date_to)
                            ]])->textInput(['class' => 'datepicker', 'id' => 'booking_to_date', 'readOnly' => true, 'autofocus' => false])->label('Booking to date *');
                                ?>
                            </div>
                        </div>
                        <div class="row-block">
                            <div class="col-md-12 col-sm-6 col-xs-12">
                                <?php
                                echo $form->field($model, 'description', ['inputOptions' => [
                                        'class' => "form-control textfeild", 'id' => "search_destination1",
                                        'value' => (isset($OldBookingDesc) && !empty($OldBookingDesc)?$OldBookingDesc:'')
                            ]])->textarea(['rows' => '6', 'maxlength' => 250, 'autofocus' => false])->label('Description <span>*</span>');
                                ?>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="captcha">
                                <?php
                                //$form->field($model, 'reCaptcha')->widget(\yii\recaptcha\ReCaptcha::className(),['siteKey' => '6LfTjjIUAAAAAN9nIrkQ46jttq6fLYwYVe8iMEgg'])->label(false);;	
                                ?>
                                <?=
                                Html::submitButton(Yii::t('yii', 'Proceed'), ['class' => 'orangeBtn', 'name' => 'proceedPayment', 'id' => 'proceedPayment'])
                                ?>
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
    $(function () {
        $("#booking_from_date").datepicker({
            numberOfMonths: 1,
            showButtonPanel: true,
            minDate: '<?= ADD_DAYS_JS ?>',
            maxDate: '+1Y',
            onSelect: function () {
                $("#booking_to_date").datepicker('option', 'minDate', $("#booking_from_date").datepicker("getDate"));
            }
        });

        $("#booking_to_date").datepicker({
            numberOfMonths: 1,
            showButtonPanel: true,
            minDate: $("#booking_from_date").datepicker("getDate"),	
            maxDate: '+1Y',
        });
    });
</script>
