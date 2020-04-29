	<?php
	/* @var $this \yii\web\View */
	/* @var $content string */

	use yii\helpers\Html;
	use yii\bootstrap\Nav;
	use yii\bootstrap\NavBar;
	use yii\widgets\Breadcrumbs;
	use frontend\assets\AppAsset;
	use common\widgets\Alert;
	use yii\helpers\Url;
	use common\components\languageSwitcher;
	use yii\db\Query;

	AppAsset::register($this);

	$asset 		= frontend\assets\AppAsset::register($this);
	$baseUrl 	= $asset->baseUrl;
	$actionId 	= Yii::$app->controller->action->id;
	$CtrlName	= Yii::$app->controller->id;
	$request 	= Yii::$app->request;
	$param 		= $request->get('id');
	$page		= '';
	$siteimage  = Yii::getAlias('@siteimage');
	$websettings = Yii::$app->commonmethod->getWebsiteSettings();
	?>
	<?php $this->beginPage() ?>
	<!DOCTYPE html>
	<html lang="<?= Yii::$app->language ?>">
		<head>
			
	<!-- Google Tag Manager -->
		<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
		new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
		j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
		'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
			})(window,document,'script','dataLayer','GTM-5CHSHNC');</script>
	<!-- End Google Tag Manager -->

		
			<meta charset="<?= Yii::$app->charset ?>">
			<meta name="viewport" content="width=device-width, initial-scale=1">
			<?= Html::csrfMetaTags() ?>
			<link rel="shortcut icon" href="<?php echo Yii::getAlias('@siteimage'); ?>/favicon.ico"/>
			<title><?= Html::encode($this->title) ?></title>


			<?php $userLOGIN = Yii::$app->user->getId(); ?>
			<script>
				var currentuser = "<?php echo (isset($userLOGIN) && $userLOGIN > 0 ? $userLOGIN : 0) ?>";
				var siteUrl = "<?php echo Yii::getAlias('@basepath'); ?>";
				var baseUrl = "<?php echo Yii::getAlias('@basepath'); ?>";
				var language = "<?php echo (isset(Yii::$app->language) ? Yii::$app->language : 'en') ?>";
				var userLogIn = "<?php echo (isset($userLOGIN) && $userLOGIN > 0 ? 1 : 0) ?>";
				
			</script>
			<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
			
			<?php $this->head(); ?> 
		</head>
		<body>
		<!-- Google Tag Manager (noscript) -->
	<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5CHSHNC"
	height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
	<!-- End Google Tag Manager (noscript) -->

	<?php echo $websettings['google_analytics']; ?>
	<?php echo $websettings['facebook_pixel']; ?>
		<?php $this->beginBody() ?>
		<?php if(basename($_SERVER['REQUEST_URI']) == 'become-a-sitter'){ $cls='become-banner'; }elseif(basename($_SERVER['REQUEST_URI']) == 'why-palcura'){ $cls='abt-banner'; }elseif(basename($_SERVER['REQUEST_URI']) == 'pal-rewards'){ $cls='rewards-banner'; } else { $cls=''; } ?>
		
			<header class="defineFloat homepage <?php echo $cls.' '; if($page != 'index.php') { echo 'innerpages'; }  ?>">
				<div class="container">
					<div class="row">
						<div class="col-xs-12">
							<div class="header">
								<div class="row">
									<div class="col-md-12 col-sm-12 col-xs-12 resTop">
									
										
										
										
										<nav class="navbar navbar-expand-lg navbar-light bg-light">
										<div class="navbar-brand">
											<a href="<?= Url::home() ?>">
												<img src="<?php echo $siteimage; ?>/palcura-logo.png" alt="" title="Palcura">

											</a>
										</div>
										
	
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
		<span class="fa fa-navicon"></span>
	</button>

	<div class="collapse navbar-collapse" id="navbarSupportedContent">
	
		
		<ul class="navbar-nav mr-auto">		<?php if($userLOGIN == 0) { ?>
											<li>
												
												<a href="signin" title="Find Dog Walkers and Pet Care"> <img src="<?php echo $siteimage; ?>/search-icon.png"> </img>Find Dog Walkers &amp; Pet Care</a>


											</li>
										<?php } else { ?>

											<li>
												<a href="http://www.palcura.com" onclick="resetsearchpettype();return false;" id="find-dog" title="Find Dog Walkers and Pet Care"> <img src="<?php echo $siteimage; ?>/search-icon.png"> </img>Find Dog Walkers &amp; Pet Care</a>
											</li>


										<?php
										} 
										?>

												<li>
													<?php echo Html::a(Yii::t('yii', 'Why PalCura?'), ['cms/page', 'slug'=>'why-palcura'],['title'=> 'Why PalCura?']); ?>
												</li>
												<?php if($userLOGIN == 0) { ?>
												<li>
													<?php echo Html::a(Yii::t('yii', 'Sign In'), ['site/signin'],['title'=> 'Sign In']); ?>
												</li>
												<?php } ?>
												
												<?php if($userLOGIN == 0) { ?>
											<div class="signUp">
												<?php echo Html::a(Yii::t('yii', 'Sign Up'), ['site/signup'],['title'=> 'Sign Up']); ?>
											</div>
											<?php } else { ?>
											<?php
												$attributes = 	Yii::$app->user->identity->getattributes();
												$profile_picture = $attributes['profile_image'];
												if(isset($attributes['profile_image']) && !empty($attributes['profile_image'])) { 
													$profile_pic = PROFILE_IMAGE_PATH . $attributes['profile_image'];
												} else {
													$profile_pic = NO_DISPLAY_IMAGE;
												}
											?>	
											<div class="topDropdown">
												<div class="notificationsArea">
													<div class="cleintimg"><img src="<?php echo $profile_pic; ?>" alt=""></div>
													<?php 
	$mcnt = '';
													$messagecount	= Yii::$app->commonmethod->getUnreadMessagesCount($attributes['id']); 
													if($messagecount > 0) {
														echo '<span class="">'.$messagecount.'</span>';
	$mcnt = '<span class="notifications">'.$messagecount.'</span>';
													}
													?>
												</div>
		
	</div>

	<div class="dropdown">
													<button class="btn btnTransparent dropdown-toggle" type="button" data-toggle="dropdown"> Welcome <?= (isset($attributes['firstname']) ? Yii::$app->commonmethod->strsublen_complete($attributes['firstname'],0,12,1) : '') ?>! <i class="fa fa-angle-down" aria-hidden="true"></i></button>
													<ul class="dropdown-menu">
														<li <?php if($CtrlName == 'users' && $actionId == 'dashboard') { ?> class="active" <?php } ?>>
															<?php echo Html::a(Yii::t('yii','Dashboard'),Url::home().'users/dashboard',['users']);?>
														</li>
														<li <?php if($CtrlName == 'users' && $actionId == 'myprofile') { ?> class="active" <?php } ?>>
															<?php echo Html::a(Yii::t('yii','Your Profile'),Url::home().'users/myprofile',['users']);?>
														</li>
														<li <?php if($CtrlName == 'bookings') { ?> class="active" <?php } ?>>
															<?php echo Html::a(Yii::t('yii','Bookings'),Url::home().'bookings',['booking']);?>
														</li>
														<li <?php if($CtrlName == 'messages') { ?> class="active" <?php } ?>>
															<?php echo Html::a(Yii::t('yii','Messages'.$mcnt),Url::home().'messages',['messages']);?>
														</li>
														<li <?php if($CtrlName == 'payments') { ?> class="active" <?php } ?>>
															<?php echo Html::a(Yii::t('yii','Payments'),Url::home().'payments',['payments']);?>
														</li>
														<li <?php if($CtrlName == 'video') { ?> class="active" <?php } ?>>
															<?php echo Html::a(Yii::t('yii','Video Calls'),Url::home().'video',['video']);?>
														</li>
														<li <?php if($CtrlName == 'reviews') { ?> class="active" <?php } ?>>
															<?php echo Html::a(Yii::t('yii','Reviews'),Url::home().'reviews',['reviews']);?>
														</li>
														<li>
															<?php echo Html::a(Yii::t('yii','Sign Out'),Url::home().'site/logout',['reviews']);?>
														</li>
													</ul>
												</div>  
	</nav>
										
									
										
										
											
											
												
											</div>
											<?php } ?>
												
											</ul>
											
			
										
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php echo $content; ?>
					<?php
						$p_faq=$p_cont=$p_blog=$p_terms='';
						$p_faq 		= ($CtrlName == 'realestate' && $actionId == 'realestateanalytics') ? 'active' : '';
						$p_cont 	= ($CtrlName == 'realestate' && $actionId == 'realestatemarket') ? 'active' : '';
						$p_blog 	= ($CtrlName == 'realestate' && $actionId == 'realestatedevelopers') ? 'active' : '';
						$p_terms 	= ($CtrlName == 'realestate' && $actionId == 'realestatedevelopers') ? 'active' : '';
					?>
					<footer class="defineFloat footer">
						<div class="container">
							<p>Palcura is currently connecting pet parents with caretakers, pet sitters & dog walkers in Florida.</p>
							<h5><a href="<?php echo Url::home();?>near-me">Do you live outside of Florida? Click here to request Palcura near you</a> </h5>
								<div class="row">
									<div class="col-sm-3">
									<h4>Palm Beach County</h4>
									<ul>
										<li><a href="">Boca Raton, FL</a></li>
	<li><a href="">Boynton Beach, FL</a></li>
	<li><a href="">Delray Beach, FL</a></li>
	<li><a href="">Greenacres, FL</a></li>
	<li><a href="">Highland Beach, FL</a></li>
	<li><a href="">Jupiter, FL</a></li>
	<li><a href="">Lake Worth, FL</a></li>
	<li><a href="">Lantana, FL</a></li>
	<li><a href="">North Palm Beach, FL</a></li>
	<li><a href="">Palm Beach, FL</a></li>
	<li><a href="">Palm Beach Gardens,FL</a></li>
	<li><a href="">Palm Springs, FL</a></li>
	<li><a href="">Rivera Beach, FL</a></li>
	<li><a href="">Royal Palm Beach, FL</a></li>
	<li><a href="">Tequesta, FL</a></li>
	<li><a href="">Wellington, FL</a></li>
	<li><a href="">West Palm Beach, FL</a></li>
									</ul>
									</div>
									<div class="col-sm-3">
									<h4>Broward County</h4>
									<ul>
										<li><a href="">Coconut Creek, FL</a></li>
	<li><a href="">Coral Springs, FL</a></li>
	<li><a href="">Davie, FL</a></li>
	<li><a href="">Deerfield Beach, FL</a></li>
	<li><a href="">Fort Lauderdale, FL</a></li>
	<li><a href="">Hallandale Beach, FL</a></li>
	<li><a href="">Hollywood, FL</a></li>
	<li><a href="">Lauderhill, FL</a></li>
	<li><a href="">Parkland, FL</a></li>
	<li><a href="">Pompano Beach, FL</a></li>
	<li><a href="">Pembroke Pines, FL</a></li>
	<li><a href="">Plantation, FL</a></li>
	<li><a href="">Margate, FL</a></li>
	<li><a href="">Miramar, FL</a></li>
	<li><a href="">Sunrise, FL</a></li>
	<li><a href="">Tamarac, FL</a></li>
	<li><a href="">Weston, FL</a></li>
									</ul>	

									</div>
									<div class="col-sm-3">
									<h4>Miami-Dade County</h4>
									<ul>
										<li><a href="">Aventura, FL</a></li>
	<li><a href="">Coral Gables, FL</a></li>
	<li><a href="">Doral, FL</a></li>
	<li><a href="">Hialeah, FL</a></li>
	<li><a href="">Homestead, FL</a></li>
	<li><a href="">Key Biscayne, FL</a></li>
	<li><a href="">Miami, FL</a></li>
	<li><a href="">Miami Beach, FL</a></li>
	<li><a href="">Miami Gardens, FL</a></li>
	<li><a href="">Miami Lakes, FL</a></li>
	<li><a href="">Miami Shores, FL</a></li>
	<li><a href="">North Miami Beach, FL</a></li>
	<li><a href="">North Miami, FL</a></li>
	<li><a href="">Pinecrest, FL</a></li>
	<li><a href="">South Miami, FL</a></li>
	<li><a href="">Surfside, FL</a></li>
	<li><a href="">West Miami, FL</a></li>
									</ul>	

									</div>
									<div class="col-sm-3">
									<h4>About Palcura</h4>
									<ul>
										<li class="<?= $p_faq ?>">
											<?php echo Html::a(Yii::t('yii', 'Home'),  ['/']); ?>				
										</li>


										<li class="<?= $p_faq ?>">
											<?php echo Html::a(Yii::t('yii', 'FAQs		'), ['cms/page', 'slug'=>'faq'],['title'=> 'FAQ']); ?>				
										</li>
										<li class="<?= $p_cont ?>">
											<?php echo Html::a(Yii::t('yii', 'Contact'), ['site/contact'],['title'=> 'Contact']); ?>
										</li>
										<li class="<?= $p_blog ?>">
										<li class="<?= $p_blog ?>">
											<a href="https://blog.palcura.com/" title="Blog" target="_blank">Blog</a>
										</li>

										<li class="<?= $p_terms ?>">
										<?php echo Html::a(Yii::t('yii', 'About Us'), ['cms/page', 'slug'=>'why-palcura'],['title'=> 'About Us']); ?>
										</li>
										<li class="<?= $p_terms ?>">
											<?php echo Html::a(Yii::t('yii', 'Rewards'), ['cms/page', 'slug'=>'pal-rewards'],['title'=> 'Rewards']); ?>
										</li>
										<li class="<?= $p_terms ?>">
											<?php echo Html::a(Yii::t('yii', 'My Account'), ['users/dashboard'],['title'=> 'My Account']); ?>
										</li>
										<li class="<?= $p_terms ?>">

											<a href="become-a-sitter" title="Become a Sitter">Become a Sitter</a>
										</li>

									</ul>

									<ul class="footerListing">
										<li><a target="_blank" href="https://www.facebook.com/palcuracares/"><i class="fa fa-facebook-f" style="font-size:20px;"></i> </a>
											<span>&nbsp;</span>				
										</li>
										<li><a target="_blank" href="https://twitter.com/palcurapetcare"><i class="fa fa-twitter" style="font-size:20px;"></i> </a>
											<span>&nbsp;</span>				
										</li>
										<li><a target="_blank" href="https://www.instagram.com/palcura/"><i class="fa fa-instagram" style="font-size:20px;"></i> </a>
											<span>&nbsp;</span>				
										</li>
										<li><a target="_blank" href="https://plus.google.com/u/0/107443116526249168052"><i class="fa fa-google-plus-square" style="font-size:20px;"></i> </a>
											<span>&nbsp;</span>				
										</li>

									</ul>

									<div class="palcura-footer-logo">
										<a href="<?= Url::home() ?>">
										<img src="<?php echo $siteimage; ?>/flogo.png"></img>
									</a>
									</div>
									<div class="col-md-4 col-sm-4 col-xs-12 pull-right">
									
									
								</div>

									</div>

								</div>

								<div class="copyright">© copyright 2018. All right reserved. 
									<?php echo Html::a(Yii::t('yii', 'Terms & Conditions'), ['cms/page', 'slug'=>'Terms-and-Conditions' ],['title'=> 'Terms-and-Conditions']); ?>
									</div>

				

	<!--
							<div class="row">
								<div class="col-md-4 col-sm-4 col-xs-12 ">
									<ul class="footerListing">
										<li class="<?= $p_faq ?>">
											<?php echo Html::a(Yii::t('yii', 'FAQ'), ['cms/page', 'slug'=>'faq'],['title'=> 'FAQ']); ?><span>|</span>				
										</li>
										<li class="<?= $p_cont ?>">
											<?php echo Html::a(Yii::t('yii', 'Contact'), ['site/contact'],['title'=> 'Contact']); ?><span>|</span>
										</li>
										<li class="<?= $p_blog ?>">
											<?php echo Html::a(Yii::t('yii', 'Blog'), ['cms/page', 'slug'=>'blog' ],['title'=> 'Blog']); ?><span>|</span>
										</li>
										<li class="<?= $p_terms ?>">
											<?php echo Html::a(Yii::t('yii', 'Terms & Conditions'), ['cms/page', 'slug'=>'terms-and-conditions'],['title'=> 'Terms and Conditions']); ?>
										</li>
									</ul>
									
								</div>
								<div class="col-md-4 col-sm-4 col-xs-12 pull-right">
									<div class="footerLogo"><img class="img-responsive" src="<?php echo $siteimage;?>/flogo.png" alt=""> </div>
									
								</div>
								<div class="col-md-4 col-sm-4 col-xs-12">
									<div class="copyOuter"> &copy; Copyright <?php echo date("Y"); ?>. All rights reserved. </div>
									
								</div>
							</div>

						-->
						</div>
					</footer>
			<?php $this->endBody() ?>
		</body>
	<!--Bootstrap modal start-->   
	<div class="modal fade" id="viModal" role="dialog">
		<div class="modal-dialog">
		
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header" style="padding:35px 50px;">
			<!--button type="button" class="close" data-dismiss="modal">&times;</button-->
			<h4><span class="glyphicon glyphicon-check"></span>Video call request...</h4>
			</div>
			<div class="modal-body" style="padding:40px 50px;">
			
			<img src="<?php echo WEBSITE_IMAGES_PATH ?>phone.gif" alt="" height="100px;">
			</div>
			<!--div class="modal-footer">
			<!--button type="submit" class="btn btn-danger btn-default pull-left" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Cancel</button-->
			<button type="submit" class="btn btn-success btn-default" id="talktocaller"><span class="glyphicon glyphicon-check"></span> Join session</button>
			<button type="submit" class="btn btn-danger btn-default pull-left" data-dismiss="modal" id="declinecaller"><span class="glyphicon glyphicon-remove"></span>Decline</button>
			<!--p>Not a member? <a href="#">Sign Up</a></p>
			<p>Forgot <a href="#">Password?</a></p>
			</div-->
		</div>
		
		</div>
	</div> 
	
	</div>

	<!--Bootstrap modal end-->
		
		
	</html>
	<?php $this->endPage() ?>

