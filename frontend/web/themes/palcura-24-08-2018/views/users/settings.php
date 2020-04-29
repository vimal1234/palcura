<?php
use yii\helpers\Html;

use yii\bootstrap\ActiveForm;
use yii\widgets\DetailView;
use common\models\Country;
use common\models\State;
use common\models\City;
use yii\helpers\ArrayHelper;

$this->title = 'Setting Profile';
$this->params['breadcrumbs'][] = $this->title;
$siteimage = Yii::getAlias('@siteimage');

####= common methods =####
$countries 				= Yii::$app->commonmethod->countries();
$regions 				= Yii::$app->commonmethod->regions();
$cities 				= Yii::$app->commonmethod->cities();
$servicesTypes 			= Yii::$app->commonmethod->servicesTypes();
$hear_about 			= Yii::$app->commonmethod->cities();
$pet_type 				= Yii::$app->commonmethod->getPetTypes();
$pet_parent_id			= 0;
$pet_parent_id 			= (isset($pet_type) ? key($pet_type) : 0 );
$pet_sub_type 			= Yii::$app->commonmethod->getPetTypes($pet_parent_id);
$pet_weight_limit 		= Yii::$app->commonmethod->cities();
$house_size 			= Yii::$app->commonmethod->getHouseSize();
$residential_status 	= Yii::$app->commonmethod->residentialStatus();
$number_of_pets		 	= Yii::$app->commonmethod->getNumberOfPets();
$income 				= Yii::$app->commonmethod->getIncome();
$children_count			= Yii::$app->commonmethod->getChildrenCount();
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
			<div class="col-md-10 col-sm-9 col-xs-12 scrolldiv01">
				<div class="formContent" style="margin:0;">
					<img class="contact-bg" src="<?php echo $siteimage;?>/contact-bg.png" alt="">
					<div class="col-md-12 col-sm-12 col-xs-12">
						<?php if (Yii::$app->session->getFlash('item')): ?>
							<div class="col-xs-12">
								<div class="alert alert-grey alert-dismissible">
									<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span>
									</button>
									<i class="glyphicon glyphicon-ok"></i><?php echo Yii::$app->session->getFlash('item'); ?>
								</div>
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
						//echo $form->field($model, 'interested_in_renting')->hiddenInput(['value'=> '1'])->label(false);
						?>
						<div class="row-block">					
							<div class="col-md-4 col-sm-6 col-xs-12">
								<?php
									#### display image
									echo $form->field($modelImageUpload, 'profile_image',['inputOptions' => [
									'class' => "form-control imageBrowser",
									]])->fileInput()->label('Display Image');
								?>
							</div>
						</div>
						<div class="row-block">
							<div class="col-md-4 col-sm-6 col-xs-12">
								<?php
									echo $form->field($model, 'firstname', ['inputOptions' => [
									'class' => "form-control",
									]])->textInput(['maxlength' => 40, 'autofocus' => true])->label('Name *');
								?>
							</div>
							<div class="col-md-4 col-sm-6 col-xs-12">
								<?php
									echo $form->field($model, 'email', ['inputOptions' => [
									'class' => "form-control",
									]])->textInput(['maxlength' => 100, 'autofocus' => true])->label('Email *');
								?>
							</div>
							<div class="col-md-4 col-sm-6 col-xs-12">
								<?php
									echo $form->field($model, 'phone', ['inputOptions' => [
									'class' => "form-control",
									]])->textInput(['maxlength'=>15 , 'autofocus' => true])->label('Phone No. <span>*</span>');
								?>   
							</div>   
						</div>   
						<div class="row-block">
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
							<div class="col-md-4 col-sm-6 col-xs-12">
								<?php
									echo $form->field($model, 'dob', ['inputOptions' => [
									'class' => "form-control datepicker",
									]])->textInput(['maxlength'=>15 , 'autofocus' => true, 'readOnly' => true])->label('Date Of Birth <span>*</span>');
								?>
							</div>
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
									echo $form->field($model, 'country', ['inputOptions' => [
									'class' => "form-control",
									]])->dropDownList($regions,['prompt'=>'Select Country'])->label('Country <span class="required">*</span>');
								?>
							</div>
							<div class="col-md-4 col-sm-6 col-xs-12">
								<?php
									echo $form->field($model, 'region', ['inputOptions' => [
									'class' => "form-control",
									]])->dropDownList($regions,['prompt'=>'Select State/Province'])->label('State/Province <span class="required">*</span>');
								?>
							</div>
						</div>
						<div class="row-block">
							<div class="col-md-4 col-sm-6 col-xs-12">
								<?php
									echo $form->field($model, 'city', ['inputOptions' => [
									'class' => "form-control",
									]])->dropDownList($cities,['prompt'=>'Select 
									City'])->label('City <span class="required">*</span>');
								?>
							</div>      
							<div class="col-md-4 col-sm-6 col-xs-12">
								<?php
									echo $form->field($model, 'residential_status', ['inputOptions' => [
									'class' => "form-control",
									]])->dropDownList($residential_status,['prompt'=>'Select 
									Residential Status'])->label('Residential status <span class="required">*</span>');
								?>
							</div>     
							<div class="col-md-4 col-sm-6 col-xs-12">
								<?php
									echo $form->field($model, 'house_size', ['inputOptions' => [
									'class' => "form-control",
									]])->dropDownList($house_size,['prompt'=>'Household size'])->label('Household size <span class="required">*</span>');
								?>
							</div>
						</div>
						<div class="col-md-12 col-sm-12 col-xs-12">
							<div class="captcha">
								<?= 
									Html::submitButton(Yii::t('yii','Submit'), ['class' => 'orangeBtn', 'name' => 'editUser-submit', 'id' => 'editUser-submit']) 
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
