<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use common\models\Country;

$this->title = 'Sign Up';
$this->params['breadcrumbs'][] = $this->title;
$siteimage = Yii::getAlias('@siteimage');
$ajaxSUrl	= SITE_URL.'ajax/updatestates';
$ajaxCUrl	= SITE_URL.'ajax/updatecities';
####= common methods =####
$countries 				= Yii::$app->commonmethod->countries();
$regions 				= Yii::$app->commonmethod->regions();
$cities 				= Yii::$app->commonmethod->cities();
$servicesTypes 			= Yii::$app->commonmethod->servicesTypes();
$hear_about 			= array(1=>'Initial launch',2=>'Google',3=>'Facebook',4=>'Instagram',5=>'Twitter',6=>'Next door',7=>'Other Social',8=>'Event',9=>'Dog Park',10=>'Flyers',11=>'Word of mouth',12=>'Referral',13=>'Other');
$pet_type 				= Yii::$app->commonmethod->getPetTypes();
$pet_parent_id			= 0;
$pet_parent_id 			= (isset($pet_type) ? key($pet_type) : 0 );
$pet_sub_type 			= Yii::$app->commonmethod->getPetTypes($pet_parent_id);
$pet_weight_limit 		= array("1" => "1 Kg","2" => "2 Kg","3" => "3 Kg","4" => "4 Kg","5" => "5 Kg","6" => "6 Kg");
$house_size 			= Yii::$app->commonmethod->getHouseSize();
$residential_status 	= Yii::$app->commonmethod->residentialStatus();
$number_of_pets		 	=  Yii::$app->commonmethod->getNumberOfPets();
$income 				= Yii::$app->commonmethod->getIncome();
$children_count			= Yii::$app->commonmethod->getChildrenCount();

$serviceOptions	=	"";
foreach($servicesTypes as $key=>$row) {
	$serviceOptions .='<option value="'.$key.'">'.$row.'</option>';
}
?>
<script>
	$(document).ready(function() {
		var defaultSelected = $("#adduserform-user_type option:selected").val();
		if(defaultSelected == 1) {
			$(".pet_t_sr").css("display","none");
			$(".pet_t_ow").css("display","block");
		} else if(defaultSelected == 2) {
			$(".pet_t_ow").css("display","none");
			$(".pet_t_sr").css("display","block");
		} else if(defaultSelected == 3) {
			$(".pet_t_ow").css("display","none");
			$(".pet_t_sr").css("display","block");
		}

		$("#adduserform-user_type").change(function() {
			var pet_type = $(this).val();
			if(pet_type == 1) {
				$(".pet_t_sr").css("display","none");
				$(".pet_t_ow").css("display","block");
			} else if(pet_type == 2) {
				$(".pet_t_ow").css("display","none");
				$(".pet_t_sr").css("display","block");
			} else if(pet_type == 3) {
				$(".pet_t_ow").css("display","none");
				$(".pet_t_sr").css("display","none");
			}
		});

		$('.other_type_ow').css("display","none");
		$(".renting_pet").change(function() {
			if($('.renting_pet:checked').val() == 1) {
				$('.rending_block').css("display","block");
			} else {
				$('.rending_block').css("display","none");
			}
		});

		$('#addmore_services').click(function() {
			var name = $("#addmore_services").attr("name");
			var newname	=	parseInt(name)+1;
			$('#addmore_services').attr('name', newname);
			$('#addmoreblock').append('<div id="'+newname+'_outer" class="row-block"><div class="col-md-4 col-sm-6 col-xs-12"><span class="field-adduserform-services has-success"><div class="form-group"><label for="adduserform-services">Services <span class="required">*</span></label><select id="adduserform-services" class="form-control whiteBorder" name="AddUserForm[services][]">'+'<?= $serviceOptions ?>'+'</select><div class="col-lg-10"><p class="help-block help-block-error"></p></div></div></span></div><div class="col-md-4 col-sm-6 col-xs-12"><span class=" field-adduserform-day_price"><div class="form-group"><label for="adduserform-day_price">Day Price <span>*</span></label><input id="adduserform-day_price" class="form-control" name="AddUserForm[day_price][]" maxlength="5" min="1" autofocus="" type="number"><div class="col-lg-10"><p class="help-block help-block-error"></p></div></div></span></div><div class="col-md-4 col-sm-6 col-xs-12"><a href="javascript:void(0)" class="orangeBtn remove_service_row" id="'+newname+'" >-</a></div></div>');
		});
		
		$('#addmoreblock').on('click', '.remove_service_row', function() {
			var ID = $(this).attr('id');
			$('#'+ID+'_outer').remove();
		});
		
		$("#adduserform-dob").datepicker({
			numberOfMonths: 1,
			showButtonPanel: true,
			changeYear: true,
yearRange: "-100:+0",
			minDate: '-100Y',
			maxDate: '-1D',
		});
	});
