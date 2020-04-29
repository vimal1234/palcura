<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
//rentingpetserror case 
                 
$this->title = 'Thank you';
$this->params['breadcrumbs'][] = $this->title;
$siteimage = Yii::getAlias('@siteimage');

####= common methods =####
$hear_about             = array(1=>'Initial launch',2=>'Google',3=>'Facebook',4=>'Instagram',5=>'Twitter',6=>'Next door',7=>'Other Social',8=>'Event',9=>'Dog Park',10=>'Flyers',11=>'Word of mouth',12=>'Referral',13=>'Other');

$session = Yii::$app->session;
$registered = $session->get('registered');
//echo '---------test-------'.$registered;
 $session->set('registered',0);
?>  <div class="row">
        <div class="col-xs-12">
            <div class="home-banner become-sitter-banner">
					<h3>Do you <img src="<?php echo $siteimage; ?>/Heart-icon.png" alt="animal image" title="Set Your own Schedule"> Pets?</h3>
				<h4>Turn your passion for pets into extra revenue by </br> becoming a dog walker or pet sitter for Palcura.</h4>
				<div class="bannerButton" id="freedownload">
					<a href="signup?type=sitter"><button class="orangebtn">SETUP YOUR SITTER PROFILE</button></a>
					
				</div>

				
			</div>
        </div>
    </div>
</div>
</header>
<section class="contentArea">
    <div class="container">
        <div class="row" >
            <div class="col-md-12 col-sm-12 col-xs-12 success-part">
                <h2 class="text-center contactTilte thank-title">Success!</h2>
				<p>Click the link below to download</p>
				<div class="download-button">
							<a href="<?= $siteimage.'/6-things-to-know-before-becoming-a-dog-walker.pdf' ?>" target="_blank"><button type="submit" class="orangebtntnx">FREE DOWNLOAD</button></a>
						</div>
            </div>
        </div>
        
</section>
<div class="clearfix"></div>
<div class="why-we-come">
	<div class="container">
		<div class="row">
			<div class="col-sm-3 col-md-3 col-xs-12 left-image-we">
<img src="<?php echo $siteimage; ?>/Why-become-a-pet-sitter.png" alt="icon" title="vedio">
			</div>
			<div class="col-sm-9 col-md-9 col-xs-12 right-text-we">
				<h3>WHY BECOME A DOG WALKER OR PET SITTER WITH PALCURA?</h3>
				<h6>We have the lowest service fee in the market. That means more money in your pocket!</h6>
				<p>Pet care providers, pet sitters, dog walkers, and day care providers are the backbone of Palcura services. We want to reward our service providers for their loyalty which is why we created the Palcura VIP program. </p>
				<p>You will automatically become a <strong>Palcura VIP Member</strong> once you’ve completed 50 or more total sitting days/service days! Palcura VIP members receive a <strong>1.5% discount</strong> off our service fees for every transaction for life, as long as you maintain the verification badge. <a href="https://www.palcura.com/signup">Register with Palcura to get started</a></p>
			</div>
		</div>
	</div>
</div>

<script>


$(document).ready(function() {

		$('html, body').animate({
			scrollTop: $("#freedownload").offset().top
		}, 1200);
});
</script>
