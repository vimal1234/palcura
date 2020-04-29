<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\widgets\LinkPager;
use yii\db\Query;
$this->title 	= Yii::t('yii','Calendar');
$siteimage  	= Yii::getAlias('@siteimage');
$newUnArr		= array();
if(isset($model->unavailabilty) && !empty($model->unavailabilty)) {
	$j=0;
	if(isset($eventData) && !empty($eventData)) {
		if(count($eventData) > 0) {
			$n = count($eventData)-1;
			$narr = array_keys($eventData);
			$key = (isset($narr[$n]) ? $narr[$n] : 0);		
			//$key = end(array_keys($eventData));
			$j=1;
		} else {
			$key = 0;
		}
	} else {
		$key = 0;
	}
	
	$arr = explode(",",$model->unavailabilty);
	if(!empty($arr)) {
		if($key > 1) {
			$i=$key+1;
		} else {
		if($j==1){
			$i=$key+1;
		}else{
		 	$i=$key;
		}	
		}
		
		foreach($arr as $r) {
			$eventData[$i]['id'] = $i;
			$eventData[$i]['title'] = "Unavailability";
			$eventData[$i]['start'] = (isset($r) && $r != "" ? date("Y-m-d",strtotime($r)) : '');
			$eventData[$i]['end'] = (isset($r) && $r != "" ? date("Y-m-d",strtotime($r)) : '');
			$eventData[$i]['description'] = "Not available for booking.";
			$i++;
		}
	}
}
//echo"<pre>"; print_r($eventData); exit();
$evdata 		= json_encode($eventData);

$session 		= Yii::$app->session;
$logged_user 	= $session->get('loggedinusertype');
?>
<link href="<?php echo SITE_URL ?>frontend/web/themes/palcura/css/fullcalendar.min.css" rel="stylesheet" />
<link href="<?php echo SITE_URL ?>frontend/web/themes/palcura/css/fullcalendar.print.min.css" rel="stylesheet" media="print" />
      <div class="col-xs-12">
        <h1>Calendar</h1>
      </div>
  </div>
</header>
<!-- END HEADER -->

<section class="contentArea">
  <div class="container">
    <div class="row">
	 <?php echo $this->render('//common/sidebar'); ?>
      <div class="col-md-10 col-sm-9 col-xs-12 scrolldiv01">
        <div class="user-contentMain">
          <div id='calendar'></div>
        </div>
      </div>
    </div>
  </div>
  </div>
</section>
<?php 
	if($logged_user == OWNER || $logged_user == SITTER) {
?>
<section class="contentArea">
  <div class="container">
    <div class="row">
		<script type="text/javascript" src="<?php echo SITE_URL ?>frontend/web/themes/palcura/multidatepicker/jquery-ui.multidatespicker.js"></script>
		<link rel="stylesheet" type="text/css" href="<?php echo SITE_URL ?>frontend/web/themes/palcura/multidatepicker/css/mdp.css">
		<link rel="stylesheet" type="text/css" href="<?php echo SITE_URL ?>frontend/web/themes/palcura/multidatepicker/css/prettify.css">
		<script type="text/javascript" src="<?php echo SITE_URL ?>frontend/web/themes/palcura/multidatepicker/js/prettify.js"></script>
		<script type="text/javascript" src="<?php echo SITE_URL ?>frontend/web/themes/palcura/multidatepicker/js/lang-css.js"></script>
		<div class="col-md-2 col-sm-3 col-xs-12">
		</div>
		<div class="col-md-10 col-sm-9 col-xs-12 scrolldiv01">
			<?php if (Yii::$app->session->getFlash('item')): ?>
				<div class="alert alert-grey alert-dismissible">
				<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span>
				</button>
				<?php echo Yii::$app->session->getFlash('item'); ?>
				</div>
			<?php endif; ?>
			<h3>Select your unavailable days.</h3>						
			<div class="formContent" style="margin:0;">
				<div class="col-md-12 col-sm-12 col-xs-12">
						<?php
						$form = ActiveForm::begin([
							'id' => 'frmDates',
							'action' => Url::to(['calendar/addunavailability']),
							'options' => [
							'enctype' => 'multipart/form-data',
							'tag' => 'span',
							],
						'fieldConfig' => [
							'template' => "<div class=\"form-group\">\n
								{label}\n
								{input}\n
								<div class=\"col-lg-10\">
									{error} {hint}
								</div>
							</div>",
							'labelOptions' => [],
							'options' => [
								'tag' => 'span',
								'class' => '',
							],
						],
						]);
						?>
						<div class="row-block">
							<div class="col-md-4 col-sm-6 col-xs-12">							
								<?php
									echo $form->field($model, 'unavailabilty', ['inputOptions' => [
									'class' => "form-control datepicker",
									"id"	=> "mdp-demo",
"style"	=> "background-color:#fffe; color:#fffe",
									]])->textInput(['readonly' => true, 'autofocus' => true])->label(false);
								?>
							</div>
							<div class="col-md-4 col-sm-6 col-xs-12">
								<?= 
									Html::submitButton(Yii::t('yii','Save'), ['class' => 'orangeBtn', 'name' => 'adddates-submit', 'id' => 	'AddDates-submit', 'style' => 'padding:16px 31px;']) 
								?>
							</div>
						</div>
					<?php ActiveForm::end(); ?>
				</div>		
			</div>		
		</div>
	</div>
</div>
</section>
<script>
	$(document).ready(function() {
		$('#mdp-demo').multiDatesPicker({
			dateFormat: "<?= DATEPICKER_FORMAT_JS ?>",
			minDate: 0,
			addDates: "",	
			onSelect: function(dateText, inst) { 
				inst.settings.defaultDate = dateText; 
			}
		});
	});
</script>
<?php } ?>
<script>
$(document).ready(function() {
		initThemeChooser({
			init: function(themeSystem) {
				$('#calendar').fullCalendar({
					themeSystem: themeSystem,
					header: {
						left: 'prev,next today',
						center: 'title',
						right: 'month,agendaWeek,agendaDay,listMonth'
					},
					defaultDate: '<?php echo date("Y-m-d")?>',
					weekNumbers: true,
					navLinks: true, // can click day/week names to navigate views
					editable: true,
					eventLimit: true, // allow "more" link when too many events
					events: <?php echo $evdata; ?>,
					eventRender: function(event, element) { 
						element.find('.fc-title').append("<br/>" + event.description); 
					}
				});
			},

			change: function(themeSystem) {
				$('#calendar').fullCalendar('option', 'themeSystem', themeSystem);
			}
			

		});

	});


</script>

