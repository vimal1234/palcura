<?php

$con = mysqli_connect("localhost","palcura_usr5745","Ea~C1+8cTT]X","palcura_d032018");
global $con;

// Check connection
if (mysqli_connect_errno()){
	echo "Failed to connect to MySQL: " . mysqli_connect_error();die;
}else{

require("PHPMailer.php");

require_once('/home3/palcura/public_html/frontend/braintree/vendor/autoload.php');
 $gateway = new Braintree\Gateway([
    'environment' => 'production',
    'merchantId' => 'g2ph339mbzqtbqbs',
    'publicKey' => '5sfhbt86vsbb5xnb',
    'privateKey' => '6af4e0ef524fa25c90657eaeb5ab8246'

]);

$bookinglist = getTodaysPaymentList();

     if(!empty($bookinglist)){
     
		 foreach($bookinglist as $key=>$val){
		 $sittername = 	$val['sittername'];
		 $sitteremail = $val['sitteremail'];
		 $ownername = $val['ownername'];
		 $owneremail = $val['owneremail'];
		 $rentername = $val['rentername'];
		 $renteremail = $val['renteremail'];
		 $services = $val['booking_services'];
		 $fromdate = date('m/d/y',strtotime($val['booking_from_date']));
		 $todate = date('m/d/y',strtotime($val['booking_to_date']));
		$datetime = $fromdate.'-'.$todate;
		$bookingid = $val['id'];
		 $servicename = getservicenames($services);
		if(!empty($servicename)){		
		$servicenames = implode(',',$servicename);
		}
		//$amount = $val['amount'];
$amount = $val['reward_points'];
		$admindata = getAdminemail();
		$from = $admindata['email'];
		$petsitterid = $val['pet_sitter_id'];
		$petownerid = $val['pet_owner_id'];
		$petrenterid = $val['pet_renter_id'];
		$customerid = $val['ownercustomerid'];
		
		if($petrenterid >0){			
		$customerid = $val['rentercustomerid'];					
		}
		//$customer = $gateway->customer()->find($customerid);
		
		   // below code is only for sale process
        
        $paymdata=array(
                'amount' => $amount,               
				 'customerId' => $customerid,			 
                 'options' => [
					'submitForSettlement' => True
			  ]            
                             	
               );
     	$result=$gateway->transaction()->sale($paymdata);
     	if($result->success == 1){
     	
     	$transactionid = $result->transaction->id;
     	$cardtype = $result->transaction->creditCard['cardType'];
     
     	//update braintree payment status in booking
		updateBraintreePaymentstatus($bookingid,$amount,$transactionid,$cardtype);
     	//emailtemplate($forname,$price,$date,$servicenames);
     	if(!empty($petownerid) && $petownerid>0 && !empty($petsitterid) && $petsitterid>0){
		$emailhtmlowner = emailtemplate($ownername,$amount,$datetime,$servicenames);
		//send email to owner
		$mail = new PHPMailer();
		$mail->isMail(); 
		
		$subject = 'Payment has been made successfully';
		$mail->SetFrom("$from",'Palcura');
		$mail->Subject  = $subject;
		$mail->isHTML(true);
		$mail->MsgHTML($emailhtmlowner);
		$mail->AddAddress($owneremail);
		//$mail->AddAddress('narinder@webworldexpertsindia.com');
		$mail->Send();
		}
		
		if(!empty($petownerid) && $petownerid>0 && !empty($petrenterid) && $petrenterid>0){
		$emailhtmlowner = emailtemplate($rentername,$amount,$datetime,$servicenames);
		//send email to owner
		$mail = new PHPMailer();
		$mail->isMail(); 
		
		$subject = 'Payment has been made successfully';
		$mail->SetFrom("$from",'Palcura');
		$mail->Subject  = $subject;
		$mail->isHTML(true);
		$mail->MsgHTML($emailhtmlowner);
		$mail->AddAddress($renteremail);
		//$mail->AddAddress('narinder@webworldexpertsindia.com');
		$mail->Send();
		}
     	
     	
     	}
     	//echo "<pre>"; print_r($result); die;
     	//sale code ends here
 	
		echo "todays payments are done";
		//die;
		
		/*if(!empty($petownerid) && $petownerid>0 && !empty($petsitterid) && $petsitterid>0){
		$emailhtmlowner = emailtemplate($ownername,$sittername);
		//send email to owner
		$mail = new PHPMailer();
		$mail->isMail(); 
		
		$subject = 'Review your pal’s stay with '.$sittername;
		$mail->SetFrom("$from",'Palcura');
		$mail->Subject  = $subject;
		$mail->isHTML(true);
		$mail->MsgHTML($emailhtmlowner);
		$mail->AddAddress($owneremail);
		$mail->Send();
		}
		
		if(!empty($petownerid) && $petownerid>0 && !empty($petrenterid) && $petrenterid>0){
		$emailhtmlowner = emailtemplate($ownername,$rentername);
		//send email to owner
		$mail = new PHPMailer();
		$mail->isMail(); 
		
		$subject = 'Review your pal’s stay with '.$rentername;
		$mail->SetFrom("$from",'Palcura');
		$mail->Subject  = $subject;
		$mail->isHTML(true);
		$mail->MsgHTML($emailhtmlowner);
		$mail->AddAddress($owneremail);
		$mail->Send();
		}*/
		
		/*if(!empty($petsitterid) && $petsitterid>0){	
		$emailhtmlsitter = emailtemplate($sittername,$ownername,$date,$servicenames);
	
		$mail2 = new PHPMailer();
		$mail2->isMail();
		$mail2->SetFrom("$from",'Palcura');
		$mail2->Subject  = $subject;
		$mail2->isHTML(true);
		$mail2->MsgHTML($emailhtmlsitter);
		$mail2->AddAddress($sitteremail);
		$mail2->Send();
		}
		
		if(!empty($petrenterid) && $petrenterid>0){	
		$emailhtmlrenter = emailtemplate($rentername,$ownername,$date,$servicenames);
	
		$mail2 = new PHPMailer();
		$mail2->isMail();
		$mail2->SetFrom("$from",'Palcura');
		$mail2->Subject  = $subject;
		$mail2->isHTML(true);
		$mail2->MsgHTML($emailhtmlsitter);
		$mail2->AddAddress($renteremail);
		$mail2->Send();
		}*/
		
		
		
		 }
     }

		}

