<?php 
use yii\helpers\Url;
use yii\widgets\LinkPager;
use common\models\User;
use yii\widgets\Pjax;

?>
<style>
.accordion-link { display:none; }
</style>
 <div class="col-md-10 col-sm-9 col-xs-12 scrolldiv01">          
        <div class="responsive-tabs-container accordion-xs accordion-sm pastBookingAccord">
          <div class="tabHead pastBooking">
            <ul class="nav nav-tabs responsive-tabs">
              <li class="active"><a data-toggle="tab" href="#menu1">Scheduled Sessions</a></li>
              <li><a data-toggle="tab" href="#menu2">Video History</a></li>
             
            </ul>
          </div>
          <div class="tab-content">
            <div id="menu1" class="tab-pane  active">
				<!--upcoming list starts-->
				<?php
					Pjax::begin(['id' => 'Pjax_videolistingResults', 'timeout' => false, 'enablePushState' => false, 'clientOptions' => ['method' => 'GET']]);
				?>
<?php 
$userModel = new User();
if(isset($listing) && count($listing)>0) {
?>
      <p class="slide" style=" float:right; text-align:right;">scroll right <i class="fa fa-angle-double-right" aria-hidden="true"></i></p>
        <div class="tablePayments">
          <div class="table-responsive">
            <table class="table">
              <colgroup>
              <col width="250px">
            
              <col width="142px">
              <col width="128px">
              <col width="128px">
              <col width="105px">
               
              </colgroup>
              <thead>
                <tr>
                  <!--th>Name</th-->
                  <th>Description</th>
                  <th>Video call with</th>                
                  <th>Scheduled Date</th>
                  <th>Actions</th>                  
                </tr>
              </thead>
              <tbody> 
              <?php foreach($listing as $key=>$val) {
              
              if(Yii::$app->user->identity->id == $val['pet_owner_id']){
              
              $userinfo = $userModel->findIdentity($val['pet_sitter_id']);
              $usernme = $userinfo->firstname.' '.$userinfo->lastname;
              }else{
              $userinfo = $userModel->findIdentity($val['pet_owner_id']);
              $usernme = $userinfo->firstname.' '.$userinfo->lastname;
              
              }
                                          
              //$currentuser 	= Yii::$app->user->getId();
              ?>                               
                <tr>
                  <!--td><p><?php echo $val['name']; ?></p></td-->
                  <td><p><?php echo $val['description']; ?></p></td>
                  <td><p><?php echo $usernme; ?></p></td>            
                  <td><p><?php 
                  $date = (isset($val['schedule_datetime']) ? date(DATEPICKER_FORMAT_PHP,strtotime($val['schedule_datetime'])) : "");
                  echo $date ;?></p></td>
                  <td>                  
                  <?php if(Yii::$app->user->identity->id == $val['pet_sitter_id'] && $val['approv_status'] == 0){ ?>
                  <a href="<?php echo Url::to(['video/acceptreq','id'=>$val['id']?$val['id']:null]) ;?>" data-pjax = "false" class="orangeBtn orangeBtnTable">Accept/Decline <i class="fa fa-angle-right" aria-hidden="true"></i></a>
                  <?php }elseif($val['approv_status'] == 1){ ?>
                  <a href="<?php echo Url::to(['video/updatevidsession','id'=>$val['id']?$val['id']:null]) ;?>" data-pjax = "false" class="orangeBtn orangeBtnTable">Update/cancel <i class="fa fa-angle-right" aria-hidden="true"></i></a>
                  <?php 
                  }elseif($val['approv_status'] == 2){
                  echo "Declined";
                  }else{ ?>                  
                 <a href="<?php echo Url::to(['video/updatevidsession','id'=>$val['id']?$val['id']:null]) ;?>" data-pjax = "false" class="orangeBtn orangeBtnTable">Update/cancel <i class="fa fa-angle-right" aria-hidden="true"></i></a>
                  <?php } ?>
                  <!--button id="requestbutton" class="orangeBtn orangeBtnTable" onclick="sendrequest(<?php $val['id']; ?>)" >Make Call <i class="fa fa-angle-right" aria-hidden="true"></i> </button-->
                  <?php if($val['approv_status'] == 1){ ?>
                  <a href="<?php echo Url::to(['video/talk','id'=>$val['id']?$val['id']:null]) ;?>" data-pjax = "false" class="orangeBtn orangeBtnTable" style="margin-top: 15px;">Make Call <i class="fa fa-angle-right" aria-hidden="true"></i></a>
                  <?php } ?>
                  </td>
                  
                </tr>
             <?php }?>   
              </tbody>
            </table>
          </div>
        </div>
        <?php          
            }else{
            echo '<p>'.NO_RESULT.'</p>';
            
            } ?> 
        <p class="slide">scroll to view</p>
        <div class="customPagination">
          <nav aria-label="Page navigation">
           
            <?php echo LinkPager::widget([
    'pagination' => $pages,
			]);?>
          </nav>
        </div>

      <script> 
    /* function sendrequest(bookingid){     
     $.ajax({ 
		url:'<?php echo Url::to(['payments/sendrequest']);?>',
		type:'post',
		data:{'booking_id':bookingid},
		success:function(response){
			if(response==true){
			$('#requestbutton').hide();
			$('#paymentrequest').show();
			}
		}	
	});
	}*/	
      
      </script>
 <?php Pjax::end(); ?> 
				<!--upcoming listing ends-->
            </div>
            
            <div id="menu2" class="tab-pane listingHis">
            <!-- history list starts-->
     	<!--upcoming list starts-->
				<?php
					Pjax::begin(['id' => 'Pjax_videohistorylistingResults', 'timeout' => false, 'enablePushState' => false, 'clientOptions' => ['method' => 'GET']]);
				?>
