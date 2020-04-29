<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\widgets\DetailView;
use common\models\Country;
use common\models\State;
use common\models\City;
use yii\helpers\ArrayHelper;
$ajaxSUrl	= SITE_URL.'ajax/updatestates';
$ajaxCUrl	= SITE_URL.'ajax/updatecities';
$this->title = 'Setting Profile';
$this->params['breadcrumbs'][] = $this->title;
$siteimage = Yii::getAlias('@siteimage');
$user_id = Yii::$app->user->identity->id;
$rentingPet = Yii::$app->user->identity->renting_pet;

####= common methods =####
$countries 				= Yii::$app->commonmethod->countries();
$regions 				= Yii::$app->commonmethod->regions();

$cities 				= Yii::$app->commonmethod->citiesbyregion($model->region);
$servicesTypes 			= Yii::$app->commonmethod->servicesTypes();

$hear_about 			= array(1=>'Initial launch',2=>'Google',3=>'Facebook',4=>'Instagram',5=>'Twitter',6=>'Next door',7=>'Other Social',8=>'Event',9=>'Dog Park',10=>'Flyers',11=>'Word of mouth',12=>'Referral',13=>'Other');
$pet_type 				= Yii::$app->commonmethod->getPetTypes();
$pet_parent_id			= 0;
$pet_parent_id 			= (isset($pet_type) ? key($pet_type) : 0 );
$pet_sub_type 			= Yii::$app->commonmethod->getPetTypes($pet_parent_id);

$pet_weight_limit		= "";
//$petinformation	=	Yii::$app->commonmethod->getPetinformation($user_id);

	if($rentingPet == 1){
	$interested_in_renting =  1;
	}else{
	$interested_in_renting = 0;
	}
	
	if(!empty($posteditdata)){
		$rentinginfo = $posteditdata['UpdateOwner'];
			if($rentinginfo['renting_pet'] == 1){
			$interested_in_renting =  1;
			}
		}
		
	$agearray = array();
	for( $i=1; $i<=100; $i++ )
	{
	$agearray[] = $i; 
	}
	$petweightarray = array(''=>'Select',"Small" => ['1'=>'0-15lbs'],"Medium" => ['2'=>'16-40lbs'],"Large" => ['3'=>'41-100lbs'],"Giant" => ['4'=>'101+lbs']);

$pet_type = array_slice($pet_type, 0, 3, true);

$pettypeOptions	=	"";
$pettypeOptions .= '<option value="">Pet Type</option>';
foreach($pet_type as $key=>$row) {

	$pettypeOptions .='<option value="'.$key.'">'.$row.'</option>';	

}