function getTodaysPaymentList(){
		global $con;
		$data = array();
		$date = date('Y-m-d');
//print_r($date);
		$sql = ('SELECT b1.*,a1.firstname as sittername,a1.email as sitteremail,a2.firstname as ownername,a2.email as owneremail,a2.braintree_customer_id as ownercustomerid,a3.firstname as rentername,a3.braintree_customer_id as rentercustomerid,a3.email as renteremail from booking b1 LEFT JOIN user a1 ON b1.pet_sitter_id=a1.id LEFT JOIN user a2 ON b1.pet_owner_id= a2.id LEFT JOIN user a3 ON b1.pet_renter_id= a3.id where b1.booking_from_date ="'.$date.'" and b1.payment_status="1" and b1.booking_status="1" and b1.braintree_payment_status="0"');

		$result = $con->query($sql);

		if ($result->num_rows > 0) {
			// output data of each row
			$data = array();
			while($row = $result->fetch_assoc()) {
			   $data[] = $row;
			}
		} 
		//echo "<pre>"; print_r($data); die;
		return $data;
		
	}
	
	function updateBraintreePaymentstatus($bookingid,$amount,$transactionid,$cardtype){
	 global $con;
	
	 $sql = "update booking set braintree_payment_status='1' where id='".$bookingid."'";
	
		if ($con->query($sql) === TRUE) {
		
		$sql2 = "Update payment_transaction set amount='".$amount."', trans_id='".$transactionid."', payment_type='".$cardtype."', payment_status='Completed' where booking_id = '".$bookingid."'";
		$con->query($sql2);		
			return true;
		} else {
			//echo "Error: " . $sql . "<br>" . $con->error;die;
			return false;
		}
		
	
	}	
	function getAdminemail(){
	global $con;
		$data = array();
		$sql = 'select email from admin where id="1"';
		$result = mysqli_query($con,$sql);
		$data = mysqli_fetch_assoc($result);
		return $data;
	
	}
	
	function getservicenames($serviceid){
	
	global $con;
		$data = array();
		
		$sql = ('select name from services where id IN('.$serviceid.')');
		$result = $con->query($sql);

		if ($result->num_rows > 0) {
			// output data of each row
			$data = array();
			while($row = $result->fetch_assoc()) {
			   $data[] = $row['name'];
			}
		} 

		return $data;

	}
	
