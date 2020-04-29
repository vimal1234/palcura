<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
//rentingpetserror case 

						if($renting_pet_selected==1) {
						$yesselected = 'checked=checked';
						$noselected = '';
						$chkd = 1;
						}else{
						$yesselected = '';
						$noselected = 'checked=checked';
						$chkd = 0;
						}
						
						if($dob_selected==1) {
						$dobselected = 'checked=checked';
						$dobnoselected = '';
						$dobchkd = 1;
						}else{
						$dobselected = '';
						$dobnoselected = 'checked=checked';
						$dobchkd = 0;
						}
					
$this->title = 'Sign Up';
$this->params['breadcrumbs'][] = $this->title;
$siteimage = Yii::getAlias('@siteimage');
####= common methods =####
$hear_about 			= array(1=>'Initial launch',2=>'Google',3=>'Facebook',4=>'Instagram',5=>'Twitter',6=>'Next door',7=>'Other Social',8=>'Event',9=>'Dog Park',10=>'Flyers',11=>'Word of mouth',12=>'Referral',13=>'Other');

$session = Yii::$app->session;
$registered = $session->get('registered');
//echo '---------test-------'.$registered;
 $session->set('registered',0);
?>
<script>
	$(document).ready(function() {	
	
		var defaultSelected = $("#adduserform-user_type option:selected").val();
		if(defaultSelected == 1) {
			$(".pet_t_sr").css("display","none");
			$(".pet_t_ow").css("display","block");
		
		} else if(defaultSelected == 2) {
$('.noofpets').show();
			$(".pet_t_ow").css("display","none");
			$(".pet_t_sr").css("display","block");		
			$('#uploads-rentinpetselecttype').val(0);
		} else if(defaultSelected == 3) {
$('.noofpets').hide();
			$(".pet_t_ow").css("display","none");
			$(".pet_t_sr").css("display","none");
			$('#uploads-rentinpetselecttype').val(0);
		}

		$("#adduserform-user_type").change(function() {
			var pet_type = $(this).val();
			if(pet_type == 1) {
				$(".pet_t_sr").css("display","none");
				$(".pet_t_ow").css("display","block");
if($('.renting_pet:checked').val() == 1) {
$(".rending_block").css("display","block");

 } else { $(".rending_block").css("display","none"); 

 }

$("label[for='adduserform-number_of_pets']").html('Number of pets');
			} else if(pet_type == 2) {
$('.noofpets').show();
				$(".pet_t_ow").css("display","none");
				$(".pet_t_sr").css("display","block");

$('#uploads-rentinpetselecttype').val(0);
$('#adduserform-interested_in_renting').val(0);
$('input:radio[class=renting_pet][value=0]').prop('checked', true);
$('#adduserform-renting_pet').val(0);
			} else if(pet_type == 3) {
$('.noofpets').hide();
				$(".pet_t_ow").css("display","none");
				$(".pet_t_sr").css("display","none");
$('#uploads-rentinpetselecttype').val(0);
$('#adduserform-interested_in_renting').val(0);
$('input:radio[class=renting_pet][value=0]').prop('checked', true);
$('#adduserform-renting_pet').val(0);
			}
		});

		$('.other_type_ow').css("display","none");
		$(".renting_pet").change(function() {
			if($('.renting_pet:checked').val() == 1) {
				$('.rending_block').css("display","block");
				$('#uploads-rentinpetselecttype').val(1);
				$('#adduserform-interested_in_renting').val(1);
				$('#adduserform-renting_pet').val(1);
			} else {
				$('.rending_block').css("display","none");
				$('#uploads-rentinpetselecttype').val(0);
				$('#adduserform-interested_in_renting').val(0);
				$('#adduserform-renting_pet').val(0);
			}
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
				<div class="formContent registerForm">
					<img class="contact-bg" src="<?= $siteimage ?>/contact-bg.png" alt="">
					<div class="col-md-12 col-sm-12 col-xs-12 removePad">
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

<?php if(isset($registered) &&  $registered == 0 || $registered==''){					
					?>
					
					<input type="hidden" name="scnt" id="scnt" value='2' />
						<div class="row-block">
							<div class="col-xs-12">
								<?php
									$p_type = array(OWNER => "Pet Owner", SITTER => "Pet Sitter", BORROWER => "Pet Borrower");
									echo $form->field($model, 'user_type', ['inputOptions' => [
									'class' => "form-control whiteBorder",
									]])->dropDownList($p_type,[])->label('<b>I want to sign up as a…</b> (Don’t worry, you can add more
										profiles later. Select one for now to get started.)
 										<span class="required"></span>');
								?>
							</div>
						</div>		
																						
						<div class="row-block">			
                       		 <div class="col-md-6 col-sm-6 col-xs-12">
								<?php
									echo $form->field($model, 'firstname', ['inputOptions' => [
									'class' => "form-control",
									]])->textInput(['maxlength' => 40, 'autofocus' => false])->label('First Name');
								?>
							</div>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<?php
									echo $form->field($model, 'lastname', ['inputOptions' => [
									'class' => "form-control",
									]])->textInput(['maxlength' => 40, 'autofocus' => false])->label('Last Name');
								?>                
							</div>							
						</div>
						<div class="row-block">
	
							<div class="col-md-6 col-sm-6 col-xs-12">
								<?php
									echo $form->field($model, 'email', ['inputOptions' => [
									'class' => "form-control",
									]])->textInput(['maxlength' => 100, 'autofocus' => false])->label('Email');
								?>
							</div>	
							<div class="col-md-6 col-sm-6 col-xs-12">
								<?php
									echo $form->field($model, 'password', ['inputOptions' => [
									'class' => "form-control",
									]])->passwordInput()->label('Create a Password <span class="required"></span>');
								?>                                    
							</div> 												
						</div>						
					
						<div class="row-block" style="display:none;">							
							<div class="col-md-6 col-sm-6 col-xs-12">
							<?php echo $form->field($model, 'interested_in_renting')->hiddenInput(['value'=> 0])->label(false); ?>						               </div>	                                
						</div>
						<div class="row-block">                                                
							<div class="col-md-6 col-sm-6 col-xs-12">
								<?php
									echo $form->field($model, 'zip_code', ['inputOptions' => [
									'class' => "form-control",
									]])->textInput(['maxlength'=>15 , 'autofocus' => false])->label('Zip Code <span></span>');
								?>   				  
							</div>
							
							<div class="col-md-6 col-sm-6 col-xs-12">
								<div class="form-group signUpOuter">
									<div class="col-lg-7  col-sm-6 col-xs-6 col-md-4 signUpListing ageListing">
										<strong>Are you 18 years or older?</strong>
										<input class="user_dob" name="dob" 
										value="1" type="radio" <?php echo $dobselected;?>> Yes
										<input class="user_dob" name="dob" 
										value="0" type="radio" <?php echo $dobnoselected; ?>> No

<p class="help-block help-block-error dob-error" style="color:#f23826 !important;display:none;">Age must be 18 years or older.</p> 
										<div style="display:none;"><?php echo $form->field($model, 'dob')->hiddenInput(['value'=>$dobchkd])->label(false);?></div>
									</div>
								</div>
							</div> 
						</div>

						<div class="row-block pet_t_ow">
							<div class="col-md-12 col-sm-6 col-xs-12">
								<div class="form-group signUpOuter accterms">
									<div class="col-lg-12  col-sm-12 col-xs-12 col-md-4 signUpListing">
										<strong>Interested in joining our pet borrowing program? Share the care of your pet and help future pet owners gain confidence</strong>
										<input class="renting_pet" name="pertrenting" 
										value="1" type="radio" <?php echo $yesselected;?>> Yes
										<input class="renting_pet" name="pertrenting" 
										value="0" type="radio" <?php echo $noselected; ?>> No
										<div style="display:none;"><?php echo $form->field($model, 'renting_pet')->hiddenInput(['value'=>$chkd])->label(false);?></div>
									</div>
								</div>
							</div> 
						</div>
						
            <div class="row-block">
							<div class="col-md-12 col-sm-6 col-xs-12">
								<div class="form-group loginCheckbox accterms">
									<?php
										$linkHtml = '<span>I agree with the <a href="'.Url::home().'cms/page/terms-and-conditions'.'" target="_blank" class="termscondition">Terms & Conditions</a> and <a href="'.Url::home().'cms/page/privacy-policy'.'" target="_blank" class="termscondition">Privacy Policy</a> of PalCura.</span>';
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
								Html::submitButton(Yii::t('yii','SIGN UP NOW'), ['class' => 'orangeBtn', 'name' => 'addUser-submit', 'id' => 'AddUser-submit']) 
							?>
							</div>
						</div>
						<div class="container">
							<div class="row">
								<img src="<?php echo $siteimage; ?>/animal-img.jpg" alt="animal image" title="Palcura">

							</div>
						</div>
             <?php } ?>
            <?php ActiveForm::end(); ?>
            
		</div>
	</div>
</div>
</section>
<?php
	$modelID = "adduserform";
?>
<script type="text/javascript">
       
   $('document').ready(function(){
   	/* code by sigma */
   	var type="<?php 
   	if(isset($_GET['type']))
   		echo $_GET['type'];
   	else
   		echo "";

   	?>";
   	/* End */
  	if(type!=''){
  		if(type=='owner'){  			
  			$('#adduserform-user_type option[value="1"]').prop('selected',true);
  		}else if(type=='sitter'){
  			$('#adduserform-user_type option[value="2"]').prop('selected',true);  			
  		}else if(type=='borrower'){
  			$('#adduserform-user_type option[value="3"]').prop('selected',true);

  		}

  	}

   var rentinPetchk = "<?php echo $chkd; ?>";
   if(rentinPetchk == 1){
   $('#pettypes').show();
   $('#uploads-rentinpetselecttype').val(1);
   $('#adduserform-interested_in_renting').val(1);
   }else{
   $('#pettypes').hide();
   $('#uploads-rentinpetselecttype').val(0);
   $('#adduserform-interested_in_renting').val(0);
   }
   var category = null;
	$("input[name='pertrenting']").click(function() {
    category = this.value;
    
    $('#adduserform-renting_pet').val(category);
    
	});
	
	var category = null;
	$("input[name='dob']").click(function() {
    category = this.value;
    
    $('#adduserform-dob').val(category);
    
	});

   });
    
</script>
<script>
$('document').ready(function(){

$("#frmSignupUser").submit(function(e) {	
 var dobval = $('input[name=dob]:checked').val();
	if(dobval == '0'){
		$('.dob-error').show();
		return false;		
	}
	
});
	
	
});
</script>