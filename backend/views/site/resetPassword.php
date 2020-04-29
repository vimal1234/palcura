<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ResetPasswordForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Reset password';
$this->params['breadcrumbs'][] = $this->title;
?>
<body style="background:#F7F7F7;">
    
    <div class="">
        <a class="hiddenanchor" id="toregister"></a>
        <a class="hiddenanchor" id="tologin"></a>

        <div id="wrapper">
            <div id="login" class="animate form">
                <section class="login_content">	
					 
					<div class="x_panel">
					<h3>
						<?= Html::encode($this->title) ?>
					</h3>						
						<div class="x_title">
							
			<?php if(Yii::$app->session->getFlash('error')):?>
				<div class="alert alert-success alert-dismissible fade in" role="alert">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
				</button>    
					<?php echo Yii::$app->session->getFlash('error'); ?>
				</div>
			<?php endif; ?>		
						</div>
						<div class="x_content">		
						<?php //$form = ActiveForm::begin(['id' => 'reset-password-form']); ?>
                       <?php $form = ActiveForm::begin(
								  [ 'id' => 'reset-password-form',
									'options'=>['class'=>'form-horizontal form-label-left'],
									 'fieldConfig'=>[
										'template'=>"<div class=\"item form-group\">\n{label}\n<div class=\"col-md-12 col-sm-12 col-xs-12\">
													{input}<div class=\"col-lg-12\">
													{error}</div></div></div>",
										'labelOptions'=>['class'=>'control-label col-md-3'],
									],
								  ]); ?>  						

							<?= $form->field($model, 'password',['inputOptions'=>[
									'placeholder'=>'password',
									'class'=>"form-control col-md-7 col-xs-12",
								]])->passwordInput(['autofocus' => true])->label(false) ?>

							<div class="form-group">
								<div class="col-md-6 col-md-offset-3">
								<?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
                </div>
                </div>
<?php ActiveForm::end(); ?>
                        <div class="clearfix"></div>
                        <div class="separator">

                            <div class="clearfix"></div>
                            <br />
                            <div>
								<p>©<?= date('Y') ?> All Rights Reserved.</p>
                            </div>
                        </div>
                    <!-- form -->
                </section>
                <!-- content -->
            </div>
            </div>
 
        </div>
    </div>
    </div>

</body>
