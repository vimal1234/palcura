<?php 
use yii\widgets\Pjax;
?>
<?php
	Pjax::begin(['id' => 'Pjax_Activitytoplistresults', 'timeout' => false, 'enablePushState' => false, 'clientOptions' => ['method' => 'GET']]);
?>
<?php 
	$tmp=array();
	
	foreach($activityTypelisting as $arg)
	{ 
	$tmp[$arg['activity_id']][] = $arg;
	}
	
	function arraymultisortdashboard($array=array()){
		if(!empty($array) && count($array)>0){ 
		$time = array();
		foreach ($array as $key => $row)
		{
		$time[$key] = $row['id'];
		}
		array_multisort($time, SORT_DESC, $array);
		return $array;
		}else{
		return array();
		}
	}
	
	$session 		= Yii::$app->session;
	$logged_user 	= $session->get('loggedinusertype');
	/*foreach($tmp as $key=>$val){
	echo "<pre>"; print_r(reset($val));
	}*/
?>

<div class="dashboardList">
								<div class="dashboardHead">Update activity for <?php echo date('m/d/y',strtotime($activitydate)) ;?></div>
								<ul>
								<?php 
								$count = 0;
								foreach($tmp as $key=>$val){
								$count++;
								$val = arraymultisortdashboard($val); 
								$newval = reset($val);
								 
								if(isset($newval['activity_start']) && !empty($newval['activity_start'])){
								 $dbtime =  $newval['activity_updated_at']; 
								 //print_r($dbtime);echo "<br>";
								 $timevar = true;
								 }else{
								 //echo "NA";echo '<br>'; 
								 $dbtime = '';
								 $timevar = false; 
								 }
								?>
									<?php if($logged_user==SITTER || $logged_user==RENTER){ ?>
								<li>
								<a href="" id="anchorid<?php echo $newval['activity_id'];?>" onclick="updateActivity(<?php echo $newval['activity_id'];?>);return false;">
								<?php if(isset($newval['activity_start']) && !empty($newval['activity_start'])){ ?>
								<div class="check" id="activity<?php echo $newval['activity_id'];?>"><i class=" fa fa-check" aria-hidden="true"></i></div>
								<?php }else{ ?>
								<div class="uncheck" id="activity<?php echo $newval['activity_id'];?>"></div>
								<?php } ?>
								</a>
								<p id="time<?php echo $newval['activity_id'];?>"><!--?php //if(isset($newval['activity_start']) && !empty($newval['activity_start'])){ //echo $newval['activity_start']; }else{ //echo 'NA'; }?--></p>
							    <div class="dashboardListing"><?php echo $newval['activityname'];?></div>
								</li>
									<?php }else{ ?>
									<li>
									<?php if(isset($newval['activity_start']) && !empty($newval['activity_start'])){
									?>
																							
								<div class="check" id="activity<?php echo $newval['activity_id'];?>"><i class=" fa fa-check" aria-hidden="true"></i></div><!--p> <?php //echo $newval['activity_start']; ?></p>
							    <div class="dashboardListing"><?php //echo $newval['activityname'];?></div-->
							    
									 <?php }else{ ?>									 
									 <div class="uncheck" id="activity<?php echo $newval['activity_id'];?>"></div>
									 
									 <?php }?>
									 <p id="time<?php echo $newval['activity_id'];?>"><!--?php //if(isset($newval['activity_start']) && !empty($newval['activity_start'])){ //echo $newval['activity_start']; }else{ //echo 'NA'; }?--></p>
									 <div class="dashboardListing"><?php echo $newval['activityname'];?></div>
								</li>
									<?php }?>
									
<script>
//var offset = new Date().getTimezoneOffset();
var timevar = "<?php echo $timevar ; ?>";
if(timevar == true){
var dateFromDb = "<?php echo strtotime($dbtime); ?>";

var monthNames = [
    "January", "February", "March",
    "April", "May", "June", "July",
    "August", "September", "October",
    "November", "December"
  ];	
 var Messagenow = new Date(dateFromDb * 1000);

 Messagenow.setMinutes(Messagenow.getMinutes());
 var day = Messagenow.getDate();
 var monthIndex = Messagenow.getMonth();
 var year = Messagenow.getFullYear();
 var msgdate =  monthNames[monthIndex] + ' ' + day + ' ' + year;
 //$('a#newdate'+"<?php echo $count;?>").text(msgdate);
 DisplayCurrentTime(Messagenow);
 }else{
 $('#time'+"<?php echo $newval['activity_id']; ?>").text('NA'); 
 } 
 function DisplayCurrentTime(Msgdate) {
        var date = Msgdate;
        var hours = date.getHours() > 12 ? date.getHours() - 12 : date.getHours();  
       
        var am_pm = date.getHours() >= 12 ? "PM" : "AM";
      
        hours = hours < 10 ? "0" + hours : hours;
        var minutes = date.getMinutes() < 10 ? "0" + date.getMinutes() : date.getMinutes();
        var seconds = date.getSeconds() < 10 ? "0" + date.getSeconds() : date.getSeconds();
        time = hours + ":" + minutes + " " + am_pm; 
        $('#time'+"<?php echo $newval['activity_id']; ?>").text(time);
       
    };
    
</script>
						
								<?php }
								?>
									
								</ul>
							</div>
						</div>
						
<?php Pjax::end(); ?>
						
