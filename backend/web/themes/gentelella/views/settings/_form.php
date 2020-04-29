<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;

?>
<div class="x_content">
    <p class="mandatory-fields">* All fields are mandatory</p>
    <?php
		$form = ActiveForm::begin(
						['id' => 'form-createuser',
							'options' => ['class' => 'form-horizontal form-label-left', 'enctype' => 'multipart/form-data'],
							'fieldConfig' => [
								'template' => "<div class=\"item form-group\">\n{label}\n<div class=\"col-md-6 col-sm-6 col-xs-12\">
								{input}<div class=\"col-lg-10\">
								{error}</div></div></div>",
								'labelOptions' => ['class' => 'control-label col-md-3'],
							],
		]);

		echo $form->field($model, 'website_fee', ['inputOptions' => [
				'placeholder' => 'Website fee',
				'class' => "form-control col-md-7 col-xs-12",
		]])->textInput(['autofocus' => true, 'value' => $data['website_fee'] ,'maxlength' => 4])->label('Website fee <span class="required">* (%)</span>');
		
		echo $form->field($model, 'family_member_discount', ['inputOptions' => [
				'placeholder' => 'Family member discounts',
				'class' => "form-control col-md-7 col-xs-12",
		]])->textInput(['autofocus' => true, 'value' => $data['family_member_discount'] ,'maxlength' => 4])->label('Family member discounts <span class="required">* (%)</span>');
		
		/*echo $form->field($model, 'discount', ['inputOptions' => [
				'placeholder' => 'discount',
				'class' => "form-control col-md-7 col-xs-12",
		]])->textInput(['autofocus' => true , 'value' => $data['discount'] ,'maxlength' => 4])->label('Website discount <span class="required">* (%)</span>');*/				

		echo $form->field($model, 'google_analytics', ['inputOptions' => [
				'placeholder' => 'Google analytics code',
				'class' => "form-control col-md-7 col-xs-12",
		]])->textarea(['rows' => 5, 'autofocus' => true, 'value' => $data['google_analytics']])->label('Google analytics <span class="required"></span>');

 echo $form->field($model, 'facebook_pixel', ['inputOptions' => [
				'placeholder' => 'Facebook pixel code',
				'class' => "form-control col-md-7 col-xs-12",
		]])->textarea(['rows' => 5, 'autofocus' => true, 'value' => $data['facebook_pixel']])->label('Facebook Pixel <span class="required"></span>');
    ?>
    ?>
    <div class="form-group">
        <div class="col-md-6 col-md-offset-3">
            <button type="button" class="btn btn-primary" onclick="window.history.go(-1); return false;">Cancel</button>
            <?= Html::submitButton('Submit', ['class' => 'btn btn-success', 'name' => 'AddBanner-submit', 'id' => 'AddBanner-submit']) ?>
        </div>
    </div>
<?php ActiveForm::end(); ?>
</div>
