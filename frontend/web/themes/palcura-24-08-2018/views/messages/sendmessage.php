<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;  
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use frontend\models\Interests;
use common\models\Country;
use common\models\State;
use common\models\City;

$this->title = Yii::t('yii','Send Message');
$this->params['breadcrumbs'][] = $this->title;
?>
<section>
<?php echo $this->render('//common/searchbox'); ?>
  <div class="searchresult">
    <div class="container">
      <div class="row">
        <div class="col-md-3 col-sm-4 col-xs-12">
            <?php echo $this->render('../common/sidebar'); ?>
        </div>
        <div class="col-xs-12 col-sm-8 col-md-9">
          <div class="message">
              <?php if (Yii::$app->session->getFlash('item')): ?>
			  <div class="col-xs-12">
				 <div class="alert alert-grey alert-dismissible">
					   <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span></button>
					   <i class="glyphicon glyphicon-ok"></i><?php echo Yii::$app->session->getFlash('item'); ?>
				 </div>
			  </div>																	
			  <?php endif;
              $form = ActiveForm::begin(
                  [ 'id' => 'editProfile-form',
                  'options' => [
                  'enctype' => 'multipart/form-data',
                  'tag' => 'span', ####THIS OPTIONS DISABLES THE DIV.FORM_GROUP ENCLOSER TAG FOR FIELDS
                  ],
                  'fieldConfig' => [
                  'template' => "<div class=\"fullwidth\">\n
					<div class=\"col-xs-12\">\n
						<div class=\"form-group\">\n
							{label}\n
								<div class=\"val\">\n
									<div class=\"controls\">
										{input}
										<div class=\"col-lg-10\">
										{error} {hint}
										</div>
									</div>
								</div>
                         </div>
                     </div>
                     </div>",
                  'labelOptions' => ['class' => ''],
                  'options' => [
                  'tag' => 'span', ####THIS OPTIONS DISABLES THE DIV.FORM_GROUP ENCLOSER TAG FOR FIELDS
                  'class' => '', ####DISABLE THE DEFAULT FORM_GROUP CLASS
                  ],
                  ],
                  ]
                  );

				$guydename = (isset($messageInfo['usrFirstname']) ? $messageInfo['usrFirstname'] : '').' '.(isset($messageInfo['usrLastname']) ? $messageInfo['usrLastname'] : '');                  
                  
                ?>

                <div class="fullwidth">
					<div class="col-xs-12">
						<div class="form-group ">
						  <label><?php echo Yii::t('yii','To'); ?></label>
						  <div class="val">
							<div class="controls">
							  <input type="text" class="form-control textfeild" name="guydename" value="<?php echo $guydename; ?>" readonly>
							</div>
						  </div>
						</div>
					</div>
				</div>


                <input type="hidden" name="Messages[user_from]" value="<?= $messageInfo['from_user_id'] ?>" />
                <input type="hidden" name="Messages[user_to]" value="<?= $messageInfo['to_user_id'] ?>" />
                <input type="hidden" name="Messages[booking_id]" value="<?= $messageInfo['booking_id'] ?>" />
				<?php
					echo $form->field($model, 'message',['inputOptions' => [
					'class' => "form-control textarea textfeild",
					]])->textarea(array("rows"=>"4"))->label(Yii::t('yii','Description'));
				?>
                <div class="fullwidth">
                  <div class="col-xs-12">
						<?= Html::submitButton(Yii::t('yii','Send'), ['class' => 'btn btn-primary orangebtn', 'name' => 'reply-btn', 'id' => 'reply-button']) ?>
                  </div>
                </div>
              <?php ActiveForm::end(); ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
