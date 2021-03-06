<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\widgets\DetailView;
use common\models\Country;
use common\models\State;
use common\models\City;
use yii\helpers\ArrayHelper;
//$ajaxSUrl	= SITE_URL.'ajax/updatestates';
//$ajaxCUrl	= SITE_URL.'ajax/updatecities';

$ajaxSUrl	= 'http://180.211.98.106:8083/Palcura/ajax/updatestates';
$ajaxCUrl	= 'http://180.211.98.106:8083/Palcura/ajax/updatecities';

$this->title = 'Your Sitter Profile';
$this->params['breadcrumbs'][] = $this->title;
$siteimage = Yii::getAlias('@siteimage');
$userServices = $modelImageUpload['userServices'];
$posteditdata = $modelImageUpload['posteditdata'];
$userid = Yii::$app->user->identity->id;
$attributes = Yii::$app->user->identity->getattributes();
//print_r($attributes);

//exit;

####= common methods =####
$countries 				= Yii::$app->commonmethod->countries();
$regions 				= Yii::$app->commonmethod->regions();
$cities 				= Yii::$app->commonmethod->citiesbyregion($model->region);
/* echo "<pre>";
print_r($cities);
exit; */

$servicesTypes 			= Yii::$app->commonmethod->servicesTypes();
$serviceproviderdata 	= Yii::$app->commonmethod->getServiceproviderdata($userid);
	if(!empty($serviceproviderdata)){
	$petweight = $serviceproviderdata['pet_weight_limit'];
	$pitch = $serviceproviderdata['pitch'];
	
	$pettypeid = $serviceproviderdata['pet_type_id'];
	}else{
	$pitch = '';
	$pet_weight_limit='';
	$pettypeid = '';
	}
#Get certification previous state

if(isset($attributes['certification']) && !empty($attributes['certification'])){
								
		$checkedList=explode(",",$attributes['certification']);
		$model->certification = $checkedList;
}
#To check Profile complited or not
$status=isset($attributes['profile_completed'])?$attributes['profile_completed'] : 0;

//echo "<pre>"; print_r($serviceproviderdata); die;
$hear_about 			= array(1=>'Initial launch',2=>'Google',3=>'Facebook',4=>'Instagram',5=>'Twitter',6=>'Next door',7=>'Other Social',8=>'Event',9=>'Dog Park',10=>'Flyers',11=>'Word of mouth',12=>'Referral',13=>'Other');
$pet_type 				= Yii::$app->commonmethod->getPetTypes();
$pet_parent_id			= 0;
$pet_parent_id 			= (isset($pet_type) ? key($pet_type) : 0 );
$pet_sub_type 			= Yii::$app->commonmethod->getPetTypes($pet_parent_id);
//$pet_weight_limit 		= array(''=>'Select',"Small" => ['1'=>'0-15lbs'],"Medium" => ['2'=>'16-40lbs'],"Large" => ['3'=>'41-100lbs'],"Giant" => ['4'=>'101+lbs']);
$pet_weight_limit 		= array("1"=>'Small(0-15lbs)',"2"=>'Medium(16-40lbs)',"3"=>'Large(41-1000lbs)',"4"	=>'Giant(101+lbs)');

$serviceOptions	=	"";
foreach($servicesTypes as $key=>$row) {
	$serviceOptions .='<option value="'.$key.'">'.$row.'</option>';
}

$weightoptions = [];
foreach ($pet_weight_limit as $key => $val) {
    $weightoptions[$key] = ['data-tokens' => $val];
}

$petweightOptions	=	"";
$petweightOptions .= '<option value="" disabled>select pet weight</option>';
foreach($pet_weight_limit as $key=>$row) {
	$petweightOptions .='<option value="'.$key.'">'.$row.'</option>';
}

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
			<div class="col-xs-12">
				<h1><?= $this->title ?></h1>
			</div>
	</div>
