<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('yii', 'Book Now');



####= common methods =####
$session = Yii::$app->session;

$booking_data = $session->get('booking_data');

$servicesTypes = Yii::$app->commonmethod->getUserServices($booking_data['book_sitter_id'], true);
$number_of_pets = Yii::$app->commonmethod->getNumberOfPets();
$options = [];


foreach ($servicesTypes as $key => $val) {
    $options[$key] = ['data-tokens' => $val];
}
$searchservice_type = 0;
$date_from = '';
$date_to = '';
$searchedservice = '';
if (!empty($searchrequestdata)) {
    $searchservice_type = $searchrequestdata['service_type'];
    $date_from = $searchrequestdata['date_from'];
    $date_to = $searchrequestdata['date_to'];
    $searchedservice = $searchrequestdata['service_type'];
}

$session = Yii::$app->session;
$loggedingusertype = $session->get('loggedinusertype');


//lets set data for edit booking
$OLdBookingName = '';
$OldBookingDesc = '';
$oldBiookingFromdate = '';
$oldBookigntodate = '';
$oldNoofPets = '';
$oldPalDetails = [];
$fillpets = 0;
$Oldservices = '';
if(isset($requestedBookigData) && !empty($requestedBookigData)){
$OLdBookingName = $requestedBookigData['booking_name'];
$OldBookingDesc = $requestedBookigData['description'];
$Oldservices = $requestedBookigData['services'];
$oldBiookingFromdate = date('m/d/y',strtotime($requestedBookigData['booking_from_date']));
$oldBookigntodate = date('m/d/y',strtotime($requestedBookigData['booking_to_date']));
$oldNoofPets = $requestedBookigData['number_of_pets'];
	if(empty($palDetails) && !empty($requestedBookigData['pets'])){

	//$palDetails = $requestedBookigData['pets'];
	$oldPalDetails = json_encode($requestedBookigData['pets']);
	$fillpets = 1;
	}

}else{
	$oldPalDetails='';
}
$fillservices = 0;
$selectedservices = (isset($Oldservices) && !empty($Oldservices)?$Oldservices:$searchedservice);

if(!empty($selectedservices) && count($selectedservices)>0){
	if(count($selectedservices)>1){
	$fillservices = 1;
	$selectedservices = implode(',',$selectedservices);
	}else{
	$selectedservices = $selectedservices[0];
	}
}


?>

<script>
var fillpets = <?php echo $fillpets; ?>;
window.preFillPets = [];
if(fillpets == 1){
window.preFillPets = <?php echo $oldPalDetails; ?>;

}

$(document).ready(function(){
var petservices = "<?php echo $selectedservices ?>";
var fillservice = "<?php echo $fillservices ?>";
if(petservices != ''){
	
	var values=petservices;
if(fillservice == 1){
	$.each(values.split(","), function(i,e){
		$("#servicetype option[value='" + e + "']").prop("selected", true);
	});

	$('#servicetype').selectpicker('refresh');
	}else{	
	$('#servicetype').val(petservices);
	$('#servicetype').selectpicker('refresh');
	}
}
});
</script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<div class="row">
    <div class="col-xs-12">
        <h1><?= $this->title ?></h1>
    </div>
