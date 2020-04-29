<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
$CtrlName	= Yii::$app->controller->id;
$funName	= Yii::$app->controller->action->id;
$ajaxSUrl	= SITE_URL.'admin/common/updatestates';
$ajaxCUrl	= SITE_URL.'admin/common/updatecities';
?>
<div class="x_content">
    <p class="mandatory-fields">* All fields are mandatory</p>
    <?php
		if($funName == 'update') {
			$modelName 	= 'UpdateSitter';
			$modelID 	= 'updatesitter';
		} else {
			$modelName 	= 'AddSitter';
			$modelID 	= 'addsitter';
		}
		#### active form
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

		#### profile image
		echo $form->field($modelImageUpload['modelImgA'], 'profile_image')->fileInput()->label('Profile Image');
		
		#### ID documents
		echo $form->field($modelImageUpload['modelImgB'], 'upload_documents[]')->fileInput(['multiple' => true,"accept"=>".docx, .txt"])->label('ID Documents');
		
		#### awaited profile pictures
		echo $form->field($modelImageUpload['modelImgC'], 'upload_images[]')->fileInput(['multiple' => true,"accept"=>"image/*"])->label('Pictures');		

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

		echo $form->field($model, 'day_price', ['inputOptions' => [
			'placeholder' => '$0.00',
			'class' => "form-control col-md-7 col-xs-12",
		]])->textInput(['maxlength' => 10, 'autofocus' => true])->label('Day Price <span class="required">*</span>');

		##### Services
			 $servicesTypes = Yii::$app->commonmethod->servicesTypes();
		echo $form->field($model, 'services_types')->dropDownList($servicesTypes,['prompt'=>'Select Services'])->label('Services <span class="required">*</span>');
				
		##### Coutries
			 $countries = Yii::$app->commonmethod->countries();
		echo $form->field($model, 'country')->dropDownList($countries,['prompt'=>'Select Country'])->label('Country <span class="required">*</span>');

		##### regions
			 $states = Yii::$app->commonmethod->regions();
		echo $form->field($model, 'region')->dropDownList($states,['prompt'=>'Select Region'])->label('Region <span class="required">*</span>');

		##### cities
			 //$cities = Yii::$app->commonmethod->cities();
			 $cities = array();
		echo $form->field($model, 'city')->dropDownList($cities,['prompt'=>'Select City'])->label('City <span class="required">*</span>');

		##### residential status
			 $cities = Yii::$app->commonmethod->residencelists();
		echo $form->field($model, 'residential_status')->dropDownList($cities,['prompt'=>'Residential status'])->label('Residential status <span class="required">*</span>');

		##### house size
			 $cities = Yii::$app->commonmethod->getHouseSize();
		echo $form->field($model, 'house_size')->dropDownList($cities,['prompt'=>'Household size'])->label('Household size <span class="required">*</span>');

		##### children
			 $cities = Yii::$app->commonmethod->getChildrenCount();
		echo $form->field($model, 'children')->dropDownList($cities,['prompt'=>'Children'])->label('Children <span class="required">*</span>');

		##### income
			 $cities = Yii::$app->commonmethod->getIncome();
		echo $form->field($model, 'income')->dropDownList($cities,['prompt'=>'Income'])->label('Income <span class="required">*</span>');

		##### number of pets
			 $cities = Yii::$app->commonmethod->getNumberOfPets();
		echo $form->field($model, 'number_of_pets')->dropDownList($cities,['prompt'=>'No. of pets'])->label('No. of pets <span class="required">*</span>');
    ?>

    <div class="form-group">
        <div class="col-md-6 col-md-offset-3">
            <button type="button" class="btn btn-primary" onclick="javascript:window.location.href = '<?php echo Url::home(); ?>'">Cancel</button>
            <?= Html::submitButton('Submit', ['class' => 'btn btn-success', 'name' => 'AddUser-submit', 'id' => 'AddUser-submit']) ?>
        </div>
    </div>

<?php ActiveForm::end(); ?>
</div>

<!-- daterangepicker -->
<script type="text/javascript" src="<?php echo Url::Home(); ?>/themes/gentelella/js/moment.min2.js"></script>
<script type="text/javascript" src="<?php echo Url::Home(); ?>/themes/gentelella/js/datepicker/daterangepicker.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('.date-picker').daterangepicker({
            maxDate: new Date(),
            singleDatePicker: true,
            showDropdowns: true,
            calender_style: "picker_4",
            format: 'MM/DD/YYYY'
        });
		/*########## update states, cities #########*/
        $('#<?= $modelID ?>-country').on('change', function () { 
            $("#<?= $modelID ?>-region, #<?= $modelID ?>-city").find("option:gt(0)").remove();
            var countryID = $(this).val();
            $("#state").find("option:first").text("Loading...");
            $.ajax({
                type: 'POST',
                url: '<?= $ajaxSUrl ?>',
                data: 'id=' + countryID,
                success: function (json) {
                    $("#<?= $modelID ?>-region").find("option:first").text("<?php echo Yii::t('yii', 'Select State'); ?>");
                    for (var i = 0; i < json.length; i++) {
                        $("<option/>").attr("value", json[i].id).text(json[i].name).appendTo($("#<?= $modelID ?>-region"));
                    }
                }
            });
        });
        $("#<?= $modelID ?>-region").on('change', function () {
            var stateID = $(this).val();
            $("#<?= $modelID ?>-city").find("option:gt(0)").remove();
            $("#<?= $modelID ?>-city").find("option:first").text("Loading...");
            $.ajax({
                type: 'POST',
                url: '<?= $ajaxCUrl ?>',
                data: 'id=' + stateID,
                success: function (json) {
                    $("#<?= $modelID ?>-city").find("option:first").text("<?php echo Yii::t('yii', 'Select City'); ?>");
                    for (var i = 0; i < json.length; i++) {
                        $("<option/>").attr("value", json[i].id).text(json[i].name).appendTo($("#<?= $modelID ?>-city"));
                    }
                }
            });
        });
    });
</script>
