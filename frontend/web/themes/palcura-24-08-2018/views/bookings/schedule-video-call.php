<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('yii','Schedule A Video Call');
$session = Yii::$app->session;
$loggedingusertype = $session->get('loggedinusertype');
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
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
			<div class="backsrch">
                        <?php if ($loggedingusertype == OWNER) { ?>
                            <a class="head" href="<?php echo Url::to(['search/petsitter']) ?>" style="margin:0 0 0 25px;"> < Back to search results</a>
                        <?php } elseif ($loggedingusertype == RENTER) { ?>
                            <a class="head" href="<?php echo Url::to(['search/petrenter']) ?>" style="margin:0 0 0 25px;"> < Back to search results</a>
                        <?php } ?>

                    </div>
                     <div id="vidcallbook" style="visibility:hidden;"></div>
				<div class="formContent" style="margin:0;"> 
					<img class="contact-bg" src="<?php echo WEBSITE_IMAGES_PATH.'contact-bg.png'; ?>" alt="">
					<div class="col-md-4 col-sm-4 col-xs-12 pull-right">
						<div class="formContentSidebar"></div>
					</div>
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
						<?php if (Yii::$app->session->getFlash('error')): ?>
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
								<i class="fa fa-remove"></i> <?php echo Yii::$app->session->getFlash('error'); ?>
							</div>
						<?php endif; ?>
						<?php
						$form = ActiveForm::begin([
						'id' => 'video-form',
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
							<!--div class="col-md-6 col-sm-6 col-xs-12">
								<?php
									//echo $form->field($model, 'name', ['inputOptions' => [
									//'class' => "form-control",
									//]])->textInput(['autofocus' => false])->label('Title *');
								?>
							</div-->
							<div class="col-md-6 col-sm-6 col-xs-12">
								<?php
									echo $form->field($model, 'schedule_datetime', ['inputOptions' => [
									'class' => "form-control",
									]])->textInput(['class' => 'datepicker', 'readOnly' => true, 'autofocus' => false])->label('Schedule date*');
								?>
							</div>
						</div>
							<div class="row-block">
								<div class="col-md-6 col-sm-6 col-xs-12">
									<div class="form-group">			 
							  <?php
								echo $form->field($model, 'start_time', ['inputOptions' => [
								'class' => "form-control customwidth",
								'id' => "datetimepicker3",
								]])->textInput()->label('Scheduled Time <span class="required">*</span>');
							   ?>					   			
									</div>
							  	</div>
						   </div>
						<div class="row-block">
							<div class="col-md-12 col-sm-6 col-xs-12">
								<?php
									echo $form->field($model, 'description', ['inputOptions' => [
									'class' => "form-control textfeild",'id' => "search_destination1"
									]])->textarea(['rows' => '6', 'maxlength' => 250, 'autofocus' => false])->label('Description <span>*</span>');
								?>
							</div>
						</div>
						<div class="col-md-12 col-sm-12 col-xs-12">
							<div class="captcha">
							<?= 
								Html::submitButton(Yii::t('yii','Request'), ['class' => 'orangeBtn', 'name' => 'proceedPayment', 'id' => 'proceedPayment'])
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
$(function() {
	$("#videoconversation-schedule_datetime").datepicker({
		numberOfMonths: 1,
		showButtonPanel: true,
		minDate: 0,
		maxDate: '+1Y',	
	});
});
</script>	
<script>
$('document').ready(function(){
//time picker  
$('#datetimepicker3').datetimepicker({
                    format: 'LT'
                });

});
</script>
<script>
$('document').ready(function(){
  var desiredHeight = $(window).height() -600;	
			$('html, body').animate({
							scrollTop: $('#vidcallbook').offset().top-desiredHeight
						}, 'slow');		
    	});
</script>