</div>
</div>
</header>
<section class="contentArea">
    <div class="container">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="row">
                <?php 
	
				echo $this->render('//common/sidebar'); ?>
                <div class="col-md-10 col-sm-12 col-xs-12">
                    <div class="backsrch">
                        <?php if ($loggedingusertype == OWNER) { ?>
                            <a class="head" href="<?php echo Url::to(['search/petsitter']) ?>" style="margin:0 0 0 25px;"> < Back to search results</a>
                        <?php } elseif ($loggedingusertype == RENTER) { ?>
                            <a class="head" href="<?php echo Url::to(['search/petrenter']) ?>" style="margin:0 0 0 25px;"> < Back to search results</a>
                        <?php } ?>

                    </div>
                    <div id="proceedbook" style="visibility:hidden;"></div>
                    <div class="formContent" style="margin:0;"> 

                        <img class="contact-bg" src="<?php echo WEBSITE_IMAGES_PATH . 'contact-bg.png'; ?>" alt="">
                        <div class="col-md-12 col-sm-8 col-xs-12">
                            <?php if (Yii::$app->session->getFlash('error')): ?>
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
                                    <i class="fa fa-remove"></i> <?php echo Yii::$app->session->getFlash('error'); ?>
                                </div>
                            <?php endif; ?>
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
                                echo $form->field($model, 'booking_type')->hiddenInput(['value' => OWNER])->label(false);
                                ?>
                            </div>
                            <div class="row-block">
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <?php
                                    echo $form->field($model, 'name', ['inputOptions' => [
                                            'class' => "form-control",
                                            'value' => (isset($OLdBookingName) && !empty($OLdBookingName)?$OLdBookingName:'')
                                ]])->textInput(['autofocus' => false])->label('Booking name *<p class="bookingnme">(Enter a short title for the booking so that you can remember)</p>');
                                    ?>
                                </div>
                            </div>						
                            <div class="row-block">
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <?php
                                    echo $form->field($model, 'booking_from_date', ['inputOptions' => [
                                            'class' => "form-control",
                                            'value' => (isset($oldBiookingFromdate) && !empty($oldBiookingFromdate)?$oldBiookingFromdate:$date_from),
                                ]])->textInput(['class' => 'datepicker', 'id' => 'booking_from_date', 'readOnly' => true, 'autofocus' => false])->label('Booking from date *');
                                    ?>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <?php
                                    echo $form->field($model, 'booking_to_date', ['inputOptions' => [
                                            'class' => "form-control",
                                            'value' => (isset($oldBookigntodate) && !empty($oldBookigntodate)?$oldBookigntodate:$date_to),
                                ]])->textInput(['class' => 'datepicker', 'id' => 'booking_to_date', 'readOnly' => true, 'autofocus' => false])->label('Booking to date *');
                                    ?>
                                </div>
                            </div>
                            <div class="row-block">
                                <div class="col-md-6 col-sm-6 col-xs-12 selectsearchoptions">
                                    <?php
									
                                    ##### number of pets
                                    $cities = Yii::$app->commonmethod->getNumberOfPets();
                                    $petnumbers = [];
                                    if(isset($oldNoofPets) && $oldNoofPets>0){
                                     $model->number_of_pets = $oldNoofPets; 
                                    }elseif (Yii::$app->request->post()['no_of_pals'] || count($palDetails) > 0) {
                                        $model->number_of_pets = Yii::$app->request->post()['no_of_pals'] ? Yii::$app->request->post()['no_of_pals'] : (count($palDetails['type']) - 1);
                                    } else {
                                        $model->number_of_pets = 1;
                                    }
                                    foreach ($cities as $kp => $vp) {
                                        $petnumbers[$kp] = ['data-tokens' => $vp];
                                    }
                                    echo $form->field($model, 'number_of_pets', ['inputOptions' => [
                                            'class' => "form-control whiteBorder selectpicker",
                                            'data-live-search' => "true",
                                ]])->dropDownList($cities, ['options' => $petnumbers])->label('No. of pets <span class="required">*</span>');
