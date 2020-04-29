<?php
use yii\helpers\Url;
/* @var $this yii\web\View */
$userLOGIN = Yii::$app->user->getId();
$siteimage = Yii::getAlias('@siteimage');
$session 				= Yii::$app->session;
/* $attributes = Yii::$app->user->identity->getattributes(); */
$logged_user 			= $session->get('loggedinusertype');
if($logged_user == OWNER) { 
	$rnt = 'Borrow';		
} else {
	$rnt = 'Borrow'	;
}
if($logged_user == RENTER) { 
	$mainheading = 'What’s your borrowing need?';		
} else {
	$mainheading = 'Select pet service type';
}

?>

<script>


function searchpettype(){
var serchtyp = "<?php echo $rnt ?>";
	if(serchtyp=='Borrow'){
	$('#maintitleheading').html("What’s your borrowing need?");
	$('#maintitleheadingoth').html("What’s your borrowing need?");
	$('#maintitleheadingcat').html("What’s your borrowing need?");
	$('#serchoffering1').html('Borrowing need');
	$('#serchoffering2').html('Borrowing need');
	$('#serchoffering3').html('Borrowing need');
 	$('#searchcategory1').val('borrow');
 	$('#searchcategory2').val('borrow');
 	$('#searchcategory3').val('borrow');
	}
}
function resetsearchpettype(){
$('#maintitleheading').html("Select pet service type");
$('#maintitleheadingoth').html("Select pet service type");
$('#maintitleheadingcat').html("Select pet service type");
$('#serchoffering').html('Service offering');
$('#serchoffering1').html('Service offering');
$('#serchoffering2').html('Service offering');
$('#serchoffering3').html('Service offering');
$('#searchcategory1').val('lovingpet');
$('#searchcategory2').val('lovingpet');
$('#searchcategory3').val('lovingpet');

}

</script>
		<div class="banner">
			<div class="col-xs-12">
				<div class="home-banner">
					<h3>Connecting Pet Parents with Loving, On-demand Pet Care </h3>
				<h4>Your pal whether a dog, cat or something more exotic, is a big part of your family. We understand the importance of finding<br/> loving pet care and have the dog walkers, pet sitters & pet care providers you need.</h4>
				
						<div class="bannerButton">
							<button class="orangebtn" onclick="resetsearchpettype();return false;">FIND PET CARE NEAR ME</button>
					
						</div>
				
			</div>
		</div>
	</div>
