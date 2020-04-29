<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \backend\models\AddUserForm */

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Add User';
//$this->params['breadcrumbs'][] = $this->title;
?>

 <!-- page content -->
	<div class="right_col" role="main">

		<div class="">
			<div class="page-title">
				<div class="title_left">
					<h3>
		   <?= Html::encode($this->title) ?>
		</h3>
		 </div>
		   </div>
			<div class="clearfix"></div>

			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
					 
					<div class="x_panel">
						<div class="x_title">
						<?php if(isset($data['respmesg'])) {?>	
						<div class="alert <?=$data['class']?> alert-dismissible fade in" role="alert">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
							</button>
							<?php echo $data['respmesg'];?>
						</div>
						 <?php } ?>  
						   
						</div>
						<div class="x_content">
							  <p>All fields are mandatory:</p>
						 
						  <?php $form = ActiveForm::begin(
						  [ 'id' => 'form-createuser',
							'options'=>['class'=>'form-horizontal form-label-left'],
							 'fieldConfig'=>[
								'template'=>"<div class=\"item form-group\">\n{label}\n<div class=\"col-md-6 col-sm-6 col-xs-12\">
											{input}<div class=\"col-lg-10\">
											{error}</div></div></div>",
								'labelOptions'=>['class'=>'control-label col-md-3'],
							],
						  ]); ?>
						 
							<?php echo $form->field($model, 'fullname',['inputOptions'=>[
									'placeholder'=>'both name(s) e.g Jon Doe',
									'class'=>"form-control col-md-7 col-xs-12",
									]])->textInput(['autofocus' => true]) ->label('Full Name <span class="required">*</span>'); ?>
									
							<?php echo $form->field($model, 'username',['inputOptions'=>[
									'placeholder'=>'Username',
									'class'=>"form-control col-md-7 col-xs-12",
									'data-validate-length-range'=>"5,10"
									]])->textInput()->label('Username <span class="required">*</span>') ; ?>
								
							<?php echo $form->field($model, 'email',['inputOptions'=>[
									'placeholder'=>'Email',
									'class'=>"form-control col-md-7 col-xs-12",
									'type'=>"email",
								]])->textInput()->label('Email <span class="required">*</span>') ; ?>
								
							<?php echo $form->field($model, 'password',['inputOptions'=>[
									'placeholder'=>'Password',
									'data-validate-length-range'=>"6,8",
									'class'=>"form-control col-md-7 col-xs-12",
								]])->passwordInput()->label('Password <span class="required">*</span>') ; ?>
							
							<?php echo $form->field($model, 'repeat_password',['inputOptions'=>[
											'placeholder'=>'Repeat Password',
											'class'=>"form-control col-md-7 col-xs-12",
										]])->passwordInput()->label('Repeat Password <span class="required">*</span>') ; ?>
										
							<?php 	$items = array('M'=>'Male','F'=>'Female');
									echo $form->field($model, 'gender',['inputOptions'=>[
									'class'=>"form-control col-md-7 col-xs-12",
									]])->inline()->radioList($items)->label('Gender <span class="required">*</span>') ;
									?>
									
							<?php echo $form->field($model, 'dob',['inputOptions'=>[
									'placeholder'=>'D.O.B.',
									'readonly' => true ,
									'class'=>"date-picker form-control col-md-7 col-xs-12",
									]],['labelOptions'=>['text'=>'Date Of Birth']])->textInput()->label('Date Of Birth <span class="required">*</span>'); ?>	
									
							<div class="form-group">
								<div class="col-md-6 col-md-offset-3">
									<button type="button" class="btn btn-primary" onclick="javascript:window.location.href='<?php echo Url::home();?>'">Cancel</button>
									<?= Html::submitButton('Submit', ['class' => 'btn btn-success', 'name' => 'AddUser-submit', 'id' => 'AddUser-submit']) ?>
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

 <!-- daterangepicker -->
	<script type="text/javascript" src="<?php echo Url::Home(); ?>/themes/gentelella/js/moment.min2.js"></script>
	<script type="text/javascript" src="<?php echo Url::Home(); ?>/themes/gentelella/js/datepicker/daterangepicker.js"></script>
        
    <script type="text/javascript">
		
		 $(document).ready(function () {
			
					$('.date-picker').daterangepicker({
						maxDate: new Date() ,
						singleDatePicker: true,
						showDropdowns: true,
						calender_style: "picker_4"
					});
				
		});
		
		$('#form-createuser').on('submit',function(){
			
			if ($('#form-createuser').find('.has-error').length) {
				// error
				$('#AddUser-submit').removeAttr('disabled');
			}else{
				$('#AddUser-submit').attr('disabled','disabled');
			}
			
		});
		

    </script>
    
