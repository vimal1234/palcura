<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('yii','Login');
$this->params['breadcrumbs'][] = $this->title;
$siteimage = Yii::getAlias('@siteimage');

$cookies_email = isset($_COOKIE[Yii::getAlias('@site_title')."_user_email"]) ? $_COOKIE[Yii::getAlias('@site_title')."_user_email"] : '';
$cookies_password = isset($_COOKIE[Yii::getAlias('@site_title')."_user_password"]) ? $_COOKIE[Yii::getAlias('@site_title')."_user_password"] : '';
?>
		<div class="row">
			<div class="col-xs-12">
				<h1><?php echo $this->title; ?></h1>
			</div>
		</div>
	</div>
</header>
<section class="contentArea loginPage">
	<div class="container">
		<div class="row">
			<div class="col-md-10 col-sm-10 col-md-offset-1 col-sm-offset-1 col-xs-12">
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
						<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span></button>
						<i class="fa fa-check"></i> <?php echo Yii::$app->session->getFlash('item'); ?>
					</div>
				<?php endif; ?>		  
				<div class="formContentLog">
					<div class="formLogleft">
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
						<div class=\"col-lg-12\">
						{error} {hint}
						</div>",
						'labelOptions' => ['class' => ''],
						],
						]);
					?>
						<div class="form-group">
							<?php
								echo $form->field($model, 'email', ['inputOptions' => [
								'class' => "form-control",
								]])->textInput(['maxlength' => 100, 'autofocus' => true, 'value' => $cookies_email])->label('Email<em>*</em>');
							?>
						</div>
						<div class="form-group">
							<?php
								echo $form->field($model, 'password', ['inputOptions' => [
								'class' => "form-control",
								]])->passwordInput(['maxlength' => 100, 'value' => $cookies_password])->label('Password<em>*</em>');
							?>
						</div>
						<div class="form-group loginCheckbox" style="margin-bottom:0px;">
							<div class="row">
								<div class="col-md-6 col-sm-6 col-xs-6 full">
									<?= $form->field($model, 'rememberMe')->checkbox(['template' => "{input} {label}"]) ?>
								</div>
								<div class="col-md-6 col-sm-6 col-xs-6 full forget">
									<?= Html::a(Yii::t('yii','Forgot password?'), ['site/forgot-password'],['class' => '']) ?>
								</div>
							</div>
						</div>
						<?= Html::submitButton(Yii::t('yii','Login'), ['class' => 'orangeBtn', 'name' => 'login-button']) ?>
					<?php ActiveForm::end(); ?>
					</div>
					<div class="formLogRight"> <img class="log-bg" src="<?php echo $siteimage; ?>/log-bg.png" alt="" >
						<div class="textinfo">
							<h4>Don’t have an account?</h4>
							<?= Html::a(Yii::t('yii','SIGN UP'), ['site/signup'],['class' => 'yellowBtn']) ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
