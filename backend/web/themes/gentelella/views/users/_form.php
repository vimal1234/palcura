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
        $modelName = 'UpdateUser';
    else
        $modelName = 'AddUser';

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
		echo $form->field($modelImageUpload, 'profile_image')->fileInput()->label('Profile Image');

		echo $form->field($model, 'firstname', ['inputOptions' => [
			'placeholder' => 'First name',
			'class' => "form-control col-md-7 col-xs-12",
		]])->textInput(['maxlength' => 60, 'autofocus' => true])->label('First Name <span class="required">*</span>');

		echo $form->field($model, 'lastname', ['inputOptions' => [
			'placeholder' => 'Last name',
			'class' => "form-control col-md-7 col-xs-12",
		]])->textInput(['maxlength' => 60, 'autofocus' => true])->label('Last Name <span class="required">*</span>');

		echo $form->field($model, 'email', [
		'enableAjaxValidation' => true,
		'inputOptions' => [
			'placeholder' => 'Email',
			'class' => "form-control col-md-7 col-xs-12",
			'type' => "email",
		]])->textInput(['maxlength' => 100, 'autofocus' => true])->label('Email <span class="required">*</span>');
				
		echo $form->field($model, 'phone', ['inputOptions' => [
			'placeholder' => 'Phone',
			'class' => "form-control col-md-7 col-xs-12",
		]])->textInput(['maxlength' => 15, 'autofocus' => true])->label('Phone <span class="required">*</span>');

		echo $form->field($model, 'password', ['inputOptions' => [
			'placeholder' => 'Password',
			'data-validate-length-range' => "6,8",
			'class' => "form-control col-md-7 col-xs-12",
		]])->passwordInput(['maxlength' => 10])->label('Password <span class="required">*</span>');

		echo $form->field($model, 'repeat_password', ['inputOptions' => [
			'placeholder' => 'Confirm Password',
			'class' => "form-control col-md-7 col-xs-12",
		]])->passwordInput(['maxlength' => 10])->label('Confirm Password <span class="required">*</span>');

		$items = array('Male' => 'Male', 'Female' => 'Female');
		echo $form->field($model, 'gender', ['inputOptions' => [
        'class' => "form-control col-md-7 col-xs-12",
		]])->radioList($items, [
            'item' => function($index, $label, $name, $checked, $value) {
                $checked = ($checked) ? 'checked' : '';

                $return = '<label class="radio-inline">';
                $return .= '<input type="radio" name="' . $name . '" value="' . $value . '" ' . $checked . ' >';
                $return .= '<span> ' . ucwords($label) . '</span>';
                $return .= '</label>';

                return $return;
            }
        ])->label('Gender <span class="required">*</span>');

		echo $form->field($model, 'dob', ['inputOptions' => [
			'placeholder' => 'D.O.B.',
			'readonly' => true,
			'class' => "date-picker form-control col-md-7 col-xs-12",
		]], ['labelOptions' => ['text' => 'Date Of Birth']])->textInput()->label('Date Of Birth <span class="required">*</span>');

		echo $form->field($model, 'residency_status', ['inputOptions' => [
			'placeholder' => 'Residency Status',
			'class' => "form-control col-md-7 col-xs-12",
		]])->textInput(['maxlength' => 100])->label('Residency <span class="required">*</span>');

			 $countries = Yii::$app->commonmethod->countries();
		echo $form->field($model, 'country')->dropDownList($countries,['prompt'=>'Select Country'])->label('Country <span class="required">*</span>');

			 $states = Yii::$app->commonmethod->regions();
		echo $form->field($model, 'region')->dropDownList($states,['prompt'=>'Select Region'])->label('Region <span class="required">*</span>');

			 $cities = Yii::$app->commonmethod->cities();
		echo $form->field($model, 'city')->dropDownList($cities,['prompt'=>'Select City'])->label('City <span class="required">*</span>');

			 $nationality = Yii::$app->commonmethod->countries();
		echo $form->field($model, 'nationality')->dropDownList($nationality,['prompt'=>'Select Nationality'])->label('Nationality <span class="required">*</span>');

			 $usertype = Yii::$app->commonmethod->userTypes();
		echo $form->field($model, 'user_type')->dropDownList($usertype,['prompt'=>'User Type'])->label('User Type <span class="required">*</span>');

    ?>

    <div class="form-group">
        <div class="col-md-6 col-md-offset-3">
            <button type="button" class="btn btn-primary" onclick="javascript:window.location.href = '<?php echo Url::home(); ?>'">Cancel</button>
            <?= Html::submitButton('Submit', ['class' => 'btn btn-success', 'name' => 'AddUser-submit', 'id' => 'AddUser-submit']) ?>
        </div>
    </div>

<?php ActiveForm::end(); ?>
</div>
