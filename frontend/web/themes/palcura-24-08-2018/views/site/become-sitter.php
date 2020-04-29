<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

//$this->title = Yii::t('yii','Become-a-sitter');
$this->params['breadcrumbs'][] = $this->title;
$siteimage = Yii::getAlias('@siteimage');

$cookies_email = isset($_COOKIE[Yii::getAlias('@site_title')."_user_email"]) ? $_COOKIE[Yii::getAlias('@site_title')."_user_email"] : '';


?>  <div class="row">
        <div class="col-xs-12">
            <div class="home-banner become-sitter-banner">
					<h3>Do you <img src="<?php echo $siteimage; ?>/Heart-icon.png" alt="Palcura loves its pets" title="Set Your own Schedule"> Pets?</h3>
				<h4>Turn your passion for pets into extra revenue by </br> becoming a dog walker or pet sitter for Palcura.</h4>
				<div class="bannerButton">
					<a href="signup?type=sitter"><button class="orangebtn">SETUP YOUR SITTER PROFILE</button></a>
					
				</div>
				
			</div>
        </div>
    </div>
</div>
</header>
<section class="contentArea">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12 become-a">
				<div class="innerheading text-center">Become a Dog Walker or Pet Sitter</div>
					
						<p>and earn up to <strong>$1,000/mo</strong> doing something you love</p>
							<div class="bannerButton bannerButton-find">
								<a class="orangebtn" href="signup?type=sitter">GET STARTED</a>
							</div>
					
            </div>
        </div>
		<div class="row">
			<div class="col-md-4 col-sm-4 col-xs-12 become-col-3">
				<div class="images-icon"><img src="<?php echo $siteimage; ?>/Set-your-schedule.png" alt="Set your own schedule" title="Set your own schedule"></div>
				<div class="title">Set Your own Schedule</div>
				<p>Palcura’s system allows you to set your own schedule to find dog walking and pet sitting gigs near you. It’s like having your own pet care company!</p>
			</div>
			<div class="col-md-4 col-sm-4 col-xs-12 become-col-3">
				<div class="images-icon"><img src="<?php echo $siteimage; ?>/get-paid.png" alt="Earn money caring for pets" title="Set Your own Schedule"></div>
				<div class="title">Get Paid to Care for Pets</div>
				<p>Set your own prices and unique profile. Palcura will take care of the rest so you can focus on providing quality pet care to local pet parents.</p>
			</div>
			<div class="col-md-4 col-sm-4 col-xs-12 become-col-3">
				<div class="images-icon"><img src="<?php echo $siteimage; ?>/Rabbit.png" alt="Pet jobs for cats, rabbits and other animals" title="Set Your own Schedule"></div>
				<div class="title">More Than Just Dogs</div>
				<p>Would you like to care for cats, rabbits, or something more exotic? Set your own pet preferences and
get matched with local pet parents.</p>
			</div>
		</div>
</div>
<div class="email-address-section">

	<div class="container">
	<div class="row">
		<h4>6 Things You Should Know Before <span>Becoming a Dog Walker or Pet Sitter</span></h4>
		<div class="email-address-form"> 

			<?php
						$form = ActiveForm::begin([
						'id' => 'login-form',
						'options' => [
						'enctype' => 'multipart/form-data',
						'class' => 'queryForm',
						],
						'fieldConfig' => [
						'template' => "
						{label}\n
						{input}
						<div class=\"col-lg-12 error-msg\">
						{error} {hint}
						</div>",
						'labelOptions' => ['class' => ''],
						],
						]);
					
								echo $form->field($model, 'email', ['inputOptions' => [
								'class' => "form-control",'placeholder' => "Enter your email address"
								]])->textInput(['maxlength' => 100,'value' => $cookies_email])->label('');
								
							?>
							<div class="download-button">
							<?= Html::submitButton('FREE DOWNLOAD',[
								'class' => "orangebtn"]
							)?>


						</div>
							<?php ActiveForm::end(); ?>

			</div>



	</div>
	</div>
</div>
</section>
<div class="container">
	<div class="col-sm-offset-2 col-md-offset-2 col-sm-8 col-md-8 col-xs-12">
		<div class="provideo-section">
			<div class="videoframe">
				<!--
<img src="<?php echo $siteimage; ?>/vedio-image.png" alt="icon" title="vedio">
	-->		
	<video controls style="width:640px;height:360px;" poster="<?php echo $siteimage; ?>/vedio-image.png" src="<?php echo $siteimage; ?>/IMG-3288.mp4">
		
	</video>
			</div>
			<div class="button-provideo">
				<a href="signup?type=sitter">PROVIDE PET CARE SERVICES</a></div>
			</div>
		</div>
	</div>
	
<div class="container">
<div class="palcura-works-maine">
	<h2>How Palcura Works</h2>
	<div class="palcura-bkg">
	<div class="border-pros"></div>
	<div class="col-sm-3 col-md-3 col-xs-12 palcura-works">
	<div class="numbers_format active">1</div>
		<h4>Register with Palcura</h4>
		<p>It takes less than 5 minutes to get started with Palcura! Just <a href="signup?type=sitter">click here</a> and complete the required fields.</p>
	</div>
	<div class="col-sm-3 col-md-3 col-xs-12 palcura-works">
	<div class="numbers_format">2</div>
		<h4>Complete Your Profile</h4>
		<p>Tell pet parents about yourself. Select your schedule, rates, and the types of pets and services you’d like to offer.</p>
	</div>
	<div class="col-sm-3 col-md-3 col-xs-12 palcura-works">
	<div class="numbers_format">3</div>
		<h4>Provide Services</h4>
		<p>Once you are matched, provide loving pet sitting and dog walking services to South Florida pet parents near you.</p>
	</div>
	<div class="col-sm-3 col-md-3 col-xs-12 palcura-works">
	<div class="numbers_format">4</div>
		<h4>Get Paid Fast</h4>
		<p>Getting paid by Palcura is easy! Just enter your Paypal email address and payments will be sent automatically.</p>
	</div>
	</div>
	<div class="get_start-button"><a href="signup?type=sitter">GET STARTED</a></div>
</div>
</div>
<div class="why-we-come">
	<div class="container">
		<div class="row">
			<div class="col-sm-3 col-md-3 col-xs-12 left-image-we">
<img src="<?php echo $siteimage; ?>/Why-become-a-pet-sitter.png" alt="Palcura has the lowest pet sitting service fees" title="vedio">
			</div>
			<div class="col-sm-9 col-md-9 col-xs-12 right-text-we">
				<h3>WHY BECOME A DOG WALKER OR PET SITTER WITH PALCURA?</h3>
				<h6>We have the lowest service fee in the market. That means more money in your pocket!</h6>
				<p>Pet care providers, pet sitters, dog walkers, and day care providers are the backbone of Palcura services. We want to reward our service providers for their loyalty which is why we created the Palcura VIP program. </p>
				<p>You will automatically become a <strong>Palcura VIP Member</strong> once you’ve completed 50 or more total sitting days/service days! Palcura VIP members receive a <strong>1.5% discount</strong> off our service fees for every transaction for life, as long as you maintain the verification badge. <a href="signup?type=sitter">Register with Palcura to get started</a></p>
			</div>
		</div>
	</div>
</div>
