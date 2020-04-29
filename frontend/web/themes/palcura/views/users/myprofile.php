<?php
use yii\helpers\Html;
use yii\helpers\Url;  
use yii\helpers\ArrayHelper;

$this->title = 'Your Profile';
$this->params['breadcrumbs'][] = $this->title;
$siteimage = Yii::getAlias('@siteimage');
$attributes = Yii::$app->user->identity->getattributes();
$profile_picture = $attributes['profile_image'];
$profile_pic = isset($profile_picture) ? Url::home() . 'common/uploads/profile/' . $profile_picture : Yii::getAlias('@webThemeUrl') . '/images/noimage.png';
$Identity_documents	=	Yii::$app->commonmethod->getUserActiveDocuments($attributes['id'],ID_DOCUMENTS);
//$user_images		=	Yii::$app->commonmethod->getUserActiveDocuments($attributes['id'],USER_IMAGES);
$user_images		=	Yii::$app->commonmethod->getUserActiveDocImages($attributes['id']);

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

if(!empty($logUserType)){
$messageText = 'You are currently logged in as <strong>'.$logUserType.'</strong>.';
}else{
$messageText = "";
}

$current_credits	=	(isset($attributes['user_credits']) ? $attributes['user_credits'] : 0) + (isset($attributes['owner_credits']) ? $attributes['owner_credits'] : 0) + (isset($attributes['sitter_credits']) ? $attributes['sitter_credits'] : 0);

$current_rewards	=	(isset($attributes['reward_points']) ? $attributes['reward_points'] : 0);
?>
		
			<div class="col-xs-12">
				<h1><?= $this->title ?></h1>
			</div>
		
	</div>
</header>
<section class="contentArea">
	<div class="container">
		<div class="row">