</header>
<section class="">
	<div id="orangeContent">
		<div class="container">
			<div class="innerheading text-center">What are you searching for?</div>
				<div class="bannerButton bannerButton-find">
					<button class="orangebtn" onclick="resetsearchpettype();return false;">Find loving pet care</button>
						<a href="become-a-sitter"><button class="yellowbtn1">PROVIDE PET SITTING & DOG WALKING SERVICES</button></a>
					
				</div>

			
			<div class="row">
				<?php 	
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
				?>
				<!--<div class="col-lg-12 sitter-profile-settings"><p style="padding: 20px 0;color: #FF8447;"></p>   						
				<?php		
				/* if ( ($logUserType == 'Sitter'  && !$attributes['verified_by_admin']) || ($logUserType == 'Borrower'  && !$attributes['verified_by_admin']) || ($logUserType == 'Owner'  && !$attributes['verified_by_admin'])) { ?>
			    <?php
			    	if ($logUserType == 'Sitter'){
						
						?>
						<div class="massage-status">
							<strong>Status:</strong> <span>Sitter <?php echo ($attributes['status']==1)?"active,":"Inactive,";?>Profile <?php echo ($attributes['profile_completed']==1)?"Complete":"Incomplete";?></span>
						</div>
				<?php 
				} elseif ($logUserType == 'Borrower') {
			    	
			    		
			    	?>
					
						<div class="massage-status">
							<strong>Status:</strong> <span>Borrower <?php echo ($attributes['status']==1)?"active,":"Inactive,";?>Profile <?php echo ($attributes['profile_completed_borrower']==1)?"Complete":"Incomplete";?></span>
						</div>
					<?php 
					
					} elseif ($logUserType == 'Owner') {
			    		
			    	?>
						<div class="massage-status">
							<strong>Status:</strong> <span>Owner <?php echo ($attributes['status']==1)?"active,":"Inactive,";?>Profile <?php echo ($attributes['profile_completed_owner']==1)?"Complete":"Incomplete";?></span>
						</div>
					<?php
					}
			    ?>
				<?php  } */ ?> 
				</div>-->

					
				<div class="col-xs-12">
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
							<i class="fa fa-remove"></i> <?php echo Yii::$app->session->getFlash('item'); ?>
							
							<?php if($logged_user != OWNER) {
								  echo $messageText;	
								  echo '<a href="'.Url::home().'users/switch-account1/'.OWNER.'"><strong style="color:white">  <u> Click here </u> </strong></a> to Access Owner Profile';
						} ?>
						</div>
					<?php endif; ?>
					<div id="selectPaltype">
						<div class="block01 selectpal">
							<div class="titleheading-center">What kind of pet do you have?</div>
							<div class="threeCol">
								<div class="col" id="selectpet1">
									<div class="item01 act">
										<div class="colThumb dog"> &nbsp; </div>
										<div class="text01">Dog</div>
									</div>
								</div>
								<div class="col" id="selectpet2">
									<div class="item02 act">
										<div class="colThumb cat"> &nbsp; </div>
										<div class="text01">Cat</div>
									</div>
								</div>
								<div class="col" id="selectpet3">
									<div class="item03 act">
										<div class="colThumb other"> &nbsp; </div>
										<div class="text01">Other</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12 ">
					<div class="one">
						<div class="block02" id="item01">
						
							<div class="titleheading" id="maintitleheading"><?php echo $mainheading; ?></div>
							
							
							<div class="towCol">
								<div class="leftCol">
									<div class="column">
										<div class="circle">
											<img src="<?php echo $siteimage;?>/doghover.png" alt="">
										</div>
										<div class="cicletext">Selected Pal <br>
											<span>Dog</span>
										</div>
									</div>
								<a href="javascript:void(0)" class="changepal">Change your pal</a>
								</div>
								<div class="rightCol">
								<?php
								if($logged_user == RENTER) {
									echo $this->render('searchform-renter',['model'=>$model,'reqData'=> $reqData,'formid' => 1]);
								} else {
									echo $this->render('searchform',['model'=>$model,'reqData'=> $reqData,'formid' => 1 ]);
								}
								?>
								</div>
							</div>
						</div>
					</div>
					<div class="two">
						<div class="block02" id="item02">
							<div class="titleheading" id="maintitleheadingcat"><?php echo $mainheading; ?></div>
							<div class="towCol">
								<div class="leftCol">
									<div class="column">
										<div class="circle">
											<img src="<?php echo $siteimage;?>/cathover.png" alt="">
										</div>
										<div class="cicletext">Selected Pal <br>
											<span>Cat</span>
										</div>
									</div>
									<a href="javascript:void(0)" class="changepal">Change your pal</a>
								</div>
								<div class="rightCol">
									<?php ?>
								<?php
								if($logged_user == RENTER) {
									echo $this->render('searchform-renter',['model'=>$model,'reqData'=> $reqData,'formid' => 2]);
								} else {
									echo $this->render('searchform',['model'=>$model,'reqData'=> $reqData,'formid' => 2]);
								}
								?>									
								</div>
							</div>
						</div>
					</div>
					<div class="three">
						<div class="block02" id="item03">
							<div class="titleheading" id="maintitleheadingoth"><?php echo $mainheading; ?></div>
							<div class="towCol">
								<div class="leftCol">
									<div class="column">
										<div class="circle"><img src="<?php echo $siteimage;?>/otherhover.png" alt=""></div>
										<div class="cicletext">Selected Pal <br>
											<span>Other</span>
										</div>
									</div>
									<a href="javascript:void(0)" class="changepal">Change your pal</a>
								</div>
								<div class="rightCol">
								
								<?php if($logged_user == RENTER) {
									echo $this->render('searchform-renter',['model'=>$model,'reqData'=> $reqData,'formid' => 3]);
								} else {
									echo $this->render('searchform',['model'=>$model,'reqData'=> $reqData,'formid' => 3]);
								}
								?>	
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<section class="contentArea defineFloat howItWorks">
	<div class="container">
		<div class="row">
			<div class="col-xs-12 text-center">
				<h1>How it works</h1>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12">
				<div class="customTabs">
					<div id="exTab1">
						<ul class="tabs nav nav-pills">
							<li class="active" rel="tab1">Pet Owner</li>
							<li rel="tab2">Pet Sitter</li>
							<li rel="tab3">Pet Borrower</li>
						</ul>
						<div class="tab_container">
							<h3 class="d_active tab_drawer_heading" rel="tab1">Pet Owner</h3>
							<div id="tab1" class="tab_content">
								<?php 
									if(isset($homeBlocks['o_easy_steps']) && !empty($homeBlocks['o_easy_steps'])) { 
										echo $homeBlocks['o_easy_steps'];
									} else {
								?>								
								<div class="row">
									<div class="col-xs-12">
										<div class="stepBg-home">
											<div class="thumb-home"><img class="img-responsive" src="<?php echo $siteimage;?>/dogthumb.png" alt=""></div>
											<div class="text">
												<h3 class="removeTiltleSpace">Easy three step process</h3>
												<p>Find a home away from home & other services for your Pal</p>
											</div>
										</div>
									</div>
								</div>
								<?php } ?>
								<?php 
									if(isset($homeBlocks['o_block01']) && !empty($homeBlocks['o_block01'])) { 
										echo $homeBlocks['o_block01'];
									} else {
								?>
								<div class="row">
									<div class="marginBlock-home">
										<div class="col-md-2 col-sm-3 col-xs-12 noPad">
											<div class="signThumb-home">
											<!--	<img class="img-responsive" src="<?php echo $siteimage;?>/signup.png" alt=""> -->
														<img class="img-responsive" src="https://www.palcura.com/frontend/web/themes/palcura/images/signup.png" alt="img">
											</div>
										</div>
										<div class="col-md-10 col-sm-9 col-xs-12">
											<div class="signBlock-home">
												<div class="signText-home">
													<h2>1. Sign up</h2>
														<p><a href="signup?type=owner" style="color: #ff8447;">Create a free account</a> and begin looking for verified Palcura pet sitters and <br/> walkers near you. Whether you have a dog, cat, fish or something more exotic, <br/> we can help connect you with caring pet care. 
</p>														
														
												</div>
											</div>
										</div>
										
									</div>
								</div>
								<?php } ?>
								<?php 
									if(isset($homeBlocks['o_block02']) && !empty($homeBlocks['o_block02'])) { 
										echo $homeBlocks['o_block02'];
									} else {
								?>								
								<div class="row">
									<div class="marginBlock-home">
										<div class="col-md-10 col-sm-10 col-xs-12 pull-right">
											<div class="signBlock-home connectBlock">
												<div class="signText-home">
													<h2>Connect</h2>
													<p>Use our online video call feature or meet in person with the pet sitter/borrower</p>
												</div>
											</div>
										</div>
										<div class="col-md-2 col-sm-2 col-xs-12 noPad">
											<div class="signThumb-home">
												<img class="img-responsive" src="<?php echo $siteimage;?>/connect.png" alt="">
											</div>
										</div>
									</div>
								</div>
								<?php } ?>
								<?php 
									if(isset($homeBlocks['o_block03']) && !empty($homeBlocks['o_block03'])) { 
										echo $homeBlocks['o_block03'];
									} else {
								?>										
								<div class="row">
									<div class="marginBlock-home noMargin">
										<div class="col-md-2 col-sm-2 col-xs-12">
											<div class="signThumb">
												<img class="img-responsive" src="<?php echo $siteimage;?>/share.png" alt="">
											</div>
										</div>
										<div class="col-md-10 col-sm-10 col-xs-12">
											<div class="signBlock-home shareBlock">
												<div class="signText-home">
													<h2>Book or Share the <i class="fa fa-heart-o" aria-hidden="true"></i></h2>
													<p>Get notified of activity updates, cute pictures and videos so you never miss your pet when you travel</p>
												</div>
											</div>
										</div>
										
									</div>
								</div>
								<?php } ?>
							</div>
							<h3 class="tab_drawer_heading" rel="tab2">Pet Sitter</h3>
							<div id="tab2" class="tab_content">
								<?php 
									if(isset($homeBlocks['s_easy_steps']) && !empty($homeBlocks['s_easy_steps'])) { 
										echo $homeBlocks['s_easy_steps'];
									} else {
								?>								
								<div class="row">
									<div class="col-xs-12">
										<div class="stepBg">
											<div class="thumb">
												<img class="img-responsive" src="<?php echo $siteimage;?>/catthumb.png" alt="">
											</div>
											<div class="text">
												<h3>Easy three step process</h3>
												<p>Make money doing what you love</p>
											</div>
										</div>
									</div>
								</div>
								<?php } ?>
								<?php 
									if(isset($homeBlocks['s_block01']) && !empty($homeBlocks['s_block01'])) { 
										echo $homeBlocks['s_block01'];
									} else {
								?>								
								<div class="row">
									<div class="marginBlock-home">
										<div class="col-md-8 col-sm-8 col-xs-12">
											<div class="signBlock-home">
												<div class="signText-home">
													<h2>Create a free profile</h2>
													<p>All details of Palcura registered sitters are verified to make sure every pet has a loving home</p>
												</div>
											</div>
										</div>
										<div class="col-md-4 col-sm-4 col-xs-12">
											<div class="signThumb-home">
												<img class="img-responsive" src="<?php echo $siteimage;?>/signup.png" alt="">
											</div>
										</div>
									</div>
								</div>
								<?php } ?>
								<?php 
									if(isset($homeBlocks['s_block02']) && !empty($homeBlocks['s_block02'])) { 
										echo $homeBlocks['s_block02'];
									} else {
								?>								
								<div class="row">
									<div class="marginBlock-home">
										<div class="col-md-10 col-sm-10 col-xs-12 pull-right">
											<div class="signBlock-home connectBlock">
												<div class="signText-home">
													<h2>Connect</h2>
													<p>Use our online video callccc feature or meet in person</p>
												</div>
											</div>
										</div>
										<div class="col-md-2 col-sm-2 col-xs-12 noPad">
											<div class="signThumb-home">
												<img class="img-responsive" src="<?php echo $siteimage;?>/connect.png" alt="">
											</div>
										</div>
									</div>
								</div>
								<?php } ?>
								<?php 
									if(isset($homeBlocks['s_block03']) && !empty($homeBlocks['s_block03'])) { 
										echo $homeBlocks['s_block03'];
									} else {
								?>								
								<div class="row">
									<div class="marginBlock-home noMargin">
										<div class="col-md-8 col-sm-8 col-xs-12">
											<div class="signBlock-home shareBlock">
												<div class="signText-home">
													<h2>Get paid</h2>
													<p>Earn income providing love and care to a pet</p>
												</div>
											</div>
										</div>
										<div class="col-md-4 col-sm-4 col-xs-12">
											<div class="signThumb">
												<img class="img-responsive" src="<?php echo $siteimage;?>/paid.png" alt="">
											</div>
										</div>
									</div>
								</div>
								<?php } ?>
							</div>								
							<h3 class=" tab_drawer_heading" rel="tab3">Pet Borrower</h3>
							<div id="tab3" class="tab_content">
								<?php 
									if(isset($homeBlocks['b_easy_steps']) && !empty($homeBlocks['b_easy_steps'])) { 
										echo $homeBlocks['b_easy_steps'];
									} else {
								?>									
								<div class="row">
									<div class="col-xs-12">
										<div class="stepBg">
											<div class="thumb">
												<img class="img-responsive" src="<?php echo $siteimage;?>/girlthumb.png" alt="">
											</div>
											<div class="text">
												<h3>Easy three step process</h3>
												<p>Share the care. Multiply the love!</p>
											</div>
										</div>
									</div>
								</div>
								<?php } ?>
								<?php 
									if(isset($homeBlocks['b_block01']) && !empty($homeBlocks['b_block01'])) { 
										echo $homeBlocks['b_block01'];
									} else {
								?>								
								<div class="row">
									<div class="marginBlock">
										<div class="col-md-8 col-sm-8 col-xs-12">
											<div class="signBlock">
												<div class="signText">
													<h2>Create a free profile</h2>
													<p>Pet lovers get the love of a pet without any commitment</p>
													<p>Pet owners can share the love of their pet, travel worry free…..and earn money!</p>
												</div>
											</div>
										</div>
										<div class="col-md-4 col-sm-4 col-xs-12">
											<div class="signThumb">
												<img class="img-responsive" src="<?php echo $siteimage;?>/signup.png" alt="">
											</div>
										</div>
									</div>
								</div>
								<?php } ?>
								<?php 
									if(isset($homeBlocks['b_block02']) && !empty($homeBlocks['b_block02'])) { 
										echo $homeBlocks['b_block02'];
									} else {
								?>						
								<div class="row">
									<div class="marginBlock">
										<div class="col-md-10 col-sm-10 col-xs-12 pull-right">
											<div class="signBlock connectBlock">
												<div class="signText">
													<h2>Get to know each other</h2>
													<p>Use our online video feature or meet in person</p>
												</div>
											</div>
										</div>
										<div class="col-md-2 col-sm-2 col-xs-12 noPad">
											<div class="signThumb">
												<img class="img-responsive" src="<?php echo $siteimage;?>/connect.png" alt="">
											</div>
										</div>
									</div>
								</div>
								<?php } ?>
								<?php 
									if(isset($homeBlocks['b_block03']) && !empty($homeBlocks['b_block03'])) { 
										echo $homeBlocks['b_block03'];
									} else {
								?>								
								<div class="row">
									<div class="marginBlock noMargin">
										<div class="col-md-8 col-sm-8 col-xs-12">
											<div class="signBlock shareBlock">
												<div class="signText">
													<h2>Share the <i class="fa fa-heart-o" aria-hidden="true"></i></h2>
													<p>Book your sharing experience and welcome a whole lot love and happiness to your home</p>
												</div>
											</div>
										</div>
										<div class="col-md-4 col-sm-4 col-xs-12">
											<div class="signThumb">
												<img class="img-responsive" src="<?php echo $siteimage;?>/share.png" alt="">
											</div>
										</div>
									</div>
								</div>
								<?php } ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<div class="clear"></div>