//                                Yii::$app->request->post()
                                    ?>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12 selectsearchoptions">
                                    <?php
									
                                    echo $form->field($model, 'services', ['inputOptions' => [
                                            'class' => "form-control whiteBorder selectpicker",
                                            'data-live-search' => "true",
                                            'id' => 'servicetype',
                                           // 'multiple' => true,
                                ]])->dropDownList($servicesTypes, ['options' => $options])->label('Services <span class="required">*</span>');
                                    ?>
                                </div>
                            </div>

                            <!-- pet details section -->
                            <!-- Do not change the structure of .pal-single div if you do so please make sure you do necessary changes in book-now.js file if required -->
                            <style>
                                .pal_single .bs-searchbox input{
                                    height:30px;
                                }
                                .pal_single .dropdown-menu.inner li .text{
                                    padding: 10px 0px;
                                }
                                ._remove_pal{
                                    display: none;
                                    color:#ff8447;
                                }

                                /*  Css for loader  */
                                @keyframes blink {
                                    0% {
                                        opacity: .2;
                                    }
                                    20% {
                                        opacity: 1;
                                    }
                                    100% {
                                        opacity: .2;
                                    }
                                }

                                .loading span {
                                    animation-name: blink;
                                    animation-duration: 1.4s;
                                    animation-iteration-count: infinite;
                                    animation-fill-mode: both;
                                    font-size: 18px;
                                    display: none;
                                }

                                .loading span:nth-child(2) {
                                    animation-delay: .2s;
                                }

                                .loading span:nth-child(3) {
                                    animation-delay: .4s;
                                }
                                /*  Css for loader ends here */

                                .ui-menu .ui-menu-item {
                                    border-bottom: 1px solid #c5c5c5;
                                    padding: 5px;
                                }
                            </style>

                            <div class="pal_single_org hidden col-md-12 col-sm-12 col-xs-12 paltype0" data-id="0">
                                <div class="col-md-4 col-sm-12 ">
                                    <div class="form-group">
                                        <label> Pal type </label>
                                        <?php
                                        $paltype = Yii::$app->commonmethod->getPetTypesSEARCH();
