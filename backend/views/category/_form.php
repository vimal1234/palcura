<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\category\Category;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model backend\models\Category */
/* @var $form yii\widgets\ActiveForm */
?>

	  <?php $form = ActiveForm::begin(
	  [ 'id' => 'form-createcategory',
		'options'=>['class'=>'form-horizontal form-label-left'],
		 'fieldConfig'=>[
			'template'=>"<div class=\"item form-group\">\n{label}\n<div class=\"col-md-6 col-sm-6 col-xs-12\">
						{input}<div class=\"col-lg-10\">
						{error}</div></div></div>",
			'labelOptions'=>['class'=>'control-label col-md-3'],
		],
	  ]); ?>

	 <?php echo $form->field($model, 'category_name',['inputOptions'=>[
			'class'=>"form-control col-md-7 col-xs-12",					
			]])->textInput(['autofocus' => true,'maxlength' => true])->textInput()->label('Category Name <span class="required">*</span>'); 
	 ?>
	
	<?php echo $form->field($model, 'parent_id' , ['inputOptions'=>['class'=>"form-control col-md-7 col-xs-12",]])->dropDownList(
				ArrayHelper :: map(category ::find()->where(['parent_id' =>0])->all(), 'category_id','category_name'), 
				['prompt' => 'Select Category'])->label('Parent Name');
	?>	

	<?php echo $form->field($model, 'category_status',['inputOptions'=>['class'=>"form-control col-md-7 col-xs-12"]])->dropDownList([ 'active' => 'Active', 'inactive' => 'Inactive', ], ['prompt' => 'Select Status'])->label('Category Status <span class="required">*</span>'); ?>

	<div class="form-group">
		<div class="col-md-6 col-md-offset-3">
			<button type="button" class="btn btn-primary" onclick="javascript:window.location.href='<?php echo Url::home()."category";?>'">Cancel</button>
			<?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
		</div>
	</div>

    <?php ActiveForm::end(); ?>
