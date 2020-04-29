<?php 
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

$newDate 			= 	Yii::$app->commonmethod->getDatepickerDate();
$model->date_from	=	(isset($model->date_from) && !empty($model->date_from) ? $model->date_from : $newDate);
$model->date_to		=	(isset($model->date_to) && !empty($model->date_to) ? $model->date_to : $newDate);
$session 			= Yii::$app->session;
$getsercharray 		=   $session->get('searchrequestdata');
$paltype 				= Yii::$app->commonmethod->getPetTypesSEARCH();
?>
<section class="lightBg">
  <div class="container">
    <div class="row">
      <div class="customForm">
        <!--form-->
    <?php	
	$servicetype = Yii::$app->commonmethod->servicesTypes();
	$form = ActiveForm::begin([
	'id' => 'petsearchfilter',
	'action' => Url::to(['search/petrenter']),
	'fieldConfig' => [

	'labelOptions' => ['class' => ''],
	'options' => [
	'tag' => 'span', ####THIS OPTIONS DISABLES THE DIV.FORM_GROUP ENCLOSER TAG FOR FIELDS
	'class' => '', ####DISABLE THE DEFAULT FORM_GROUP CLASS
	],
	],
	]);
	//$paltype = array('1'=>'Dog','2'=>'Cat','3'=>'Other');        
?>
	<input type="hidden" name="Search[u_latitude]" class="userlatitude" id="lat" value="<?= (isset($getsercharray['u_latitude']) ? $getsercharray['u_latitude'] : '') ?>" />
	<input type="hidden" name="Search[u_longitude]" class="userlongitude"  id="long" value="<?= (isset($getsercharray['u_longitude']) ? $getsercharray['u_longitude'] : '') ?>" />
          <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="form-group">
              <label>Pal type</label>
              <div class="select-contact">
               <select id="selected_pal" class="form-control searchfilter01" name="Search[selected_pal]">
				   <option value="">All</option>
					<?php
						foreach($paltype as $pkey=>$prow) {
							$psl='';
							if($getsercharray['selected_pal'] == $pkey) { 
								$psl = "selected";
							}
							echo "<option value='".$pkey."' ".$psl.">".$prow."</option>";
						}
					?>
                </select>
              </div>
            </div>
          </div>


          <div class="col-md-3 col-sm-6 col-xs-12" id="pet_weight_div">
            <div class="form-group">
              <label>Pet weight</label>
              <div class="select-contact">
               <select id="pet_weight" class="form-control searchfilter01" name="Search[pet_weight]">
				   <option value="">Pet weight</option>
					<?php
						/*for($i=1;$i<=20;$i++) {
							$wsl='';
							if($model->pet_weight == $i) { 
								$wsl = "selected";
							}							
							echo "<option value='".$i."' ".$wsl.">".$i." Kg</option>";
						}*/
					?>
			<option value="" disabled ><strong>Small</strong></option>
			<option value="1" <?php if($getsercharray['pet_weight'] == "1"){echo "selected"; }else{echo '';} ?>>0-15lbs</option>
			<option value="" disabled><strong>Medium</strong></option>
			<option value="2" <?php if($getsercharray['pet_weight'] == "2"){echo "selected"; }else{echo '';} ?>>16-40lbs</option>
			<option value="" disabled><strong>Large</strong></option>
			<option value="3" <?php if($getsercharray['pet_weight'] == "3"){echo "selected"; }else{echo '';} ?>>41-100lbs</option>
			<option value="" disabled><strong>Giant</strong></option>
			<option value="4" <?php if($getsercharray['pet_weight'] == "4"){echo "selected"; }else{echo '';} ?>>101+lbs</option>
                </select>
              </div>
            </div>
          </div>

          <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="form-group">
            <?php echo $form->field($model, 'zip',['inputOptions' => [
            'class' => "inputfeild searchfilter01",
            'placeholder' => 'Enter zip code/address',
            'id' => 'zip',
            'value' => (isset($getsercharray['zip'])?$getsercharray['zip']:'')
            ]])->textInput(); ?>
            </div>
          </div>

          <!--div class="col-md-3 col-sm-6 col-xs-12">
            <div class="form-group">
              <label>Borrowing need</label>
              <div class="select-contact">
                <select id="service_type" class="form-control searchfilter01" name="Search[service_type]">
					<?php
						//foreach($servicetype as $skey=>$srow) {
							//$stsl='';
							//if($getsercharray['service_type'] == $skey) { 
							//	$stsl = "selected";
							//}								
							//echo "<option value='".$skey."' ".$stsl.">".$srow."</option>";
						//}
					?>
                </select>
              </div>
            </div>
          </div-->
      </div>
    </div>
    <div class="row">
      <div class="customForm">
        <form>
          <div class="col-md-6 col-sm-12 col-xs-12">
            <div class="row">
              <div class="col-md-6 col-sm-6 col-xs-12">                
                <div class="sliderTime">
                  <p>
                    <label for="amount">Rate:</label>
                    <input type="text" id="amount" readonly>
                  </p>
                  <div id="slider-range"></div>
                </div>
              </div>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                  <label>How many pals you need care for?</label>
                  <div class="select-contact">
                    <select class="form-control searchfilter01" id="no_of_pals" name="Search[no_of_pals]">
						
						<?php
							for($i=1;$i<=20;$i++) {
								$npsl='';
								if($getsercharray['no_of_pals'] == $i) { 
									$npsl = "selected";
								}
								echo "<option value='".$i."' ".$npsl.">".$i."</option>";
							}
						?>
                    </select>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-6 col-sm-12 col-xs-12">
            <div class="row">
              <div class="col-md-4 col-sm-4 col-xs-12">
                <div class="form-group">
                  <label>Choose date from to</label>
                  <input type="text" id="date_from" class="datepicker searchfilter01" name="Search[date_from]" value="<?= $getsercharray['date_from']; ?>" readonly />
                </div>
              </div>
              <div class="col-md-4 col-sm-4 col-xs-12">
                <div class="form-group">
                  <label>To</label>
                  <input type="text" id="date_to" class="datepicker searchfilter01" name="Search[date_to]" value="<?= $getsercharray['date_to']; ?>" readonly />
                </div>
              </div>
              <div class="col-md-4 col-sm-4 col-xs-12">
                <div class="filterBtn">
                  <button class="orangeBtn" type="button" id="palsearchfilter">Filter Results</button>
                </div>
              </div>
            </div>
          </div>
          <?php ActiveForm::end() ; ?>
      </div>
    </div>
  </div>
