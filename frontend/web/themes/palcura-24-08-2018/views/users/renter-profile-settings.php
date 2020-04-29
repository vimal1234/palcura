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
$ajaxSUrl	= SITE_URL.'ajax/updatestates';
$ajaxCUrl	= SITE_URL.'ajax/updatecities';
$attributes = Yii::$app->user->identity->getattributes();
####= common methods =####
$countries 				= Yii::$app->commonmethod->countries();
$regions 				= Yii::$app->commonmethod->regions();
//$cities 				= Yii::$app->commonmethod->cities();
$cities 				= Yii::$app->commonmethod->citiesbyregion($model->region);
$servicesTypes 			= Yii::$app->commonmethod->servicesTypes();
$hear_about 			= array(1=>'Initial launch',2=>'Google',3=>'Facebook',4=>'Instagram',5=>'Twitter',6=>'Next door',7=>'Other Social',8=>'Event',9=>'Dog Park',10=>'Flyers',11=>'Word of mouth',12=>'Referral',13=>'Other');
$pet_type 				= Yii::$app->commonmethod->getPetTypes();
$pet_parent_id			= 0;
$pet_parent_id 			= (isset($pet_type) ? key($pet_type) : 0 );
$pet_sub_type 			= Yii::$app->commonmethod->getPetTypes($pet_parent_id);
//$pet_weight_limit 		= Yii::$app->commonmethod->cities();
$pet_weight_limit		= "";

$session 		= Yii::$app->session;
$logged_user 	= $session->get('loggedinusertype');

$logUserType = '';
if($logged_user==1){
$logUserType = 'Owner';
}elseif($logged_user==2){
$logUserType = 'Sitter';
}elseif($logged_user==3){
$logUserType = 'Borrower';
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
			<div class="col-md-10 col-sm-9 col-xs-12 scrolldiv01">
				<div class="formContent" style="margin:0;">
						<div class="col-md-12 col-sm-12 col-xs-12">
					
					<?php
					/*
		  if ( ($logUserType == 'Sitter'  && !$attributes['profile_completed']) || ($logUserType == 'Borrower'  && !$attributes['profile_completed_borrower']) || ($logUserType == 'Owner'  && !$attributes['profile_completed_owner'])) { ?>
			<div class="col-xs-12" id="scrrollhere">
			  <div class="alert alert-grey alert-dismissible">
			    <button type="button" class="close" data-dismiss="alert">
			      <span aria-hidden="true">&times;</span>
			    </button>	
			    <?php
			    	if ($logUserType == 'Sitter'){
			    		echo "Please complete your profile to get verified and accept bookings";
			    	} elseif ($logUserType == 'Borrower') {
			    	
			    		echo "Please complete your profile to get verified";
			    	} elseif ($logUserType == 'Owner') {
			    		echo "Please complete your profile to participate in the pet borrowing program.";
			    	}
			    ?>
			   </div>
			 </div>
	    <?php  } 
		*/
		?>
		<?php
						
		 if ( ($logUserType == 'Sitter'  && !$attributes['verified_by_admin']) || ($logUserType == 'Borrower'  && !$attributes['verified_by_admin']) || ($logUserType == 'Owner'  && !$attributes['verified_by_admin'])) { ?>
			    <?php
			    	if ($logUserType == 'Sitter'){
						//echo "Please complete your profile to get verified and accept bookings";
						?>
						<div class="massage-status">
							<strong>Status:</strong> <span>Sitter <?php echo ($attributes['status']==1)?"active,":"Inactive,";?>Profile <?php echo ($attributes['profile_completed']==1)?"Complete":"Incomplete";?></span>
						</div>
				<?php 
				} elseif ($logUserType == 'Borrower') {
			    	
			    		//echo "Please complete your profile to get verified";
			    	?>
					
						<div class="massage-status">
							<strong>Status:</strong> <span>Borrower <?php echo ($attributes['status']==1)?"active,":"Inactive,";?>Profile <?php echo ($attributes['profile_completed_borrower']==1)?"Complete":"Incomplete";?></span>
						</div>
					<?php 
					
					} elseif ($logUserType == 'Owner') {
			    		//echo "Please complete your profile to participate in the pet borrowing program.";
			    	?>
						<div class="massage-status">
							<strong>Status:</strong> <span>Owner <?php echo ($attributes['status']==1)?"active,":"Inactive,";?>Profile <?php echo ($attributes['profile_completed_owner']==1)?"Complete":"Incomplete";?></span>
						</div>
				
					
					<?php
					}
			    ?>
			  
	    <?php  } ?> 
						<?php if (Yii::$app->session->getFlash('item')): ?>
						
							<div class="col-xs-12">
								<div class="alert alert-grey alert-dismissible">
									<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span>
									</button>
									<?php echo Yii::$app->session->getFlash('item'); ?>
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
									]])->fileInput()->label('Profile Image<span> * </span>');
								?>
							</div>
						</div>
						<div class="row-block">
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
									]])->textInput(['maxlength' => 100, 'readOnly' => true])->label('Email *');
								?>
							</div>							
						</div>	
						
						<div class="row-block">                                                
							<div class="col-md-6 col-sm-6 col-xs-12">
								<?php
									echo $form->field($model, 'zip_code', ['inputOptions' => [
									'class' => "form-control",
									]])->textInput(['maxlength'=>15 , 'autofocus' => false])->label('Zip/Postal Code <span></span>');
								?>   				  
							</div>
						</div>	  
						<div class="row-block">
							<div class="col-md-4 col-sm-6 col-xs-12 passLable">
								<?php
									echo $form->field($model, 'password', ['inputOptions' => [
									'class' => "form-control",
									]])->passwordInput()->label('Change Palcura Login Password (optional) <span class="required"></span>');
								?>   
							</div>    
							<div class="col-md-4 col-sm-6 col-xs-12 passLable">
								<?php
									echo $form->field($model, 'repeat_password', ['inputOptions' => [
									'class' => "form-control",
									]])->passwordInput()->label('Confirm Password (optional) <span class="required"></span>');
								?>
							</div>                                                  
							
						</div>
	
						<div class="col-md-12 col-sm-12 col-xs-12">
							<div class="captcha">
								<?= 
									Html::submitButton(Yii::t('yii','Save'), ['class' => 'orangeBtn', 'name' => 'editUser-submit', 'id' => 'editUser-submit']) 
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
	$modelID = "updaterenter";
?>

