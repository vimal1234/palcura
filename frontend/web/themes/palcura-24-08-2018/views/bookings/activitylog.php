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
					foreach($activityDataArray as $key=>$val){
					
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
														<td><ul><?php foreach($tmp as $ke=>$val){
														
														 if($ke==1){ $val = arraymultisort($val); ?> 
														<?php foreach($val as $m=>$p){ ?>
														<li>
														<?php echo $p['activity_start'];
														?>
														</li>
														<?php } } }?></ul></td>			
														<td><ul><?php foreach($tmp as $ke=>$val){ if($ke==2){ $val = arraymultisort($val);?> 
														<?php foreach($val as $m=>$p){ ?>
														<li>
														<?php echo $p['activity_start'];
														?>
														</li>
														<?php } } }?></ul></td>
														<td><ul><?php foreach($tmp as $ke=>$val){ if($ke==3){ $val = arraymultisort($val);?> 
														<?php foreach($val as $m=>$p){ ?>
														<li>
														<?php echo $p['activity_start'];
														?>
														</li>
														<?php } } }?></ul></td>
														<td><ul><?php foreach($tmp as $ke=>$val){ if($ke==4){ $val = arraymultisort($val);?> 
														<?php foreach($val as $m=>$p){ ?>
														<li>
														<?php echo $p['activity_start'];
														?>
														</li>
														<?php } } }?></ul></td>
														<td><ul><?php foreach($tmp as $ke=>$val){ if($ke==5){ $val = arraymultisort($val);?> 
														<?php foreach($val as $m=>$p){ ?>
														<li>
														<?php echo $p['activity_start'];
														?>
														</li>
														<?php } } }?></ul></td>
														<td><ul><?php foreach($tmp as $ke=>$val){ if($ke==6){ $val = arraymultisort($val); ?> 
														<?php foreach($val as $m=>$p){ ?>
														<li>
														<?php echo $p['activity_start'];
														?>
														</li>
														<?php } } }?></ul></td>
														<td><ul><?php foreach($tmp as $ke=>$val){ if($ke==7){ $val = arraymultisort($val);?> 
														<?php foreach($val as $m=>$p){ ?>
														<li>
														<?php echo $p['activity_start'];
														?>
														</li>
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
					
					echo "<p>No activities yet.</p>";
					}?>
				</section>
 <?php Pjax::end(); ?> 				