</section>
<script>
$(function() {
	$("#date_from").datepicker({
		dateFormat: '<?= DATEPICKER_FORMAT_JS ?>',
		numberOfMonths: 1,
		showButtonPanel: true,
		minDate: '<?= ADD_DAYS_JS ?>',
		maxDate: '+2Y',
		//onSelect: function() {
		//	$("#date_to").datepicker('option', 'minDate', $("#date_from").datepicker("getDate"));
		//}
	});

	$("#date_to").datepicker({
		dateFormat: '<?= DATEPICKER_FORMAT_JS ?>',
		numberOfMonths: 1,
		showButtonPanel: true,
		minDate: '<?= ADD_DAYS_JS ?>',
		maxDate: '+2Y',
	});
});

 $('document').ready(function(){
    var selectedpal =  $('#selected_pal').val();
		if(selectedpal == 2 || selectedpal==3){
		$('#pet_weight').prop('selectedIndex','');
		$('#pet_weight_div').hide();
		}else if(selectedpal==1 || selectedpal==''){
		$('#pet_weight_div').show();
		}
    
    $('#selected_pal').on('change',function(){ 
     var selectedpal =  $('#selected_pal').val();
		if(selectedpal == 2 || selectedpal==3){
		$('#pet_weight').prop('selectedIndex','');
	   
		$('#pet_weight_div').hide(); 
		}else if(selectedpal==1 || selectedpal==''){
		$('#pet_weight_div').show();
		}   
    });
    
    });
</script>
