<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

//$this->title = Yii::t('yii','Palcura Rewards');
$this->params['breadcrumbs'][] = $this->title;
$siteimage = Yii::getAlias('@siteimage');

$cookies_email = isset($_COOKIE[Yii::getAlias('@site_title')."_user_email"]) ? $_COOKIE[Yii::getAlias('@site_title')."_user_email"] : '';


?> 
        <div class="col-xs-12">
            <div class="home-banner rewards-banner">
					<h1>REWARDS PROGRAM</h1>
				
			</div>
        </div>
</div>
</header>
<section class="contentArea">
<div class="pal-rewards">
<div class="container">
<div class="row">
	<div class="col-md-9 col-sm-9 col-xs-12">
		<h3>Pal Rewards</h3>
		<p>Pet parents earn points with each Palcura purchase PLUS exclusive discounts at local veterinarians, pet stores, groomers, and trainers!</p>
		<h6>$1 SPENT = 1 POINT EARNED*</h6>
	</div>
	<div class="col-md-3 col-sm-3 col-xs-12">
		<div class="imagespal">
		<img src="<?php echo $siteimage; ?>/PalRewards-icon.png" alt="Earn a point for every dollar spent with Pal Rewards" />
		</div>
	</div>
</div>
</div>
</div>
<div class="how-it_work">
	<div class="container">
		<div class="row">
			<div class="col-sm-6 col-md-6 col-xs-12">
				<h3>How it Works</h3>
				<p>Pet parents earn points with each purchase:</p>
				<p>Earn 500 points and receive a <strong>$5 credit</strong> <br/>Earn 1000 points and receive a <strong>$10 credit</strong><br/> And so on!</p>
				<p><strong>PLUS</strong> receive coupons for veterinarians, groomers, and pet related stores and services near you!</p>
			</div>
			<div class="col-sm-6 col-md-6 col-xs-12"><div class="imageswork"><img src="<?php echo $siteimage; ?>/How-palcura-rewards-work.jpg" alt="How Palcura Rewards Work" /></div></div>
		</div>
	</div>
</div>
<div class="pet-perents-main">
	<div class="container">
	<div class="row">
		<div class="col-sm-6 col-md-6 col-xs-12">
			<div class="perents-owners">
				<h4>Rewards for Pet Parents</h4>
				<h5>(OWNERS)</h5>
				<p>Pet Owners who reserve a service for their pet through Palcura are willing to go the extra mile in making sure that their Pal gets the loving care that they truly deserve. </p>
				<p>At Palcura, we believe that care should be rewarded which is why you <strong>earn 1 point for every $1 you spend.</strong> For every 500 points you earn, get $5 as credit that can be used to reserve the next care for your pet. </p>
				<div class="row">
				<div class="bottom-images_part">
					<div class="col-sm-7 col-md-7 col-xs-12 images_text">
						<p>Earn up to 2000 points ($20) at a time and use them towards pet sitting and dog walking services at Palcura.</p>
					</div>
					<div class="col-sm-5 col-md-5 col-xs-12 images-pet">
						<div class="images-owners"><img src="<?php echo $siteimage; ?>/Pet-parents-rewards.png" alt="Rewards for pet parents" title="Rewards"></div>
					</div>
				</div>
			</div>
		</div>
		</div>
		<div class="col-sm-6 col-md-6 col-xs-12">
			<div class="borrowers-owners">
				<h4>Rewards for Pet Borrowers</h4>
				<h5>(COMING SOON)</h5>
				<p>For those who are willing to take the first step towards pet ownership or want to fill a void in their space and love pets, deserve to get rewarded too! </p>
				<p>For every $1 you spend in borrowing a pet, you will earn 1 point. <strong>For every 500 points you earn, get $5 as credit</strong> that can be used towards your next borrowing service.</p>
				<div class="row">
				<div class="bottom-images_part">
					<div class="col-sm-7 col-md-7 col-xs-12 images_text">
						<p>Earn up to 2000 points ($20) and use it towards your next Palcura service. We hope that pet borrowing will help you and one day  give you the confidence to become a
pet parent yourself!
 <!--pet parent yourself!--></p>
					</div>
					<div class="col-sm-5 col-md-5 col-xs-12 images-pet">
						<div class="images-owners"><img src="<?php echo $siteimage; ?>/Pet-borrower-rewards.png" alt="Rewards for Pet Borrowers" title="Borrowers"></div>
					</div>
				</div>
				</div>
			</div>
			</div>
		</div>
	</div>
</div>
		<div class="exclusive_discounts">
		<div class="container">
			<h3>Exclusive Coupons & Discounts ** </h3>
			<p>Owners deserve a lot more and points are good, but not enough. We have partnered with exclusive organic pet food stores, veterinarians, groomers, trainers, and other pet related stores and services near you that offer exclusive coupons and discount codes that you can use for your Pal!</p>
			</div>
		</div>
		
			<div class="container">
			<div class="vip_rewards">
			<div class="row">
			
			<div class="col-sm-3 col-md-3 col-xs-12 left-image-vip">
			<img src="<?php echo $siteimage; ?>/Pet-sitter-rewards.png" alt="Rewards for Pet Sitters and Dog Walkers" title="VIP">
			</div>
			<div class="col-sm-9 col-md-9 col-xs-12 right-text-vip">
				<h3>VIP Rewards for Pet Sitters & Dog Walkers</h3>
				<h6>We have the lowest service fee in the market. That means more money in your pocket!</h6>
				<p>Pet care providers, pet sitters, dog walkers, and day care providers automatically become a Palcura VIP Member once they’ve completed 50 or more total sitting days/service days!</p>
				<p>Palcura VIP members receive a 1.5% discount off our service fees for every transaction for life, as long as they maintain their verification badge. <strong>Interested in becoming a pet sitter or dog walker with Palcura?</strong> <a href="signup?type=sitter">Register to Get Started</a></p>
			</div>
		</div>
		</div>
		</div>
		<div class="finel_print">
		<div class="container">
			<h3>The Fine Print</h3>
			<p>*Points expire after 12 months. You can accumulate up to 2000 points at a time. Points will stop accumulating after 2000 points and you will have an option to convert the points to credit for booking a service.</p>
			<p>**Coupons are made available through partnership with local businesses. Coupons, if available, will be emailed after every second transaction.</p>
			</div>
		</div>
</section>
<section id="ready-to-find">
	<div class="container">
	<h4>READY TO START EARNING REWARDS?</h4>
<p>Find and book on-demand dog walkers, pet sitters, pet boarding and other pet care providers<br/>near you. Our Palcura sitters are ready to provide loving care for your dog, cat or other pets</p>
<a href="signup">GET STARTED</a>
 


</div>	
</section>




