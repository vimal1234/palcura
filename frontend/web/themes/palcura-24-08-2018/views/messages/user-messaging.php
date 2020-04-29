<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;

$this->title 	= Yii::t('yii','Messages');
$attributes 	= Yii::$app->user->identity->getattributes();
$userId 		= Yii::$app->user->getId();
$siteimage  	= Yii::getAlias('@siteimage');
$session = Yii::$app->session;
$loggedingusertype = $session->get('loggedinusertype');
$searchdata = $session->get('searchrequestdata');
?>
		<div class="row">
			<div class="col-xs-12">
				<h1><?= $this->title ?></h1>
			</div>
		</div>
	</div>
</header>
<section class="contentArea">
	<div class="container">
		<div class="row">
			<?php echo $this->render('//common/sidebar'); ?>
			<div class="col-md-10 col-sm-9 col-xs-12">
				<div class="borderBox">
					<div class="orangeBtn" style="margin-top:5px;">
						<a href="<?=  Url::home().'messages' ?>">
							<i class="fa fa-angle-left" aria-hidden="true"></i> Back to your messages
						</a>
					</div>
					
					 <?php if ($loggedingusertype == OWNER && !empty($searchdata)) { ?>                   
                      <div class="orangeBtn" style="margin-top:5px;text-align:center">
						<a href="<?php echo Url::to(['search/petsitter']) ?>">
							<i class="fa fa-angle-left" aria-hidden="true"></i> Go to search results
						</a>
					</div>
                        <?php } elseif ($loggedingusertype == RENTER && !empty($searchdata)) { ?>                         
                         <div class="orangeBtn" style="margin-top:5px;text-align:center">
							<a href="<?php echo Url::to(['search/petrenter']) ?>">
							<i class="fa fa-angle-left" aria-hidden="true"></i> Go to search results
							</a>
						</div>
                        <?php } ?>
					
				<!--button type="button" class="orangeBtn" data-toggle="modal" data-target="#reviewModal" style="float:right;">Set Final Price</button-->
				</div>
				<div class="greyBox messageBox" id="chat-result">
					<?php echo $this->render('chatting-result.php',['messages' => $messages]); ?>
				</div>
				<div class="lightGrey">
					<div class="form-group">
						<input type="text" name="text_message" id="text_message" class="form-control" maxlength="500" placeholder="Type your message here" autofocus>
						<div id="errorMessage" class="col-lg-10"></div>
					</div>
					<div class="orangeBtn">
						<a href="javascript:void(0)" id="sendMessageBTN">send</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<!--Boootstrap modal start-->		  
<!--div class="container">
	<div class="modal fade" id="reviewModal" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header" style="padding:35px 50px;">
					<h4><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> Services final price with discount</h4>
				</div>
				<img class="contact-bg" src="<?= $siteimage ?>/contact-bg.png" alt="">
				<?php
					$form = ActiveForm::begin([
					'id' => 'frmSignupUser',
					'action' => Url::to(['messages/bookingdiscount']),
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
					echo Html::hiddenInput('owner_id', 1);
					echo Html::hiddenInput('chat_id', $threadID);
				?>
				<div class="modal-body" style="padding:0 40px;">
					<div class="row-block">
						<div class="col-md-12 col-sm-12 col-xs-12">
							<?php
								echo $form->field($model, 'minimum_price', ['inputOptions' => [
								'class' => "form-control",
								]])->textInput(['maxlength' => 5, 'autofocus' => false])->label('Minimum Booking Price <span>*</span>');
							?>
						</div>
					</div>

					<div class="row-block">
						<div class="col-md-12 col-sm-12 col-xs-12">
							<?php
								echo $form->field($model, 'discount', ['inputOptions' => [
								'class' => "form-control",	
								]])->textInput(['maxlength' => 2, 'autofocus' => false])->label('Discount price (%)<span>*</span>');
							?>                
						</div>
					</div>

					<div class="row-block">
						<div class="col-md-12 col-sm-12 col-xs-12">
						<?= 
							Html::submitButton(Yii::t('yii','Submit'), ['class' => 'orangeBtn', 'name' => 'booking-discount', 'id' => 'Booking-discount']) 
						?>
						</div>
					</div>					
				</div>
				<div class="modal-footer"></div>
				<?php ActiveForm::end() ?>
			</div>	
		</div>
	</div>	
</div-->

<script>
     
    document.onkeydown=function(evt){
        var keyCode = evt ? (evt.which ? evt.which : evt.keyCode) : event.keyCode;
        if(keyCode == 13)
        {
          $('#sendMessageBTN').trigger('click');
          //$(".orangeBtn").trigger("select");
        }
    }	
	
	$(document).ready(function() {
		$("#chat-result").scrollTop($("#chat-result")[0].scrollHeight);
		var postData = {};
		var getData = {};
		var chat_id = '<?= $threadID ?>';
		$("#sendMessageBTN").on('click',function() {
			$('#errorMessage').html("");
			//var chat_id = '<?= $threadID ?>';
			var message = $("#text_message").val();
			var user_to = $("#user_to").val();
			if(chat_id == 0 || chat_id == '') {
				return false;
			}

			if(message == 0 || message == '') {
				$('#errorMessage').html("<p style='color:red;'>Please enter a valid text message.</p>");
				return false;
			}

			var messageLenght = message.length;
			if(messageLenght > 500) {
				$('#errorMessage').html("<p style='color:red;'>Please use only 500 characters.</p>");
				return false;
			}

			postData.chat_id = chat_id;
			postData.message = message;
			postData.user_to = user_to;
			postMessagings(postData);
		});

		setInterval(function() {
			getData.chat_id = chat_id; 
			liveMessagings(getData);
		}, 5000);

	});

	/*####= sending messaging =####*/
	function postMessagings(postData) {
		$.ajax({
			url:'<?php echo Url::to(['messages/messagesending']); ?>',
			type:'post',
			data:{'postData':postData},
			success:function(response) {
				var data = JSON.parse(response)
				if(data.status == 'success') {
					 $(".greyBox").append(data.response);
					 $("#text_message").val('');
				}
				$("#chat-result").scrollTop($("#chat-result")[0].scrollHeight);
			}
		});
	}

	/*####= live messaging messaging =####*/
	function liveMessagings(getData) {
		$.ajax({
			url:'<?php echo Url::to(['messages/live-messaging']);?>',
			type:'post',
			data:{'livechatting':getData},
			success:function(response) {
				if(response)	
					$('#chat-result').html(response);	
			}
			//$("#chat-result").scrollTop($("#chat-result")[0].scrollHeight);
		});
	}
	
</script>
