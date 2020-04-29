<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\bootstrap\ActiveForm;


$this->title = Yii::t('yii','Bookings');
$siteimage  = Yii::getAlias('@siteimage');
$userId 	= Yii::$app->user->getId();
$session = Yii::$app->session;
$usertype = $session->get('loggedinusertype');	

?>
<style>
.accordion-link { display:none; }
</style>
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
		
   <?php if (Yii::$app->session->getFlash('error')): ?>
    <script>
						$(document).ready(function () {
						// Handler for .ready() called.
						$('html, body').animate({
							scrollTop: $('#scrrollhere').offset().top
						}, 'slow');
						});
												
						</script>
    <div class="alert alert-grey alert-dismissible" id="scrrollhere"> <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span> </button> <i class="fa fa-remove"></i> <?php echo Yii::$app->session->getFlash('error'); ?> </div>
   <?php endif; ?>
    <?php if (Yii::$app->session->getFlash('success')): ?>
     <script>
						$(document).ready(function () {
						// Handler for .ready() called.
						$('html, body').animate({
							scrollTop: $('#scrrollhere').offset().top
						}, 'slow');
						});
												
						</script>
    <div class="alert alert-grey alert-dismissible" id="scrrollhere"> <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span> </button> <i class="fa fa-check"></i> <?php echo Yii::$app->session->getFlash('success'); ?> </div>
   <?php endif; ?>
	  <?php echo $this->render('//common/sidebar'); ?>
      <div class="col-md-10 col-sm-9 col-xs-12 scrolldiv01">
        <div class="responsive-tabs-container accordion-xs accordion-sm pastBookingAccord">
		<div class="tabHead pastBooking">
			<ul class="nav nav-tabs responsive-tabs">
				<li class="active"><a data-toggle="tab" href="#menu1">Current</a></li>
				<li><a data-toggle="tab" href="#menu2">Past</a></li>
				<li><a data-toggle="tab" href="#menu3">Declined/Cancelled</a></li>
			</ul>
		</div>
          <div class="tab-content">
            <div id="menu1" class="tab-pane active">
				<?php
				
				Pjax::begin(['id' => 'Pjax_SearchResults', 'timeout' => false, 'enablePushState' => false, 'clientOptions' => ['method' => 'GET']]);

				if(isset($current_bookings) && !empty($current_bookings)) {
					
					
					echo "i ddam begine";
					exit;
					//$lastKey = end(array_keys($current_bookings));
					$lastKey = 0;
//echo '<pre>';print_r($current_bookings); die;
					foreach($current_bookings as $c_key => $crnt_booking) {
	
						if($userId == $crnt_booking['pet_sitter_id'] && $crnt_booking['pet_sitter_id'] > 0 && $crnt_booking['pet_renter_id'] == 0){
						
	
							if($crnt_booking['booking_status']=='1' && $crnt_booking['payment_status']=='1'){ 
								$totalAmountBooking = $crnt_booking['booking_credits']; 
								}else{ 
									$totalAmountBooking = $crnt_booking['amount']; 
									}
			
							$nameHref = '<a href="'.Url::home().'users/testview/'.$crnt_booking['pet_owner_id'].'" target="_blank" title="view user profile">'.$crnt_booking['o_fname'].' '.$crnt_booking['o_lname'].'</a>';
							
						  $user_zipcode 	= (isset($crnt_booking['o_zip_code']) ? $crnt_booking['o_zip_code'] : '');
						  $user_countryname = (isset($crnt_booking['o_country']) ? $crnt_booking['o_country'] : '');
						  $user_cityname	= (isset($crnt_booking['o_city']) ? $crnt_booking['o_city'] : '');
						  $profile_image 	= (isset($crnt_booking['o_pimage']) ? $crnt_booking['o_pimage'] : '');
						} else if($userId == $crnt_booking['pet_owner_id'] && $crnt_booking['pet_renter_id'] > 0) {
	
if($crnt_booking['booking_status']=='1' && $crnt_booking['payment_status']=='1'){ $totalAmountBooking = $crnt_booking['booking_credits']; }
else {$totalAmountBooking = $crnt_booking['amount']; }
							$nameHref = '<a href="'.Url::home().'users/testview/'.$crnt_booking['pet_renter_id'].'" target="_blank" title="view user profile">'.$crnt_booking['r_fname'].' '.$crnt_booking['r_lname'].'</a>';
						  $user_zipcode 	= (isset($crnt_booking['r_zip_code']) ? $crnt_booking['r_zip_code'] : '');
						  $user_countryname = (isset($crnt_booking['r_country']) ? $crnt_booking['r_country'] : '');
						  $user_cityname	= (isset($crnt_booking['r_city']) ? $crnt_booking['r_city'] : '');
						  $profile_image 	= (isset($crnt_booking['r_pimage']) ? $crnt_booking['r_pimage'] : '');														
						}elseif($userId == $crnt_booking['pet_renter_id'] && $crnt_booking['pet_sitter_id']==0){
						$totalAmountBooking = $crnt_booking['amount'];
							$nameHref = '<a href="'.Url::home().'users/testview/'.$crnt_booking['pet_owner_id'].'" target="_blank" title="view user profile">'.$crnt_booking['o_fname'].' '.$crnt_booking['o_lname'].'</a>';
						  $user_zipcode 	= (isset($crnt_booking['o_zip_code']) ? $crnt_booking['o_zip_code'] : '');
						  $user_countryname = (isset($crnt_booking['o_country']) ? $crnt_booking['o_country'] : '');
						  $user_cityname	= (isset($crnt_booking['o_city']) ? $crnt_booking['o_city'] : '');
						  $profile_image 	= (isset($crnt_booking['o_pimage']) ? $crnt_booking['o_pimage'] : '');
						
						} else {
							
							$totalAmountBooking = $crnt_booking['amount'];
							$nameHref = '<a href="'.Url::home().'users/testview/'.$crnt_booking['pet_sitter_id'].'" target="_blank" title="view user profile">'.$crnt_booking['s_fname'].' '.$crnt_booking['s_lname'].'</a>';
						  $user_zipcode 	= (isset($crnt_booking['s_zip_code']) ? $crnt_booking['s_zip_code'] : '');
						  $user_countryname = (isset($crnt_booking['s_country']) ? $crnt_booking['s_country'] : '');
						  $user_cityname	= (isset($crnt_booking['s_city']) ? $crnt_booking['s_city'] : '');
						  $profile_image 	= (isset($crnt_booking['s_pimage']) ? $crnt_booking['s_pimage'] : '');														
						
						}
						$user_location	= $user_cityname.', '.$user_countryname.', '.$user_zipcode;
						if(!empty($profile_image)) { 
							$profile_pic = PROFILE_IMAGE_PATH . $profile_image;
						} else {
							$profile_pic = NO_DISPLAY_IMAGE;
						}					
				?>
				  <div class="greyBox pastBookingDetails <?php //if($lastKey == $c_key) { echo 'noMargin'; } ?> ">
					<div class="borderbottom">
					  <div class="leftColumn">
						<div class="greyThumb"> <img class="img-responsive" src="<?php echo $profile_pic; ?>" alt="" width="80" height="80"> </div>
						<div class="greyText">
						  <div class="head"><?= $nameHref ?></div>
						  <span><i class="fa fa-map-marker" aria-hidden="true"></i> <?= $user_location ?></span>
						  <br/><br/><span><b>Booking Name</b>: <?php echo $crnt_booking['name']; ?></span>
<br/><br/><span><b>Status</b>: <?php if($crnt_booking['booking_status']==0 && $crnt_booking['payment_status']==0){
							
							echo "Not Confirmed";
							}elseif($crnt_booking['booking_status']==1 && $crnt_booking['payment_status']==0){
							echo "Payment Pending";
							}elseif($crnt_booking['booking_status']==1 && $crnt_booking['payment_status']==1 && $crnt_booking['braintree_payment_status']==1){
							echo "Payment Done";
							}elseif($crnt_booking['booking_status']==1 && $crnt_booking['payment_status']==1 && $crnt_booking['braintree_payment_status']==0){
							echo "Payment due on ".date('m/d/y', strtotime($crnt_booking['booking_from_date']));
							}
							
							
							?></span>
						</div>
					  </div>
					  <div class="rightColumn bookingstatus"> 
					  					
						<div class="DateBlk">
						  <div class="form-group">
							<label>From</label>
							<div class="datetext"><?php echo date('m-d-Y', strtotime($crnt_booking['booking_from_date'])); ?></div>
						  </div>
						</div>
						<div class="DateBlk">
						  <div class="form-group">
							<label>To</label>
							<div class="datetext"><?php echo date('m-d-Y', strtotime($crnt_booking['booking_to_date'])); ?></div>
						  </div>
						</div>
						<div class="greenBlkMain">
						  <div class="greenBlk"> <?php echo CURRENCY_SIGN.$totalAmountBooking; ?> </div>
						</div>
					  </div>
					</div>
					<div class="tabBlock btSpace">
					  <ul>
						<?php
						
							if($crnt_booking['pet_renter_id'] == 0 && $crnt_booking['pet_sitter_id'] > 0 && $crnt_booking['booking_services'] != '') {
							$user_services = explode(",",$crnt_booking['booking_services']);
							$userservices = Yii::$app->commonmethod->getServicesName($user_services);
							if(isset($userservices) && !empty($userservices)) {
							  foreach($userservices as $s_row) {
								  echo '<li><a>'.$s_row.'</a></li>';
								}
							}
						}
						?>
					  </ul>
					  
					  <div class="orangeBtn"> <a href="<?php echo Url::home().'bookings/booking-details/'.$crnt_booking['id'] ?>">View details <i class="fa fa-angle-right" aria-hidden="true"></i> </a> </div>
					  <?php $today = date('Y-m-d');
					  
					  if($crnt_booking['booking_from_date']<$today){
					  echo "";
					  }elseif($crnt_booking['booking_from_date']>=$today && $crnt_booking['payment_status']==1){
					  ?>
					  <div class="orangeBtn"> <a href="javascript:function() { return false; }" onclick="cancelbooking(<?php echo $crnt_booking['id'];?>,15);return false;">Cancel Booking <i class="fa fa-angle-right" aria-hidden="true"></i> </a> </div>
					  <?php }else{
					  echo "";
					  }
					 
					  ?>
					  										  
					  <button type="button" class="orangeBtn" data-toggle="modal" data-target="#myModal<?php echo $crnt_booking['id'];?>">Talk to Admin</button>
					  <?php if($crnt_booking['booking_from_date']>=$today && $crnt_booking['booking_status']==0 && $userId == $crnt_booking['pet_sitter_id'] && $crnt_booking['pet_sitter_id'] > 0 && $crnt_booking['pet_renter_id'] == 0){?>
					  <div class="orangeBtn"><a href="<?php echo Url::to(['bookings/confirm',"id"=>$crnt_booking['id']]);?>">Accept/Decline</a></div>
					  <?php }
						

					  ?>
					  
					  <?php if($crnt_booking['booking_from_date']>=$today && $crnt_booking['booking_status']==0 && $userId == $crnt_booking['pet_owner_id'] && $crnt_booking['pet_owner_id'] > 0 && $crnt_booking['pet_sitter_id'] == 0){?>
					  <div class="orangeBtn"><a href="<?php echo Url::to(['bookings/confirm',"id"=>$crnt_booking['id']]);?>">Accept/Decline</a></div>
					  <?php } 
					  
					  ?>
					  
					  <?php if($crnt_booking['booking_from_date']>=$today && $userId == $crnt_booking['pet_owner_id'] && $crnt_booking['pet_sitter_id'] > 0 && $crnt_booking['pet_renter_id'] == 0){?>
					  					  
					  <?php if($crnt_booking['booking_status']==0){
						  ?>
					  <div class="orangeBtn">
					  <a onclick="return false;">Make payment</a>
					  </div>
					  <?php }elseif($crnt_booking['booking_status']==1 && $crnt_booking['payment_status']==0){
						  
						  ?>
					  <div class="orangeBtn">
					  <a href="<?php echo Url::to(['payments/pay-now',id=>$crnt_booking['id']]);?>">Make payment</a>
					  </div>
					  	<?php }
						?>
					 
					  <?php } ?>
					  
					  <?php 
					  	
					  if($crnt_booking['booking_from_date'] >= $today && $userId == $crnt_booking['pet_renter_id'] && $crnt_booking['pet_renter_id'] > 0 && $crnt_booking['pet_sitter_id'] == 0){
						    ?>
					  
					  
					  <?php

					  if($crnt_booking['booking_status']==0){?>
					  <div class="orangeBtn">
					  <a onclick="return false;">Make payment</a>
					  </div>
					  <?php }elseif($crnt_booking['booking_status']==1 && $crnt_booking['payment_status']==0){?>
					  <div class="orangeBtn">
					  <a href="<?php echo Url::to(['payments/pay-now',id=>$crnt_booking['id']]);?>">Make payment</a>
					  </div>
					  	<?php }?>
					 
					  <?php }


					  ?>
					  
					</div>
				  </div>
		<!--Boootstrap modal start-->		  
		<div class="container">
		<!-- Modal -->
		  <div class="modal fade" id="myModal<?php echo $crnt_booking['id'];?>" role="dialog">
			<div class="modal-dialog">
			
			<!-- Modal content-->
			  <div class="modal-content">
				  <div class="modal-header" style="padding:35px 50px;">
				  <!--button type="button" class="close" data-dismiss="modal">&times;</button-->
				  <h4><span class="glyphicon glyphicon-user"></span> Connect with Palcura Admin</h4>
			   	 </div>
			   	 
			   	 <div class="modal-body" style="padding:40px 50px;">
			  <?php
						$form = ActiveForm::begin([
						'id' => 'connectadmin-form',
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
					 <?php echo Html::hiddenInput('booking_id', $crnt_booking['id']); ?>
					  <?php echo $form->field($ConnectadminModel, 'title')->textInput()->label('Title<span>*</span>') ?>
					  <div class="row-block">
					  <?php
						
						$formtype = Yii::$app->commonmethod->getFormType();
						echo $form->field($ConnectadminModel, 'form_type', ['inputOptions' => [
						'class' => "form-control",
						]])->dropDownList($formtype,['prompt'=>'Select Category'])->label('Type<span>*</span>');
						?>
					</div>	
					 <?php
						echo $form->field($ConnectadminModel, 'description', ['inputOptions' => [
						'class' => "form-control textfeild",'id' => "search_destination1"
						]])->textarea(['rows' => '4', 'maxlength' => 250, 'autofocus' => false])->label('Description <span>*</span>');
					 ?>
			   	 </div>
			   	 
			   	 <div class="modal-footer">
			   	 <?php echo Html::submitButton('Submit', ['class' => 'btn btn-primary']); ?>
				  <button type="submit" class="btn btn-danger btn-default pull-right" data-dismiss="modal" id="cancelModal"><span class="glyphicon glyphicon-remove"></span> Cancel</button>
				  <?php ActiveForm::end();


				  ?>
				  <!--p>Not a member? <a href="#">Sign Up</a></p>
				  <p>Forgot <a href="#">Password?</a></p-->
				</div>
				
			  </div>	
			
			
			</div>
		 </div>	
		
		</div>
		 <!--Modal end---->		  
				  				  
			<?php
				
					
					}
					echo "hhihihihi";
					exit;
				}else{
					echo '<p>'.NO_RESULT.'</p>';
				}
				
			?>
				<div class="customPagination">
						<?php
							echo yii\widgets\LinkPager::widget([
								'pagination' => $pagesA,
								'prevPageLabel' => '<i class="fa fa-caret-left" aria-hidden="true"></i>',
								'nextPageLabel' => '<i class="fa fa-caret-right" aria-hidden="true"></i>',
								'activePageCssClass' => 'active',
								'disabledPageCssClass' => 'disabled',
								'prevPageCssClass' => 'enable prev',
								'nextPageCssClass' => 'enable next',
								'hideOnSinglePage' => true
							]);
						?>
				</div>
				<?php Pjax::end(); ?>
            </div>
            
            <div id="menu2" class="tab-pane">
				<?php
				Pjax::begin(['id' => 'Pjax_SearchResults1', 'timeout' => false, 'enablePushState' => false, 'clientOptions' => ['method' => 'GET']]);

				if(isset($completed_bookings) && !empty($completed_bookings)) { 
					//$lastKey = end(array_keys($completed_bookings));
					$lastKey = 0;
					foreach($completed_bookings as $c_key => $cmpl_booking) {   
					
						if($userId == $cmpl_booking['pet_sitter_id'] && $cmpl_booking['pet_sitter_id'] > 0 && $cmpl_booking['pet_renter_id'] == 0) {
							$totalAmountBooking = $cmpl_booking['booking_credits'];
							$nameHref = '<a href="'.Url::home().'users/testview/'.$cmpl_booking['pet_owner_id'].'" target="_blank" title="view user profile">'.$cmpl_booking['o_fname'].' '.$cmpl_booking['o_lname'].'</a>';
						  $user_zipcode 	= (isset($cmpl_booking['o_zip_code']) ? $cmpl_booking['o_zip_code'] : '');
						  $user_countryname = (isset($cmpl_booking['o_country']) ? $cmpl_booking['o_country'] : '');
						  $user_cityname	= (isset($cmpl_booking['o_city']) ? $cmpl_booking['o_city'] : '');
						  $profile_image 	= (isset($cmpl_booking['o_pimage']) ? $cmpl_booking['o_pimage'] : '');															
						} else if($userId == $cmpl_booking['pet_owner_id'] && $cmpl_booking['pet_renter_id'] > 0) {
							$totalAmountBooking = $cmpl_booking['booking_credits'];
							$nameHref = '<a href="'.Url::home().'users/testview/'.$cmpl_booking['pet_renter_id'].'" target="_blank" title="view user profile">'.$cmpl_booking['r_fname'].' '.$cmpl_booking['r_lname'].'</a>';
						  $user_zipcode 	= (isset($cmpl_booking['r_zip_code']) ? $cmpl_booking['r_zip_code'] : '');
						  $user_countryname = (isset($cmpl_booking['r_country']) ? $cmpl_booking['r_country'] : '');
						  $user_cityname	= (isset($cmpl_booking['r_city']) ? $cmpl_booking['r_city'] : '');
						  $profile_image 	= (isset($cmpl_booking['r_pimage']) ? $cmpl_booking['r_pimage'] : '');															
						} else {
							$totalAmountBooking = $cmpl_booking['amount'];

							$nameHref = '<a href="'.Url::home().'users/testview/'.$cmpl_booking['pet_sitter_id'].'" target="_blank" title="view user profile">'.$cmpl_booking['s_fname'].' '.$cmpl_booking['s_lname'].'</a>';
						  $user_zipcode 	= (isset($cmpl_booking['s_zip_code']) ? $cmpl_booking['s_zip_code'] : '');
						  $user_countryname = (isset($cmpl_booking['s_country']) ? $cmpl_booking['s_country'] : '');
						  $user_cityname	= (isset($cmpl_booking['s_city']) ? $cmpl_booking['s_city'] : '');							
						  $profile_image 	= (isset($cmpl_booking['s_pimage']) ? $cmpl_booking['s_pimage'] : '');
						}
						$user_location	= $user_cityname.', '.$user_countryname.', '.$user_zipcode;
						if(!empty($profile_image)) { 
							$profile_pic = PROFILE_IMAGE_PATH . $profile_image;
						} else {
							$profile_pic = NO_DISPLAY_IMAGE;
						}													
				?>
				  <div class="greyBox pastBookingDetails <?php //if($lastKey == $c_key) { echo 'noMargin'; } ?> ">
					<div class="borderbottom">
					  <div class="leftColumn">
						<div class="greyThumb"> <img class="img-responsive" src="<?php echo $profile_pic; ?>" alt="" width="80" height="80"> </div>
						<div class="greyText">
						  <div class="head"><?= $nameHref ?></div>
						  <span><i class="fa fa-map-marker" aria-hidden="true"></i> <?= $user_location ?></span> </div>
					  </div>
					  <div class="rightColumn">
						<div class="DateBlk">
						  <div class="form-group">
							<label>From</label>
							<div class="datetext"><?= date('m-d-Y', strtotime($cmpl_booking['booking_from_date'])) ?></div>
						  </div>
						</div>
						<div class="DateBlk">
						  <div class="form-group">
							<label>To</label>
							<div class="datetext"><?= date('m-d-Y', strtotime($cmpl_booking['booking_to_date'])) ?></div>
						  </div>
						</div>
						<div class="greenBlkMain">
						  <div class="greenBlk"> <?= CURRENCY_SIGN.$totalAmountBooking ?> </div>
						</div>
					  </div>
					</div>
					<div class="tabBlock">
					  <ul>
						<?php
							if($cmpl_booking['pet_renter_id'] == 0 && $cmpl_booking['pet_sitter_id'] > 0 && $cmpl_booking['booking_services'] != '') {
							$user_services = explode(",",$cmpl_booking['booking_services']);
							$userservices = Yii::$app->commonmethod->getServicesName($user_services);
							if(isset($userservices) && !empty($userservices)) {
							  foreach($userservices as $s_row) {
								  echo '<li><a>'.$s_row.'</a></li>';
								}
							}
						}
						?>						
					  </ul>
					
					  <div class="orangeBtn"> <a href="<?=  Url::home().'bookings/booking-details/'.$cmpl_booking['id'] ?>">View details <i class="fa fa-angle-right" aria-hidden="true"></i> </a> </div>
					    <!--?php echo $cmpl_booking['id'];  echo '<br>'; echo $userId; echo "<br>"; echo  $cmpl_booking['pet_owner_id']; echo "<br>"; echo  $cmpl_booking['pet_renter_id']; echo "<br>"; echo $cmpl_booking['pet_sitter_id'];?-->
					  <?php if($userId == $cmpl_booking['pet_owner_id'] || $userId == $cmpl_booking['pet_renter_id']) { ?>
					  <button type="button" class="orangeBtn" data-toggle="modal" data-target="#reviewModal<?php echo $cmpl_booking['id'] ;?>">Add review</button>
					  <?php } ?>
					</div>
				  </div>
				  				  				  
	<!--Boootstrap modal start-->		  
		<div class="container">
		<!-- Modal -->
		  <div class="modal fade" id="reviewModal<?php echo $cmpl_booking['id'] ; ?>" role="dialog">
			<div class="modal-dialog">
			
			<!-- Modal content-->
			  <div class="modal-content">
				  <div class="modal-header" style="padding:35px 50px;">
				  <!--button type="button" class="close" data-dismiss="modal">&times;</button-->
				  <h4><span class="glyphicon glyphicon-user"></span> Add your review</h4>
			   	 </div>
			   	
			   	 <div class="modal-body" style="padding:40px 50px;">
			  <?php
						$form = ActiveForm::begin([
						'id' => 'addreview-form'.$cmpl_booking['id'],
						//'action' => ['reviews/addreview'],
						//'data-pjax'=>true,
						'options' => [
								
						//'enctype' => 'multipart/form-data',
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
					 <?php echo Html::hiddenInput('booking_id', $cmpl_booking['id']); ?>
					 <?php echo Html::hiddenInput('pet_sitter_id', $cmpl_booking['pet_sitter_id']); ?>
					 <?php echo Html::hiddenInput('pet_owner_id', $cmpl_booking['pet_owner_id']); ?>
					 <?php echo Html::hiddenInput('pet_renter_id',$cmpl_booking['pet_renter_id']); ?>					
					  <div class="row-block">
					 <?php
				    echo $form->field($model, 'starrating', ['inputOptions' => [
				    'class' => "form-control customwidth myreviw".$cmpl_booking['id']."",
				    'data-min'=>"0",
				    'data-max'=>"5",
				    'data-step'=>"1",
				    'data-size'=>"xs",
				    'data-show-clear'=>'false',
				    'data-glyphicon'=>'false',
				    'data-rating-class'=>'rating-fa',
				    'id'=>"input-7-lg".$cmpl_booking['id'].""
				    				    
				    ]])->textInput()->label('How many Stars <span class="required">*</span>');
				 ?>
					</div>	
					 <?php
						echo $form->field($model, 'comment', ['inputOptions' => [
						'class' => "form-control textfeild",'id' => "search_destination".$cmpl_booking['id']
						]])->textarea(['rows' => '4', 'maxlength' => 250, 'autofocus' => false])->label('Comment <span>*</span>');
					 ?>
			   	 </div>
			   	 
			   	 <div class="modal-footer">
			   	 			
			   	 <!--?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'id' => 'submitReview'.$cmpl_booking['id'].'']) ?-->
			   	  <button type="button" class="btn btn-primary" id="submitReview<?php echo $cmpl_booking['id'] ?>" onclick = "addreview(<?php echo $cmpl_booking['id'] ?>,<?php echo $cmpl_booking['pet_sitter_id']; ?>,<?php echo $cmpl_booking['pet_owner_id']; ?>,<?php echo $cmpl_booking['pet_renter_id']; ?>)"><span class="glyphicon glyphicon-add"></span> Submit</button>
				  <button type="submit" class="btn btn-danger btn-default pull-right" data-dismiss="modal" id="cancelModal"><span class="glyphicon glyphicon-remove"></span> Cancel</button>
				  <?php ActiveForm::end() ?>
				  <!--p>Not a member? <a href="#">Sign Up</a></p>
				  <p>Forgot <a href="#">Password?</a></p-->
				</div>
				
			  </div>	
			
			
			</div>
		 </div>	
		
		</div>
		<script>
		$(document).on('ready pjax:success', function() {
		var bookid = "<?php echo $cmpl_booking['id'] ?>";
			$('#input-7-lg'+bookid).rating({
		
			});
		});
</script>

		 <!--Modal end---->		   
				  
			<?php 	} 
				} else {
					echo '<p>'.NO_RESULT.'</p>';
				}
			?>
				<div class="customPagination">
						<?php
							echo yii\widgets\LinkPager::widget([
								'pagination' => $pagesB,
								'prevPageLabel' => '<i class="fa fa-caret-left" aria-hidden="true"></i>',
								'nextPageLabel' => '<i class="fa fa-caret-right" aria-hidden="true"></i>',
								'activePageCssClass' => 'active',
								'disabledPageCssClass' => 'disabled',
								'prevPageCssClass' => 'enable prev',
								'nextPageCssClass' => 'enable next',
								'hideOnSinglePage' => true
							]);
						?>
				</div>
			<?php Pjax::end(); ?>
            </div>

            <div id="menu3" class="tab-pane">
				<?php
				Pjax::begin(['id' => 'Pjax_SearchResults2', 'timeout' => false, 'enablePushState' => false, 'clientOptions' => ['method' => 'GET']]);

				if(isset($declined_bookings) && !empty($declined_bookings)) {
					//$lastKey = end(array_keys($declined_bookings));
					$lastKey =0;
					foreach($declined_bookings as $c_key => $d_booking) {
						if($userId == $d_booking['pet_sitter_id'] && $d_booking['pet_sitter_id'] > 0 && $d_booking['pet_renter_id'] == 0) {
							$totalAmountBooking = $d_booking['booking_credits'];
							$nameHref = '<a href="'.Url::home().'users/testview/'.$d_booking['pet_owner_id'].'" target="_blank" title="view user profile">'.$d_booking['o_fname'].' '.$d_booking['o_lname'].'</a>';
						  $user_zipcode 	= (isset($d_booking['o_zip_code']) ? $d_booking['o_zip_code'] : '');
						  $user_countryname = (isset($d_booking['o_country']) ? $d_booking['o_country'] : '');
						  $user_cityname	= (isset($d_booking['o_city']) ? $d_booking['o_city'] : '');
						  $profile_image 	= (isset($d_booking['o_pimage']) ? $d_booking['o_pimage'] : '');															
						} else if($userId == $d_booking['pet_owner_id'] && $d_booking['pet_renter_id'] > 0) {
							$totalAmountBooking = $d_booking['booking_credits'];
							$nameHref = '<a href="'.Url::home().'users/testview/'.$d_booking['pet_renter_id'].'" target="_blank" title="view user profile">'.$d_booking['r_fname'].' '.$d_booking['r_lname'].'</a>';
						  $user_zipcode 	= (isset($d_booking['r_zip_code']) ? $d_booking['r_zip_code'] : '');
						  $user_countryname = (isset($d_booking['r_country']) ? $d_booking['r_country'] : '');
						  $user_cityname	= (isset($d_booking['r_city']) ? $d_booking['r_city'] : '');
						  $profile_image 	= (isset($d_booking['r_pimage']) ? $d_booking['r_pimage'] : '');															
						}elseif($userId == $d_booking['pet_renter_id'] && $d_booking['pet_sitter_id']==0){
						$totalAmountBooking = $d_booking['amount'];
							$nameHref = '<a href="'.Url::home().'users/testview/'.$d_booking['pet_owner_id'].'" target="_blank" title="view user profile">'.$d_booking['o_fname'].' '.$crnt_booking['o_lname'].'</a>';
						  $user_zipcode 	= (isset($d_booking['o_zip_code']) ? $d_booking['o_zip_code'] : '');
						  $user_countryname = (isset($d_booking['o_country']) ? $d_booking['o_country'] : '');
						  $user_cityname	= (isset($d_booking['o_city']) ? $d_booking['o_city'] : '');
						  $profile_image 	= (isset($d_booking['o_pimage']) ? $d_booking['o_pimage'] : '');
						
						} else {
							$totalAmountBooking = $d_booking['amount'];
							
							$nameHref = '<a href="'.Url::home().'users/testview/'.$d_booking['pet_sitter_id'].'" target="_blank" title="view user profile">'.$d_booking['s_fname'].' '.$d_booking['s_lname'].'</a>';
						  $user_zipcode 	= (isset($d_booking['s_zip_code']) ? $d_booking['s_zip_code'] : '');
						  $user_countryname = (isset($d_booking['s_country']) ? $d_booking['s_country'] : '');
						  $user_cityname	= (isset($d_booking['s_city']) ? $d_booking['s_city'] : '');
						  $profile_image 	= (isset($d_booking['s_pimage']) ? $d_booking['s_pimage'] : '');														
						}
						$user_location	= $user_cityname.', '.$user_countryname.', '.$user_zipcode;
						if(!empty($profile_image)) { 
							$profile_pic = PROFILE_IMAGE_PATH . $profile_image;
						} else {
							$profile_pic = NO_DISPLAY_IMAGE;
						}
				?>
				  <div class="greyBox pastBookingDetails <?php //if($lastKey == $c_key) { echo 'noMargin'; } ?> ">
					<div class="borderbottom">
					  <div class="leftColumn">
						<div class="greyThumb"> <img class="img-responsive" src="<?php echo $profile_pic; ?>" alt="" width="80" height="80"> </div>
						<div class="greyText">
						  <div class="head"><?= $nameHref ?></div>
						  <span><i class="fa fa-map-marker" aria-hidden="true"></i> <?= $user_location ?></span> </div>
					  </div>
					  <div class="rightColumn">
						<div class="DateBlk">
						  <div class="form-group">
							<label>From</label>
							<div class="datetext"><?= date('m-d-Y', strtotime($d_booking['booking_from_date'])) ?></div>
						  </div>
						</div>
						<div class="DateBlk">
						  <div class="form-group">
							<label>To</label>
							<div class="datetext"><?= date('m-d-Y', strtotime($d_booking['booking_to_date'])) ?></div>
						  </div>
						</div>
						<div class="greenBlkMain">
						  <div class="greenBlk"> <?= CURRENCY_SIGN.$totalAmountBooking ?> </div>
						</div>
					  </div>
					</div>
					<div class="tabBlock">
					  <ul>
						<?php
							if($d_booking['pet_renter_id'] == 0 && $d_booking['pet_sitter_id'] > 0 && $d_booking['booking_services'] != '') {
							$user_services = explode(",",$d_booking['booking_services']);
							$userservices = Yii::$app->commonmethod->getServicesName($user_services);
							if(isset($userservices) && !empty($userservices)) {
							  foreach($userservices as $s_row) {
								  echo '<li><a>'.$s_row.'</a></li>';
								}
							}
						}
						?>	
					  </ul>
						<div class="orangeBtn">
							<a href="<?=  Url::home().'bookings/booking-details/'.$d_booking['id'] ?>">View details
								<i class="fa fa-angle-right" aria-hidden="true"></i>
							</a>
						</div>
					</div>
				  </div>
			<?php 	}
				} else {
					echo '<p>'.NO_RESULT.'</p>';
				}
			?>
				<div class="customPagination">
					<?php
						echo yii\widgets\LinkPager::widget([
							'pagination' => $pagesC,
							'prevPageLabel' => '<i class="fa fa-caret-left" aria-hidden="true"></i>',
							'nextPageLabel' => '<i class="fa fa-caret-right" aria-hidden="true"></i>',
							'activePageCssClass' => 'active',
							'disabledPageCssClass' => 'disabled',
							'prevPageCssClass' => 'enable prev',
							'nextPageCssClass' => 'enable next',
							'hideOnSinglePage' => true
						]);
					?>
				</div>
			<?php Pjax::end(); ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<div class="modal fade" id="doctorModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="padding:35px 50px;">
          <!--button type="button" class="close" data-dismiss="modal">&times;</button-->
          <h4><span class="glyphicon glyphicon-check"></span> Are you sure you want to cancel this booking ?</h4>
        </div>
        
        <div class="modal-body" style="padding:40px 50px; display:none;" id="showloader">
         <div  id="loader"  align="center" style="display:none;">        
		    <img class="img-responsive" src="<?php echo SITE_URL; ?>common/uploads/loader/giphy.gif" alt="" width="70px" height="70px">
		    </div>
         
        </div>
        <div class="modal-footer" id="loaderfooter">
        <button  class="btn btn-success btn-default" id="acceptconfirmation"><span class="glyphicon glyphicon-check"></span> Yes</button>
          <button type="submit" class="btn btn-danger btn-default pull-right" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> No</button>
         
          <!--p>Not a member? <a href="#">Sign Up</a></p>
          <p>Forgot <a href="#">Password?</a></p-->
        </div>
      </div>
      
    </div>
  </div>
<script>
function cancelbooking(bookingid,amount){

$("#doctorModal").modal({"backdrop": "static"});
$('#acceptconfirmation').click(function(){
$('#showloader').show();
$('#loader').show();
$('#loaderfooter').hide();
$.ajax({ 
		url:'<?php echo Url::to(['bookings/cancelbooking']);?>',
		type:'post',
		data:{'bookingid':bookingid,'amount':amount},
		success:function(response){
		console.log('success');
		window.location.href = '<?php echo SITE_URL ?>'+'bookings/index'; 
			//if(response)	
				//$('#search-result').html(response);	
		}	
	});
});	

}


</script>

<script>
function addreview(thisid,sitid,ownid,rentid){

	var rev = $('#input-7-lg'+thisid).val();
	var descript = $('#search_destination'+thisid).val();
	$.ajax({ 
			url:'<?php echo Url::to(['reviews/addreview']);?>',
			type:'post',
			data:{'thisid':thisid,'ownid':ownid,'sitid':sitid,'rentid':rentid,'rev':rev,'descript':descript},
			success:function(){
			console.log('success');
			//$('#reviewSubmit'+thisid).remove();
			window.location.href = '<?php echo SITE_URL ?>'+'bookings/index'; 
			
			}	
		});

}
</script>

