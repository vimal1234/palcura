<?php 
	include("config.php");
require('PHPMailer.php');
	if (isset($_GET['email_id']) && !empty($_GET['email_id']))  {
		$useremailID = $_GET['email_id'];
		function emailContent($name,$information){
			return $mail_message='<table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;color:#ffffff;line-height:20px;">
			  <tr>
				<td>&nbsp;</td>
				<td width="590" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
					  <td style="border:solid 1px #f2b51b; background:#f2b51b; padding:4px;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
						  <tr>
							<td bgcolor="#ffffff"><table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr>
								  <td height="86" style="text-align:center; padding-top:20px; padding-bottom:20px; background:#ffffff"><img style="width:302px;" src="'.site_url.'images/logo.png" border="0" alt="" /></td>
								</tr>
								<tr>
								  <td bgcolor="#fc6720"  style="font-size:14px; color:#ffffff; padding:12px 10px;  "><font style="font-size:17px; font-weight:500; color:#ffffff;   ">Welcome to Palcura!</font></td>
								</tr>
								<tr>
								  <td valign="top" bgcolor="#ffffff" style="padding:12px;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
									  <tr>
										<td height="26" style="font-size:15px; font-weight:500; color:#2c2c2c;  ">Hi, '.$name.'!</td>
									  </tr>
										<tr>
										<td height="15"></td>
									  </tr>
									  <tr>
										<td style="font-size:13px; color:#656565; line-height:18px; padding-bottom:10px;">'.$information.'</td>
									  </tr>
									  <tr>
										<td height="5"></td>
									  </tr>
									  <tr>
										<td height="10"></td>
									  </tr>
									  <tr  style="color:#656565; font-size:13px; line-height:19px; ">
										<td>Your friends at PalCura<br />
										  <i>Share the care.. Multiply the love! <img style="width:18px;" src="'.site_url.'images/paws.png"/></i> </td>
									  </tr>
									</table></td>
								</tr>
							  </table></td>
						  </tr>
						</table></td>
					</tr>
				  </table></td>
				<td>&nbsp;</td>
			  </tr>
			</table>';
		}
		
		$getquery = "select * from tbl_userDetails where email='$useremailID' and status = '0'";
		$checkresult = mysqli_query($db,$getquery);  
		if($checkresult->num_rows > 0) {
			$resultvalues = $checkresult->fetch_assoc();
			$query = "select * from tbl_userDetails where status='1'";
			$result = mysqli_query($db,$query);  
			$number_of_rows = mysqli_num_rows($result);  
			if($number_of_rows < 500){
				$sql = "Update tbl_userDetails set status = '1',amount_credited = '1' where email='$useremailID'";
				$updatedata = mysqli_query($db,$sql);
				$to = $useremailID; 
				$subject = 'Congratulations on the 5$ credit';
				$fromEmail ='hello@palcura.com';
				/*$headers = "From: Palcura <". strip_tags($fromEmail) . ">\r\n";
				$headers .= "Reply-To: <". strip_tags($fromEmail) . ">\r\n"; 
				$headers .= "MIME-Version: 1.0\r\n";
				$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";*/
				$information_message = 'Thanks for verifying your email!'.'<br/><br/>';
				$information_message .= 'When our website goes live (you will be the first to know when we launch), please use this email address and register to use your $5 credit towards finding a loving pet care or start making money doing what you love!'.'<br/><br/>';  
				$information_message .= 'Feel free to share the love and spread the message.'.'<br/><br/>';
				$information_message .='Thank you and welcome aboard!';
				$emailContent = emailContent($resultvalues['name'],$information_message);
				//mail($to,$subject,$emailContent,$headers);


$mail = new PHPMailer(true);

//Recipients
    $mail->setFrom($fromEmail, 'Palcura');
    $mail->addAddress($to);     // Add a recipient
    $mail->addReplyTo($fromEmail, 'Palcura');


$mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = $subject;
    $mail->Body    = $emailContent;
    $mail->AltBody = $emailContent;

    $mail->send();

				header('Location: '.site_url."?verified");
	
			} else {
				$sql = "Update tbl_userDetails set status = '2' where email='$useremailID'";
				$updatedata = mysqli_query($db,$sql);
				$to = $useremailID; 
				$subject = 'Welcome aboard!';
				$fromEmail ='hello@palcura.com';
				/*$headers = "From: Palcura <". strip_tags($fromEmail) . ">\r\n";
				$headers .= "Reply-To: <". strip_tags($fromEmail) . ">\r\n"; 
				$headers .= "MIME-Version: 1.0\r\n";
				$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";*/
				$information_message .= 'Thanks for verifying your email!'.'<br/><br/>';
				$information_message .= 'When our website goes live please use this email address and register. Whether you are looking for loving pet care or want to make money doing what you love or want to borrow a pet - '.'<b>'.'we are here to help'.'</b>'.'. You will be one of the first ones to know when our website is launched.'.'<br/><br/>';
				$information_message .='We cannot wait to go on this journey with you!';
				$emailContent = emailContent($resultvalues['name'],$information_message);
				//mail($to,$subject,$emailContent,$headers);


$mail = new PHPMailer(true);

//Recipients
    $mail->setFrom($fromEmail, 'Palcura');
    $mail->addAddress($to);     // Add a recipient
    $mail->addReplyTo($fromEmail, 'Palcura');


$mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = $subject;
    $mail->Body    = $emailContent;
    $mail->AltBody = $emailContent;

    $mail->send();

				header('Location: '.site_url."?verified");
				
			}	
				
		}  
		
		else  {
			
			header('Location: '.site_url."?failverified");
			
		}
		
	}   
	
	else {
		
		header('Location: '.site_url);
		
	}
		

?>