function emailtemplate($forname,$price,$date,$servicenames){
if(!empty($servicenames)){
	$servicetype = '<li>Service Type: <span style="font-weight:400; line-height:30px;">' . $servicenames . '</span></li>';
	}else{
	$servicetype = '';
	}
    $htmlemail = '<html">
<head><meta http-equiv="Content-Type" content="text/html; charset=windows-1252">

<title>Palcura :: Email</title>
<style type="text/css">
a {
	color: #ff8447;
}
a:hover {
	color: #656565;
}
.email_footer a {
	color: #ff8447;
}
.email_footer a:hover {
	color: #656565;
}
</style>
</head>

<body style="margin:0px;">
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;color:#ffffff;line-height:20px;">
  <tr>
    <td>&nbsp;</td>
    <td width="590" valign="top">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td height="30" align="right" style=" font-size:13px; color:#656565;padding:0 5px 0 0;"></td>
        </tr>
        <tr>
          <td style="border:solid 1px #ffc588; background:#ffc588; padding:2px;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td bgcolor="#ffffff"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td height="64" style="text-align:center; padding-top:30px; padding-bottom:30px; background:#4D402F"><img style="width:226px;" src="http://myprojectdemonstration.com/development/palcura/images/logoimg.png" border="0" alt="" /></td>
                    </tr>
                    <tr>
                      <td bgcolor="#ff8447"  style="font-size:14px; color:#ffffff; padding:12px 10px;  "><font style="font-size:17px; font-weight:500; color:#ffffff;   ">Payment has been made successfully</font> </td>
                    </tr>
                    <tr>
                      <td valign="top" bgcolor="#fcfcfc" style="padding:12px;"><table width="100%" border="0" cellspacing="0" cellpadding="0">';
                         $htmlemail .= '<tr>
                            <td height="26" style="font-size:15px; font-weight:500; color:#2c2c2c;">Hi '.$forname.',</td>
                          </tr>                     
                          <tr>
                            <td style="font-size:13px; color:#656565; line-height:18px; padding-bottom:10px;">Your payment for the booking has been done successfully. Here are the details:</td>
                          </tr>
                       
                          <tr>
                            <td height="5"></td>
                          </tr>
                          <tr>
                          <td align="left">
								<ul style="background-color: #fff3e3; color:#000000; font-size:15px; list-style:none; margin:0; padding:0; font-weight:600; padding: 10px 20px; letter-spacing: 0.3px;">
									<li style="line-height:30px; text-decoration:underline">Itinerary</li>		
									<li>Date (s): <span style="font-style:italic; font-weight:400; line-height:30px;">' . $date . '</span></li>'; 
									$htmlemail.=$servicetype;									
									
									$htmlemail.='<li>Price: <span style="font-weight:400; line-height:30px;">' . '$'.$price.'</span></li>
								</ul>
							</td>
                          
                          </tr>
                          
                          <tr>
                            <td height="15"></td>
                          </tr>
                          <tr>
                            <td style="font-size:13px; color:#656565; line-height:18px; padding-bottom:10px;">Please click <a href="http://myprojectdemonstration.com/development/palcura/payments" target="_blank">here</a> to access your account.</td>
                          </tr>';
                         $htmlemail .='<tr>
                         <td><p style="color:#8c8c8c; font-size:13px;line-height:19px; margin:0 0 12px 0; font-style:italic;">Your friends at PalCura<br/>Share the care...Multiply the love! <img style="width:18px;" src="http://myprojectdemonstration.com/development/palcura/images/foot-pic.jpg" border="0" alt="" /></p>
								<!--<ul style="color:#8c8c8c; font-size:13px; list-style:none; margin:0; padding:0; font-weight:600; padding: 10px 0; letter-spacing: 0.3px;">
									<li style="line-height:13px;">Your friends at PalCura</li>
									<li style="line-height:13px;">Share the care'.'...'.'Multiply the love! </li>
								</ul>
								
								 <td valign="top" style="color:#8c8c8c; font-size:13px;letter-spacing: 0.3px;font-weight:600;">Your friends at PalCura</td>
							  <td valign="top" style="color:#8c8c8c; font-size:13px;letter-spacing: 0.3px;font-weight:600;">Share the care...Multiply the love! <img style="width:18px;" src="http://myprojectdemonstration.com/development/palcura/images/foot-pic.jpg" border="0" alt="" /></td>-->
								
								
						</td>
               
                          </tr>
						  <tr>
                            <td>
                              <p style="font-size:13px;color:#000000; line-height:19px; margin:0 0 12px 0; font-style:italic;">Always book through PalCura to earn member discounts, points, coupons and connection to a large pet care community around you. </p></td>
                          </tr>                         
                        </table></td>
                   
                    </tr>
                  </table></td>
              </tr>
            </table></td>
        </tr>
        <tr>
          <td height="37" valign="top" align="center"><pre class="email_footer" style="margin:7px 0 0 0; font-size:10px; font-weight:bold; text-transform:uppercase; color:#611012;"><a href="http://myprojectdemonstration.com/development/palcura/demo/" style="text-decoration:none; font-family:Arial, Helvetica, sans-serif;  ">Home</a>   |   <a href="http://myprojectdemonstration.com/development/palcura/demo/cms/page/about-us" style="text-decoration:none; font-family:Arial, Helvetica, sans-serif; ">About us</a>   |  <a href="http://myprojectdemonstration.com/development/palcura/demo/site/contact" style="text-decoration:none; font-family:Arial, Helvetica, sans-serif; ">Contact Us</a></pre></td>
        </tr>
      </table>
      </td>
    <td>&nbsp;</td>
  </tr>
</table>
</body>
</html>';

return $htmlemail;
    
     }


?>
