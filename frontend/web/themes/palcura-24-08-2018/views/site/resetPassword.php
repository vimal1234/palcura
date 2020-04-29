<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('yii','Reset Password');
$this->params['breadcrumbs'][] = $this->title;
$siteimage = Yii::getAlias('@siteimage');
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
                                <?php $form = ActiveForm::begin(['id' => 'reset-password-form','options' => [
									'class' => 'inner', 
									]]); ?>
              <div class="form-group">
               <?= $form->field($model, 'password')->passwordInput(['maxlength'=>20 , 'autofocus' => true]) ?>
              </div>
              
                            <div class="form-group">
                <?= $form->field($model, 'repeat_password')->passwordInput(['maxlength'=>20 , 'autofocus' => true]) ?>
              </div>


                                  <?= Html::submitButton(Yii::t('yii','Save'), ['class' => 'orangeBtn']) ?>

                                <?php ActiveForm::end(); ?>
                                </div>
          <div class="formLogRight"> <img class="log-bg" src="<?php echo $siteimage; ?>/log-bg.png" alt="" >
          
           <div class="textinfo"> <h4>PalCura</h4>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>


