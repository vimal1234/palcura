<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;

?>
<div class="x_content">
    <p class="mandatory-fields">* All fields are mandatory</p>
    <?php
    if(Yii::$app->controller->action->id == 'update')
        $modelName = 'UpdateCoupon';
    else
        $modelName = 'AddCoupon';

		####################################################= user registration form =####################################################
		$form = ActiveForm::begin(
			['id' => 'form-createuser',
				'options' => ['class' => 'form-horizontal form-label-left', 'enctype' => 'multipart/form-data'],
				'fieldConfig' => [
					'template' => "<div class=\"item form-group\">\n
									{label}\n
									<div class=\"col-md-6 col-sm-6 col-xs-12\">
										{input}
										<div class=\"col-lg-10\">
										{error}
										</div>
									</div>
								</div>",
					'labelOptions' => ['class' => 'control-label col-md-3'],
				],
		]);
		
		################################################## user registration form fields =#################################################
		
		echo $form->field($model, 'coupon_name', ['inputOptions' => [
			'placeholder' => 'Name',
			'class' => "form-control col-md-7 col-xs-12",
		]])->textInput(['maxlength' => 60, 'autofocus' => true])->label('Name <span class="required">*</span>');

		echo $form->field($model, 'coupon_code', ['inputOptions' => [
			'placeholder' => 'code',
			'class' => "form-control col-md-7 col-xs-12",
		]])->textInput(['maxlength' => 60, 'autofocus' => true])->label('Coupon Code <span class="required">*</span>');


		echo $form->field($model, 'coupon_valid_date', ['inputOptions' => [
			'readonly' => true,
			'class' => "date-picker form-control col-md-7 col-xs-12",
		]], ['labelOptions' => ['text' => 'Validity Date']])->textInput()->label('Valid up to <span class="required">*</span>');

		echo $form->field($model, 'coupon_description', ['inputOptions' => [
			'placeholder' => 'Coupon Description',
			'class' => "form-control col-md-7 col-xs-12",
		]])->textarea(['rows' => '6'])->label('Coupon description <span class="required">*</span>');
		
		echo $form->field($model, 'owner_list')->listBox(\yii\helpers\ArrayHelper::map(\backend\models\owners\Owners::find()->all(),'id','firstname'),['multiple' => true]);
		
		echo $form->field($model, 'renter_list')->listBox(\yii\helpers\ArrayHelper::map(\backend\models\renters\Renters::find()->all(),'id','firstname'),['multiple' => true]);

    ?>

    <div class="form-group">
        <div class="col-md-6 col-md-offset-3">
            <button type="button" class="btn btn-primary" onclick="javascript:window.location.href = '<?php echo Url::home(); ?>'">Cancel</button>
            <?= Html::submitButton('Submit', ['class' => 'btn btn-success', 'name' => 'AddCoupon-submit', 'id' => 'AddCoupon-submit']) ?>
        </div>
    </div>

<?php ActiveForm::end(); ?>
</div>
