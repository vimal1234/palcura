<?php  include("config.php");   ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Subscription List</title>
<!-- css link start-->
<link rel="stylesheet" href="css/bootstrap.css" type="text/css" />
<link rel="stylesheet" href="css/bootstrap.min.css" type="text/css" />
<link rel="stylesheet" href="css/countdown.css" type="text/css" />
<link rel="stylesheet" href="css/font-awesome.css" type="text/css" />
<link rel="stylesheet" href="css/core.css" type="text/css" />
<link rel="stylesheet" href="css/responsive.css" type="text/css" />
<link rel="stylesheet" type="text/css" href="css/validationEngine.jquery.css" />
<!-- css end-->
</head>
	<body>
		<header class="defineFloat">
		  <div class="container">
			<div class="row">
			  <div class="col-xs-12 text-center">
				<div class="logo"> <a href="javascript:void(0)"><img class="img-responsive" src="images/logo.png" alt="" /></a> </div>
			  </div>
			</div>
		</header>
		<section class="contentArea defineFloat">
			<div class="container">
				<div class="row">
					<div class="col-xs-12 text-center">
						<?php 
							$getquery = "select * from tbl_userDetails";
							$checkresult = mysqli_query($db,$getquery); 
							if($checkresult->num_rows > 0)  { 
						?>
						  <div class="table-responsive"> 
							<table class="subscribedList table borderless">
								<tr>
									<th>Sr No.</th>
									<th>Name of User</th>
									<th>Email</th>
									<th>Type</th>
									<th>Amount Credited</th>
									<th>Status</th>
								</tr>
								
								<?php 
									$i =0;
									while ($row = $checkresult->fetch_assoc()) { 
									if($row['amount_credited'] == 1)	{
										
										$amount = 'Yes';
										
									}  else {
										
										$amount = 'No';
										
									}
									
									if($row['status'] == 1 || $row['status'] == 2) {
										
										$status = 'Verified';
										
									}  else {
										
										$status = 'Pending';
										
									}
									
									$i++;	
								?>
								<tr>
									<td><?php  echo $i;  ?></td>
									<td><?php  echo $row['name'];  ?></td>
									<td><?php  echo $row['email'];  ?></td>
									<td><?php  echo $row['choice'];  ?></td>
									<td><?php  echo $amount;  ?></td>
									<td><?php  echo $status;  ?></td>
								</tr>
								<?php    }  ?>
							</table>
							</div>
						<?php   }  else  {   ?>
						
						<p>  No Subscriber Found. </p>	
							
						<?php    }  ?>		
					</div>
				</div>
			</div>	
		</section>			
	</body>
</html>	
