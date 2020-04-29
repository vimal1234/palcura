<?php
use yii\helpers\Html;
use yii\helpers\Url;  
use yii\helpers\ArrayHelper;

$this->title = 'Dashboard';
$this->params['breadcrumbs'][] = $this->title;
$siteimage = Yii::getAlias('@siteimage');
$attributes = Yii::$app->user->identity->getattributes();
$profile_picture = $attributes['profile_image'];
$profile_pic = isset($profile_picture) ? Url::home() . 'common/uploads/profile/' . $profile_picture : Yii::getAlias('@webThemeUrl') . '/images/noimage.png';
$session 		= Yii::$app->session;
$reminder 	= $session->get('reminedviewed');

$logged_user 	= $session->get('loggedinusertype');
$logUserType = '';
if($logged_user==1){
$logUserType = 'Owner';
}elseif($logged_user==2){
$logUserType = 'Sitter';
}elseif($logged_user==3){
$logUserType = 'Borrower';
}

if(!empty($logUserType)){
$messageText = 'You are currently logged in as <strong>'.$logUserType.'</strong>.';
}else{
$messageText = "";
} 
?>
			<div class="col-xs-12">
				<h1><?= $this->title ?></h1>
			</div>
	</div>
</header>
<section class="contentArea contentAreaDashboard">
	<div class="container">
	<div class="col-lg-12 row"><p style="padding: 20px 0;color: #FF8447;"><?php echo $messageText ;?></p>
		</div>
	<div id="reminder" class="row">				
	</div>
		<div class="row scrolldiv01">
			<?php echo $this->render('//common/sidebar'); ?>
			<?php if(!empty($myBookings) && count($myBookings)>0){ ?>
			<div class="col-md-4 col-sm-9 col-xs-12" >
			<label>Select Booking:</label>
			<select class="form-control" id="selectedbooking">
		  <?php foreach($myBookings as $k=>$v){ ?>
			<option value="<?php echo $v['id']; ?>"><?php echo $v['name'];?></option>		
		  <?php } ?>
			</select>
			</div>
			
			<div class="col-md-10 col-sm-9 col-xs-12">
				<section class="dashboardSitterMain dashowner">
					<div class="row">
						<div class="col-md-6 col-sm-7 col-xs-12">
							<div id="dashboardlistresult">
							<?php 
							echo $this->render('dashboardlist',['activityTypelisting'=>$activityTypelisting,'activitydate'=>$activitydate]);
							?>
							</div>
						<div class="col-md-6 col-sm-5 col-xs-12">
							<div class="dashboardUpload">
								<div class="dashboardHead">Charges</div>
								<div class="greyBox">
									<p>Total Charges</p>
									<h4 id="bookingprice">$ <?php echo $myBookings[0]['amount'];?></h4>
								</div>
							</div>
						</div>
					</div>
					<div class="bookingDetail bookingDetailDashboard">
						<div class="row">
							<div class="col-md-12 col-sm-12 col-xs-12">
								<div class="head-all">Pictures/Videos</div>
							</div>
						</div>
						
						
						<!--image slider start-->
						<div id="bookingimageslider">
                          <?php
                          echo $this->render('bookingimageslider',[
                          'bookingImages' => $bookingImages
                          ]);                          
                           ?>
                   		</div>
						<!--slider end-->
	
					</div>
				</section>
				<div id="search-result">								
				<?php  echo $this->render('activitylog',[
				'activityDataArray' => $activityDataArray,
				]);?>
				</div>
			</div><?php }else{
			//echo '<p align="center">'.NO_RESULT.'</p>';
			echo '<p align="center">You will see your pal activities on the service day and after your sitter updates the dashboard.</p>';
			} ?>
		</div>
	</div>
</section>
<script>
 $(document).ready(function(){
 var searchPost = {};
        $('#selectedbooking').on('change',function(){
			searchPost.bookingid = $(this).val();
			$('#uploads-bookingid').val($(this).val());
			filterSearchResult(searchPost);
		});
		
 });
 
 $('document').ready(function(){
var reminder= <?php echo $reminder; ?>;
if(reminder==0){
	$.ajax({
		url: "<?php echo Url::to(['users/bookingreminders']);?>",
		type: 'post',
		data: reminder,
		success: function (response) {
			$('#reminder').append(response);
		}
	});
	}else{
	console.log('no reminder');
	return false;	
	}		
});

function filterSearchResult(searchPost){
	$.ajax({ 
		url:'<?php echo Url::to(['users/activitylog']);?>',
		type:'post',
		data:{'filter':searchPost},
		success:function(response){					
				$('#search-result').html(response);
				$.ajax({ 
								url:'<?php echo Url::to(['users/bookingprice']);?>',
								type:'post',
								data:{'filter':searchPost},
								success:function(response){					
									$('#bookingprice').html(response);									
									}	
								});
					
				$.ajax({ 
				url:'<?php echo Url::to(['users/newdashboardlist']);?>',
				type:'post',
				data:{'filter':searchPost},
				success:function(response){					
					$('#dashboardlistresult').html(response);
					$.ajax({ 
						url:'<?php echo Url::to(['users/newimagelist']);?>',
						type:'post',
						data:{'filter':searchPost},
						success:function(response){					
							$('#bookingimageslider').html(response);
							
							
																
							}	
						});
														
					}	
				});								
		}	
	});		
}
</script>


