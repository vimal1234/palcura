<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('yii','Add Card Details');
$session 	= Yii::$app->session;	
$loggedingusertype = $session->get('loggedinusertype');
$refcard = $session->get('refercard');

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
			<div class="col-md-10 col-sm-12 col-xs-12">
	<div class="col-md-10 col-sm-12 col-xs-12 backsrch">
	<?php if ($loggedingusertype == OWNER && (isset($refcard) && $refcard==TRUE)) { ?>
                            <a class="head" href="<?php echo Url::to(['search/petsitter']) ?>" style="margin:0 0 0 25px;"> < Back to search results</a>
                        <?php } elseif ($loggedingusertype == RENTER && (isset($refcard) && $refcard==TRUE)) { ?>
                            <a class="head" href="<?php echo Url::to(['search/petrenter']) ?>" style="margin:0 0 0 25px;"> < Back to search results</a>
                        <?php } ?>
	<!--a class="head" href="<?php //echo SITE_URL.'bookings/book-now' ?>">< Return to booking</a--></div>
				<div class="formContent" style="margin:0;"> 
					<img class="contact-bg" src="<?php echo WEBSITE_IMAGES_PATH.'contact-bg.png'; ?>" alt="">
					
					
					<div class="col-md-12 col-sm-8 col-xs-12">
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
								<i class="fa fa-check"></i> <?php echo Yii::$app->session->getFlash('item'); ?>
							</div>
						<?php endif; ?>
						<p class="col-md-12">Enter your card details to save it. Your card will not be charged at this stage.</p>
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
						<div class="row-block">
							<div class="col-md-6 col-sm-6 col-xs-12">
								<?php
									echo $form->field($model, 'card_holder_name', ['inputOptions' => [
									'class' => "form-control",
									]])->textInput(['autofocus' => false])->label('Card Holder Name <span class="required">*</span>');
								?>
							</div>
							<!--div class="col-md-6 col-sm-6 col-xs-12">
								<?php
									//echo $form->field($model, 'card_bank_name', ['inputOptions' => [
									//'class' => "form-control",
									//]])->textInput(['autofocus' => false])->label('Card Bank Name <span class="required">*</span>');
								?>
							</div-->							
						</div>						
						<div class="row-block">
							<div class="col-md-6 col-sm-6 col-xs-12">
								<?php
									echo $form->field($model, 'card_number', ['inputOptions' => [
									'class' => "form-control",
									]])->textInput(['maxlength' => 16, 'autofocus' => false])->label('Card Number <span class="required">*</span>');
								?>
							</div>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<?php
									##### select card expiry month
									$items = array(
									'Visa' => 'Visa',
									'MasterCard' => 'Master Card',
									'Discover' => 'Discover',
									'Amex' => 'American Express',
									);
									echo $form->field($model, 'card_type', ['inputOptions' => [
									'class' => "form-control whiteBorder",
									]])->dropDownList($items,['prompt'=>'Select Card Type'])->label('Card Type <span class="required">*</span>');
								?>
							</div>
						</div>
						<div class="row-block">
							<div class="col-md-6 col-sm-6 col-xs-12">
								<?php
									##### select card expiry month
									$selMonth = array();
									for($m=1;$m<=12;$m++)	{
										$selMonth[$m] = $m;
									}
									echo $form->field($model, 'card_exp_month', ['inputOptions' => [
									'class' => "form-control whiteBorder",
									]])->dropDownList($selMonth,['prompt'=>'Select Month'])->label('Card Expiry Month <span class="required">*</span>');
								?>
							</div>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<?php
									##### select card expiry year
									$selYear = array();
									for($y=2018;$y<=2030;$y++)	{
										$selYear[$y] = $y;
									}								
									echo $form->field($model, 'card_exp_year', ['inputOptions' => [
									'class' => "form-control whiteBorder",
									]])->dropDownList($selYear,['prompt'=>'Select Year'])->label('Card Expiry Year <span class="required">*</span>');
								?>
							</div>
						</div>
						
						<div class="col-md-12 col-sm-12 col-xs-12">
							<div class="captcha">
							<?= 
								Html::submitButton(Yii::t('yii','Save'), ['class' => 'orangeBtn', 'name' => 'proceedPayment', 'id' => 'proceedPayment'])
							?>
							</div>
						</div>
						<!--div class="col-md-12 col-sm-12 col-xs-12">
							<div class="captcha">
						
								<a class="orangeBtn button" href="<?php echo SITE_URL.'bookings/book-now' ?>">BOOK NOW</a>

							</div>
						</div-->
						<?php ActiveForm::end(); ?>
						
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
