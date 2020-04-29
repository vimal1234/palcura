<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('yii','Pet Information');
$agearray = array();
for( $i=1; $i<=100; $i++ )
{
$agearray[] = $i; 
}
$petweightarray = array(''=>'Select',"Small" => ['1'=>'0-15lbs'],"Medium" => ['2'=>'16-40lbs'],"Large" => ['3'=>'41-100lbs'],"Giant" => ['4'=>'101+lbs']);
####= common methods =####
$session 					= 	Yii::$app->session;	
$loggedingusertype = $session->get('loggedinusertype');
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
				<?php if( $loggedingusertype==OWNER){?>
					<a class="head" href="<?php echo Url::to(['search/petsitter'])?>" style="margin:0 0 0 25px;"> Back to search results</a>
					<?php }elseif($loggedingusertype == RENTER){ ?>
					<a class="head" href="<?php echo Url::to(['search/petrenter'])?>" style="margin:0 0 0 25px;"> Back to search results</a>
					<?php }?>
					
					<a class="head" href="<?php echo SITE_URL.'bookings/book-now'?>" style="float:right;">Book Now</a>
				<div class="formContent" style="margin:0;"> 
			
					<img class="contact-bg" src="<?php echo WEBSITE_IMAGES_PATH.'contact-bg.png'; ?>" alt="">
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
						//echo $form->field($model, 'booking_type')->hiddenInput(['value'=> OWNER])->label(false);
						?>
						<div class="row-block">
						<div class="col-md-4 col-sm-6 col-xs-12">
								<?php
									echo $form->field($vaccinationModel, 'pet_name', ['inputOptions' => [
									'class' => "form-control",								
									]])->textInput()->label('Pet Name <span class="required">*</span>');
								?>   
							</div>   
							<div class="col-md-4 col-sm-6 col-xs-12">
								<?php								
									echo $form->field($vaccinationModel, 'my_pet_age', ['inputOptions' => [
									'class' => "form-control whiteBorder",
									]])->dropDownList($agearray)->label('Pet Age <span class="required"></span>');
								?>				  
							</div>							
							<div class="col-md-4 col-sm-6 col-xs-12">
								<?php
								echo $form->field($vaccinationModel, 'my_pet_weight', ['inputOptions' => [
								'class' => "form-control whiteBorder",
								]])->dropDownList($petweightarray)->label('Pet Weight <span></span>');
								?>   	                
							</div>
										
						</div>
						<div class="row-block">
							<div class="col-md-4 col-sm-6 col-xs-12">
								<?php
								$petsex = array('Male'=>'Male','Female'=>'Female');
								echo $form->field($vaccinationModel, 'my_pet_sex', ['inputOptions' => [
								'class' => "form-control whiteBorder",
								]])->dropDownList($petsex)->label('Sex <span class="required"></span>');
								?>					               
							</div>	
							 <div class="col-md-4 col-sm-6 col-xs-12">
								<?php
									#### display image
									echo $form->field($modelImageUploadF, 'vaccination_doc',['inputOptions' => [
									'class' => "form-control imageBrowser",
									]])->fileInput()->label('Vaccination Document <span class="required"> </span>');
								?>
							</div>
							<div class="col-md-4 col-sm-6 col-xs-12">
								<?php 
								echo $form->field($vaccinationModel, 'vaccination_validity', ['inputOptions' => [
								'class' => "form-control datepicker",
								
								]])->textInput(['maxlength'=>15 , 'autofocus' => true, 'readOnly' => true])->label('Vaccination Expiration date <span class="required">*</span>');
								?>
							</div>						
							 	
						</div>
						<div class="col-md-12 col-sm-12 col-xs-12">
							<div class="captcha">
							<?php 
								//$form->field($model, 'reCaptcha')->widget(\yii\recaptcha\ReCaptcha::className(),['siteKey' => '6LfTjjIUAAAAAN9nIrkQ46jttq6fLYwYVe8iMEgg'])->label(false);;	
							?>
							<?= 
								Html::submitButton(Yii::t('yii','Save'), ['class' => 'orangeBtn', 'name' => 'proceedPayment', 'id' => 'proceedPayment'])
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
$('document').ready(function(){

$("#vaccinationdetails-vaccination_validity").datepicker({
		numberOfMonths: 1,
		showButtonPanel: true,
		maxDate: '+1Y',
	});
});
</script>