//                                    echo Html::dropDownList('palDetails[type][]', null, $paltype, [
//                                        'class' => "form-control whiteBorder selectpicker",
//                                        'data-live-search' => "true",
//                                        'id' => 'pal_type_' . $i
//                                    ]);

                                        ?>
                                        
                                        <select class="form-control" name='palDetails[type][]' style="height:50px;">
                                            <?php
									
                                            foreach ($paltype as $palTypeId => $palTypeName) {
                                                ?>
                                                <option value="<?= $palTypeId ?>"> <?= $palTypeName ?> </option>
                                                <?php
                                            }
                                            ?>
                                        </select>

                                    </div>
                                </div>

                                <div class="col-md-4 col-sm-12 pal_name_div">
                                    <div class="form-group">
                                        <label> Pal Name * <small class='_remove_pal' style="cursor:pointer;"><i>(remove)</i></small>
                                            <span class="loading"><span>.</span><span>.</span><span>.</span></span>
                                        </label>
                                        <input type="text" class="form-control booking_pal_name" name="palDetails[name][]" > 
                                        <input type="hidden" class="booking_user_pet_id" name="palDetails[id][]" > 
                                    </div>
                                </div>

                                <div class="col-md-4 col-sm-12 pal_note_div">
                                    <div class="form-group">
                                        <label>Care Note *</label>
                                        <textarea class="form-control textfeild" name="palDetails[care_note][]" maxlength="250" rows="2"></textarea>
                                    </div>
                                </div>

                            </div>

                            <div class="row-block pal_details pal_single_container" style="padding: 0 0 0 0px;">
                                <?php
								
                                for ($i = 1; $i <= $model->number_of_pets; $i++) {
                                    ?>
                                    <div class="pal_single col-md-12 col-sm-12 col-xs-12" data-id="<?= $i ?>">
                                        <div class="col-md-4 col-sm-12 ">
                                            <div class="form-group">
                                                <label> Pal type </label>
                                                <?php
//                                            echo Html::dropDownList('palDetails[type][]', null, $paltype, [
//                                                'class' => "form-control whiteBorder selectpicker",
//                                                'data-live-search' => "true",
//                                                'id' => 'pal_type_' . $i
//                                            ])
                                                ?>
                                                <select class="form-control" name='palDetails[type][]' style="height:50px;" <?php echo (count($palDetails) > 0 && trim($palDetails['id'][$i]) != "") ? 'readonly="readonly"' : '' ?>>
                                                    <?php
													
                                                    foreach ($paltype as $palTypeId => $palTypeName) {
                                                        ?>
                                                        <option value="<?= $palTypeId ?>" <?php echo (count($palDetails) > 0 && $palDetails['type'][$i] == $palTypeId) ? 'selected' : '' ?>> 
                                                            <?= $palTypeName ?> 
                                                        </option>
                                                        <?php
                                                    }
													
                                                    ?>
                                                </select>
                                                <?php 
								
												if (isset($userPet->petDetailsErrors['type'][$i]) AND !empty($userPet->petDetailsErrors['type'][$i])) { ?>
                                                    <p class="help-block help-block-error" style="color:#a94442"> <?= $userPet->petDetailsErrors['type'][$i] ?> </p>
                                                <?php }
													
						?>
                                            </div>
                                        </div>

                                        <div class="col-md-4 col-sm-12 pal_name_div">
                                            <div class="form-group">
                                                <label> Pal Name * <small class='_remove_pal' style="cursor:pointer;"><i>(remove)</i></small>
                                                    <span class="loading"><span>.</span><span>.</span><span>.</span></span>
                                                </label>
                                                <input type="text" class="form-control booking_pal_name" name="palDetails[name][]" value="<?php echo (count($palDetails) > 0 && trim($palDetails['name'][$i]) != "") ? trim($palDetails['name'][$i]) : '' ?>" <?php echo (count($palDetails) > 0 && trim($palDetails['id'][$i]) != "") ? ' readonly="readonly"' : '' ?>> 
                                                <input type="hidden" class="booking_user_pet_id" name="palDetails[id][]" value="<?php echo (count($palDetails) > 0 && trim($palDetails['id'][$i]) != "") ? trim($palDetails['id'][$i]) : '' ?>"> 
                                                <?php if(isset($userPet->petDetailsErrors['name'][$i]) AND !empty($userPet->petDetailsErrors['name'][$i])) { ?>
                                                    <p class="help-block help-block-error" style="color:#a94442"> <?= $userPet->petDetailsErrors['name'][$i] ?> </p>
                                                <?php } ?>
                                            </div>
                                        </div>

                                        <div class="col-md-4 col-sm-12 pal_note_div">
                                            <div class="form-group">
                                                <label>Care Note *</label>
                                                <textarea class="form-control textfeild" name="palDetails[care_note][]" maxlength="250" rows="2"><?php echo (count($palDetails) > 0 && trim($palDetails['care_note'][$i]) != '') ? trim($palDetails['care_note'][$i]) : '' ?></textarea>
                                                <?php if(isset($userPet->petDetailsErrors['care_note'][$i]) AND !empty($userPet->petDetailsErrors['care_note'][$i])) { ?>
                                                    <p class="help-block help-block-error" style="color:#a94442"> <?= $userPet->petDetailsErrors['care_note'][$i] ?> </p>
                                                <?php } ?>
                                            </div>
                                        </div>

                                    </div>
                                    <?php
                                }

                                ?>
                            </div>

                            <!-- /pet details section -->
                            <div class="row-block">
                                <div class="col-md-12 col-sm-6 col-xs-12">
                                    <?php
                                    echo $form->field($model, 'description', ['inputOptions' => [
                                            'class' => "form-control textfeild", 'id' => "search_destination1",
                                            'value' => (isset($OldBookingDesc) && !empty($OldBookingDesc)?$OldBookingDesc:'')
                                ]])->textarea(['rows' => '6', 'maxlength' => 250, 'autofocus' => false])->label('Additional notes');
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
                            <?php
							ActiveForm::end(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>

<!-- Please make sure you are using v1.12.1 of jquery-ui -->
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="<?= WEBSITE_JS_PATH ?>book-now.js"></script>
<script>
    $('document').ready(function () {
       // var servicetype = <?php echo $searchservice_type; ?>;
        //$('#servicetype').val(servicetype);
        
        var desiredHeight = $(window).height() - 650;	
			$('html, body').animate({
							scrollTop: $('#proceedbook').offset().top-desiredHeight
						}, 'slow');		
    	});

    $(function () {
        $("#booking_from_date").datepicker({
            numberOfMonths: 1,
            showButtonPanel: true,
            minDate: '<?= ADD_DAYS_JS ?>',
            maxDate: '+2Y',
            onSelect: function () {
                $("#booking_to_date").datepicker('option', 'minDate', $("#booking_from_date").datepicker("getDate"));
            }
        });

        $("#booking_to_date").datepicker({
            numberOfMonths: 1,
            showButtonPanel: true,
            minDate:$("#booking_from_date").datepicker("getDate"),
            maxDate: '+2Y',
        });
    });
    
  
</script>
