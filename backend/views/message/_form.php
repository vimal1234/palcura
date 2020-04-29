<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

?>
	<div class="right_col" role="main">

		<div class="">
			<div class="page-title">
				<div class="title_left">
					<h3>
		   <?= Html::encode($this->title) ?>
		</h3>
		 </div>
		   </div>
			<div class="clearfix"></div>

			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
					 
					<div class="x_panel">
						<div class="x_title">
						<?php if(isset($data['respmesg'])) {?>	
						<div class="alert <?=$data['class']?> alert-dismissible fade in" role="alert">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
							</button>
							<?php echo $data['respmesg'];?>
						</div>
						 <?php } ?>  
						   
						</div>
						<div class="x_content">
							  <p>Please fill out the following fields:</p>
						 
							<?php 
								  $form = ActiveForm::begin(
								  [ 'id' => 'form-createuser',
									'options'=>['class'=>'form-horizontal form-label-left'],
									 'fieldConfig'=>[
										'template'=>"<div class=\"item form-group\">\n{label}\n<div class=\"col-md-6 col-sm-6 col-xs-12\">
													{input}<div class=\"col-lg-10\">
													{error}</div></div></div>",
										'labelOptions'=>['class'=>'control-label col-md-3'],
									],
								  ]); 
							?>
									
							<?php 
								//~ echo $form->field($model, 'user_to',['inputOptions'=>[
									//~ 'class'=>"form-control col-md-7 col-xs-12",					
								//~ ]])->textInput(['maxlength' => true])->label('Send To <span class="required">*</span>'); 
							?>


			<?php
		//~ $users = backend\models\users\Users::find()->select(['usrFirstname', 'id'])->indexBy('id')->column();										
		//~ $selUser = explode(",", $model->user_to);
		//~ $i 		= 0;
		//~ $chk	=	'';
		//~ foreach ($users as $key => $user) {
			//~ if($i%3==0) { echo'<br/>'; }
			//~ if (in_array($key, $selUser)) {
				 //~ $chk = 'checked';
			//~ }
			//~ echo '<div class="checkboxes"><input type="checkbox"  id="language1" name="AddMemberForm[usrLanguage][]" value="'.$key.'" '.$chk.'/>'.$language.'</div>';
			//~ $i++;
			//~ $chk = '';
		//~ }
			?>
			<?php
				$currency = ArrayHelper::map(backend\models\users\Users::find()->all(), 'id', 'usrFirstname');
				echo $form->field($model, 'user_to')->dropDownList($currency, ['prompt' => Yii::t('yii', 'Select User')]);
			?>								
							<?php 
								echo $form->field($model, 'subject',['inputOptions'=>[
									'class'=>"form-control col-md-7 col-xs-12",					
								]])->textInput(['maxlength' => 100])->label('Subject <span class="required">*</span>'); 
							?>
							
							<?php
								echo $form->field($model, 'message',['inputOptions' => [
									'class' => "form-control col-md-7 col-xs-12",
							    ]])->textarea(array("rows"=>"4"))->label('Message <span class="required">*</span>');
							?>
							
							<div class="form-group">
								<div class="col-md-6 col-md-offset-3">
									<button type="button" class="btn btn-primary" onclick="javascript:window.location.href='<?php echo Url::home()."message";?>'">Cancel</button>
									<?php echo  Html::submitButton('Create', ['class' => 'btn btn-success']) ?>
								</div>
							</div>
							

						<?php ActiveForm::end(); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	  <!-- footer content -->
		<?php echo $this->render('../includes/footer'); ?>
	  <!-- /footer content -->
	   
	</div>

	<script type="text/javascript">
		$(document).ready(function() {
			$("input[name$='page[pageType]']").click(function() {
				var test = $(this).val();
				if(test == 2){
					$("div #Metafeilds").show();
				}else{
					$("div #Metafeilds").hide();
				}
			});
		});
	</script>