$breedNameOptions	=	"";
$breedNameOptions .= '<option value="">Your Pet Type</option>';
foreach($pet_sub_type as $key=>$row) {
	$breedNameOptions .='<option value="'.$key.'">'.$row.'</option>';
}
;
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
						
						
						?>
						<div style="display:none;">
						<?php echo $form->field($modelImgK, 'renting_a_pet')->hiddenInput(['value'=> $interested_in_renting])->label(false); ?>
						</div>
						<div class="row-block">					
							<div class="col-md-4 col-sm-6 col-xs-12">
								<?php
									#### display image
									echo $form->field($modelImageUpload, 'profile_image',['inputOptions' => [
									'class' => "form-control imageBrowser",
									]])->fileInput()->label('Profile Image (optional)');
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
							<div class="col-md-4 col-sm-6 col-xs-12">
								<?php
									echo $form->field($model, 'email', ['inputOptions' => [
									'class' => "form-control",
									]])->textInput(['maxlength' => 100, 'readOnly' => true])->label('Email *');
								?>
							</div>
							 
						</div> 
						
						<div class="row-block">								
							<div class="col-md-4 col-sm-6 col-xs-12">
								<?php
									echo $form->field($model, 'registration_type', ['inputOptions' => [
									'class' => "form-control whiteBorder",
									]])->dropDownList($hear_about,['prompt'=>'Select'])->label('How did you hear about us? <span class="required"> * </span>');
								?>
							</div>
						</div>
									<script>
									$('document').ready(function(){																		
										$('.collapseIcon').click(function(){	
											$( ".collapseField" ).toggleClass('show');
										});
									});
									</script>														
						<div class="row-block">
							<div class="col-md-12 col-sm-12 col-xs-12">
							<div class="collapseIcon vaccinfo"><div class="vaccinat">Additional Information (optional)</div></div></div>
							 <div class="col-md-4 col-sm-6 col-xs-12 collapseField">
								<?php
									#### display image
									echo $form->field($modelImageUploadF, 'vaccination_doc',['inputOptions' => [
									'class' => "form-control imageBrowser",
									]])->fileInput()->label('Vaccination Document');
								?>
							</div>
							<div class="col-md-4 col-sm-6 col-xs-12 collapseField">
								<?php 
								echo $form->field($vaccinationModel, 'vaccination_validity', ['inputOptions' => [
								'class' => "form-control datepicker",
								'value' => isset($vaccinationdetails['vaccination_validity'])?$vaccinationdetails['vaccination_validity']:'',
								]])->textInput(['maxlength'=>15 , 'autofocus' => true, 'readOnly' => true])->label('Vaccination Expiration date <span></span>');
								?>
							</div>						
							 	
						</div> 
						<div class="row-block pet_t_ow">
							<div class="col-md-12 col-sm-6 col-xs-12">
								<div class="form-group signUpOuter">
									<div class="col-lg-12  col-sm-12 col-xs-12 col-md-4 signUpListing"><strong>Interested in joining our pet borrowing program? Share the care of your pet and help future pet owners gain confidence *</strong></br>
										<?php if($interested_in_renting == 1){?>
										<input class="renting_pet" name="pertrenting" 
										value="1" type="radio" checked> Yes
										<input class="renting_pet" name="pertrenting" 
										value="0" type="radio"> No
										<div style="display:none;"><?php echo $form->field($model, 'renting_pet')->hiddenInput(['value'=>1])->label(false);?></div>
										<?php }else{?>
										<input class="renting_pet" name="pertrenting" 
										value="1" type="radio" > Yes
										<input class="renting_pet" name="pertrenting" 
										value="0" type="radio" checked> No
										<div style="display:none;"><?php echo $form->field($model, 'renting_pet')->hiddenInput(['value'=>0])->label(false);?></div>
										<?php } ?>
										
									</div>
								</div>
							</div> 
						</div>
						
						<div class="pet_t_ow rending_block">
						
						<div class="row-block">                                                

							<div class="col-md-4 col-sm-6 col-xs-12 addressfield">
								<?php
									echo $form->field($model, 'address', ['inputOptions' => [
									'class' => "form-control textfeild",'id' => "search_destination1"
									]])->textInput(['maxlength' => 250, 'autofocus' => false])->label('Address <span>*</span>');
								?>
							</div>
							<div class="col-md-4 col-sm-6 col-xs-12">
								<?php
									echo $form->field($model, 'zip_code', ['inputOptions' => [
									'class' => "form-control",
									]])->textInput(['maxlength'=>15 , 'autofocus' => false])->label('Zip Code <span>*</span>');
								?>   				  
							</div>
							<div class="col-md-4 col-sm-6 col-xs-12">
								<?php
									echo $form->field($model, 'paypal_email', ['inputOptions' => [
									'class' => "form-control",
									]])->textInput(['maxlength'=>150 , 'autofocus' => true])->label('PayPal Email. <span>*</span>');
								?>   
							</div>
						</div>
						<div class="row-block">
							
							<div class="col-md-4 col-sm-6 col-xs-12">
								<?php
									echo $form->field($model, 'country', ['inputOptions' => [
									'class' => "form-control whiteBorder",
									]])->dropDownList($countries,['prompt'=>'Select Country'])->label('Country <span class="required">*</span>');
								?>
							</div>
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
							</div>
						</div>
						
						
						<?php if(!empty($petinformation) && count($petinformation)>0 && empty($posteditdata)){						
								$contval = 0;
								foreach($petinformation as $k => $v){ 
								$contval++;
								$pet_parent_type = $v['pet_parent_id'];
								$pet_type_id = $v['type'];
								$per_day_price = $v['per_day_price'];
								$pet_name = $v['name'];
								$pet_care_note = $v['care_note'];
								$petid = $v['id'];
								
						?>						
							<div id="addmoreRentingblocktwo<?php echo $k;?>">	
							<?php echo $form->field($model, 'pet_id['.$k.']')->hiddenInput(['value'=> $petid])->label(false); ?>
						<div class="row-block">
							<div class="col-md-4 col-sm-6 col-xs-12">
								<?php
									echo $form->field($model, 'pet_parent_type['.$k.']', ['inputOptions' => [
									'class' => "form-control whiteBorder",
									'id' => 'petparentype',
									]])->dropDownList($pet_type,['options'=>[$pet_parent_type=>["Selected"=>true]]])->label('Pet Types <span class="required">*</span>');
								?>				  
							</div> 
							<div class="col-md-4 col-sm-6 col-xs-12" id="pettype">
								<?php
									echo $form->field($model, 'pet_type['.$k.']', ['inputOptions' => [
									'class' => "form-control whiteBorder",
									]])->dropDownList($pet_sub_type,['options'=>[$pet_type_id=>["Selected"=>true]]])->label('Breed of the pet <span class="required">*</span>');
								?>				  
							</div> 
							
							<div class="col-md-4 col-sm-6 col-xs-12">
										<?php
											echo $form->field($model, 'pet_name['.$k.']', ['inputOptions' => [
												'class' => "form-control whiteBorder",
												'value' => $pet_name
									]])->textInput(['autofocus' => false])->label('Pet Name <span>*</span>');
								?>
							</div>
										
						</div>
						<div class="row-block">	
						<div class="col-md-4 col-sm-4 col-xs-12">
								<?php
									echo $form->field($model, 'care_note['.$k.']', ['inputOptions' => [
									'class' => "form-control whiteBorder",
									'value' => $pet_care_note
									]])->textInput(['autofocus' => false])->label('Care Note');
								?>
							</div>				
							<div class="col-md-4 col-sm-6 col-xs-12">
								<?php
									echo $form->field($model, 'per_day_price['.$k.']', ['inputOptions' => [
									'class' => "form-control whiteBorder",
									]])->textInput(['maxlength'=>40 ,'autofocus' => true, 'type' => 'number', 'min' => 1, 'value' => $per_day_price])->label('Borrowing Price Per Day <span>*</span>');
								?>
							</div>
							<div class="col-md-4 col-sm-4 col-xs-12 ">
								<?php
					
									#### awaited profile pictures
									echo $form->field($modelImgK,'picture_of_pet['.$k.']',['inputOptions' => [
									'class' => "form-control imageBrowser",
									]])->fileInput(['multiple' => true,"accept"=>"image/*"])->label('Picture of your Pet <span> * </span>');
								?>							
							</div>
					  </div>	
				 </div>
				 	
				 	 <div class="row-block addmore" id="oladdmore<?php echo $k;?>">							 
							<div class="col-md-12 col-sm-12 col-xs-12">
							<?php if($k == 0){?>
							<!--a href="javascript:void(0)" class="orangeBtn" id="addmore_rentingpets" name="1">+</a-->
							<?php }else{?>
							<a href="javascript:void(0)" class="orangeBtn remove_renter_row minusbut" id="removethisdiv<?php echo $k; ?>" onclick="removenewdivs(<?php echo $k; ?>,<?php echo $petid ?>)">-</a>
							<?php }?>
					</div>
				 </div>
						<?php 
						}?>
						
					<?php } else if(!empty($posteditdata)){  
					$petid = $posteditdata['UpdateOwner']['pet_id'];
					$pet_parent_type = $posteditdata['UpdateOwner']['pet_parent_type'];
					$pet_type_id = $posteditdata['UpdateOwner']['pet_type'];
					$pet_name = $posteditdata['UpdateOwner']['pet_name'];
					$care_note = $posteditdata['UpdateOwner']['care_note'];
					$per_day_price = $posteditdata['UpdateOwner']['per_day_price'];
					
					$j = 0; ?>
				
<div id="addmoreRentingblockTnew">
				<?php 	foreach($petid as $v){
												
					 ?>
				<div id="newrentingdiv<?php echo $j ?>">											
					<?php echo $form->field($model, 'pet_id[]')->hiddenInput(['value'=> $petid[$j]])->label(false); ?>
				
						<div class="row-block">						
							<div class="col-md-4 col-sm-6 col-xs-12">
								<?php
									echo $form->field($model, 'pet_parent_type['.$j.']', ['inputOptions' => [
									'class' => "form-control whiteBorder petparentn".$j."",								
									]])->dropDownList($pet_type,['options'=>[$pet_parent_type[$j]=>["Selected"=>true]]])->label('Pet Type <span class="required">*</span>');
								?>				  
							</div> 
							<div class="col-md-4 col-sm-6 col-xs-12" id="pettype<?php echo $j;?>">
								<?php						
									echo $form->field($model, 'pet_type['.$j.']', ['inputOptions' => [
									'class' => "form-control whiteBorder petbreedtypen".$j."",
									]])->dropDownList($pet_sub_type,['options'=>[$pet_type_id[$j]=>["Selected"=>true]]])->label('Breed of the pet <span class="required">*</span>');
								?>				  
							</div> 
							<script>
							$('document').ready(function(){
						var key = "<?php echo $j ?>";
						var parentopt = $('.petparentn'+key+' option:selected').val();
						if(parentopt == 2){
						$('#pettype'+key).hide();
						}

							$('.petparentn'+key).on('change', function (e) {
								var optionSelected = $("option:selected", this);
								var valueSelected = this.value;
							   if(valueSelected == 1){
							   $('#pettype'+key).show();
							   }
							   if(valueSelected == 2 || valueSelected==3){
							   $('#pettype'+key).hide();
							   }
							});


							});
							
							</script> 
							<div class="col-md-4 col-sm-6 col-xs-12">
										<?php
											echo $form->field($model, 'pet_name['.$j.']', ['inputOptions' => [
												'class' => "form-control whiteBorder",
												'value' => $pet_name[$j]
									]])->textInput(['autofocus' => false])->label('Pet Name <span>*</span>');
								?>
							</div>
					 </div>
					 <div class="row-block">	
					 		
							<div class="col-md-4 col-sm-4 col-xs-12">
								<?php
									echo $form->field($model, 'care_note['.$j.']', ['inputOptions' => [
									'class' => "form-control whiteBorder",
									'value' => $care_note[$j]
									]])->textInput(['autofocus' => false])->label('Care Note');
								?>
							</div>				
							<div class="col-md-4 col-sm-6 col-xs-12">
								<?php
									echo $form->field($model, 'per_day_price['.$j.']', ['inputOptions' => [
									'class' => "form-control whiteBorder",
									]])->textInput(['maxlength'=>40 ,'autofocus' => true, 'type' => 'number', 'min' => 1, 'value' => $per_day_price[$j]])->label('Borrowing Price Per Day <span>*</span>');
								?>
							</div>
							<div class="col-md-4 col-sm-4 col-xs-12 ">
								<?php
					
									#### awaited profile pictures
									echo $form->field($modelImgK,'picture_of_pet['.$j.']',['inputOptions' => [
									'class' => "form-control imageBrowser",
									]])->fileInput(['multiple' => true,"accept"=>"image/*"])->label('Picture of your Pet<span> * </span>');
								?>							
							</div>													
					</div>
					
							
						<div class="row-block addmore" id="oladdmore<?php echo $j;?>">							 
							<div class="col-md-12 col-sm-12 col-xs-12">
							<?php if($j == 0){?>
							
							<?php }else{
							if($petid[$j] > 0){
							?>
							<a href="javascript:void(0)" class="orangeBtn remove_renter_row minusbut" id="removethisdiv<?php echo $j; ?>" onclick="removenewdivs(<?php echo $j; ?>,<?php echo $petid[$j] ?>)">-</a>
							<?php }else{ ?>
							<a href="javascript:void(0)" class="orangeBtn remove_renter_row minusbut" id="<?php echo $j; ?>">-</a>
							<?php }
							} ?>
					  		</div>
				
						</div>
			</div>				
					<?php 
					
						$j++;
					 }
					?>
   		 </div>
					<?php } else{?>
						<?php echo $form->field($model, 'pet_id[]')->hiddenInput(['value'=> 0])->label(false); ?>
						<div class="row-block">
						
							<div class="col-md-4 col-sm-4 col-xs-12 ">
								<?php
									echo $form->field($model, 'pet_parent_type[]', ['inputOptions' => [
									'class' => "form-control whiteBorder selctpettype",									
									]])->dropDownList($pet_type,['prompt'=>'Pet Type'])->label('Pet Type <span class="required">*</span>');
								?>											  
							</div> 
							<div class="col-md-4 col-sm-4 col-xs-12 selectsearchoptions pettpesiaplay" id="pettype">
								<?php
									echo $form->field($model, 'pet_type[]', ['inputOptions' => [
									'class' => "form-control whiteBorder selectpicker ",
									'data-live-search'=>"true",
									]])->dropDownList($pet_sub_type,['prompt'=>'Your Pet Type'])->label('Breed of the pet <span class="required">*</span>');
								?>				  
							</div> 
							<div class="col-md-4 col-sm-4 col-xs-12">
								<?php
									echo $form->field($model, 'pet_name[]', ['inputOptions' => [
									'class' => "form-control whiteBorder",
									]])->textInput(['autofocus' => false])->label('Pet Name <span>*</span>');
								?>
							</div>
					 </div>
					 <div class="row-block">	
					 		
							<div class="col-md-4 col-sm-4 col-xs-12">
								<?php
									echo $form->field($model, 'care_note[]', ['inputOptions' => [
									'class' => "form-control whiteBorder",
									]])->textInput(['autofocus' => false])->label('Care Note');
								?>
							</div>
							<div class="col-md-4 col-sm-4 col-xs-12">
								<?php
									echo $form->field($model, 'per_day_price[]', ['inputOptions' => [
									'class' => "form-control whiteBorder",
									]])->textInput(['maxlength'=>40 ,'type' => 'number', 'min' => 1,'autofocus' => false])->label('Borrowing Price Per Day <span>*</span>');
								?>
							</div>
							<div class="col-md-4 col-sm-4 col-xs-12 ">
								<?php					
									#### awaited profile pictures
									echo $form->field($modelImgK,'picture_of_pet[]',['inputOptions' => [
									'class' => "form-control imageBrowser",
									]])->fileInput(['multiple' => true,"accept"=>"image/*"])->label('Picture of your Pet<span> * </span>');
								?>							
							</div>														
					</div>
					
												
					<?php } ?>
					
					<div id="addmoreRentingblockOne"> </div>
					 <div class="row-block addmore">							 
							<div class="col-md-4 col-sm-4 col-xs-12">
							<a href="javascript:void(0)" class="orangeBtn" id="addmore_rentingpets" name="1">+</a>
							</div>
				</div>