<?php 
$userModel = new User();
if(isset($videohistory) && count($videohistory)>0) {
?>

      <p class="slide" style=" float:right; text-align:right;">scroll right <i class="fa fa-angle-double-right" aria-hidden="true"></i></p>
        <div class="tablePayments">
          <div class="table-responsive">
            <table class="table">
              <colgroup>
              <col width="270px">                     
              <col width="142px">              
              <col width="128px">
              <col width="105px">
               
              </colgroup>
              <thead>
                <tr>
                  <!--th>Name</th-->
                  <th>Description</th>
                  <th>Video call with</th>
                 
                  <th>Scheduled Date</th>
                  <th></th>                  
                </tr>
              </thead>
              <tbody> 
              <?php foreach($videohistory as $key=>$val) {
              if(Yii::$app->user->identity->id == $val['pet_owner_id']){
              
              $userinfo = $userModel->findIdentity($val['pet_sitter_id']);
              $usernme = $userinfo->firstname.' '.$userinfo->lastname;
              }else{
              $userinfo = $userModel->findIdentity($val['pet_owner_id']);
              $usernme = $userinfo->firstname.' '.$userinfo->lastname;
              
              }
                                 
              //$currentuser 	= Yii::$app->user->getId();
              ?>                               
                <tr>
                  <!--td><p><?php echo $val['name']; ?></p></td-->
                  <td><p><?php echo $val['description']; ?></p></td>
                  <td><p><?php echo $usernme; ?></p></td>
                  
                  <td><p><?php
                  $date = (isset($val['schedule_datetime']) ? date(DATEPICKER_FORMAT_PHP,strtotime($val['schedule_datetime'])) : "");
                  echo $date;?></p></td>
                  <td><!--button id="requestbutton" class="orangeBtn orangeBtnTable" onclick="sendrequest(<?php $val['id']; ?>)" >Make Call <i class="fa fa-angle-right" aria-hidden="true"></i> </button>
                  <!--a href="<?php echo Url::to(['video/talk','id'=>$val['id']?$val['id']:null]) ;?>" data-pjax = "false" class="orangeBtn orangeBtnTable">Make Call <i class="fa fa-angle-right" aria-hidden="true"></i></a-->
                  </td>
                </tr>
             <?php }?>   
              </tbody>
            </table>
          </div>
        </div>
        <?php          
            }else{
            echo '<p>'.NO_RESULT.'</p>';
            
            } ?> 
        <p class="slide">scroll to view</p>
        <div class="customPagination">
          <nav aria-label="Page navigation">
           
            <?php echo LinkPager::widget([
    'pagination' => $pages,
			]);?>
          </nav>
        </div>

      <script> 
    /* function sendrequest(bookingid){     
     $.ajax({ 
		url:'<?php echo Url::to(['payments/sendrequest']);?>',
		type:'post',
		data:{'booking_id':bookingid},
		success:function(response){
			if(response==true){
			$('#requestbutton').hide();
			$('#paymentrequest').show();
			}
		}	
	});
	}*/	
      
      </script>
 <?php Pjax::end(); ?> 
            <!--history list ends-->
            </div>
            
          </div>
        </div>
    <!--custom pagination-->
      </div>
    </div>
  </div>
  </div>
</section>
