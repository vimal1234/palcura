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
		
		echo $form->field($model, 'title', ['inputOptions' => [
				'placeholder' => 'Title',
				'class' => "form-control col-md-7 col-xs-12",
		]])->textInput(['autofocus' => true, 'readonly' => true])->label('Title <span class="required">*</span>');
		
		$verify_status     = Yii::$app->commonmethod->verifyStatus();
		echo $form->field($model, 'verified_by_admin')->dropDownList($verify_status,['prompt'=>'Approve Status'])->label('Admin Approval <span class="required">*</span>');
			
		$plenty_amount = array("1" => "Penalty Required","2" => "Penalty Not Required");
		echo $form->field($model, 'paid_charges')->dropDownList($plenty_amount,['prompt'=>'User Penalty Status'])->label('Penalty Status <span class="required">*</span>');

		echo $form->field($model, 'admin_comment', ['inputOptions' => [
				'placeholder' => 'Comment',
				'class' => "form-control col-md-7 col-xs-12",
		]])->textarea(['rows' => 5,'autofocus' => true])->label('Comment <span class="required">*</span>');
    ?>
    <div class="form-group">
        <div class="col-md-6 col-md-offset-3">
            <button type="button" class="btn btn-primary" onclick="window.history.go(-1); return false;">Cancel</button>
            <?= Html::submitButton('Submit', ['class' => 'btn btn-success', 'name' => 'AddBanner-submit', 'id' => 'AddBanner-submit']) ?>
        </div>
    </div>
<?php ActiveForm::end(); ?>
</div>
