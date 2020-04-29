<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\AdminLoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
$value = Yii::$app->getRequest()->getCookies()->getValue('userdetails');
?>
	<!-- Bootstrap core CSS -->

	<link href="../themes/gentelella/css/bootstrap.min.css" rel="stylesheet">

	<link href="../themes/gentelella/fonts/css/font-awesome.min.css" rel="stylesheet">
	<link href="../themes/gentelella/css/animate.min.css" rel="stylesheet">

	<!-- Custom styling plus plugins -->
	<link href="../themes/gentelella/css/custom.css" rel="stylesheet">
	<link href="../themes/gentelella/css/icheck/flat/green.css" rel="stylesheet">


	<script src="../themes/gentelella/js/jquery.min.js"></script>
	<script src="../themes/gentelella/js/bootstrap.min.js"></script>

	<!--[if lt IE 9]>
		<script src="../assets/js/ie8-responsive-file-warning.js"></script>
		<![endif]-->

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
		  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->

<body style="background:#F7F7F7;">
    
    <div class="">
        <a class="hiddenanchor" id="toregister"></a>
        <a class="hiddenanchor" id="tologin"></a>

        <div id="wrapper">
            <div id="login" class="animate form">
                <section class="login_content">
						<?php if(Yii::$app->session->getFlash('success')):?>
							<div class="alert alert-success alert-dismissible fade in" role="alert">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
							</button>    
								<?php echo Yii::$app->session->getFlash('success'); ?>
							</div>
						<?php endif; ?>	
						<?php if(Yii::$app->session->getFlash('error')):?>
							<div class="alert alert-success alert-dismissible fade in" role="alert">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
							</button>    
								<?php echo Yii::$app->session->getFlash('error'); ?>
							</div>
						<?php endif; ?>							                    
                       <?php $form = ActiveForm::begin(
								  [ 'id' => 'login-form',
									 'fieldConfig'=>[
										'template'=>"<div>\n{label}\n
													{input}\n<div>
													{error}</div></div>",
										'labelOptions'=>['class'=>'control-label col-md-3'],
									],
								  ]); ?>
                          <!--     <h1><?php //echo Html::encode($this->title) ; ?></h1>  -->
                         <div class="loginImage">    <img src="../themes/gentelella/images/logo.png" /></div>
                       <?php echo $form->field($model, 'username',
									['inputOptions'=>[
									'placeholder'=>'Username',
									'class'=>"form-control",
									'required'=>"required",
									'value'=>(isset($value['user']) ?$value['user']: '') ,
								]])->textInput(['autofocus' => true])->label(false); ?>
								
                       <?php echo $form->field($model, 'password',
									['inputOptions'=>[
									'placeholder'=>'Password',
									'class'=>"form-control",
									'required'=>"required",
									'value'=>(isset($value['pass']) ?$value['pass']: '') ,
								]])->passwordInput()->label(false); ?>
								
                        <?= $form->field($model, 'rememberMe')->checkbox() ?>

						<div class="form-group">
							<?= Html::submitButton('Login', ['class' => 'btn btn-primary submit', 'name' => 'login-button']) ?>
							<!-- <a class="reset_pass" href="#">Lost your password?</a> -->
							<?= Html::a('Lost your password?', ['site/request-password-reset']) ?>
						</div>
                      
                        <div class="clearfix"></div>
                        <div class="separator">

                            <div class="clearfix"></div>
                            <br />
                            <div>
								<p>©2016 All Rights Reserved.</p>
                            </div>
                        </div>
                     <?php ActiveForm::end(); ?>
                    <!-- form -->
                </section>
                <!-- content -->
            </div>
 
        </div>
    </div>

</body>

