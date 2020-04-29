<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('yii','Accept/Decline');

####= common methods =####
//$paymentData			=  Yii::$app->session->get('booking_details');

####= { $selectCol } use to get specific columns of users. 
$selectCol  		= "firstname,lastname";
$userInfo			= Yii::$app->commonmethod->getUserColumnsData($videodata['pet_sitter_id'],$selectCol);

$attributes 		= Yii::$app->user->identity->getattributes();
$session 			= Yii::$app->session;
$logged_user 		= $session->get('loggedinusertype');
$userid = Yii::$app->user->identity->id;
if($userid == $videodata['pet_owner_id'] && $videodata['pet_renter_id']>0) {
	$BookedUserName			=   "Sitter name";
	$userInfo			= Yii::$app->commonmethod->getUserColumnsData($videodata['pet_renter_id'],$selectCol);
	
} else if($userid == $videodata['pet_owner_id'] && $videodata['pet_renter_id']==0) {
	$BookedUserName			=   "Owner name";
	
} elseif($userid == $videodata['pet_sitter_id'] && $videodata['pet_renter_id']>0){

	$BookedUserName			=   "Owner name";
	$userInfo			= Yii::$app->commonmethod->getUserColumnsData($videodata['pet_renter_id'],$selectCol);
}elseif($userid == $videodata['pet_sitter_id'] && $videodata['pet_renter_id']==0){
$userInfo			= Yii::$app->commonmethod->getUserColumnsData($videodata['pet_owner_id'],$selectCol);

}elseif($userid == $videodata['pet_renter_id'] && $videodata['pet_owner_id']>0){
$userInfo			= Yii::$app->commonmethod->getUserColumnsData($videodata['pet_owner_id'],$selectCol);
}
if(!empty($videodata['services'])){
$servicesarray = explode(',',$videodata['services']);
$servicesName = Yii::$app->commonmethod->getServicesName($servicesarray);
$servicenames = implode(',',$servicesName);
}else{
$servicenames = '';
}
?>

			<div class="col-xs-12">
				<h1><?= $this->title ?></h1>
			</div>
	</div>
</header>
<section class="contentArea">
	<div class="container">
		<div class="row">
			<?php echo $this->render('//common/sidebar'); ?>
			<div class="col-md-10 col-sm-12 col-xs-12">
				<div class="formContent" style="margin:0;"> 
					<img class="contact-bg" src="<?php echo WEBSITE_IMAGES_PATH.'contact-bg.png'; ?>" alt="">
					<div class="col-md-4 col-sm-4 col-xs-12 pull-right">
						<div class="formContentSidebar"></div>
					</div>
					
          <div class="col-md-12 col-sm-8 col-xs-12">
			<div id="msgbox" style="display:none;">
			</div>	
					
					<?php
					$form = ActiveForm::begin([
					'id' => 'frmSignupUser',
					//'action' => Url::to(['bookings/confirm']),
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
					//echo $form->field($model, 'sitter_id')->hiddenInput(['value'=> '1'])->label(false);
					echo Html::hiddenInput('owner_id', $videodata['pet_owner_id']);

					echo Html::hiddenInput('sitter_id', $videodata['pet_sitter_id']);
					
					echo Html::hiddenInput('vid_id', $videodata['id']);
					echo Html::hiddenInput('schedule_datetime', $videodata['schedule_datetime']);
					echo Html::hiddenInput('start_time', $videodata['start_time']);
				?>
					<!--input type="hidden" name="reward_status" id="reward_status" value="" /> 
					<input type="hidden" name="credit_amount" id="credit_amount" value="" /--> 
					
				  <div class="col-md-6 col-sm-6 col-xs-12 paymentSection singleLine">
					<div class="form-group">
						<label>Scheduled Date :</label>
						<div class="bookingNameExt"><?php echo date('m/d/y',strtotime($videodata['schedule_datetime']));?> </div>
					</div>
				  </div>
				  <div class="col-md-6 col-sm-6 col-xs-12 paymentSection singleLine">
					<div class="form-group">
					  <label>Scheduled Time :</label>
					  <div class="bookingNameExt"><?php echo $videodata['start_time'];?> </div>
					</div>
				  </div>
				  <div class="col-md-6 col-sm-6 col-xs-12 paymentSection singleLine">
					<div class="form-group ">
					  <label>Description :</label>
					  <div class="bookingNameExt"><?php echo nl2br($videodata['description']); ?></div>
					</div>
				  </div>  
				 <div class="col-md-6 col-sm-6 col-xs-12 paymentSection singleLine">
					<div class="form-group ">
					  <label>Scheduled With :</label>
					  <div class="bookingNameExt"><?= $userInfo['firstname']." ".$userInfo['lastname'] ?></div>
					</div>
				  </div> 
				   <div class="col-md-6 col-sm-6 col-xs-12 paymentSection singleLine">
					<div class="form-group ">
					  <label>Service Type :</label>
					  <div class="bookingNameExt"><?php 
					  if(!empty($servicenames)){ 
					  echo $servicenames;
					  }else{
					   echo 'NA';
					  }?></div>
					</div>
				  </div> 

				<div class="col-md-6 col-sm-6 col-xs-12 paymentbutton"> </div>                
              <div class="col-md-12 col-sm-12 col-xs-12 paymentbutton">
                <div class="captcha">
                  <div class="btnSubmit">
		    <button type="submit" class="orangeBtn">Confirm</button></br>
                  </div>
                  <div class="btnSubmit" style="margin-right:20px;">
			<button type="button" class="orangeBtn" id="rejectcall">Decline</button>
                  </div>
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
$('document').ready(function(){

$('#rejectcall').on('click',function(){
var vid_id = '<?php echo $videodata[id]?>';
var services = '<?php echo  $servicenames?>';
$.ajax({ 
		url:'<?php echo Url::to(['video/rejectcall']);?>',
		type:'post',
		data:{'id':vid_id,'sitter_id':'<?php echo $videodata[pet_sitter_id]; ?>','owner_id':'<?php echo $videodata[pet_owner_id]; ?>','date':'<?php echo $videodata[schedule_datetime]; ?>','time':'<?php echo $videodata[start_time]; ?>','sid':services},
		success:function(response){
		if(response==true){
		console.log('call rejected');
		window.location.href = '<?php echo SITE_URL ?>'+'video/index'; 
		}else{
		console.log('error updating call status');
		return false;
		}
		
			
		}	
	});
});

});


</script>