<section id="ready-to-find">
	<div class="container">
	<h4>READY TO FIND LOVING PET CARE NEAR YOU?</h4>
<p>Find and book on-demand dog walkers, pet sitters, pet boarding and other pet care providers<br/>near you. Our Palcura sitters are ready to provide loving care for your dog, cat or other pets</p>
<a href="signup">GET STARTED</a>
 


</div>	
</section>
<section class="vissionmission">
	<div class="container">
		<div class="row">
			<?php 
				if(isset($homeBlocks['visionblock']) && !empty($homeBlocks['visionblock'])) { 
					echo $homeBlocks['visionblock'];
				} else {
			?>					
			<div class="col-xs-12 col-sm-6 col-md-6">
				<div class="textBlock vision_block">
					<div class="title">PAL REWARDS</div>
					<div class="subtitle">
						Be a one stop “service” <br>
						for everything related to Pet Care
					</div>
					
				</div>
			</div>
			<?php } ?>
			<?php 
				if(isset($homeBlocks['missionblock']) && !empty($homeBlocks['missionblock'])) { 
					echo $homeBlocks['missionblock'];
				} else {
			?>					
			<div class="col-xs-12 col-sm-6 col-md-6">
				<div class="textBlock mission_block">
						<ul>
						<li><img src="<?php echo $siteimage;?>/FidoGuarantee.png"
							alt="Palcura Approved"/></li>
						<li><img src="<?php echo $siteimage;?>/FIDOapproved.png"/></li>
						</ul>
					<div class="subtitle">Palcura was born through our own experiences with our dog. Every decision we’ve made along the way has been with his well-being in mind.</div>
					<a href="<?php echo Url::to(['cms/page', 'slug'=> 'why-palcura']) ;?>" class="learn-more">Learn more</a>


				</div>
			</div>
			<?php } ?>
		</div>
	</div>
