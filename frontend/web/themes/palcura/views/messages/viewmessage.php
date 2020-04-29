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

$this->title = Yii::t('yii', 'View Message');
$this->params['breadcrumbs'][] = $this->title;
$request = Yii::$app->request;
$msgId = $request->get('id');$userId 	= Yii::$app->user->getId();
$userId = Yii::$app->user->getId();
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
                        <h4><?php echo Yii::t('yii', 'Inbox'); ?></h4>
                        <div class="table-responsive">
               <?php

                ?>
              <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />							
                            <table class="table ">
                                <tbody>
                                    
                                    <?php
                                    if(isset($messages) && !empty($messages)) {
                                    foreach ($messages as $key => $message) {
                                    ?>
                                        <tr class="unread">
                                            <td>
												<div class="Checkbox"></div>
												<?php 
													if($message['send_status'] == '1') {
														$senderName = "Admin";
													} else {
														if($userId == $message['user_from']) {
															$senderName = 'Me';
														} else {
															$senderName = (isset($message['sender_fname']) ? $message['sender_fname'] . " " . $message['sender_lname'] : '');
														}
													}
												?>                                         
                                            <?php echo Yii::t('yii', 'From'); ?>: <?= $senderName ?>
                                            </td>
                                            <td align="right"><div class="Checkbox"></div>
                                            <?php echo Yii::t('yii', 'Date'); ?>: <?= (isset($message['date_created']) ? date(MESSAGE_DATE_FORMAT, strtotime($message['date_created'])) : '') ?></td>                                            
                                        </tr>
                                        
                                        <?php
                                        if(!empty($message['subject']) && $key == '0' && 0)
                                        {
                                        ?>
                                            <tr>
                                                <td> <?php echo Yii::t('yii', 'Subject'); ?>: <?= (isset($message['subject']) ? $message['subject'] : '') ?></td>
                                            </tr>
                                        <?php
                                        }
                                        ?>     
                                            
                                        <tr>
                                            <td colspan="2"> 
												<?= (isset($message['message']) ? $message['message'] : '')?>
												<?php 
                                                                                                
if($message['booking_request'] == '1'):


					$form = ActiveForm::begin(
						[ 
							'id' => 'message-form'.$message['booking_id'],
							'action' => ['messages/bookrequestresponse/'.$msgId],
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
					    
    $bookingDetail = frontend\models\Booking::findOne($message['booking_id']); 
    if(!empty($bookingDetail)): ?>
        <div class="booking-detail">
        <div>Destination: <?=$bookingDetail->booking_destination?></div>
        <?php if($bookingDetail->no_of_days > 0) {  ?>
			<div>Booking From: <?=$bookingDetail->booked_from_date?></div>
			<div>Booking To: <?=$bookingDetail->booked_to_date?></div>			
			<div>No Of Days: <?=$bookingDetail->no_of_days?></div>
        <?php } ?>
        <?php if($bookingDetail->no_of_hours > 0) {  ?>
			<div>Booking Date: <?=$bookingDetail->booked_to_date?></div>
			<div>No Of Hours: <?=$bookingDetail->no_of_hours?></div>
        <?php } ?>
        <div>No Of Travellers: <?=$bookingDetail->no_of_travellers?></div>
        <?php if($userId == $bookingDetail->customer_user_id) {?>
			<div>Booking Price : $<?=$bookingDetail->booking_price ?></div>
        <?php } else { ?>
			 <div>Booking Price : $<?=$bookingDetail->booking_price-($bookingDetail->admin_fee+$bookingDetail->service_fee) ?></div>
        <?php } ?>
        </div>
                                                
   <?php endif; 
   
   if(isset($message['pbk']) && !empty($message['pbk']) && $message['user_to'] == Yii::$app->user->getId() && $message['booking_status'] == '0') {
	$b = $message['booking_id'];
    echo'<input type="hidden" name="bookingID" class="bookingID" value="'.$message['booking_id'].'">';
												echo'<input type="hidden" name="bookingResponse" id="bookingResponse'.$b.'" value="">';
												echo'<input type="hidden" name="messageID" id="messageID" value="'.$message['booking_id'].'">';
												echo'<input type="hidden" name="threadID" id="threadID" value="'.$msgId.'">';
												echo'<input type="hidden" name="user_to" id="user_to" value="'.$message['user_to'].'">';
												echo'<input type="hidden" name="user_from" id="user_from" value="'.$message['user_from'].'">';
												echo'<div class="fullwidth">
														<div style="margin-top:10px;">
															<button name="reply-btn" class="btn btn-primary accept accept-booking"  type="button" value="'.$b.'">Accept</button>
															<button name="reply-btn" class="btn btn-primary decline decline-booking"  type="button" value="'.$b.'">Decline</button>
														</div>
													</div>'; 
												} else if($message['booking_status'] == '1' && $message['booking_request'] == '1') {
													echo'</br> Booking: <b>Accepted</b>';
												} else if($message['booking_status'] == '2' && $message['booking_request'] == '1') {
													echo'</br> Booking: <b>Declined</b>';
												}
												ActiveForm::end();
endif;												?>
											</td>
                                        </tr>
                                    <?php
                                    }
									} else {
										echo'<tr>
												<td colspan="3" align="center">'.Yii::t('yii','No Messages').'</td>
											</tr>';	
									}
                                    ?>
<!--
<tr>
	<td>
		<textarea cols="50" rows="5"></textarea>
	</td>
</tr>
-->
           
           
                                  

                                </tbody>
                            </table>
                    <div class="message">        
                          <?php
                        $form = ActiveForm::begin( 
                            [ 
                            'id' => 'editProfile-form',
                            'action'=>['messages/messagereply/'.$messages['0']['message_id']],
                                'options' => [
                                    'enctype' => 'multipart/form-data',
                                    'tag' => 'span', ####THIS OPTIONS DISABLES THE DIV.FORM_GROUP ENCLOSER TAG FOR FIELDS
                                ],
                                'fieldConfig' => [
                                    'template' => "<div class=\"fullwidth\">\n
                                                        <div class=\"col-xs-16\">\n
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
                        ?>
                        <input type="hidden" name="Messages[user_from]" value="<?= $model['user_from'] ?>" />
                        <input type="hidden" name="Messages[user_to]" value="<?= $userTo ?>" />
                        <input type="hidden" name="Messages[booking_id]" value="<?= $model['booking_id'] ?>" />
                        <input type="hidden" name="Messages[send_status]" value="<?= (isset($model['send_status']) ? $model['send_status'] : '') ?>" />

                        <?php
                        echo $form->field($model, 'message', ['inputOptions' => [
                                'class' => "form-control textarea textfeild",
                            ]])->textarea(array("rows" => "4", 'value' => ''))->label(Yii::t('yii', 'Message'));
                        ?>
                        <div class="fullwidth">
                            <div class="col-xs-12">
                        <?= Html::submitButton(Yii::t('yii', 'Send'), ['class' => 'btn btn-primary orangebtn', 'name' => 'reply-btn', 'id' => 'reply-button']) ?>
                            </div>
                        </div>
                        <?php ActiveForm::end(); ?>                          
                            
                        <?php  ?>    
                        </div>
                        </div>
                        <p class="scrolltable"><?php echo Yii::t('yii', 'Scroll to see the table.'); ?></p>
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
                    <?php  
						//echo Html::a(Yii::t('yii', 'Reply'), ['messages/messagereply', 'id' => $messages['0']['message_id']], ['class' => 'orangebtn']);
				   ?>
                </div>
            </div>
        </div>
    </div>
<!--</div>-->
</section>
 <script>
    $(document).ready(function(){
        //########################= Accept booking request =########################//
	$('.accept-booking').click(function() {
				var booking_id = $(this).val();
                var acceptVal = $('#bookingResponse'+booking_id).val('1');
                var acptVal = $('#bookingResponse'+booking_id).val();
                var form_id = $(this).closest("form").attr("id");
                
                if(acptVal == '1') {
                        if(!confirm('Are you sure to accept booking request?')) {
                                return false;
                        }
						document.getElementById(form_id).submit();
                        return true;
                } else {
                        return false;
                }
	});
		//########################= Decline booking request =########################//
        $('.decline-booking').click(function() {
			var booking_id = $(this).val();
            var acceptVal = $('#bookingResponse'+booking_id).val('2');
            var acptVal = $('#bookingResponse'+booking_id).val();
            var form_id = $(this).closest("form").attr("id");
            
            if(acptVal == '2') {
                    if(!confirm('Are you sure to decline booking request?')) {
                            return false;
                    }
                    document.getElementById(form_id).submit();
                    return true;
            } else {
                    return false;
            }
        });
        
    });
 </script>