</div>
				
				<script>
			$('#addmore_rentingpets').click(function() {
	
			var Rname = $("#addmore_rentingpets").attr("name");
			var Rnewname	=	parseInt(Rname)+1;
			var Rscnt  = $("#scntrent").val();

			$('#addmore_rentingpets').attr('name', Rnewname);
			
			//scroll page to here
			$('html, body').animate({
							scrollTop: $('#addmore_rentingpets').offset().top
						}, 'slow');
												
			$('#addmoreRentingblockOne').append('<div id="'+Rnewname+'_Router"><span class=" field-updateowner-pet_id-'+Rnewname+'"><div class="form-group"><input id="updateowner-pet_id-'+Rnewname+'" class="form-control" name="UpdateOwner[pet_id][]" value="0" type="hidden"><div class="col-lg-10"><p class="help-block help-block-error"></p></div></div></span><div class="row-block"><div class="col-md-4 col-sm-4 col-xs-12"><span class=" field-updateowner-pet_parent_type"><div class="form-group"><label for="updateowner-pet_parent_type">Pet Types <span class="required">*</span></label><select id="updateowner-pet_parent_type'+Rnewname+'" class="form-control whiteBorder selctpettype" name="UpdateOwner[pet_parent_type][]">'+'<?= $pettypeOptions ?>'+'</select><div class="col-lg-10"><p class="help-block help-block-error"></p></div></div></span></div><div class="col-md-4 col-sm-4 col-xs-12 selectsearchoptions pettpesiaplay" id="pettype'+Rnewname+'"><span class=" field-updateowner-pet_type"><div class="form-group"><label for="updateowner-pet_type">Breed of the pet <span class="required">*</span></label><select id="updateowner-pet_type'+Rnewname+'" class="form-control whiteBorder selectpicker" name="UpdateOwner[pet_type][]" data-live-search="true">'+'<?= $breedNameOptions ?>'+'</select><div class="col-lg-10"><p class="help-block help-block-error"></p></div></div></span></div><div class="col-md-4 col-sm-4 col-xs-12"><span class=" field-updateowner-pet_name"><div class="form-group"><label for="updateowner-pet_name">Pet Name <span>*</span></label><input id="updateowner-pet_name'+Rnewname+'" class="form-control whiteBorder" name="UpdateOwner[pet_name][]" autofocus="" type="text"><div class="col-lg-10"><p class="help-block help-block-error"></p></div></div></span></div></div><div class="row-block"><div class="col-md-4 col-sm-4 col-xs-12"><span class=" field-updateowner-care_note"><div class="form-group"><label for="updateowner-care_note">Care Note</label><input id="updateowner-care_note'+Rnewname+'" class="form-control whiteBorder" name="UpdateOwner[care_note][]" autofocus="" type="text"><div class="col-lg-10"><p class="help-block help-block-error"></p></div></div></span></div><div class="col-md-4 col-sm-4 col-xs-12"><span class=" field-updateowner-per_day_price"><div class="form-group"><label for="updateowner-per_day_price">Borrowing Price Per Day <span>*</span></label><input id="updateowner-per_day_price'+Rnewname+'" class="form-control whiteBorder" name="UpdateOwner[per_day_price][]" maxlength="40" min="1" autofocus="" type="number"><div class="col-lg-10"><p class="help-block help-block-error"></p></div></div></span></div><div class="col-md-4 col-sm-4 col-xs-12 "><span class="field-uploads-picture_of_pet"><div class="form-group"><label for="uploads-picture_of_pet">Picture of your Pet<span> * </span></label><input name="Uploads[picture_of_pet][]" value="" type="hidden"><input id="uploads-picture_of_pet'+Rnewname+'" class="form-control imageBrowser" name="Uploads[picture_of_pet][]" multiple="" accept="image/*" type="file"><div class="col-lg-10"><p class="help-block help-block-error"></p></div></div></span></div></div><div class="row-block addmore"><div class="col-md-12 col-sm-12 col-xs-12"><a href="javascript:void(0)" class="orangeBtn remove_renter_row minusbut" id="'+Rnewname+'" >-</a></div></div></div>');
			$('.selectpicker').selectpicker('refresh');
			var Rnewfscnt = parseInt(Rscnt)+1;
			$("#scntrent").val(newfscnt);
			
			$('#updateowner-pet_parent_type2').on('change',function(){   
			if(this.value=='2' || this.value=='3'){
			$('#pettype2').hide();
			}
			if(this.value=='1'){
			$('#pettype2').show();
			}
			});
			
		});
		
		$('#addmoreRentingblockOne').on('click', '.remove_renter_row', function() {
			var ID = $(this).attr('id');
			$('#'+ID+'_Router').remove();
			var Rscnt  = $("#scntrent").val();
			var Rnewfscnt = parseInt(Rscnt)-1;
			$("#scntrent").val(Rnewfscnt);
var prevID = ID-1;
			var desiredHeight = $(window).height() - 100;	
			$('html, body').animate({
							scrollTop: $('#addmore_rentingpets').offset().top-desiredHeight
						}, 'slow');			
		});
		
		$('#addmoreRentingblockTnew').on('click', '.remove_renter_row', function() {
			var ID = $(this).attr('id');
			$('#newrentingdiv'+ID).remove();
			var Rscnt  = $("#scntrent").val();
			var Rnewfscnt = parseInt(Rscnt)-1;
			$("#scntrent").val(Rnewfscnt);
var prevID = ID-1;
			var desiredHeight = $(window).height() - 100;	
			$('html, body').animate({
							scrollTop: $('#addmore_rentingpets').offset().top-desiredHeight
						}, 'slow');			
		});
		
		
		function removenewdivs(id, petid){
			$('#addmoreRentingblocktwo'+id).remove();
			$('#removethisdiv'+id).remove();
			$('#oladdmore'+id).remove();
			
			var r = confirm("Are You sure, You want to remove this pet permanently!");
			if (r == true) {
			   	$.ajax({
					url:'<?php echo Url::to(['users/removepet']);?>',
					type:'post',
					data:{'id':petid},
					success:function(response){				
							if(response==1){
							console.log('success');
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
<script>
$(".renting_pet").change(function() {
			if($('.renting_pet:checked').val() == 1) {
				$('.rending_block').css("display","block");
			} else {
				$('.rending_block').css("display","none");
			}
		});
$('document').ready(function(){
var interestedinrenting = '<?php echo $interested_in_renting; ?>';
if(interestedinrenting==1){
$('.rending_block').css("display","block");
}else{
$('.rending_block').css("display","none");
}
});

$('document').ready(function(){
var category = null;
	$("input[name='pertrenting']").click(function() {
    category = this.value;
    
    $('#updateowner-renting_pet').val(category);
    $('#uploads-renting_a_pet').val(category);
    
});

});

</script>
<?php
	$modelID = "updateowner";
?>
<script type="text/javascript">

$('document').ready(function(){
   
    if($('#updateowner-pet_parent_type').val()==3 || $('#updateowner-pet_parent_type').val()==2){
    $('#pettype').hide();
    }
   
    $('body').on('change','.selctpettype',function(){   
    if(this.value=='2' || this.value=='3'){
    $(this).parent().parent().parent().parent().find('.pettpesiaplay').hide();
    }
    if(this.value=='1'){
    $(this).parent().parent().parent().parent().find('.pettpesiaplay').show();
    }
    });
    
    });


	$(function() {
		$("#vaccinationdetails-vaccination_validity").datepicker({
			numberOfMonths: 1,
			showButtonPanel: true,
			minDate: 0,
			maxDate: '+1Y',	
		});
	}); 
	   
</script>

<script>
$("#<?= $modelID ?>-region").on('change',function(){
    var stateID = $(this).val();
    $("#<?= $modelID ?>-city").find("option:gt(0)").remove();
    //$("#state").find("option:first").text("Loading...");
    $.ajax({
                type: 'POST',
                url: '<?= $ajaxCUrl ?>',
                data: 'id=' + stateID,
                success: function (json) {
                    $("#<?= $modelID ?>-city").find("option:first").text("<?php echo Yii::t('yii', 'Select City'); ?>");
                    for (var i = 0; i < json.length; i++) {
                        $("<option/*>").attr("value", json[i].id).text(json[i].name + '-' + json[i].cityAlias).appendTo($("#<?= $modelID ?>-city"));
                    }
                  
                    $("#<?= $modelID ?>-city").selectpicker('refresh');
                }
            });    
    });
</script>

