<?php 
use yii\widgets\Pjax;
?>
<?php
	Pjax::begin(['id' => 'Pjax_Activityesults', 'timeout' => false, 'enablePushState' => false, 'clientOptions' => ['method' => 'GET']]);
	
	function arraymultisort($array=array()){
		if(!empty($array)){
		$time = array();
		foreach ($array as $key => $row)
		{
			$time[$key] = $row['id'];
		}
		 array_multisort($time, SORT_ASC, $array);
		return $array;
		}else{
		return array();
		}
	}
?>
<section class="activityLog">
					<div class="row">
						<div class="col-md-12 col-sm-12 col-xs-12">
							<div class="head-all">Activity log</div>
						</div>
					</div>
					<?php 
				
					//print_r($activityDataArray); exit();
					if(!empty($activityDataArray) && count($activityDataArray)>0){
					$countrow = 0;
					foreach($activityDataArray as $key=>$val){
					$countrow++;
					$tmp=array();			
					foreach($val as $arg)
					{ 
						$tmp[$arg['activity_id']][] = $arg;				
					}
				
					?>
					<div class="row">
						<div class="col-md-12 col-sm-12 col-xs-12">
							<div class="activityBox">
								<div class="greyBox">
									<div class="activityLogInner">
										<div class="activityLogDate"><?php echo date('m/d/y',strtotime($key)) ;?></div>
										<div class="tableresp">   
											<table class="table">
												<thead>
													<tr>
														<th>Woke up</th>
														<th>Ate food</th>
														<th>Walk done</th>
														<th>Playing</th>
														<th>Sleeping</th>
														<th>At park</th>
														<th>On couch/bed</th>
													</tr>
												</thead>
												<tbody>
																																			
													<tr>																			
														<td><ul><?php 
														
														foreach($tmp as $ke=>$val){
														
														 if($ke==1){ $val = arraymultisort($val); ?>
														  
														<?php foreach($val as $m=>$p){
														if(isset($p['activity_start']) && !empty($p['activity_start'])){
														$dbtime =  $p['activity_updated_at'];
														 
														 $timevar = true;
														 }else{ 
														 $dbtime = '';
														 $timevar = false; 
														 }
														
														 ?>
														<li id="<?php echo $ke.$m.$countrow; ?>">
														<!--?php //echo $p['activity_start'];
														?-->
														</li>
														
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
 var key = "<?php echo $ke.$m.$countrow; ?>";
 DisplayCurrentTime(Messagenow,key);
 }
  function DisplayCurrentTime(Msgdate ,key) {
        var date = Msgdate;   
        var hours = date.getHours() > 12 ? date.getHours() - 12 : date.getHours();       
        var am_pm = date.getHours() >= 12 ? "PM" : "AM";
        hours = hours < 10 ? "0" + hours : hours;
        var minutes = date.getMinutes() < 10 ? "0" + date.getMinutes() : date.getMinutes();
        var seconds = date.getSeconds() < 10 ? "0" + date.getSeconds() : date.getSeconds();
        time = hours + ":" + minutes + " " + am_pm; 
      
        $('#'+key).text(time);
       
    };    
</script>
														
														
														<?php } } }?></ul></td>			
														<td><ul><?php foreach($tmp as $ke=>$val){ if($ke==2){ $val = arraymultisort($val);?> 
														<?php foreach($val as $m=>$p){ 
														if(isset($p['activity_start']) && !empty($p['activity_start'])){
														 $dbtime =  $p['activity_updated_at']; 
														 $timevar = true;
														 }else{ 
														 $dbtime = '';
														 $timevar = false; 
														 }
														?>
														<li id="<?php echo $ke.$m.$countrow; ?>">
														<!--?php //echo $p['activity_start'];
														?-->
														</li>
														
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
 var key = "<?php echo $ke.$m.$countrow; ?>";
 DisplayCurrentTime(Messagenow,key);
 }
 
  function DisplayCurrentTime(Msgdate ,key) {
        var date = Msgdate;
        var hours = date.getHours() > 12 ? date.getHours() - 12 : date.getHours();       
        var am_pm = date.getHours() >= 12 ? "PM" : "AM";
        hours = hours < 10 ? "0" + hours : hours;
        var minutes = date.getMinutes() < 10 ? "0" + date.getMinutes() : date.getMinutes();
        var seconds = date.getSeconds() < 10 ? "0" + date.getSeconds() : date.getSeconds();
        time = hours + ":" + minutes + " " + am_pm;        
        $('#'+key).text(time);
       
    };    
</script>
														<?php } } }?></ul></td>
														<td><ul><?php foreach($tmp as $ke=>$val){ if($ke==3){ $val = arraymultisort($val);?> 
														<?php foreach($val as $m=>$p){ 
														if(isset($p['activity_start']) && !empty($p['activity_start'])){
														 $dbtime =  $p['activity_updated_at']; 
														 $timevar = true;
														 }else{ 
														 $dbtime = '';
														 $timevar = false; 
														 }
														?>
														<li id="<?php echo $ke.$m.$countrow; ?>">
														<!--?php //echo $p['activity_start'];
														?-->
														</li>
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
 var key = "<?php echo $ke.$m.$countrow; ?>";
 DisplayCurrentTime(Messagenow,key);
 }
 
  function DisplayCurrentTime(Msgdate ,key) {
        var date = Msgdate;
        var hours = date.getHours() > 12 ? date.getHours() - 12 : date.getHours();       
        var am_pm = date.getHours() >= 12 ? "PM" : "AM";
        hours = hours < 10 ? "0" + hours : hours;
        var minutes = date.getMinutes() < 10 ? "0" + date.getMinutes() : date.getMinutes();
        var seconds = date.getSeconds() < 10 ? "0" + date.getSeconds() : date.getSeconds();
        time = hours + ":" + minutes + " " + am_pm;        
        $('#'+key).text(time);
       
    };    
</script>														
														<?php } } }?></ul></td>
														<td><ul><?php foreach($tmp as $ke=>$val){ if($ke==4){ $val = arraymultisort($val);?> 
														<?php foreach($val as $m=>$p){ 
														
														if(isset($p['activity_start']) && !empty($p['activity_start'])){
														 $dbtime =  $p['activity_updated_at']; 
														 $timevar = true;
														 }else{ 
														 $dbtime = '';
														 $timevar = false; 
														 }
														?>
														<li id="<?php echo $ke.$m.$countrow; ?>">
														<!--?php //echo $p['activity_start'];
														?-->
														</li>
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
 var key = "<?php echo $ke.$m.$countrow; ?>";
 DisplayCurrentTime(Messagenow,key);
 }
 
  function DisplayCurrentTime(Msgdate ,key) {
        var date = Msgdate;
        var hours = date.getHours() > 12 ? date.getHours() - 12 : date.getHours();       
        var am_pm = date.getHours() >= 12 ? "PM" : "AM";
        hours = hours < 10 ? "0" + hours : hours;
        var minutes = date.getMinutes() < 10 ? "0" + date.getMinutes() : date.getMinutes();
        var seconds = date.getSeconds() < 10 ? "0" + date.getSeconds() : date.getSeconds();
        time = hours + ":" + minutes + " " + am_pm;        
        $('#'+key).text(time);
       
    };    
</script>														
														<?php } } }?></ul></td>
														<td><ul><?php foreach($tmp as $ke=>$val){ if($ke==5){ $val = arraymultisort($val);?> 
														<?php foreach($val as $m=>$p){ 
														if(isset($p['activity_start']) && !empty($p['activity_start'])){
														 $dbtime =  $p['activity_updated_at']; 
														 $timevar = true;
														 }else{ 
														 $dbtime = '';
														 $timevar = false; 
														 }
														
														?>
														<li id="<?php echo $ke.$m.$countrow; ?>">
														<!--?php //echo $p['activity_start'];
														?-->
														</li>
														
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
 var key = "<?php echo $ke.$m.$countrow; ?>";
 DisplayCurrentTime(Messagenow,key);
 }
 
  function DisplayCurrentTime(Msgdate ,key) {
        var date = Msgdate;
        var hours = date.getHours() > 12 ? date.getHours() - 12 : date.getHours();       
        var am_pm = date.getHours() >= 12 ? "PM" : "AM";
        hours = hours < 10 ? "0" + hours : hours;
        var minutes = date.getMinutes() < 10 ? "0" + date.getMinutes() : date.getMinutes();
        var seconds = date.getSeconds() < 10 ? "0" + date.getSeconds() : date.getSeconds();
        time = hours + ":" + minutes + " " + am_pm;        
        $('#'+key).text(time);
       
    };    
</script>														
														<?php } } }?></ul></td>
														<td><ul><?php foreach($tmp as $ke=>$val){ if($ke==6){ $val = arraymultisort($val); ?> 
														<?php foreach($val as $m=>$p){ 
														if(isset($p['activity_start']) && !empty($p['activity_start'])){
														 $dbtime =  $p['activity_updated_at']; 
														 $timevar = true;
														 }else{ 
														 $dbtime = '';
														 $timevar = false; 
														 }
														?>
														<li id="<?php echo $ke.$m.$countrow; ?>">
														<!--?php //echo $p['activity_start'];
														?-->
														</li>
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
 var key = "<?php echo $ke.$m.$countrow; ?>";
 DisplayCurrentTime(Messagenow,key);
 }
 
  function DisplayCurrentTime(Msgdate ,key) {
        var date = Msgdate;
        var hours = date.getHours() > 12 ? date.getHours() - 12 : date.getHours();       
        var am_pm = date.getHours() >= 12 ? "PM" : "AM";
        hours = hours < 10 ? "0" + hours : hours;
        var minutes = date.getMinutes() < 10 ? "0" + date.getMinutes() : date.getMinutes();
        var seconds = date.getSeconds() < 10 ? "0" + date.getSeconds() : date.getSeconds();
        time = hours + ":" + minutes + " " + am_pm;        
        $('#'+key).text(time);
       
    };    
</script>														
														<?php } } }?></ul></td>
														<td><ul><?php foreach($tmp as $ke=>$val){ if($ke==7){ $val = arraymultisort($val);?> 
														<?php foreach($val as $m=>$p){ 
														
														if(isset($p['activity_start']) && !empty($p['activity_start'])){
														 $dbtime =  $p['activity_updated_at']; 
														 $timevar = true;
														 }else{ 
														 $dbtime = '';
														 $timevar = false; 
														 }
														?>
														<li id="<?php echo $ke.$m.$countrow; ?>">
														<!--?php //echo $p['activity_start'];
														?-->
														</li>
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
 var key = "<?php echo $ke.$m.$countrow; ?>";
 DisplayCurrentTime(Messagenow,key);
 }
 
  function DisplayCurrentTime(Msgdate ,key) {
        var date = Msgdate;
        var hours = date.getHours() > 12 ? date.getHours() - 12 : date.getHours();       
        var am_pm = date.getHours() >= 12 ? "PM" : "AM";
        hours = hours < 10 ? "0" + hours : hours;
        var minutes = date.getMinutes() < 10 ? "0" + date.getMinutes() : date.getMinutes();
        var seconds = date.getSeconds() < 10 ? "0" + date.getSeconds() : date.getSeconds();
        time = hours + ":" + minutes + " " + am_pm;        
        $('#'+key).text(time);
       
    };    
</script>														
														<?php } } }?></ul></td>
														
													</tr>
																								
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php }
					 }else{
					
					echo "No activities yet.";
					}?>
				</section>
				<script>
				
	  
				</script>
 <?php Pjax::end(); ?> 				