</section>
<script>


$(document).ready(function() {
    $(".tab_content").hide();
    $(".tab_content:first").show();
	$("ul.tabs li").click(function() {
		$(".tab_content").hide();
		var activeTab = $(this).attr("rel"); 
		$("#"+activeTab).fadeIn();
		$("ul.tabs li").removeClass("active");
		$(this).addClass("active");
		$(".tab_drawer_heading").removeClass("d_active");
		$(".tab_drawer_heading[rel^='"+activeTab+"']").addClass("d_active");
	});
<?php if($userLOGIN == 0) { ?>
		$("button.yellow-Btn").click(function() {
		window.location.href="signin";
	});
	/*
	$("a.yellow-Btn").click(function() {
		window.location.href="signup?type=sitter";
	});
	*/
	$("button.orangebtn").click(function() {
			window.location.href="signin";
	});
	
	<?php }else{ ?>	
	$("button.yellow-Btn").click(function() {
		$('html, body').animate({
			scrollTop: $("#selectPaltype").offset().top
		}, 1200);
		
	});
	$("button.orangebtn").click(function() {
		$('html, body').animate({
			scrollTop: $("#selectPaltype").offset().top
		}, 1200);
	});
	

	<?php } ?>
	
$("#find-dog").click(function() {
		$('html, body').animate({
			scrollTop: $("#selectPaltype").offset().top
		}, 1200);
	});

	$("button.yellowbtn").click(function() {
		$('html, body').animate({
			scrollTop: $("#selectPaltype").offset().top
		}, 1200);
	});

	$(".item01").click(function() {
		$('html, body').animate({
			scrollTop: $(".one").offset().top
		}, 1200);
		locate();
	});

	$(".item02").click(function() {
		$('html, body').animate({
			scrollTop: $(".two").offset().top
		}, 1200);
		locate();
	});

	$(".item03").click(function() {
		$('html, body').animate({
			scrollTop: $(".three").offset().top
		}, 1200);
		locate();
	});	

	$("a.changepal").click(function() {
		$('html, body').animate({
			scrollTop: $("#selectPaltype").offset().top
		}, 1200);
	});
$(".tab_drawer_heading").click(function() {
	$('html, body').animate({
        scrollTop: $(".d_active").offset().top 
    } , 2000);
});

});

function locate(){
	if ("geolocation" in navigator){
		navigator.geolocation.getCurrentPosition(function(position){
			var currentLatitude = position.coords.latitude;
			var currentLongitude = position.coords.longitude;
			$(".userlatitude").val(currentLatitude);
			$(".userlongitude").val(currentLongitude);
		});
	}
}
</script>