<div class="col-lg-12"><p style="padding: 20px 0;color: #FF8447;"><?php echo $messageText ;?></p>
		</div>
		
		<?php
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
	    <?php  } ?>		 
		
		
		
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
		  <?php echo $this->render('//common/sidebar'); ?>
			<div class="col-md-2 col-sm-3 col-xs-12 pull-right scrolldiv01">
				<div class="sidebarUser editUser">
					<a class="orangeBtn" href="<?= Url::home().'users/settings' ?>"><i class="fa fa-pencil" aria-hidden="true"></i>EDIT PROFILE</a>
					<div class="sidebarBlk sideuserBlk">
						<?php //if($logged_user == SITTER) { ?>
						<div class="sidebarPrice">Current Credits</div>
						<div class="sidebarPriceValue"><?= CURRENCY_SIGN.$current_credits ?></div>
						<?php //} else { ?>
						
						<?php //} ?>
					</div>
					<div class="sidebarBlk sideuserBlk">
					 	<div class="sidebarPrice">Current Points</div>
						<div class="sidebarPriceValue"><?= $current_rewards ?></div>
					</div>	
					<?php
						if(isset($attributes['user_type']) && !empty($attributes['user_type'])) {
							switch ($attributes['user_type']) {
								case OWNER:
									echo '<a class="orangeBtn switchacc" href="'.Url::home().'users/becomeaborrower">Become a Borrower</a>';
									echo '<a class="orangeBtn switchacc" href="'.Url::home().'users/becomeasitter">Become a Sitter</a>';
									break;
								case SITTER:
									//if(isset($attributes['verified_by_admin']) && $attributes['verified_by_admin'] == ACTIVE) {
										echo '<a class="orangeBtn switchacc" href="'.Url::home().'users/becomeaborrower">Become a Borrower</a>';
										echo '<a class="orangeBtn switchacc" href="'.Url::home().'users/becomeanowner">Become an Owner</a>';
										break;
									//}
								case BORROWER:
									echo '<a class="orangeBtn switchacc" href="'.Url::home().'users/becomeanowner">Become an Owner</a>';
									echo '<a class="orangeBtn switchacc" href="'.Url::home().'users/becomeasitter">Become a Sitter</a>';
									break;
								case OWNER_SITTER:
									if($logged_user == OWNER) {
										echo '<a class="orangeBtn switchacc" href="'.Url::home().'users/switch-account/'.SITTER.'">Access Sitter Profile</a>';
									} else {
										echo '<a class="orangeBtn switchacc" href="'.Url::home().'users/switch-account/'.OWNER.'">Access Owner Profile</a>';
									}
									echo '<a class="orangeBtn switchacc" href="'.Url::home().'users/becomeaborrower">Become a Borrower</a>';
									break;
								case BORROWER_SITTER:
									if($logged_user == SITTER) {
										echo '<a class="orangeBtn switchacc" href="'.Url::home().'users/switch-account/'.BORROWER.'">Access Borrower Profile</a>';
									} else {
										echo '<a class="orangeBtn switchacc" href="'.Url::home().'users/switch-account/'.SITTER.'">Access Sitter Profile</a>';
									}
									echo '<a class="orangeBtn switchacc" href="'.Url::home().'users/becomeanowner">Become an Owner</a>';
									break;
								case OWNER_BORROWER:
									if($logged_user == OWNER) {
										echo '<a class="orangeBtn switchacc" href="'.Url::home().'users/switch-account/'.BORROWER.'">Access Borrower Profile</a>';
									} else {
										echo '<a class="orangeBtn switchacc" href="'.Url::home().'users/switch-account/'.OWNER.'">Access Owner Profile</a>';
									}
									echo '<a class="orangeBtn switchacc" href="'.Url::home().'users/becomeasitter">Become a Sitter</a>';
									break;
								case ALL_PROFILES:
									if($logged_user == SITTER) {
										echo '<a class="orangeBtn switchacc" href="'.Url::home().'users/switch-account/'.BORROWER.'">Access Borrower Profile</a>';
										echo '<a class="orangeBtn switchacc" href="'.Url::home().'users/switch-account/'.OWNER.'">Access Owner Profile</a>';
									} else if($logged_user == OWNER) {
										echo '<a class="orangeBtn switchacc" href="'.Url::home().'users/switch-account/'.BORROWER.'">Access Borrower Profile</a>';
										echo '<a class="orangeBtn switchacc" href="'.Url::home().'users/switch-account/'.SITTER.'">Access Sitter Profile</a>';
									} else {
										echo '<a class="orangeBtn switchacc" href="'.Url::home().'users/switch-account/'.OWNER.'">Access Owner Profile</a>';
										echo '<a class="orangeBtn switchacc" href="'.Url::home().'users/switch-account/'.SITTER.'">Access Sitter Profile</a>';
									}
									break;																		
								default:
									//$this->redirect(['site/home']);
							}
						}
						if($logged_user != SITTER){
						echo '<a class="orangeBtn switchacc" href="'.Url::home().'account/save-card-details">Card details</a>';
						}
						echo '<a class="orangeBtn switchacc" href="javascript:function() { return false; }" onclick="cancelbooking();return false;">Unsubscribe/Subscribe</a>';
					?>
				</div>
			</div>
			<div class="col-md-8 col-sm-6 col-xs-12">
				<div class="user-contentMain">
					<div class="row">
						<div class="col-md-12 col-sm-12 col-xs-12">
							<div class="userProfile">
								<h4 class="user-heading">Personal Information</h4>
								<ul>
									<li>Name <span><?= (isset($attributes['firstname']) ? $attributes['firstname'] : ' ').' '.(isset($attributes['lastname']) ? $attributes['lastname'] : '') ?></span></li>
									<li>Email address <span><?= (isset($attributes['email']) ? $attributes['email'] : '--') ?></span></li>
									
										<li>Address <span><?php if(isset($attributes['address']) && !empty($attributes['address'])){ ?><?= (isset($attributes['address']) ? $attributes['address'] : '') ?>,  <?= (isset($attributes['zip_code']) ? $attributes['zip_code'] : '') ?><?php }elseif((isset($attributes['zip_code']) && !empty($attributes['zip_code'])) && (!isset($attributes['address']))){
									echo $attributes['zip_code'];
									}else{
									echo 'NA';
									} ?></span></li>
								</ul>
							</div>
						</div>
					</div>
					<?php if($logged_user == SITTER) { ?>
					<div class="row">
						<div class="col-md-12 col-sm-12 col-xs-12">
							<div class="userProfile userDocument">
								<h4 class="user-heading">YOUR UPLOADED ID<span>'s</span></h4>
								<?php
									if(isset($Identity_documents) && !empty($Identity_documents)) {
										foreach($Identity_documents as $u_doc) {
											echo '<p><a href="'.DOCUMENT_DOWNLOAD_PATH.$u_doc['name'].'" target="_blank"><i class="fa fa-paperclip" aria-hidden="true"></i>' .(isset($u_doc['name']) ? $u_doc['name'] : ''). '</a></p>';
										}
									}
								?>
							</div>
						</div>
					</div>
					<?php } ?>
					<?php if($logged_user == SITTER) { ?>
				<div class="row">
					<div class="col-md-12 col-sm-12 col-xs-12">
						<div class="userService detailsBottom">
						<h4 class="user-heading">Types of Services provided</h4>
							<ul>
								<?php
								if(isset($user_services) && !empty($user_services)) {
									foreach($user_services as $s_name) {
										echo '<li><p>'. (isset($s_name['service_name']) ? $s_name['service_name'] : '') .'</p><span>'. (isset($s_name['service_price']) ? CURRENCY_SIGN.$s_name['service_price'] : ''). '</span></li>';
									}
								}
								?>
							</ul>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12 col-sm-12 col-xs-12">
						<div class="userAccordian">
							<!--div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
								<div class="panel panel-default">
									<div class="panel-heading" role="tab" id="headingOne">
										<h4 class="panel-title">
											<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
												<i class="more-less glyphicon glyphicon-plus"></i>
												Other information
											</a>
										</h4>
									</div>
									<div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
										<div class="panel-body">
											<div class="userProfile userProfileList">
												<ul>
													<li>No. of pets that I can board at the same time <span><?php echo Yii::$app->user->identity->number_of_pets?Yii::$app->user->identity->number_of_pets:0 ;?></span></li>
													<li>Income <span><?php //echo Yii::$app->user->identity->income?Yii::$app->user->identity->income:'' ;?></span></li>
													<?php $countryname	= Yii::$app->commonmethod->countries(Yii::$app->user->identity->country);?>
													<li>Country <span><?php //echo $countryname->name?$countryname->name:'';?></span></li>
													
													<?php $myresidency	= Yii::$app->commonmethod->residentialStatus(Yii::$app->user->identity->residential_status);?>
													<li>Residential status <span><?php //echo $myresidency?$myresidency:'';?></span></li>
													<li>Household size <span><?php //echo Yii::$app->user->identity->house_size?Yii::$app->user->identity->house_size:''; ?> adults</span></li>
													<li>Children<span><?php //echo Yii::$app->user->identity->children?Yii::$app->user->identity->children:0; ?></span></li>				
												</ul>
											</div>
										</div>
									</div>
								</div>
							</div--><!-- panel-group -->
						</div>
					</div>
				</div>
				<?php } ?>
				<?php if($logged_user == SITTER) { ?>
					<div class="row">
						<div class="col-md-12 col-sm-12 col-xs-12">
							<div class="userProfile userPictures">
							<h4 class="user-heading">House & additional images</h4>
								<div class="row">
									<?php
										if(isset($user_images) && !empty($user_images)) {
											foreach($user_images as $row_uimg) {
												$u_img = (isset($row_uimg['name']) ? UPLOAD_IMAGE.$row_uimg['name'] : '');
									?>
												<div class="col-md-3 col-sm-6 col-xs-3 responsive420">
													<div class="pictures">
														<img class="img-responsive" src="<?php echo $u_img; ?>" alt=""> 
													</div>
												</div>
									<?php
											}
										}
									?>
								</div>
							</div>
						</div>
					</div>
					<?php } ?>
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
          <h4><span class="glyphicon glyphicon-check"></span> Subscribe or Unsubscribe yourself from below profiles.</h4>
        </div>
        
        <div class="modal-body" style="padding:40px 50px;" id="showloader">
         <?php $usertype = Yii::$app->user->identity->user_type; ?>
        <?php if($usertype == 1 || $usertype==4 || $usertype==6 || $usertype==7){?>
		    <div class="row">
		    <p><label>Owner profile :</label></br>(Unsubscribe will deactivate your Owner profile and disallow Borrowers to search for your owner profile.) <select id="ownerprofile" class="form-control"><option value="1" <?php if(Yii::$app->user->identity->unsubscribe_owner==1){ echo 'selected=selected';}else{ echo '';}?>>Subscribe</option><option value="2" <?php if(Yii::$app->user->identity->unsubscribe_owner==2){ echo 'selected=selected';}else{echo '';}?>>Unsubscribe</option></select></p>
		    </div>
		 <?php }?> 
		 <?php if($usertype == 2 || $usertype==4 || $usertype==5 || $usertype==7){?>  
		    <div class="row">
		    <p><label>Sitter profile :</label></br>(Unsubscribe will deactivate your Sitter profile and disallow Owners to search for your sitter profile.) <select id="sitterprofile" class="form-control"><option value="1" <?php if(Yii::$app->user->identity->unsubscribe_sitter==1){ echo 'selected=selected';}else{ echo '';}?>>Subscribe</option><option value="2" <?php if(Yii::$app->user->identity->unsubscribe_sitter==2){ echo 'selected=selected';}else{ echo '';}?>>Unsubscribe</option></select></p>
		   </div>
		    <?php }?> 
		    <?php if($usertype == 3 || $usertype==5 || $usertype==6 || $usertype==7){?>  
		   <div class="row">
		    <p><label>Borrower profile :</label></br>(Unsubscribe will deactivate your Borrower profile and disallow you searching for owner profiles.) <select id="borrowerprofile" class="form-control"><option value="1" <?php if(Yii::$app->user->identity->unsubscribe_renter==1){ echo 'selected=selected';}else{ echo '';}?>>Subscribe</option><option value="2" <?php if(Yii::$app->user->identity->unsubscribe_renter==2){ echo 'selected=selected';}else{ echo '';}?>>Unsubscribe</option></select></p>
		   </div>
		   <?php }?> 
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
function toggleIcon(e) {
    $(e.target)
        .prev('.panel-heading')
        .find(".more-less")
        .toggleClass('glyphicon-plus glyphicon-minus');
}
$('.panel-group').on('hidden.bs.collapse', toggleIcon);
$('.panel-group').on('shown.bs.collapse', toggleIcon);

function cancelbooking(){

$("#doctorModal").modal({"backdrop": "static"});
$('#acceptconfirmation').click(function(){

var owner = $('#ownerprofile :selected').val();
var sitter = $('#sitterprofile :selected').val();
var renter = $('#borrowerprofile :selected').val();

//$('#showloader').show();
//$('#loader').show();
//$('#loaderfooter').hide();
$.ajax({ 
		url:'<?php echo Url::to(['users/updatesubscription']);?>',
		type:'post',
		data:{'owner':owner,'sitter':sitter,'renter':renter},
		success:function(response){
		console.log('profile updated');
		window.location.href = '<?php echo SITE_URL ?>'+'users/myprofile'; 
			//if(response)	
				//$('#search-result').html(response);	
		}	
	});
});	

}
</script>
