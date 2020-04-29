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
        <h1>Contact us</h1>
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
        <p class="Highlighted text-center">Send us a message and you will hear back from us in one business day</p>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="formContent"> <img class="contact-bg" src="<?= $siteimage ?>/contact-bg.png" alt="">
          <div class="col-md-4 col-sm-4 col-xs-12 pull-right">
            <div class="formContentSidebar">
              <h4>PalCura Contact Details</h4>
              <ul>
                <li><i class="fa fa-envelope-o" aria-hidden="true"></i><a href="mailto:hello@palcura.com">hello@palcura.com</a></li>
                <li><i class="fa fa-map-marker" aria-hidden="true"></i>
                  <p>Boca Raton,FL,<br/>
                    32004</p>
                </li>
              </ul>
            </div>
          </div>
          <div class="col-md-8 col-sm-8 col-xs-12">
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
                
                   <?php echo $form->field($model, 'name')->textInput()->label('Name<span>*</span>') ?>
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
                  <label>Phone No.(Optional)</label>
                  <?php echo $form->field($model, 'phone')->textInput()->label('') ?>
                </div>
              </div>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="form-group"> 
                  <?php
                  //$subject = array(1=>'1',2=>'2',3=>'3',4=>'4');
                  $subject = Yii::$app->commonmethod->getFormType();
					echo $form->field($model, 'subject', ['inputOptions' => [
					'class' => "form-control whiteBorder",
					]])->dropDownList($subject,['prompt'=>'Select'])->label('Subject <span class="required">*</span>');
				?>
                </div>
              </div>
              </div>
            <div class="row-block">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="form-group">
                  <!--label for="comment">Message *</label>
                  <textarea class="form-control" rows="5" id="comment"></textarea-->
                   <?php
						echo $form->field($model, 'description', ['inputOptions' => [
						'class' => "form-control textfeild",
						]])->textarea(['rows' => '4', 'maxlength' => 250, 'autofocus' => false])->label('Message <span>*</span>');
					 ?>
                </div>
              </div>
              </div>
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="captcha">
					<?php echo $form->field($model, 'reCaptcha')->widget(\yii\recaptcha\ReCaptcha::className(),['siteKey' => SITE_KEY])->label(false); ?>
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

