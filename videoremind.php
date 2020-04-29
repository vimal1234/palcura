<?php
//error_reporting(E_ALL');
$con = mysqli_connect("localhost","palcura_usr5745","Ea~C1+8cTT]X","palcura_d032018");
global $con;
// Check connection
if (mysqli_connect_errno()){
	echo "Failed to connect to MySQL: " . mysqli_connect_error();die;
}else{
require("PHPMailer.php");

//require_once("Emailmessage.php");
$videolist = getvideocalllistfortommorow();
$admindata = getAdminemail();
		$from = $admindata['email'];
     if(!empty($videolist)){
     
		 foreach($videolist as $key=>$val){
		 $sittername = 	$val['sittername'];
		 $sitteremail = $val['sitteremail'];
		 $ownername = $val['ownername'];
		 $owneremail = $val['owneremail'];
		 $services = $val['services'];
		 $date = date('m/d/y',strtotime($val['schedule_datetime']));
		 $time = $val['start_time'];
		 $servicename = getservicename($services);
		$serviceName = $servicename['name'];
		$emailhtmlowner = emailtemplate($ownername,$sittername,$date,$time,$serviceName);	
		$emailhtmlsitter = emailtemplate($sittername,$ownername,$date,$time,$serviceName);
		
		//send email to owner
		$mail = new PHPMailer();
		$mail->isMail(); 
		
		$subject = 'Reminder - you have a video call scheduled';
		$mail->SetFrom("$from",'Palcura');
		$mail->CharSet = 'UTF-8';
		$mail->Subject  = $subject;
		$mail->isHTML(true);
		$mail->MsgHTML($emailhtmlowner);
		$mail->AddAddress($owneremail);
		$mail->Send();
		
		$mail2 = new PHPMailer();
		$mail2->isMail();
		$mail2->CharSet = 'UTF-8';
		$mail2->SetFrom("$from",'Palcura');
		$mail2->Subject  = $subject;
		$mail2->isHTML(true);
		$mail2->MsgHTML($emailhtmlsitter);
		$mail2->AddAddress($sitteremail);
		$mail2->Send();
		
		 }
     }


		}

