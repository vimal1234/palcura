<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$form	= ActiveForm::begin([
	'id' => 'petsearch-form'.$formid,
	'action' => Url::to(['search/petrenter']),
	'fieldConfig' => [
	'labelOptions' => ['class' => ''],
	'options' => [
	'tag' => 'span', ####THIS OPTIONS DISABLES THE DIV.FORM_GROUP ENCLOSER TAG FOR FIELDS
	'class' => '', ####DISABLE THE DEFAULT FORM_GROUP CLASS
	],
	],
]);

$newDate = Yii::$app->commonmethod->getDatepickerDate();
?>
	<div class="fullWidth">
	<?php echo $form->field($model, 'selected_pal')->hiddenInput(['id' => 'selectedpal'.$formid])->label(false); ?>
	<?php if($formid == 1){?>
		<div class="cols">
		  <label>Pet weight</label>
		  <div class="selectBG">
			<select name="Search[pet_weight]" id="pet_weight<?= $formid ?>">
			<option value="">Pet weight</option>
			<option value="" disabled><strong>Small</strong></option>
			<option value="1">0-15lbs</option>
			<option value="" disabled><strong>Medium</strong></option>
			<option value="2">16-40lbs</option>
			<option value="" disabled><strong>Large</strong></option>
			<option value="3">41-100lbs</option>
			<option value="" disabled><strong>Giant</strong></option>
			<option value="4">101+lbs</option>
			</select>
		  </div>
		</div>
		<?php } ?>
		<div class="cols">
			<?php echo $form->field($model, 'zip',['inputOptions' => [
            'class' => "inputfeild",
            'placeholder' => 'Enter zip code/address',
            'id' => 'zip'.$formid
            ]])->textInput(); ?>
		</div>
	</div>
	<div class="fullWidth">
		<div class="cols">
			<?php echo $form->field($model, 'date_from',['inputOptions' => [
            'class' => "datepicker",
          	'value' => $newDate,
          	'readonly' => 'readonly',
          	'id' => 'date_from'.$formid
            ]])->textInput()->label('Choose date from'); ?>
		</div>
		<div class="cols">			
			<?php echo $form->field($model, 'date_to',['inputOptions' => [
            'class' => "datepicker",
          	'value' => 	$newDate,
          	'readonly' => 'readonly',
          	'id' => 'date_to'.$formid
            ]])->textInput()->label('To'); ?>
		</div>
	</div>
	<input type="hidden" name="Search[u_latitude]" class="userlatitude" id="lat" value="" />
	<input type="hidden" name="Search[u_longitude]" class="userlongitude"  id="long" value="" />
	<input type="hidden" name="Search[searchcategory]"  value="borrow" id="searchcategory<?php echo $formid; ?>">	
	<div class="cols">
	<button id="submitsearch<?php echo $formid; ?>" type="button">Search</button>
	<?php echo Html::submitButton('Search', ['class' => '', 'name' => 'search-submit', 'id' => 'petsearch-submit'.$formid, 'style'=> 'display:none']) ?>
	</div>
<?php ActiveForm::end() ;
?>

<script>
$(function() {
	$("#date_from"+<?= $formid ?>).datepicker({
		dateFormat: '<?= DATEPICKER_FORMAT_JS ?>', 
		numberOfMonths: 1,
		showButtonPanel: true,
		minDate: '<?= ADD_DAYS_JS ?>',
		maxDate: '+2Y',
		onSelect: function(){
			$("#date_to"+<?= $formid ?>).datepicker('option', 'minDate', $("#date_from"+<?= $formid ?>).datepicker("getDate"));
		}
	});
	$("#date_to"+<?= $formid ?>).datepicker({
		dateFormat: '<?= DATEPICKER_FORMAT_JS ?>', 
		numberOfMonths: 1,
		showButtonPanel: true,
		minDate: '<?= ADD_DAYS_JS ?>',
		maxDate: '+2Y',
	});
});	

$('document').ready(function(){
//&& pet_weight='' && zip=='' && (date_from=='' || date_to=='')
$('#submitsearch'+"<?php echo $formid ?>").click(function(){
//var service_type = $('#service_type'+"<?php echo $formid ?>").val();
var pet_weight = $('#pet_weight'+"<?php echo $formid ?>").val();
var zip = $('#zip'+"<?php echo $formid ?>").val();
var date_from = $('#date_from'+"<?php echo $formid ?>").val();
var date_to = $('#date_to'+"<?php echo $formid ?>").val();
var selectedpal = $('#selectedpal'+"<?php echo $formid ?>").val("<?php echo $formid ?>");
$("#petsearch-submit"+"<?php echo $formid ?>" ).click();
/*if(pet_weight=='' && zip==''){alert('No serach options selected');
$("#errormessage").append('<p>Please add some search field information.</p>');
}else{
$("#petsearch-submit"+"<?php echo $formid ?>" ).click();
}*/
});
});
</script>