</header>
<section class="contentArea sitter-profile-settings">
	<div class="container">
		<div class="row">
		<?php
		/*
		  if ( ($logUserType == 'Sitter'  && !$attributes['profile_completed']) || ($logUserType == 'Borrower'  && !$attributes['profile_completed_borrower']) || ($logUserType == 'Owner'  && !$attributes['profile_completed_owner'])) {
			  
			  if ($logUserType == 'Sitter'){
			    		?>
						<div class="massage-status">
							<strong>Status:</strong> <span>Sitter Inactive, Profile Incomplete</span>
						</div>
						<?php 
			    	} elseif ($logUserType == 'Borrower') {
			    	
			    		echo "Please complete your profile to get verified";
			    	} elseif ($logUserType == 'Owner') {
			    		echo "Please complete your profile to participate in the pet borrowing program.";
			    	}
			  
			  
			  
			  
		  }else{
			  
			  if ($logUserType == 'Sitter'){
			    		?>
						<div class="massage-status">
							<strong>Status:</strong> <span>Sitter Inactive, Profile complete</span>
						</div>
						<?php 
			  }
		  }
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
			
		
		  
		  
		
			<?php echo $this->render('//common/sidebar'); ?>
			<div class="col-md-10 col-sm-9 col-xs-12 scrolldiv01">
				<div class="formContent" style="margin:0;">
					<!-- <img class="contact-bg" src="<?php echo $siteimage;?>/contact-bg.png" alt=""> -->
					
		<?php
		/*
		  if ( ($logUserType == 'Sitter'  && !$attributes['profile_completed']) || ($logUserType == 'Borrower'  && !$attributes['profile_completed_borrower']) || ($logUserType == 'Owner'  && !$attributes['profile_completed_owner'])) { ?>
			<div class="" id="scrrollhere">
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
	    <?php  } */?> 
			
		
					
						<?php if (Yii::$app->session->getFlash('item')): ?>
						
						<script>
						$(document).ready(function () {
						// Handler for .ready() called.
						$('html, body').animate({
							scrollTop: $('#scrrollhere').offset().top
						}, 'slow');
						});
												
						</script>
							<div class="col-xs-12" id="scrrollhere">
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
						$oldservicecount = count($userServices);
						if($oldservicecount == 0){
						$oldservicecount = 1;
						}
						?>
						<input type="hidden" name="scnt" id="scnt" value="<?php echo $oldservicecount+1; ?>" />
						<div class="row-block settings-section">
											
							<div class="col-md-12 col-sm-12 col-xs-12">
								<h4><strong>Contact Information</strong></h4>
							</div>
						<?php /* echo "<pre>";
						print_r($model); */
						
						?>
						<div class="row-inner">
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
							
							<div class="col-md-4 col-sm-6 col-xs-12">
								<?php
									echo $form->field($model, 'email', ['inputOptions' => [
									'class' => "form-control",
									]])->textInput(['maxlength' => 100, 'readOnly' => true])->label('Email *');
								?>
							</div>
							   
						</div> 
						  
						<!--<div class="row-block">
							<div class="col-md-4 col-sm-6 col-xs-12 passLable">
								<?php
									echo $form->field($model, 'password', ['inputOptions' => [
									'class' => "form-control",
									]])->passwordInput()->label('Change Palcura Login Password (optional)<span class="required"></span>');
								?>   
							</div>    
							<div class="col-md-4 col-sm-6 col-xs-12 passLable">
								<?php
									echo $form->field($model, 'repeat_password', ['inputOptions' => [
									'class' => "form-control",
									]])->passwordInput()->label('Confirm Password (optional)<span class="required"></span>');
								?>
							</div>                                                  
							
						</div>-->

						<div class="row-inner">
								
							
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
							<div class="col-md-4 col-sm-6 col-xs-12">
								<?php
									echo $form->field($model, 'country', ['inputOptions' => [
									'class' => "form-control whiteBorder",
									]])->dropDownList($countries,['prompt'=>'Select Country'])->label('Country <span class="required">*</span>');
								?>
							</div>
						</div>	
						<div class="row-inner">
							
							
							<div class="col-md-4 col-sm-6 col-xs-12 selectsearchoptions">
								<?php
									echo $form->field($model, 'region', ['inputOptions' => [
									'class' => "form-control whiteBorder selectpicker",
									'data-live-search'=>"true",
									]])->dropDownList($regions,['prompt'=>'Select State/Province'])->label('State/Province <span class="required">*</span>');
								?>
							</div>
							<div class="col-md-4 col-sm-6 col-xs-12 selectsearchoptions">
								<?php
									echo $form->field($model, 'city', ['inputOptions' => [
									'class' => "form-control whiteBorder selectpicker",
									'data-live-search'=>"true",
									]])->dropDownList($cities,['prompt'=>'Select 
									City'])->label('City <span class="required">*</span>');
								?>
							<!--<span class=" field-updatesitter-city required">
								<div class="form-group">

								<label for="updatesitter-city">City <span class="required">*</span></label>	
							<input id="updatesitter-city" class="form-control whiteBorder selectpicker" value="<?php echo $model->city;?>" name="UpdateSitter[city]" type="text" placeholder="Search for engine..."/>
								</div>
								</span>-->
							</div>
							
							
						</div>
						
						</div>
						<!-- sitter profile fields -->	
						<div class="row-block settings-section">	
							<div class="col-md-12 col-sm-12 col-xs-12">
								<h4><strong>Customize Your Profile</strong></h4>
							</div>
						
							<div class="col-md-4 col-sm-6 col-xs-12">
						
								<?php
									#### display image
									echo $form->field($modelImageUpload['modelImgA'], 'profile_image',['inputOptions' => [
									'class' => "form-control imageBrowser",
									]])->fileInput()->label('Upload Your Primary Profile Image<span> * </span>');
								?>
							</div>		
							<div class="col-md-8 col-sm-6 col-xs-12">
								<?php
								echo $form->field($model, 'pitch', ['inputOptions' => [
								'class' => "form-control whiteBorder",
								]])->textInput(['maxlength'=>255 , "placeholder" => "Eg: We only take one Pet at a time",'autofocus' => true,'value'=> $pitch])->label('Your Pitch <span>*</span> (Let pet parents know why they should choose you.)');
								?>   	                
							</div>							
							<div class="col-md-12 col-sm-6 col-xs-12">
							
							<!--label>Additional images</label>(optional)<br><strong class="reduceSize">(Add pictures that will help pet owners get to know you and your love for pets.)</strong><br-->
							<!--input id="additionalimg" placeholder="Add files from My Computer"/-->
							<!--div class="fileUpload btn btn-primary buttonht"-->
							<?php
								#### awaited profile pictures
								echo $form->field($modelImageUpload['modelImgC'], 'upload_images[]',['inputOptions' => [
								'class' => "form-control imageBrowser",
								]])->fileInput(['multiple' => true,"accept"=>"image/*"])->label('Additional images (optional)<br><strong class="reduceSize">(Add pictures that will help pet owners get to know you and your love for pets.)</strong>');
								?>
								<!--/div-->	
								<!--div id="upload_add"></div-->	
							</div>		
						</div>
							<div class="row-block settings-section">	
							<div class="col-md-12 col-sm-12 col-xs-12">
								<h4><strong>Security & Payment Details</strong></h4> 
							</div>	
							<div class="row-block">
								<div class="col-md-6 col-sm-6 col-xs-12">
									<?php
									#### ID documents
									echo $form->field($modelImageUpload['modelImgB'],'upload_documents[]',['inputOptions' => [
									'class' => "form-control imageBrowser",
									]])->fileInput(['multiple' => true])->label('Upload a picture of your Driver’s License or Photo ID<span> * </span>');
									?>
								</div>	
								<div class="col-md-6 col-sm-6 col-xs-12 multiple-browsefile">
								<!--label>House Images</label><span> * </span><br><strong class="reduceSize">(Minimum two inside house pictures. Rest assured these pictures will not be displayed to the owners)</strong><br-->
								<!--input id="uploadFile" placeholder="Add files from My Computer"/-->
								
									<!--div class="fileUpload btn btn-primary buttonht"-->
									
									<?php
										#### awaited profile pictures
										echo $form->field($modelImageUpload['modelImgD'],'upload_home_images[]',['inputOptions' => [
										'class' => "form-control imageBrowser",
										]])->fileInput(['multiple' => true,"accept"=>"image/*"])->label('Upload 2 or more images of the inside of your home.<span> * </span><br><strong class="reduceSize">(Rest assured these pictures will not be displayed to the owners)</strong><span class="orangeBtn bottomSpace plus">+</span>');
									?>
									<div id="output"></div>
									
									<!--/div-->	
									<!--div id="upload_prev"></div-->							    							
								</div>
							</div>
							
							
							<div class="col-md-12 col-sm-12 col-xs-12">
							
								<?php
									echo $form->field($model, 'paypal_email', ['inputOptions' => [
									'class' => "form-control",
									]])->textInput(['maxlength'=>150 , 'autofocus' => true])->label('PayPal Email<br><strong class="reduceSize">(Payment will be made to your paypal account after completion of service)</strong>');
								?>   
							</div>
						</div>		
										
						
																							
					<?php
					
					if(!empty($userServices) && count($userServices)>0 && empty($posteditdata)){ 
					$chkminservice = 0;
					
					?>
						<div id="addmoreblock" class="row-block">
							<div class="col-md-12 col-sm-12 col-xs-12">
								<h4 class="block-title-s"><strong>Pet Care Details</strong></h4>
							</div>
							
							<div class="col-md-12 col-sm-12 col-xs-12">
								<?php echo $form->field($model, 'certification')->checkboxList(['Pet Trainer' => '<div class="checkmark">Pet Trainer</div>', 'Veterinary School' => '<div class="checkmark">Veterinary School</div>', 'Groomer' => '<div class="checkmark">Groomer</div>','Behavioral Specialist' => '<div class="checkmark">Behavioral Specialist</div>','Fellow Pet Parent' => '<div class="checkmark">Fellow Pet Parent</div>','NULL' => '<div class="checkmark">None</div>']);?>
							
							</div>
													
							<?php foreach($userServices as $key=>$val){	
							$petweightlimit = $val['pet_weight_limit'];
							$pettypeid = $val['pet_type_id'];												
							?>
							<div class="row-block pet_t_sr">	
							<div id="servicelist<?php echo $val['id']?>">
								<div style="display:none;"><?php echo $form->field($model, 'pet_service_id[]')->hiddenInput(['value'=>$val['id']])->label(false); ?>
`							</div>
						<div class="row-inner">
							
							<div class="col-md-6 col-sm-6 col-xs-12">
								<?php
									echo $form->field($model, 'pet_parent_type_sr['.$chkminservice.']', ['inputOptions' => [
									'class' => "form-control whiteBorder selctpettype",
									]])->dropDownList($pet_type,['options'=>[$pettypeid=>["Selected"=>true]]])->label('Choose the type of pet you can care for <span class="required">*</span>');
								?>				  
							</div>														
							<div class="col-md-6 col-sm-6 col-xs-12 selectsearchoptions weightclass" id="weightid<?php echo $chkminservice ?>">								
								<?php
								echo $form->field($model, 'pet_weight_limit['.$chkminservice.']', ['inputOptions' => [
								'class' => "form-control whiteBorder selectpicker",
								'data-live-search' => "true",
								'multiple' => 'multiple',
								]])->dropDownList($pet_weight_limit, ['options' => $weightoptions])->label('Choose the sizes of pet you would accept <span class="required">*</span>');
								?>						               
							</div>				
					 </div>
				<script>
					$('document').ready(function(){
					var weight = '<?php echo $petweightlimit; ?>';
					var key = '<?php echo $chkminservice; ?>';
					var pettype = '<?php echo $pettypeid; ?>';
					if(pettype !=1){
					$('#weightid'+key).hide();
					}
					
					setpetweight(weight,key);
					
					});
				</script>
														
								<div class="col-md-6 col-sm-6 col-xs-12">
									<?php
										echo $form->field($model, 'services['.$chkminservice.']', ['inputOptions' => [
										'class' => "form-control whiteBorder lblchang1",
										]])->dropDownList($servicesTypes,['options'=>[$val['service_id']=>["Selected"=>true]]])->label('Services <span class="required">*</span>');
									?>
								</div>
								<div class="col-md-4 col-sm-6 col-xs-12">
									<?php
										echo $form->field($model, 'day_price['.$chkminservice.']', ['inputOptions' => [
										'class' => "form-control whiteBorder",
										]])->textInput(['maxlength'=>15 ,'type' => 'number', 'min' => 1, 'autofocus' => true,'value' => $val['price']])->label('Price <span>*</span> <span id="lbl1"></span>');
									?>
								</div>	
								<?php if($chkminservice > 0){?>						
								<div class="col-md-2 col-sm-6 col-xs-12">
								<a href="javascript:void(0)" class="orangeBtn remove_service_row" onclick="removeservice(<?php echo $val['id']; ?>)" >-</a></div>
								<?php }?>
							</div>	
							</div>
								<?php 
								$chkminservice++;
								} ?>							
						</div>
					<?php 
						
					}elseif(!empty($posteditdata)){
						
						
						
					$petserviceid = $posteditdata['UpdateSitter']['pet_service_id'];
					$pet_parent_type_sr = $posteditdata['UpdateSitter']['pet_parent_type_sr'];
					$oldpet_weight_limit = $posteditdata['UpdateSitter']['pet_weight_limit'];
					
					$userServices = $posteditdata['UpdateSitter']['services'];
					$day_price = $posteditdata['UpdateSitter']['day_price'];
		
					$j = 0;
						
					?>
						<div id="addmoreblock">
														
							<?php foreach($userServices as $val){
							$thisweightlimit = null;
							$petweightselected = array_values($oldpet_weight_limit);	
						
							
							if(isset($petweightselected[$j]) && !empty($petweightselected) ){
							
							$oldpetweightlimit = $petweightselected[$j];
								if(count($petweightselected[$j]) > 1){
								$thisweightlimit = implode(',',$petweightselected[$j]);
								}else{
							
									 if(count($petweightselected[$j]) == 1 && !empty($petweightselected[$j])){
									
									 $thisweightlimit = $petweightselected[$j][0];
									 }
								}
							}	
							
							$pettypeid = $pet_parent_type_sr[$j];												
							?>
							<div class="row-block spasaller pet_t_sr">	
							<div id="servicelist<?php echo $petserviceid[$j] ?>">
								<div style="display:none;"><?php echo $form->field($model, 'pet_service_id[]')->hiddenInput(['value'=>$petserviceid[$j]])->label(false); ?></div>
						<div class="row-inner">
							<div class="col-md-12 col-sm-12 col-xs-12">
								<h4 class="block-title-s"><strong>Pet Care Details</strong></h4>
							</div>	
							
							<div class="col-md-12 col-sm-12 col-xs-12">
							<?php echo $form->field($model, 'certification[]')->checkboxList(['Pet Trainer' => '<div class="checkmark">Pet Trainer</div>', 'Veterinary School' => '<div class="checkmark">Veterinary School</div>', 'Groomer' => '<div class="checkmark">Groomer</div>','Behavioral Specialist' => '<div class="checkmark">Behavioral Specialist</div>','Fellow Pet Parent' => '<div class="checkmark">Fellow Pet Parent</div>','NULL' => '<div class="checkmark">None</div>']);?>
							
							</div>
							
							<div class="col-md-6 col-sm-6 col-xs-12">
								<?php
									echo $form->field($model, 'pet_parent_type_sr['.$j.']', ['inputOptions' => [
									'class' => "form-control whiteBorder selctpettype",
									]])->dropDownList($pet_type,['options'=>[$pettypeid=>["Selected"=>true]]])->label('Choose the type of pet you can care for <span class="required">*</span>');
								?>				  
							</div>														
							<div class="col-md-6 col-sm-6 col-xs-12 selectsearchoptions weightclass" id="weightid<?php echo $j; ?>">								
								<?php
								echo $form->field($model, 'pet_weight_limit['.$j.']', ['inputOptions' => [
								'class' => "form-control whiteBorder selectpicker",
								'data-live-search' => "true",
								'multiple' => 'multiple',
								]])->dropDownList($pet_weight_limit, ['options' => $weightoptions])->label('Choose the sizes of pet you would accept <span class="required">*</span>');
								?>						               
							</div>				
					 </div>
				<script>
					$('document').ready(function(){
					var weight = '<?php echo $thisweightlimit; ?>';
					var key = '<?php echo $j; ?>';
					setpetweight(weight,key);
					
					var pettype = '<?php echo $pettypeid; ?>';
					if(pettype !=1){
					$('#weightid'+key).hide();
					}
					});
				</script>
														
								<div class="col-md-6 col-sm-6 col-xs-12">
									<?php
										echo $form->field($model, 'services['.$j.']', ['inputOptions' => [
										'class' => "form-control whiteBorder",
										]])->dropDownList($servicesTypes,['options'=>[$userServices[$j]=>["Selected"=>true]]])->label('Services <span class="required">*</span>');
									?>
								</div>
								<div class="col-md-4 col-sm-6 col-xs-12">
									<?php
										echo $form->field($model, 'day_price['.$j.']', ['inputOptions' => [
										'class' => "form-control whiteBorder",
									
										]])->textInput(['maxlength'=>15 ,'type' => 'number', 'min' => 1, 'autofocus' => true,'value' => $day_price[$j]])->label('Price <span>*</span>');
									?>
								</div>		
								<?php if($j > 0){?>						
								<div class="col-md-2 col-sm-6 col-xs-12">
								<a href="javascript:void(0)" class="orangeBtn remove_service_row" onclick="removeservice(<?php echo $petserviceid[$j]; ?>)" >-</a></div>
								<?php }?>
							</div>	
							</div>
								<?php 
								$j++;
								} ?>							
						</div>
						
						
						<?php }else{  ?>
						
						<div id="addmoreblock">
							<div class="row-block spasaller pet_t_sr">
							
							<div id="servicelist">
							<div style="display:none;"><?php echo $form->field($model, 'pet_service_id[]')->hiddenInput(['value'=>0])->label(false); ?></div>
							<div class="row-inner">
							<div class="col-md-12 col-sm-12 col-xs-12">
								<h4 class="block-title-s"><strong>Pet Care Details</strong></h4>
							</div>
							
							<div class="col-md-12 col-sm-12 col-xs-12">
							<?php echo $form->field($model, 'certification')->checkboxList(['Pet Trainer' => '<div class="checkmark">Pet Trainer</div>', 'Veterinary School' => '<div class="checkmark">Veterinary School</div>', 'Groomer' => '<div class="checkmark">Groomer</div>','Behavioral Specialist' => '<div class="checkmark">Behavioral Specialist</div>','Fellow Pet Parent' => '<div class="checkmark">Fellow Pet Parent</div>','NULL' => '<div class="checkmark">None</div>']);?>
							
							</div>
							
							<div class="col-md-6 col-sm-6 col-xs-12">
								<?php
									echo $form->field($model, 'pet_parent_type_sr[]', ['inputOptions' => [
									'class' => "form-control whiteBorder selctpettype",
									]])->dropDownList($pet_type)->label('Choose the type of pet you can care for <span class="required">*</span>');
								?>				  
							</div>														
							<div class="col-md-6 col-sm-6 col-xs-12 selectsearchoptions weightclass">								
								<?php
								echo $form->field($model, 'pet_weight_limit[0][]', ['inputOptions' => [
								'class' => "form-control whiteBorder selectpicker",
								'data-live-search' => "true",
								'multiple' => 'multiple',
								]])->dropDownList($pet_weight_limit, ['options' => $weightoptions])->label('Choose the sizes of pet you would accept <span class="required">*</span>');
								?>						               
							</div>				
					 </div>	 						
								
								<div class="col-md-6 col-sm-6 col-xs-12">
									<?php
										echo $form->field($model, 'services[]', ['inputOptions' => [
										'class' => "form-control whiteBorder lblchang1",
										]])->dropDownList($servicesTypes,['prompt'=>'select'])->label('Services <span class="required">*</span>');
									?>
								</div>
								<div class="col-md-4 col-sm-6 col-xs-12">
									<?php
										echo $form->field($model, 'day_price[]', ['inputOptions' => [
										'class' => "form-control whiteBorder",
										]])->textInput(['maxlength'=>15 ,'type' => 'number', 'min' => 1, 'autofocus' => true])->label('Price <span>*</span> <span id="lbl1"></span>');
									?>
								</div>	
								<!--div class="col-md-4 col-sm-6 col-xs-12">
								<a href="javascript:void(0)" class="orangeBtn remove_service_row" >-</a></div-->
							</div>	
						
						</div>
						</div>
						<?php  }?>
						<div class="row-block spasaller">
							<div class="col-lg-1 col-md-2 col-sm-2 col-xs-12">
									<a href="javascript:void(0)" class="orangeBtn bottomSpace" id="addmore_services" name="1">+</a>
							</div>	
							<div class="col-lg-11 col-md-10 col-sm-10 col-xs-12 btn-textinfo">Add another Type of Pet or Service</div>
							</div>
						<div class="button-condense">
						<div class="col-md-12 col-sm-12 col-xs-12">																	
						
		           
								
									<?php
									if($status > 0){
												echo Html::submitButton(Yii::t('yii','Save'), ['class' => 'orangeBtn-disable','disabled' => 'disabled', 'name' => 'editUser-submit', 'id' => 'editUser-submit']); 
												echo Html::submitButton(Yii::t('yii','SUBMIT FOR APPROVAL'), ['class' => 'orangeBtn', 'name' => 'editUser-submit', 'id' => 'editUser-submit']); 
									}else{
												echo Html::submitButton(Yii::t('yii','Save'), ['class' => 'orangeBtn', 'name' => 'editUser-submit', 'id' => 'editUser-submit']); 
												echo Html::submitButton(Yii::t('yii','SUBMIT FOR APPROVAL'), ['class' => 'orangeBtn-disable','disabled' => 'disabled','name' => 'editUser-submit', 'id' => 'editUser-submit']); 
												
									}	
									?>                  
							       
							
				
						</div>
						</div>
						<?php ActiveForm::end(); ?>
						<div class="col-sm-12">
						<p class="setting-bottomSpace">Once each of the required fields have been completed, please click "Submit for Approval" above. Palcura will then review and complete a background check. Upon approval, you will be notified by email and your profile will become activated and viewable by pet parents in your area.</p>
						</div>
				</div>
			</div>
		</div>
	</div>
</section>
<script>
	$(document).ready(function() {
		
		$('input[type=checkbox]').each(function () {
		
					if($(this).is(':checked')) 
						$(this).parent().addClass('selected'); 
					else 
						$(this).parent().removeClass('selected')
		
		});
		
		$('input:checkbox').change(function(){
		if($(this).is(':checked')) 
			$(this).parent().addClass('selected'); 
		else 
			$(this).parent().removeClass('selected')
		});
	
	
	$( ".lblchang1" ).change(function() {
		if($(this).val() == 1)
		{
			$('#lbl1').html('(Per Walk)');
		}
		else if($(this).val() == 2)
		{
			$('#lbl1').html('(Per Day)');
		}
		else if($(this).val() == 3)
		{
			$('#lbl1').html('(Per Day)');
		}
		else if($(this).val() == 4)
		{
			$('#lbl1').html('(Per Night)');
		}
		else if($(this).val() == 5)
		{
			$('#lbl1').html('(Per Visit)');
		}
		else
		{
			$('#lbl1').html('');
		}
	});

	
	//hide or show pet weight for doag and other pets type
	$('body').on('change','.selctpettype',function(){   
    if(this.value=='2' || this.value=='3'){
    $(this).parent().parent().parent().parent().find('.weightclass').hide();
    }
    if(this.value=='1'){
    $(this).parent().parent().parent().parent().find('.weightclass').show();
    }
    });
	

	var servicetypecount = "<?php echo count($servicesTypes); ?>";
	$(".pet_t_sr").css("display","block");
	
	$('#addmore_services').click(function() {
	
			var name = $("#addmore_services").attr("name");
			var newname	=	parseInt(name)+1;
			var scnt  = $("#scnt").val();
			/*if(scnt > servicetypecount){	
			alert('Can not add more services');
			return false;
			}*/
			
			$('#addmore_services').attr('name', newname);
			$('#addmoreblock').append('<div id="'+newname+'_outer" class="row-block spasaller"><div style="display:none;"><input id="updatesitter-pet_service_id" class="form-control" name="UpdateSitter[pet_service_id][]" value="0" type="hidden"></div><div class="row-block"><div class="col-md-6 col-sm-6 col-xs-12"><span class=" field-updatesitter-pet_parent_type_sr required"><div class="form-group"><label for="updatesitter-pet_parent_type_sr">Choose the type of pet you can care for <span class="required">*</span></label><select id="updatesitter-pet_parent_type_sr" class="form-control whiteBorder selctpettype" name="UpdateSitter[pet_parent_type_sr][]"><option value="1">Dog</option><option value="2">Cat</option><option value="3">Other</option></select><div class="col-lg-10"><p class="help-block help-block-error"></p></div></div></span></div><div class="col-md-6 col-sm-6 col-xs-12 selectsearchoptions weightclass"><span class="field-updatesitter-pet_weight_limit"><div class="form-group"><label for="updatesitter-pet_weight_limit">Choose the sizes of pet you would accept <span class="required">*</span></label><select id="updatesitter-pet_weight_limit" class="form-control whiteBorder selectpicker" name="UpdateSitter[pet_weight_limit][][]" size="4" data-live-search="true" multiple>'+'<?php echo $petweightOptions ?>'+'</select></div><div class="col-lg-10"><p class="help-block help-block-error"></p></div></span></div></div><div class="col-md-6 col-sm-6 col-xs-12"><span class="field-updatesitter-services has-success"><div class="form-group"><label for="updatesitter-services">Services <span class="required">*</span></label><select id="updatesitter-services" class="form-control whiteBorder lblchang'+newname+'" name="UpdateSitter[services][]">'+'<?= $serviceOptions ?>'+'</select><div class="col-lg-10"><p class="help-block help-block-error"></p></div></div></span></div><div class="col-md-4 col-sm-6 col-xs-12"><span class=" field-updatesitter-day_price"><div class="form-group"><label for="updatesitter-day_price">Price <span>*</span><span id="lbl'+newname+'"></span></label><input id="updatesitter-day_price" class="form-control" name="UpdateSitter[day_price][]" maxlength="5" min="1" autofocus="" type="number"><div class="col-lg-10"><p class="help-block help-block-error"></p></div></div></span></div><div class="col-md-2 col-sm-6 col-xs-12"><a href="javascript:void(0)" class="orangeBtn remove_service_row" id="'+newname+'" >-</a></div></div>');
			$('.selectpicker').selectpicker('refresh');
			var newfscnt = parseInt(scnt)+1;
			$("#scnt").val(newfscnt);
			
			var idname = 'lblchang'+newname;
			var idlbl = 'lbl'+newname;
			
			if($('.'+idname).val() == 1)
			{
				$('#'+idlbl).html('(Per Walk)');
			}
			
			$("."+idname).change(function() {
				if($(this).val() == 1)
				{
					$('#'+idlbl).html('(Per Walk)');
				}
				else if($(this).val() == 2)
				{
					$('#'+idlbl).html('(Per Day)');
				}
				else if($(this).val() == 3)
				{
					$('#'+idlbl).html('(Per Day)');
				}
				else if($(this).val() == 4)
				{
					$('#'+idlbl).html('(Per Night)');
				}
				else if($(this).val() == 5)
				{
					$('#'+idlbl).html('(Per Visit)');
				}
				else
				{
					$('#'+idlbl).html('');
				}
			});
		
		});
		
	
		
			//$("#scnt").val(newfscnt);
		$('#addmoreblock').on('click', '.remove_service_row', function() {
			var ID = $(this).attr('id');
			$('#'+ID+'_outer').remove();
			var scnt  = $("#scnt").val();
			var newfscnt = parseInt(scnt)-1;
			$("#scnt").val(newfscnt);
		});
	});
	
	function removeservice(id){
	var r = confirm("Are You sure, You want to remove this service permanently!");
	if (r == true) {
	   	$.ajax({
			url:'<?php echo Url::to(['users/removeservice']);?>',
			type:'post',
			data:{'id':id},
			success:function(response){				
					if(response==1){
					$('#servicelist'+id).remove();
					}else{
					console.log('failed');
					}
			}	
		});
	}else{
	return false;
	}
	}
</script>	
<?php
	$modelID = "updatesitter";
?>
<!--<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>-->


<script>
$("#<?= $modelID ?>-region").on('change',function(){
	//$("#updatesitter-city").val('');
    var stateID = $(this).val();
    $("#<?= $modelID ?>-city").find("option:gt(0)").remove();
    //$("#state").find("option:first").text("Loading...");
    $.ajax({
                type: 'POST',
                url: '<?= $ajaxCUrl ?>',
                data: 'id=' + stateID,
                success: function (json) {
					
					/* var myarray = [];
					
					for (var i = 0; i < json.length; i++) {
						myarray.push({
							"label": json[i].name + '-' + json[i].cityAlias,
							"value": json[i].id
							});
					}
					
					
					$(function() {
						$( "#updatesitter-city" ).autocomplete({
						 source: function( request, response ) {
							 var matcher = new RegExp( "^" + $.ui.autocomplete.escapeRegex( request.term ), "i" );
							 response( $.grep( myarray, function( item ){
								 return matcher.test( item.label );
							 }) );
						   },
							minLength: 1,
							select: function(event, ui) {
								event.preventDefault();
								$("#updatesitter-city").val(ui.item.label);
								$("#selected-tag").val(ui.item.label);
								
							}
							,
							focus: function(event, ui) {
								event.preventDefault();
								$("#updatesitter-city").val(ui.item.label);
							}
						});
					}); */


                    $("#<?= $modelID ?>-city").find("option:first").text("<?php echo Yii::t('yii', 'Select City'); ?>");
                    for (var i = 0; i < json.length; i++) {
                        $("<option>").attr("value", json[i].id).text(json[i].name + '-' + json[i].cityAlias).appendTo($("#<?= $modelID ?>-city"));
                    }
                  
                    $("#<?= $modelID ?>-city").selectpicker('refresh');
                }
            });    
    });
</script>

<script>

function setpetweight(weight,key){
var petweights = weight;
var values=petweights;

$.each(values.split(","), function(i,e){
		$("#updatesitter-pet_weight_limit-"+key+" option[value='" + e + "']").prop("selected", true);
	});
$('#updatesitter-pet_weight_limit-'+key+'').selectpicker('refresh');
}
</script>

<script>
							
/*$(document).on('click','.houseimg',function(){
$(this).parents('span').remove();

})*/
//document.getElementById('uploads-upload_home_images').onchange = uploadOnChange;
							
function uploadOnChange() { 
    document.getElementById("uploadFile").value = this.value;
    var filename = this.value;
    var lastIndex = filename.lastIndexOf("\\");
    if (lastIndex >= 0) {
        filename = filename.substring(lastIndex + 1);
    }
    var files = $('#uploads-upload_home_images')[0].files;
    for (var i = 0; i < files.length; i++) {
     $("#upload_prev").append('<span>'+'<div class="filenameupload">'+files[i].name+'</div>'+'<p class="houseimg" >X</p></span>');
    }
    //document.getElementById('filename').value = filename;
}


/*$(document).on('click','.addimg',function(){
$(this).parents('span').remove();

})*/
//document.getElementById('uploads-upload_images').onchange = uploadadditionalimg;
							
function uploadadditionalimg() { 
    document.getElementById("additionalimg").value = this.value;
    var filename = this.value;
    var lastIndex = filename.lastIndexOf("\\");
    if (lastIndex >= 0) {
        filename = filename.substring(lastIndex + 1);
    }
    var files = $('#uploads-upload_images')[0].files;
    for (var i = 0; i < files.length; i++) {
     $("#upload_add").append('<span>'+'<div class="filenameupload">'+files[i].name+'</div>'+'<p class="addimg" >X</p></span>');
    }
    //document.getElementById('filename').value = filename;
}
$("#uploads-upload_home_images").change(function() {
    var result = $(this)[0].files;
    for(var x = 0;x< result.length;x++){
       var file = result[x];
       // here are the files
        $("#output").append("<p>" + file.name + " (TYPE: " + file.type + ", SIZE: " + file.size + ")</p>");  
    }
    
});
</script>
<!--link href="<?= WEBSITE_CSS_PATH ?>sittersettings.css" rel="stylesheet"-->
