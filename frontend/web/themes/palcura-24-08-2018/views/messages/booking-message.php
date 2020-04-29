<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;

$this->title 	= Yii::t('yii','Messages');
$attributes 	= Yii::$app->user->identity->getattributes();
$userId 		= Yii::$app->user->getId();
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
					<div class="orangeBtn">
						<a href="<?=  Url::home().'messages' ?>">
							<i class="fa fa-angle-left" aria-hidden="true"></i> Back to your messages
						</a>
					</div>
				</div>
				
				<div class="greyBox messageBox" id="chat-result">
				</div>
				<div class="lightGrey">
						<?php
							$form = ActiveForm::begin(
								[ 'id' => 'search-form', 'method' => 'post',
									'fieldConfig' => [],
							]);
							echo $form->field($model, 'user_to')->hiddenInput(['value'=> $book_user_id])->label(false);
						?>
							<div class="form-group bookusr">
								<input type="hidden" name="book_user_id" id="bookuserid" value="" />
								<?php
									echo $form->field($model, 'message', ['inputOptions' => [
									'class' => "form-control ",
									]])->textArea(['rows' => 2, 'cols' => 20, 'maxlength' => 500, 'placeholder' => 'Type your message here'])->label(false);
								?>						
								</div>
								<button type="submit" class="orangeBtn" id="sendMessageBTN">send</button>
<!--
								<div class="orangeBtn">
									<a href="javascript:void(0)" id="sendMessageBTN">send</a>
								</div>							
-->
						<?php ActiveForm::end(); ?>	
				</div>
			</div>
		</div>
	</div>
</section>
<script>
     
    document.onkeydown=function(evt){
        var keyCode = evt ? (evt.which ? evt.which : evt.keyCode) : event.keyCode;
        if(keyCode == 13)
        {
          $('#sendMessageBTN').trigger('click');
          //$(".orangeBtn").trigger("select");
        }
    }	

    </script>
