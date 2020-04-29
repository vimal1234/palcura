<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model app\models\Coupon */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="coupon-form">
	
			<?php 	$form = ActiveForm::begin([
						'options'=>['class'=>'form-horizontal form-label-left'],
					]);
			 ?>

			<?= $form->field($model, 'couponCode', [
									'template'=>"{label}\n<div class=\"col-md-6 col-sm-6 col-xs-12\">
												{input}<ul class='parsley-errors-list filled' id='parsley-id-1727'><li class='rsley-required'>
												{error}</li></ul></div>",
									'labelOptions'=>['class'=>'control-label col-md-3 col-sm-3 col-xs-12'],
								])->textInput(['maxlength' => true])->label('Coupon Code <span class="required">*</span>')
			?>
			
		<!--<div class="form-group">
			 <label for="coupon-couponcode" class="control-label col-md-3 col-sm-3 col-xs-12"></label>
			 <div class="col-md-6 col-sm-6 col-xs-12">
		  <input type="checkbox" id="showCodeAttr" value="autoGenerate" name="Coupon[code]">Check to auto generate coupon code <br>
			 </div>
		  </div> -->
		  
		  <div class="form-group field-coupon-couponcode">
			  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="coupon-couponcode"></label>
			  <div class="col-md-6 col-sm-6 col-xs-12">
				  <input type="checkbox" id="showCodeAttr" value="autoGenerate" name="Coupon[code]">  Check to auto generate coupon code
			  </div>
		  </div>	
		  
		 <?= $form->field($model, 'description' , [
									'template'=>"{label}\n<div class=\"col-md-6 col-sm-6 col-xs-12\">
												{input}<ul class='parsley-errors-list filled' id='parsley-id-1727'><li class='rsley-required'>
												{error}</li></ul></div>",
									'labelOptions'=>['class'=>'control-label col-md-3 col-sm-3 col-xs-12'],
								])->textArea(['maxlength' => true])->label('Description <span class="required">*</span>') ?>
      
           <?=            $form->field($model, 'discountType' , [
								'template'=>"{label}\n<div class=\"col-md-6 col-sm-6 col-xs-12\">
											{input}<ul class='parsley-errors-list filled' id='parsley-id-1727'><li class='rsley-required'>
											{error}</li></ul></div>",
								'labelOptions'=>['class'=>'control-label col-md-3 col-sm-3 col-xs-12'],
							])->radioList(['percentage' => 'Percentage', 'fixed' => 'Fixed'])->label('Discount Type <span class="required">*</span>') ?>


		   <?= $form->field($model, 'discount' , [
								'template'=>"{label}\n<div class=\"col-md-6 col-sm-6 col-xs-12\">
											{input}<ul class='parsley-errors-list filled' id='parsley-id-1727'><li class='rsley-required'>
											{error}</li></ul></div>",
								'labelOptions'=>['class'=>'control-label col-md-3 col-sm-3 col-xs-12'],
							])->textInput()->label('Discount <span class="required">*</span>') ?>


		  <?= $form->field($model, 'validFrom' , [
								'template'=>"{label}\n<div class=\"col-md-6 col-sm-6 col-xs-12\">
											{input}<ul class='parsley-errors-list filled' id='parsley-id-1727'><li class='rsley-required'>
											{error}</li></ul></div>",
								'labelOptions'=>['class'=>'control-label col-md-3 col-sm-3 col-xs-12'],
							])->textInput(['class' => 'form-control date-picker','readonly' => true])->label('Valid From <span class="required">*</span>') ?>
		 <?= $form->field($model, 'validTill' , [
								'template'=>"{label}\n<div class=\"col-md-6 col-sm-6 col-xs-12\">
											{input}<ul class='parsley-errors-list filled' id='parsley-id-1727'><li class='rsley-required'>
											{error}</li></ul></div>",
								'labelOptions'=>['class'=>'control-label col-md-3 col-sm-3 col-xs-12'],
							])->textInput(['class' => 'form-control date-picker','readonly' => true])->label('Valid Till <span class="required">*</span>') ?>
		
		<div class="form-group">
			<div class="col-md-6 col-md-offset-3">
				<button type="button" class="btn btn-primary" onclick="javascript:window.location.href='<?php echo Url::home()."coupon";?>'">Cancel</button>
				<?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
			</div>
		</div>		

   <?php ActiveForm::end(); ?>

</div>
<!-- daterangepicker -->
	<script type="text/javascript" src="<?php echo Url::home(); ?>themes/gentelella/js/moment.min2.js"></script>
	<script type="text/javascript" src="<?php echo Url::Home(); ?>themes/gentelella/js/datepicker/daterangepicker.js"></script>
    <script type="text/javascript">
	 $(document).ready(function () {
				$('.date-picker').daterangepicker({
						format: 'YYYY-MM-DD',
						singleDatePicker: true,
						 showDropdowns: true,
						calender_style: "picker_4"					
				});
	});
	</script>
	
