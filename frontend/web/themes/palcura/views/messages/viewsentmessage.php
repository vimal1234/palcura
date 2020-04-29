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

$this->title = Yii::t('yii','View Message');
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
				<h4><?php echo Yii::t('yii','Sent Message'); ?></h4>
				<div class="table-responsive">
				  <table class="table ">
					<tbody>
					  <tr class="unread">
						<td><div class="Checkbox">
							<?php 
								if($messageInfo['send_status'] == '1') {
									$senderName = "Admin";
								} else {
									$senderName = (isset($messageInfo['usrFirstname']) ? $messageInfo['usrFirstname']." ".$messageInfo['usrLastname'] : '');
								}
							?>
							<?php echo Yii::t('yii','To'); ?>: <?= $senderName ?></td>
							
						<td align="right"><div class="Checkbox">
							<?php echo Yii::t('yii','Date'); ?>: <?= (isset($messageInfo['date_created']) ? date(MESSAGE_DATE_FORMAT,strtotime($messageInfo['date_created'])) : '') ?></td>							
					  </tr>
					  <tr>
						<td colspan="2"><?= (isset($messageInfo['message']) ? $messageInfo['message'] : '') ?></td>
					  </tr>
					 
					</tbody>
				  </table>
				</div>
				<p class="scrolltable"><?php echo Yii::t('yii','Scroll to see the table.'); ?></p>
			  </div>
			</div>
		  </div>
		</div>
	</div>
  </div>
</section>