function getvideocalllistfortommorow(){
		global $con;
		$data = array();
		$date = date('Y-m-d', strtotime(date('Y-m-d') . ' +1 day'));
		
		$sql = ('SELECT b1.*,a1.firstname as sittername,a1.email as sitteremail,a2.firstname as ownername,a2.email as owneremail
FROM video_call_details b1 JOIN user a1 ON b1.pet_sitter_id= a1.id JOIN user a2 ON b1.pet_owner_id= a2.id where b1.schedule_datetime ="'.$date.'" and approv_status="1"');

		$result = $con->query($sql);

		if ($result->num_rows > 0) {
			// output data of each row
			$data = array();
			while($row = $result->fetch_assoc()) {
			   $data[] = $row;
			}
		} 
		
		return $data;
	}
	
	function getservicename($serviceid){
	global $con;
		$data = array();
		$sql = ('select name from services where id='.$serviceid);
		$result = mysqli_query($con,$sql);
		$data = mysqli_fetch_assoc($result);
		return $data;

	}
	
	function getAdminemail(){
	global $con;
		$data = array();
		$sql = 'select email from admin where id="1"';
		$result = mysqli_query($con,$sql);
		$data = mysqli_fetch_assoc($result);
		return $data;
	
	}
	
	function emailtemplate($forname,$withname,$date,$time,$serviceName){
	  
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
          <!--td height="30" align="right" style=" font-size:13px; color:#656565;padding:0 5px 0 0;">'.date('d M, Y - h:m A').'</td-->
        </tr>
        <tr>
          <td style="border:solid 1px #ffc588; background:#ffc588; padding:2px;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td bgcolor="#ffffff"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td height="64" style="text-align:center; padding-top:30px; padding-bottom:30px; background:#4D402F"><img style="width:226px;" src="https://www.palcura.com/images/logoimg.png" border="0" alt="" /></td>
                    </tr>
                    <tr>
                      <td bgcolor="#ff8447"  style="font-size:14px; color:#ffffff; padding:12px 10px;  "><font style="font-size:17px; font-weight:500; color:#ffffff;   ">Reminder - you have a video call scheduled</font> </td>
                    </tr>
                    <tr>
                      <td valign="top" bgcolor="#fcfcfc" style="padding:12px;"><table width="100%" border="0" cellspacing="0" cellpadding="0">';
                         $htmlemail .= '<tr>
                            <td height="26" style="font-size:15px; font-weight:500; color:#2c2c2c;">Hi '.$forname.',</td>
                          </tr>                     
                          <tr>
                            <td style="font-size:13px; color:#656565; line-height:18px; padding-bottom:10px;">A friendly reminder of your upcoming video call with '.$withname.'. Here are the details:</td>
                          </tr>
                       
                          <tr>
                            <td height="5"></td>
                          </tr>
                          <tr>
                          <td align="left">
								<ul style="background-color: #fff3e3; color:#000000; font-size:15px; list-style:none; margin:0; padding:0; font-weight:600; padding: 10px 20px; letter-spacing: 0.3px;">
									<li>Video call with:  <span style="font-weight:400; line-height:30px;">'.$withname.'</span>	
									<li>Date : <span style="font-style:italic; font-weight:400; line-height:30px;">' . $date . '</span></li>
									<li>Time: <span style="font-weight:400; line-height:30px;">'.$time.'</span>
									<li>Service Type: <span style="font-weight:400; line-height:30px;">'.$serviceName.'</span>
								</ul>
							</td>
                          
                          </tr>
                          <tr>
                            <td height="10"></td>
                          </tr>
                          <tr>
                            <td>
                              <p style="color:#656565; font-size:13px; line-height:19px; margin:0 0 12px 0; font-style:italic;">Remember to sign in at the time and date of your video call. The call will be capped to 10 minutes for security reasons. You can always set up a meet and greet later. Once you are satisfied, reserve/accept the service request.</p></td>
                          </tr>
                          <tr>
                            <td height="10"></td>
                          </tr>';
                         $htmlemail .='<tr>
                         <td><p style="color:#8c8c8c; font-size:13px;line-height:19px; margin:0 0 12px 0; font-style:italic;">Your friends at PalCura<br/>Share the care...Multiply the love! <img style="width:18px;" src="https://www.palcura.com/images/foot-pic.jpg" border="0" alt="" /></p>
								<!--<ul style="color:#8c8c8c; font-size:13px; list-style:none; margin:0; padding:0; font-weight:600; padding: 10px 0; letter-spacing: 0.3px;">
									<li style="line-height:13px;">Your friends at PalCura</li>
									<li style="line-height:13px;">Share the care'.'...'.'Multiply the love! </li>
								</ul>
								
								 <td valign="top" style="color:#8c8c8c; font-size:13px;letter-spacing: 0.3px;font-weight:600;">Your friends at PalCura</td>
							  <td valign="top" style="color:#8c8c8c; font-size:13px;letter-spacing: 0.3px;font-weight:600;">Share the care...Multiply the love! <img style="width:18px;" src="https://www.palcura.com/images/foot-pic.jpg" border="0" alt="" /></td>-->
								
								
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
           <td height="37" valign="top" align="center"><pre class="email_footer" style="margin:7px 0 0 0; font-size:10px; font-weight:bold; text-transform:uppercase; color:#611012;"><a href="https://www.palcura.com/" style="text-decoration:none; font-family:Arial, Helvetica, sans-serif;  ">Home</a>   |   <a href="https://www.palcura.com/cms/page/about-us" style="text-decoration:none; font-family:Arial, Helvetica, sans-serif; ">About us</a>   |  <a href="https://www.palcura.com/site/contact" style="text-decoration:none; font-family:Arial, Helvetica, sans-serif; ">Contact Us</a></pre></td>
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