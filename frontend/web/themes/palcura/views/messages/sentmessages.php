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

$this->title = Yii::t('yii','Messages');
$this->params['breadcrumbs'][] = $this->title;
$attributes = Yii::$app->user->identity->getattributes();
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
		<?php if (Yii::$app->session->getFlash('item')): ?>
	
			 <div class="alert alert-grey alert-dismissible">
				   <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span>
				   </button>
				   <i class="glyphicon glyphicon-ok"></i><?php echo Yii::$app->session->getFlash('item'); ?>
			 </div>
														
		  <?php endif; ?>
          <div class="message">
            <h4 class="messageTabUnSel"><?php  echo Html::a(Yii::t('yii','Inbox').'',['messages/index'],['title'=>'Inbox']);?></h4>
            <h4 class="messageTabSel"><?php  echo Html::a(Yii::t('yii','Sent Messages').'',['messages/sent-messages'],['title'=>'Sent messages']);?></h4>
            <div class="tableOuter">
               <?php
					$form = ActiveForm::begin(
						[ 
							'id' => 'message-form',
							'action' => ['messages/removesentmessages'],
							'options' => [
								'enctype' => 'multipart/form-data',
								'tag' => 'span', ####THIS OPTIONS DISABLES THE DIV.FORM_GROUP ENCLOSER TAG FOR FIELDS
							],
							'fieldConfig' => [
								'template' => "{input}",
								'labelOptions' => ['class' => ''],
								'options' => [
									'tag' => 'span', ####THIS OPTIONS DISABLES THE DIV.FORM_GROUP ENCLOSER TAG FOR FIELDS
									'class' => '', ####DISABLE THE DEFAULT FORM_GROUP CLASS
								],
							],
						]
					);
                ?>
              <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
              <table class="table ">
				  
				   <col width="190">
				   <col width="505">
				   <col width="150">
				  
				  
                <tbody method="post">
                  <tr>
					 <td colspan="3">
						<div class="Checkbox">
							<input type="checkbox" name="checkbok" id="selectAll-chkbox" class="selectRow-All">
							<ul class="selectall">
								<li><a href="javascript:void(0)" id="selectAll"><?php echo Yii::t('yii','Select All'); ?></a></li>
								<li><a href="#" id="deletebtn"><?php echo Yii::t('yii','Delete'); ?></a></li>
						    </ul>
						</div>
					 </td>
                  </tr>
                  <?php
                  if(isset($messagesListing) && !empty($messagesListing)) {
				  $i = 1;
                  foreach($messagesListing as $msg) {
				  ?>
                  <tr>
                    <td>
						<div class="Checkbox">
							<input type="checkbox" name="optcheckbox[]" class="selectRow" value="<?= $msg['message_id'] ?>">
							<?php 
								if($msg['send_status'] == '1') {
									$custName = "Admin";
								} else {
									$custName = (isset($msg['tofn']) ? $msg['tofn'] : '')." ".(isset($msg['toln']) ? $msg['toln'] : '');
								}
							?>
							<?= Html::a(substr($custName,0,30), ['messages/viewsentmessage', 'id' => $msg['message_id']]) ?>
                        </div>
                    </td>
                    <td><?php echo (isset($msg['message']) ? substr($msg['message'],0,50) : '--'); ?></td>
                    <td><?php echo (isset($msg['date_created']) ? date(MESSAGE_DATE_FORMAT,strtotime($msg['date_created'])) : ''); ?></td>
                  </tr>
                  <?php $i++; }
                  } else { ?>
                  <tr>
                    <td colspan="3" align="center"><?php echo Yii::t('yii','No Messages'); ?></td>
                  </tr>					  
				  <?php } ?>
               </tbody>
              </table>
              <?php ActiveForm::end(); ?>
            </div>
            <p class="scrolltable new"><?php echo Yii::t('yii','Scroll to see the table.'); ?></p>
            <nav class="paginationdesign">
			<?php 
			#################= pagination =#################

                    echo yii\widgets\LinkPager::widget([
                        'pagination' => $pages,
                        'prevPageLabel' => '<i class="fa fa-angle-left" aria-hidden="true"></i>',
                        'nextPageLabel' => '<i class="fa fa-angle-right" aria-hidden="true"></i>',
                        'activePageCssClass' => 'active',
                        'disabledPageCssClass' => 'disabled',
                        'prevPageCssClass' => 'enable',
                        'nextPageCssClass' => 'enable',
                        'hideOnSinglePage' => true
                    ]);
	?>
            </nav>
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>
  </div>
</section>  
 <script>
	 $(document).ready(function(){
		//########################= remove messages =########################//
		$('#deletebtn').click(function() {
		var checkedItems = $('.selectRow:checked').val();
			if(checkedItems != 0 && checkedItems != '' && checkedItems != null) {
				if(!confirm('Are you sure to delete selected message'+'(s)?')) {
					return false;
				}
				document.getElementById("message-form").submit();
				return true;
			} else {
				return false;
			}
		});
		
		//######################= ckeck/uncheck all checkbox =######################//
		$('#selectAll').click(function() {
			var val = $('#selectAll-chkbox').is(':checked');
			if( val == true ) {
				$('.selectRow-All').prop("checked", false);
				$('.selectRow').prop("checked", false);
			} else {
				$('.selectRow-All').prop("checked", true);			
				$('.selectRow').prop("checked", true);			
			}
		});

		//######################= ckeck/uncheck all checkbox =######################//
		$('#selectAll-chkbox').click(function() {
			var val = $('#selectAll-chkbox').is(':checked');
			if( val == false ) {
				$('.selectRow-All').prop("checked", false);
				$('.selectRow').prop("checked", false);
			} else {
				$('.selectRow-All').prop("checked", true);				
				$('.selectRow').prop("checked", true);				
			}
		});

    });
 </script>
