<?php 
use yii\helpers\Url;
use yii\widgets\LinkPager;
use common\models\User;
use frontend\models\Booking;
use yii\widgets\Pjax;
$attributes = Yii::$app->user->identity->getattributes();
$session 		= Yii::$app->session;
$logged_user 	= $session->get('loggedinusertype');

$logUserType = '';
if($logged_user==1){
$logUserType = 'Owner';
}elseif($logged_user==2){
$logUserType = 'Sitter';
}elseif($logged_user==3){
$logUserType = 'Borrower';
}


$current_credits	=	(isset($attributes['sitter_credits']) ? $attributes['sitter_credits'] : 0);

?>



<?php
	Pjax::begin(['id' => 'Pjax_paymentlistingResults', 'timeout' => false, 'enablePushState' => false, 'clientOptions' => ['method' => 'GET']]);
?>
<?php 
$BookingModel = new Booking();
if(isset($listing) && count($listing)>0) {//echo "<pre>"; print_r($listing); die;
?>
<div class="col-md-10 col-sm-9 col-xs-12 scrolldiv01">
			<?php if (Yii::$app->session->getFlash('item')): ?>
			<script>
						$(document).ready(function () {
						// Handler for .ready() called.
						$('html, body').animate({
							scrollTop: $('#scrrollhere').offset().top
						}, 'slow');
						});
												
						</script>
				<div class="col-xs-12" id="scrrollhere">
					<div class="alert alert-grey alert-dismissible">
						<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span>
						</button>
						<i class="glyphicon glyphicon-ok"></i><?php echo Yii::$app->session->getFlash('item'); ?>
					</div>
				</div>
			<?php endif; ?>		
      <p class="slide" style=" float:right; text-align:right;">scroll right <i class="fa fa-angle-double-right" aria-hidden="true"></i></p>
		<div style="
    float:  right;
    margin-bottom:  10px;
">

<?php 

	$array = array();
	foreach($listing as $key=>$val) 
	{
		
		if(isset($val['booking_status']) && $val['booking_status']=='3')
		{
			$status = 'Booking Cancelled';
			$showbutton = false;
		}
		else
		{
			$currentuser 	= Yii::$app->user->getId();
			if($val['user_id'] == $currentuser)
			{
				$showbutton = false;								
			}
			else
			{
				if(!empty($val['request_status']) && $val['request_status']=='1')
				{
					$status = 'Paid';
				}
				elseif($val['request_status']=='0' && !empty($val['disbursementid']))
				{
					$status = 'Request sent';			 
				}
				else
				{
					$status = 'Pending';
					$showbutton = true;
				}
			}
		}
		if(isset($status) && $status== 'Booking Cancelled')
		{
			$array[] = $val['booking_id'];
		}
		
	}

 $fulldata = implode(',',$array);

?>

<button id="ids" idsss="<?php echo $fulldata; ?>" class="orangeBtn orangeBtnTable" onclick="sendrequest1();return false;" >
Request payment for <?= CURRENCY_SIGN.$current_credits ?> <i class="fa fa-angle-right" aria-hidden="true"></i> </button>
</div>
        <div class="tablePayments">
          <div class="table-responsive">
            <table class="table">
              <colgroup>
              <col width="200px">
              <col width="128px">
              <col width="142px">
              <col width="105px">
              <col width="128px">
              <col width="200px">
              <!--<col width="180px">-->
              </colgroup>
              <thead>
                <tr>
                  <th>Name</th>
                  <th>From</th>
                  <th>To</th>
                  <th>Paid</th>
                  <th>Earned</th>
                  <th>Payment Request</th>
                  <th>&nbsp;</th>
                </tr>
              </thead>
              <tbody> 
              <?php foreach($listing as $key=>$val) {
              //print_r($val);
             
              $currentdate = date('Y-m-d');
              $reqDis = 0;
              if(strtotime($currentdate) > strtotime($val['booking_to_date'])){
              $reqDis = 1;
              }
              $name = $val['firstname'].' '.$val['lastname'];
              //$fromdate = $val['booking_from_date'];
              //$todate = $val['booking_to_date'];
              $fromdate = (isset($val['booking_from_date']) ? date(DATEPICKER_FORMAT_PHP,strtotime($val['booking_from_date'])) : "");
              $todate = (isset($val['booking_to_date']) ? date(DATEPICKER_FORMAT_PHP,strtotime($val['booking_to_date'])) : "");
              $currentuser 	= Yii::$app->user->getId();
              $st = true;
              if($val['user_id'] == $currentuser) {
              $earned = '--';
              $paid = CURRENCY_SIGN.($val['bookingamount']);
	      	$st = false;
              }else{
              $earned = CURRENCY_SIGN.($val['booking_credits']);
              $paid = '--';
              $userModel = new User();
              //$finduserInfo = $userModel->findIdentity($val['pet_sitter_id']);
              //$name = $finduserInfo['firstname'].' '.$finduserInfo['lastname'];
              }
              $status = '--';
              $showbutton = false;
              
			if(isset($val['booking_status']) && $val['booking_status']=='3'){
			$status = 'Booking Cancelled';
			$showbutton = false;
			}else{
			if($val['user_id'] == $currentuser){
			$showbutton = false;								
			}else{
			 if(!empty($val['request_status']) && $val['request_status']=='1'){
			 $status = 'Paid';
			 }elseif($val['request_status']=='0' && !empty($val['disbursementid'])){
			 $status = 'Request sent';			 
			 }else{
			 $status = 'Pending';
             $showbutton = true;
			 }
			
			}
			
			
             //if(!empty($val['request_status']) && $val['request_status']=='1' || ($val['pet_owner_id'] == $currentuser)){
              /* if(!empty($val['request_status']) && $val['request_status']=='1' && $val['user_id'] == $currentuser){
              $status = 'Paid';
              $showbutton = false;
              }if(!empty($val['request_status']) && $val['request_status']=='1' && $val['user_id'] != $currentuser){
              $status = '--';
              $showbutton = false;
              }elseif($val['request_status']=='0' && !empty($val['disbursementid']) && $val['user_id'] != $currentuser){
              $status = 'Request sent';
              $showbutton = true;
              }elseif($val['request_status']=='0' && !empty($val['disbursementid']) && $val['user_id'] == $currentuser){
              $status = '--';
              $showbutton = false;            
              }else{
              $status = 'Pending';
              $showbutton = true;
              }*/
              
              
              
}
              ?>                               
                <tr>
                  <td><p><?php echo $name; ?></p></td>
                  <td><p><?php echo $fromdate; ?></p></td>
                  <td><p><?php echo $todate; ?></p></td>
                  <td><p><span><?php echo $paid;?></span></p></td>
                  <td><p><span><?php echo $earned; ?></span></p></td>
                  <td><p><?php echo $status; ?></p></td>
                  <!--<td><?php if($st == true && empty($val['disbursementid']) && $reqDis==1 && $val['booking_status']!='3'){?><button id="requestbutton<?php echo $val['booking_id']?>" class="orangeBtn orangeBtnTable" onclick="sendrequest(<?php echo $val['booking_id']?>);return false;" >Request payment <i class="fa fa-angle-right" aria-hidden="true"></i> </button><?php } ?></td>-->
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
            <!--ul class="pagination">
              <li> <a href="javascript:void(0)" aria-label="Previous"> <span aria-hidden="true"><i class="fa fa-angle-left" aria-hidden="true"></i></span> </a> </li>
              <li><a href="#">1</a></li>
              <li class="active"><a href="#">2</a></li>
              <li><a href="#">3</a></li>
              <li><a href="#">4</a></li>
              <li><a href="#">5</a></li>
              <li> <a href="javascript:void(0)" aria-label="Next"> <span aria-hidden="true"><i class="fa fa-angle-right" aria-hidden="true"></i></span> </a> </li>
            </ul-->
            <?php echo LinkPager::widget([
    'pagination' => $pages,
			]);?>
          </nav>
        </div>
      </div>
      <script> 
     function sendrequest1(){    
	 var bookingid = $('#ids').attr('idsss');
 
     $.ajax({ 
		url:'<?php echo Url::to(['payments/sendrequest1']);?>',
		type:'post',
		data:{'booking_id':bookingid},
		success:function(response){
			if(response==true){
			$('#requestbutton'+bookingid).hide();
			//$('#paymentrequest').show();
			}
		}	
	});
	}	
      
      </script>
 <?php Pjax::end(); ?>       
