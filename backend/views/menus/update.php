<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Menus */

$this->title = 'Update Menus: ' . ' ' . $model->mnuName;
$this->params['breadcrumbs'][] = ['label' => 'Menus', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->mnuName, 'url' => ['view', 'id' => $model->mnuId]];
$this->params['breadcrumbs'][] = 'Update';
?>
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
							  <p>Please fill out the following fields:</p>
						 
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
						 
						  						 
							<?php echo $form->field($model, 'mnuName',['inputOptions'=>[
									'class'=>"form-control col-md-7 col-xs-12",					
									]])->textInput(['autofocus' => true])->label('Name <span class="required">*</span>'); ?>
							<?php echo $form->field($model, 'mnuStatus' , ['inputOptions'=>[
									'class'=>"form-control col-md-7 col-xs-12",
									]])->dropDownList([ 'Active' => 'Active', 'Inactive' => 'Inactive', ]);
							?>

							<div class="form-group">
								<div class="col-md-6 col-md-offset-3">
									<button type="button" class="btn btn-primary" onclick="javascript:window.location.href='<?php echo Url::home()."menus";?>'">Cancel</button>
									<?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
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
