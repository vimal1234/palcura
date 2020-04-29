<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'General Settings';
?>
 <!-- page content -->
            <div class="right_col" role="main">

                <div class="">
                    <div class="page-title">
                        <div class="title_left">
                            <h3>Update Settings</h3>
						</div>
					</div>
                    <div class="clearfix"></div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
							 
                            <div class="x_panel">
                                <div class="x_title">
								<?php 
								$respmesg = isset($data['respmesg'])? $data['respmesg']:Yii::$app->session->getFlash('respmesg'); 
								$mesgClass = isset($data['class'])? $data['class']:Yii::$app->session->getFlash('class'); 
								
								if(isset($respmesg) && $respmesg!='') {
									?>	
								<div class="alert <?=$mesgClass?> alert-dismissible fade in" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                                    </button>
                                    <?php echo $respmesg;?>
                                </div>
                                 <?php } ?> 
                                   
                                </div>
                                <div class="x_content">							
								  <?php $form = ActiveForm::begin(
								  [ 'id' => 'changepassword-form',
									'options'=>['class'=>'form-horizontal form-label-left'],
									 'fieldConfig'=>[
										'template'=>"<div class=\"item form-group\">\n{label}\n<div class=\"col-md-6 col-sm-6 col-xs-12\">
													{input}<div class=\"col-lg-10\">
													{error}</div></div></div>",
										'labelOptions'=>['class'=>'control-label col-md-3'],
									],
								  ]); ?>
								  
									<?php echo $form->field($model, 'email',['inputOptions'=>[
											'placeholder'=>'Email',
											'class'=>"form-control col-md-7 col-xs-12",
											//'required'=>"required",
											'type'=>"email",
											'value'=>Yii::$app->user->identity->email,
										]])->textInput(['autofocus' => true]) ?>
										
									<?php 
									echo $form->field($model, 'phone_number',['inputOptions'=>[
											'placeholder'=>'+1',
											'class'=>"form-control col-md-7 col-xs-12",
											'value'=>Yii::$app->user->identity->phone_number,
										]])->textInput()->label('Phone Number'); 
										?>
										
									<?php 
									echo $form->field($model, 'whatsapp_number',['inputOptions'=>[
											'placeholder'=>'+1',
											'class'=>"form-control col-md-7 col-xs-12",
											'value'=>Yii::$app->user->identity->whatsapp_number,
										]])->textInput()->label('Whatsapp Number'); 
										?>										
										
									<?php echo $form->field($model, 'old_password',['inputOptions'=>[
											'placeholder'=>'Old Password',
											'data-validate-length'=>"6,7,8",
											'class'=>"form-control col-md-7 col-xs-12",
										]])->passwordInput() ?>
									
									<?php echo $form->field($model, 'new_password',['inputOptions'=>[
											'placeholder'=>'New Password',
											'data-validate-length-range'=>"6,8",
											'class'=>"form-control col-md-7 col-xs-12",
										]])->passwordInput() ?>
									
									<?php echo $form->field($model, 'repeat_password',['inputOptions'=>[
											'placeholder'=>'Repeat Password',
											'data-validate-length-range'=>"6,8",
											'data-validate-linked'=>"new_password",
											'class'=>"form-control col-md-7 col-xs-12",
										]])->passwordInput() ?>
									
									
									<div class="form-group">
										<div class="col-md-6 col-md-offset-3">
											<button type="button" class="btn btn-primary" onclick="javascript:window.location.href='<?php echo Url::home();?>'">Cancel</button>
											<?= Html::submitButton('Submit', ['class' => 'btn btn-success', 'name' => 'changePass-button']) ?>
										</div>
									</div>
									

								<?php ActiveForm::end(); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
              <!-- footer content -->
				<?php echo $this->render('../includes/footer'); ?>
              <!-- /footer content -->
               
            </div>
            <!-- /page content -->
        </div>
  