</script>
	<div class="row">
		<div class="col-xs-12">
			<h1>Sign Up</h1>
		</div>
	</div>
</div>
</header>
<section class="contentArea">
	<div class="container">
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<h2 class="text-center contactTilte">Register with palcura</h2>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">	  
				<div class="formContent">
					<img class="contact-bg" src="<?= $siteimage ?>/contact-bg.png" alt="">
					<div class="col-md-12 col-sm-12 col-xs-12">
					<?php if (Yii::$app->session->getFlash('item')): ?>
						<div class="alert alert-grey alert-dismissible">
							<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span>
							</button>
							<i class="fa fa-check"></i> <?php echo Yii::$app->session->getFlash('item'); ?>
						</div>
					<?php endif; ?>
					<?php
					$form = ActiveForm::begin([
					'id' => 'frmSignupUser',
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
					
						<div class="row-block">
							<div class="col-md-4 col-sm-6 col-xs-12">
								<?php
									$p_type = array(OWNER => "Pet Owner", SITTER => "Pet Sitter", BORROWER => "Pet Borrower");
									echo $form->field($model, 'user_type', ['inputOptions' => [
									'class' => "form-control whiteBorder",
									]])->dropDownList($p_type,[])->label('User Type <span class="required">*</span>');
								?>
							</div>
						</div>		
						<div class="row-block">
							<div class="col-md-4 col-sm-6 col-xs-12">
								<?php
									#### profile image
									echo $form->field($modelImageUpload['modelImgA'], 'profile_image',['inputOptions' => [
									'class' => "form-control imageBrowser",
									]])->fileInput()->label('Display Image');
								?>
							</div>
						</div>																		
						<div class="row-block">
							<div class="col-md-4 col-sm-6 col-xs-12">
								<label class="headingType">Prefix *</label>
								<div class="select-contact">
									<select class="form-control">
										<option value="1">Mr.</option>
										<option value="2">Miss.</option>
										<option value="3">Mrs.</option>
										<option value="4">Ms.</option>
									</select>
								</div>
							</div>
						<div class="col-md-4 col-sm-6 col-xs-12">
								<?php
									echo $form->field($model, 'firstname', ['inputOptions' => [
									'class' => "form-control",
									]])->textInput(['maxlength' => 40, 'autofocus' => true])->label('First Name *');
								?>
							</div>
							<div class="col-md-4 col-sm-6 col-xs-12">
								<?php
									echo $form->field($model, 'lastname', ['inputOptions' => [
									'class' => "form-control",
									]])->textInput(['maxlength' => 40, 'autofocus' => true])->label('Last Name *');
								?>                
							</div>							
						</div>
						<div class="row-block">
	
							<div class="col-md-4 col-sm-6 col-xs-12">
								<?php
									echo $form->field($model, 'email', ['inputOptions' => [
									'class' => "form-control",
									]])->textInput(['maxlength' => 100, 'autofocus' => true])->label('Email *');
								?>
							</div>
							<div class="col-md-4 col-sm-6 col-xs-12">
								<?php
									echo $form->field($model, 'password', ['inputOptions' => [
									'class' => "form-control",
									]])->passwordInput()->label('Password <span class="required">*</span>');
								?>                                    
							</div>    
							<div class="col-md-4 col-sm-6 col-xs-12">
								<?php
									echo $form->field($model, 'repeat_password', ['inputOptions' => [
									'class' => "form-control",
									]])->passwordInput()->label('Confirm Password <span class="required">*</span>');
								?>
							</div>							
						</div>
						<div class="row-block">

							<div class="col-md-4 col-sm-6 col-xs-12">
								<?php
									echo $form->field($model, 'phone', ['inputOptions' => [
									'class' => "form-control",
									]])->textInput(['maxlength'=>15 , 'autofocus' => true])->label('Phone No. <span>*</span>');
								?>                
							</div>   
							<div class="col-md-4 col-sm-6 col-xs-12">
								<?php
									echo $form->field($model, 'dob', ['inputOptions' => [
									'class' => "form-control datepicker",
									]])->textInput(['maxlength'=>15 , 'autofocus' => true, 'readOnly' => true])->label('Date Of Birth <span>*</span>');
								?>
							</div>	
							<div class="col-md-4 col-sm-6 col-xs-12">
							<?php echo $form->field($model, 'interested_in_renting')->hiddenInput(['value'=> '1'])->label(false); ?>						               </div>	                                
						</div>
						<div class="row-block">                                                

							<div class="col-md-4 col-sm-6 col-xs-12">
								<?php
									echo $form->field($model, 'address', ['inputOptions' => [
									'class' => "form-control textfeild",'id' => "search_destination1"
									]])->textInput(['maxlength' => 250, 'autofocus' => true])->label('Address <span>*</span>');
								?>
							</div>
							<div class="col-md-4 col-sm-6 col-xs-12">
								<?php
									echo $form->field($model, 'zip_code', ['inputOptions' => [
									'class' => "form-control",
									]])->textInput(['maxlength'=>15 , 'autofocus' => true])->label('Zip/Postal Code <span>*</span>');
								?>   				  
							</div>
						</div>
						<div class="row-block">
							<div class="col-md-4 col-sm-6 col-xs-12 selectsearchoptions">
								<?php
									$countryarray = [];
									foreach ($countries as $kp=>$vp) {
										$countryarray[$kp] = ['data-tokens' => $vp];  
									}								
									echo $form->field($model, 'country', ['inputOptions' => [
									'class' => "form-control whiteBorder selectpicker" ,
									'data-live-search'=>"true",
									]])->dropDownList($countries,['prompt'=>'Select Country','options' => $countryarray])->label('Country <span class="required">*</span>');
								?>
							</div>							
							<div class="col-md-4 col-sm-6 col-xs-12 selectsearchoptions">
								<?php
									$regionsarray = [];
									foreach ($regions as $kp=>$vp) {
										$regionsarray[$kp] = ['data-tokens' => $vp];  
									}									
									echo $form->field($model, 'region', ['inputOptions' => [
									'class' => "form-control whiteBorder selectpicker",
									'data-live-search'=>"true",
									]])->dropDownList($regions,['prompt'=>'Select State/Province','options' => $regionsarray])->label('State/Province <span class="required">*</span>');
								?>
							</div>		
							<div class="col-md-4 col-sm-6 col-xs-12 selectsearchoptions">
								<?php
									$cityarray = [];
									foreach ($cities as $kp=>$vp) {
										$cityarray[$kp] = ['data-tokens' => $vp];  
									}										
									echo $form->field($model, 'city', ['inputOptions' => [
									'class' => "form-control whiteBorder selectpicker",
									'data-live-search'=>"true",
									]])->dropDownList($cities,['prompt'=>'Select 
									City','options' => $cityarray])->label('City <span class="required">*</span>');
								?>
							</div>
						</div>
						<div class="row-block">
							<div class="col-md-4 col-sm-6 col-xs-12">
								<?php
									echo $form->field($model, 'residential_status', ['inputOptions' => [
									'class' => "form-control whiteBorder",
									]])->dropDownList($residential_status,['prompt'=>'Select 
									Residential Status'])->label('Residential status <span class="required">*</span>');
								?>
							</div>
							<div class="col-md-4 col-sm-6 col-xs-12">
								<?php					
									##### children
									$cities = Yii::$app->commonmethod->getChildrenCount();
									echo $form->field($model, 'children',['inputOptions' => [
									'class' => "form-control whiteBorder",
									]])->dropDownList($cities,['prompt'=>'Children'])->label('Children <span class="required">*</span>');					
								?>
							</div>					
							<div class="col-md-4 col-sm-6 col-xs-12">
								<?php
									##### income
									$cities = Yii::$app->commonmethod->getIncome();
									echo $form->field($model, 'income',['inputOptions' => [
									'class' => "form-control whiteBorder",
									]])->dropDownList($cities,['prompt'=>'Income'])->label('Income <span class="required">*</span>');
								?>
							</div>					
						</div>				
						<div class="row-block">
							<div class="col-md-4 col-sm-6 col-xs-12">
								<?php
									echo $form->field($model, 'house_size', ['inputOptions' => [
									'class' => "form-control whiteBorder",
									]])->dropDownList($house_size,['prompt'=>'Household size'])->label('Household size <span class="required">*</span>');
								?>
							</div>		
							<div class="col-md-4 col-sm-6 col-xs-12">
								<?php
									##### number of pets
									$cities = Yii::$app->commonmethod->getNumberOfPets();
									echo $form->field($model, 'number_of_pets', ['inputOptions' => [
									'class' => "form-control whiteBorder",
									]])->dropDownList($cities,['prompt'=>'No. of pets'])->label('No. of pets <span class="required">*</span>');
								?>
							</div>		
							<div class="col-md-4 col-sm-6 col-xs-12">
								<?php
									echo $form->field($model, 'registration_type', ['inputOptions' => [
									'class' => "form-control whiteBorder",
									]])->dropDownList($hear_about,['prompt'=>'Select'])->label('How did you hear about us? <span class="required">*</span>');
								?>
							</div>
						</div>
<!--
						<div class="row-block pet_t_sr">
							<div class="col-md-4 col-sm-6 col-xs-12">
								<?php
									//~ echo $form->field($model, 'day_price', ['inputOptions' => [
									//~ 'class' => "form-control",
									//~ ]])->textInput(['maxlength'=>15 , 'autofocus' => true])->label('Day Price <span>*</span>');
								?>   				  
							</div>
							<div class="col-md-4 col-sm-6 col-xs-12">
								<?php
									//~ echo $form->field($model, 'services', ['inputOptions' => [
									//~ 'class' => "form-control",
									//~ ]])->dropDownList($servicesTypes,['prompt'=>'Select services'])->label('Services <span class="required">*</span>');
								?>
							</div>
						</div>
-->
						<div class="row-block pet_t_sr">
							<div class="col-md-4 col-sm-6 col-xs-12 ">
								<?php
									#### awaited profile pictures
									echo $form->field($modelImageUpload['modelImgD'],'upload_home_images[]',['inputOptions' => [
									'class' => "form-control imageBrowser",
									]])->fileInput(['multiple' => true,"accept"=>"image/*"])->label('House Image');
								?>							
							</div>
							<div class="col-md-4 col-sm-6 col-xs-12">
								<?php
								#### ID documents
								echo $form->field($modelImageUpload['modelImgB'],'upload_documents[]',['inputOptions' => [
								'class' => "form-control imageBrowser",
								]])->fileInput(['multiple' => true, "accept"=>".docx, .txt"])->label('Upload Your ID');
								?>
							</div>
							<div class="col-md-4 col-sm-6 col-xs-12">
							<?php
								#### awaited profile pictures
								echo $form->field($modelImageUpload['modelImgC'], 'upload_images[]',['inputOptions' => [
								'class' => "form-control imageBrowser",
								]])->fileInput(['multiple' => true,"accept"=>"image/*"])->label('Upload additional pictures');
								?>
							</div>
						</div>
						<div class="row-block pet_t_sr">
							<div class="col-md-4 col-sm-6 col-xs-12">
								<?php
									echo $form->field($model, 'pet_parent_type_sr', ['inputOptions' => [
									'class' => "form-control whiteBorder",
									]])->dropDownList($pet_type,['prompt'=>'Pet Types'])->label('Pet Types <span class="required">*</span>');
								?>				  
							</div>							
							<div class="col-md-4 col-sm-6 col-xs-12">
								<?php
								echo $form->field($model, 'pitch', ['inputOptions' => [
								'class' => "form-control whiteBorder",
								]])->textInput(['maxlength'=>40 , "placeholder" => "Eg: We only take one Pet at a time",'autofocus' => true])->label('Your Pitch <span>*</span>');
								?>   	                
							</div>
							<div class="col-md-4 col-sm-6 col-xs-12">
								<?php
								echo $form->field($model, 'pet_weight_limit', ['inputOptions' => [
								'class' => "form-control whiteBorder",
								]])->dropDownList($pet_weight_limit,['prompt'=>'Select'])->label('Pet Weight Limit <span class="required">*</span>');
								?>					               
							</div>				
						</div>
						<div class="row-block pet_t_ow">
							<div class="col-md-12 col-sm-6 col-xs-12">
								<div class="form-group signUpOuter">
									<div class="col-lg-12  col-sm-12 col-xs-12 col-md-4 signUpListing">
										<strong>Interested in renting your pet to future pet owners and help them gain confidence? *</strong>
										<input class="renting_pet" name="AddUserForm[renting_pet]" 
										value="1" type="radio" checked> Yes
										<input class="renting_pet" name="AddUserForm[renting_pet]" 
										value="0" type="radio"> No
									</div>
								</div>
							</div> 
						</div>
						<div class="row-block pet_t_ow rending_block">
							<div class="col-md-4 col-sm-6 col-xs-12">
								<?php
									echo $form->field($model, 'pet_parent_type', ['inputOptions' => [
									'class' => "form-control whiteBorder",
									]])->dropDownList($pet_type,['prompt'=>'Pet Types'])->label('Pet Types <span class="required">*</span>');
								?>				  
							</div> 
							<div class="col-md-4 col-sm-6 col-xs-12">
								<?php
									echo $form->field($model, 'pet_type', ['inputOptions' => [
									'class' => "form-control whiteBorder",
									]])->dropDownList($pet_sub_type,['prompt'=>'Your Pet Types'])->label('Your Pet Type <span class="required">*</span>');
								?>				  
							</div> 
							<div class="col-md-4 col-sm-6 col-xs-12">
								<?php
									echo $form->field($model, 'per_day_price', ['inputOptions' => [
									'class' => "form-control whiteBorder",
									]])->textInput(['maxlength'=>40 ,'type' => 'number', 'min' => 1,'autofocus' => true])->label('Price Per Day <span>*</span>');
								?>
							</div>
						</div>

						<div id="addmoreblock">
							<div class="row-block pet_t_sr">
								<div class="col-md-4 col-sm-6 col-xs-12">
									<?php
										echo $form->field($model, 'services[]', ['inputOptions' => [
										'class' => "form-control whiteBorder",
										]])->dropDownList($servicesTypes,['prompt'=>'Select services'])->label('Services <span class="required">*</span>');
									?>
								</div>
								<div class="col-md-4 col-sm-6 col-xs-12">
									<?php
										echo $form->field($model, 'day_price[]', ['inputOptions' => [
										'class' => "form-control whiteBorder",
										]])->textInput(['maxlength'=>15 , 'type' => 'number', 'min' => 1, 'autofocus' => true])->label('Day Price <span>*</span>');
									?>
								</div>
								<div class="col-md-4 col-sm-6 col-xs-12">
									<a href="javascript:void(0)" class="orangeBtn" id="addmore_services" name="1">+</a>
								</div>
							</div>
						</div>

						<div class="row-block">
							<div class="col-md-12 col-sm-6 col-xs-12">
								<div class="form-group loginCheckbox">
									<?php
										$linkHtml = '<span>I agree with the <a href="'.Url::home().'cms/page/terms-and-conditions'.'" target="_blank" class="termscondition">Terms & Conditions</a> of PalCura, and <a href="'.Url::home().'cms/page/privacy-policy'.'" target="_blank" class="termscondition">Privacy Policy</a> of PalCura.</span>';
										echo $form->field($model, 'accept_terms', ['inputOptions' => [
										'class' => "form-control",                                                            
										],
										'template' => "{input}\n$linkHtml {error}",
										]
										)->checkbox([], false);
									?>
								</div>
							</div>
						</div>
						<div class="col-md-12 col-sm-12 col-xs-12">
							<div class="captcha">
<?php echo $form->field($model, 'reCaptcha')->widget(\yii\recaptcha\ReCaptcha::className(),['siteKey' => SITE_KEY])->label(false); ?>
							<?= 
								Html::submitButton(Yii::t('yii','Save'), ['class' => 'orangeBtn', 'name' => 'addUser-submit', 'id' => 'AddUser-submit']) 
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
<?php
	$modelID = "adduserform";
?>
<script type="text/javascript">
    //$(document).ready(function () {
		/*########## update states, cities #########*/
      /*  $('#<?= $modelID ?>-country').on('change', function () { 
            $("#<?= $modelID ?>-region, #<?= $modelID ?>-city").find("option:gt(0)").remove();
            var countryID = $(this).val();
            $("#state").find("option:first").text("Loading...");
            $.ajax({
                type: 'POST',
                url: '<?= $ajaxSUrl ?>',
                data: 'id=' + countryID,
                success: function (json) {
                    $("#<?= $modelID ?>-region").find("option:first").text("<?php echo Yii::t('yii', 'Select State/Province'); ?>");
                    for (var i = 0; i < json.length; i++) {
                        $("<option/>").attr("value", json[i].id).text(json[i].name).appendTo($("#<?= $modelID ?>-region"));
                    }
                }
            });
        });
        $("#<?= $modelID ?>-region").on('change', function () {
            var stateID = $(this).val();
            $("#<?= $modelID ?>-city").find("option:gt(0)").remove();
            $("#<?= $modelID ?>-city").find("option:first").text("Loading...");
            $.ajax({
                type: 'POST',
                url: '<?= $ajaxCUrl ?>',
                data: 'id=' + stateID,
                success: function (json) {
                    $("#<?= $modelID ?>-city").find("option:first").text("<?php echo Yii::t('yii', 'Select City'); ?>");
                    for (var i = 0; i < json.length; i++) {
                        $("<option/>").attr("value", json[i].id).text(json[i].name).appendTo($("#<?= $modelID ?>-city"));
                    }
                }
            });
        });*/
    //});
</script>
