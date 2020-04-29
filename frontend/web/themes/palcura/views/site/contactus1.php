<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
/* @var $this yii\web\View */
//$this->title = 'Contact Us!';
$siteimage = Yii::getAlias('@siteimage');
?>
    <div class="row">
      <div class="col-xs-12">
        <h1>Request Palcura Near Me</h1>
      </div>
    </div>
  </div>
</header>
<!-- END HEADER -->

<section class="contentArea">
  <div class="container">
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <h2 class="text-center contactTilte">We’d love to hear from you</h2>
        <p class="Highlighted text-center">Let us know where you’d like to provide or receive loving pet care</p>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="formContent"> <img class="contact-bg" src="<?= $siteimage ?>/contact-bg.png" alt="">
          
          <div class="col-md-12 col-sm-12 col-xs-12">
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
			<div class="row-block">
              <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                
                   <?php echo $form->field($model, 'name')->textInput()->label('Name') ?>
                </div>
              </div>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
              
                  <?php echo $form->field($model, 'email')->textInput(['type'=>'email'])->label('Email address<span>*</span>') ?>
                </div>
              </div>
             </div>
             <div class="row-block">
              <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                  <?php echo $form->field($model, 'zip_code')->textInput()->label('Zip Code<span>*</span>') ?>
                </div>
              </div>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="form-group"> 
                  <?php
                  //$subject = array(1=>'1',2=>'2',3=>'3',4=>'4');
                  $subject = Yii::$app->commonmethod->getFormType1();
					echo $form->field($model, 'subject', ['inputOptions' => [
					'class' => "form-control whiteBorder",
					]])->dropDownList($subject,['prompt'=>'Select'])->label('I am a <span class="required">*</span>');
				?>
                </div>
              </div>
            </div>
            <div class="row-block">
				<div class="col-md-12 col-sm-6 col-xs-12">
					<div class="form-group loginCheckbox accterms">
						<?php
							$linkHtml = '<span>I’d like to receive occasional pet-related articles and newsletters from Palcura.</span>';
							echo $form->field($model, 'accept_terms', ['inputOptions' => [
							'class' => "form-control", 
							
							],
							'template' => "{input}\n$linkHtml {error}",
							]
							)->checkbox([], false);
						?>
					</div>
				</div>
			</div>
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="captcha">
					<?php //echo $form->field($model, 'reCaptcha')->widget(\yii\recaptcha\ReCaptcha::className(),['siteKey' => SITE_KEY])->label(false); ?>
                  <div class="btnSubmit">
                    <!--button type="submit" class="orangeBtn">Submit</button-->
                    <?= Html::submitButton('Submit', ['class' => 'orangeBtn']) ?>
                  </div>
                </div>
              </div>
            <?php ActiveForm::end() ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

