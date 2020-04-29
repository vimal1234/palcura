<?php
	include("config.php");
	
	if(isset($_GET['verified'])){
		
		$message = "Thanks for verifying your email!.";
		
	} else if (isset($_GET['failverified'])){
		
		$message = "Already subscribed or Email id is not correct.";
		
	} else {
		
		$message = "";
	}
	
	if(isset($_POST['signup']) && !empty($_POST))  {
	
		$name   = 	$_POST['txtName']; 
		$email  =	$_POST['txtEmail']; 
		$choice =	$_POST['txtChoice']; 
if((isset($name) && empty($name))){
$message = "Please enter your name";
} else if((isset($email) && empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL))){
$message = "Please enter valid email address";
} else if((isset($choice) && empty($choice))){
$message = "Please select user type";
}else{
		
		$result = "SELECT * FROM tbl_userDetails WHERE email = '".$email."'";
		$get_result = mysqli_query($db,$result);
		if ($get_result->num_rows > 0) {
			
			$message = "You have already signed up.";
			
		} else {
			
			function emailContent($name,$information){
				$currentDate =  date('Y-m-d H:i:s');
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
			$sql = "INSERT INTO tbl_userDetails(name,email,choice,status,amount_credited)VALUES('".$name."','".$email."','".$choice."','0','0')";
			$insertdata = mysqli_query($db,$sql);
			if ($insertdata) {
				
				$to = $email; 
				$subject = 'Verify your email account';
				$fromEmail ='hello@palcura.com';
				//$headers = "From: Palcura ". strip_tags($fromEmail) . "\r\n";
				//$headers .= "Reply-To: ". strip_tags($fromEmail) . "\r\n"; 
//$headers .= "X-Sender: Palcura <". strip_tags($fromEmail) . ">\r\n";
//$headers .= "X-Mailer: PHP/" . phpversion()."\r\n";
				//$headers .= "MIME-Version: 1.0\r\n";
				//$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
				$information_message = 'We are stoked to have you as a part of PalCura!'.'<br/><br/>';
				$information_message .= 'To ensure, we have your email address captured correctly, please verify your email address by clicking on following link:'.'<br/><br/>';  
				$information_message .= '<a target="_blank" href ="'.site_url.'accountverify.php?email_id='.$email.'">Verify Link</a><br/><br/>';
				$information_message .='Thank you and welcome aboard!';
				$emailContent = emailContent($name,$information_message);
require('PHPMailer.php');
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



				//mail($to,$subject,$emailContent,$headers);
				
				$message = "Thank you, your subscription was successful! Please check your inbox to verify your email.";
					
			} else {
				$message = "There is some error while subscribing. Please try again.";
			}
			
		}
		}
	
	}     
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Palcura - No pet left behind</title>
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
        <h1 class="comingsoon">coming soon!</h1>
      </div>
    </div>
    <div class="row">
      <div class="col-xs-12 text-center">
        <!--<div class='countdown' data-date="2017-11-30"></div>-->
<div class='countdown' > </div>
      </div>
    </div>
    <div class="row">
      <div class="col-xs-12 text-center">
        <h2 class="white"><span>$5 </span>credit bonus for <span>first 500 sign ups</span></h2>
      </div>
      <form id="teaserSignup" name="teaserSignup" class="teasureForm" method="post">
		  <?php  if(isset($message) && !empty($message)) {  ?>
		  <div class="col-xs-12">
			<div class="messageBlock">
				<?php echo $message; ?>
			</div>
		  </div>
		<?php	}   ?> 
        <div class="col-md-3 col-sm-3 col-xs-6 full">
          <div class="form-group">
            <input data-errormessage-value-missing ="Name is required!" type="text" data-prompt-position="topLeft:0,10" name="txtName" value="<?php echo ((isset($post['txtName']) && !empty($post['txtName'])) ? $post['txtName'] :'') ?>"  class="form-control validate[required,custom[onlyLetterSp]" id="exampleInputName" placeholder="Name">
          </div>
        </div>
        <div class="col-md-3 col-sm-3 col-xs-6 full">
          <div class="form-group">
            <input data-errormessage-value-missing ="Email is required!" type="text" data-prompt-position="topLeft:0,10" name="txtEmail" value="<?php echo ((isset($post['txtEmail']) && !empty($post['txtEmail'])) ? $post['txtEmail'] :'') ?>" class="form-control validate[required,custom[email]]" id="exampleInputEmail1" placeholder="Your email address">
          </div>
        </div>
        <div class="col-md-3 col-sm-3 col-xs-6 full">
          <div class="form-group">
            <select data-prompt-position="topLeft:0,10" data-errormessage-value-missing ="Please select below options !" name="txtChoice" class="form-control validate[required]">
			  <option value="">Please select options</option>
              <option value="Pet owner">Signup as Pet Owner</option>
              <option value="Pet sitter">Signup as Pet Sitter</option>
              <option value="Pet Owner and Sitter">Signup as Pet Owner and Sitter</option>
              <option value="Pet renter">Signup as Pet Borrower</option>
            </select>
          </div>
        </div>
        <div class="col-md-3 col-sm-3 col-xs-6 full">
          <div class="signButton"> 
			  <input type="submit" name="signup" value="Sign up">
<!--
			  <a href="javascript:void(0)">sign up</a> </div>
-->
        </div>
      </form>
    </div>
  </div>
</header>
<section class="contentArea defineFloat">
  <div class="container">
	<div class="row">
		<div class="col-xs-12 text-center">
			<h1>How it works</h1>
		</div>
	</div>  
  <div class="row">
  <div class="col-xs-12">
    <div class="customTabs">
      <div id="exTab1">
        <ul class="tabs nav nav-pills">
          <li class="active" rel="tab1">Pet Owner</li>
          <li rel="tab2">Pet Sitter</li>
          <li rel="tab3">Pet Borrower</li>
        </ul>
        <div class="tab_container">
          <h3 class="d_active tab_drawer_heading" rel="tab1">Pet Owner</h3>
          <div id="tab1" class="tab_content">
            <div class="row">
              <div class="col-xs-12">
                <div class="stepBg">
                  <div class="thumb"> <img class="img-responsive" src="images/dogthumb.png" alt="" /> </div>
                  <div class="text">
                    <h3>Easy three step process</h3>
                    <p>Find a home away from home & other services for your Pet.</p>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="marginBlock">
                <div class="col-md-8 col-sm-8 col-xs-12">
                  <div class="signBlock">
                    <div class="signText">
                      <h2>Sign up</h2>
                      <p>Connect with verified Palcura sitters and find loving care for your pet.</p>
                      <p class="orange">OR</p>
                      <p>Share the love of your pet, travel worry free…..and earn money!</p>
                    </div>
                  </div>
                </div>
                <div class="col-md-4 col-sm-4 col-xs-12">
                  <div class="signThumb"> <img class="img-responsive" src="images/signup.png" alt="" /> </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="marginBlock">
                <div class="col-md-8 col-sm-8 col-xs-12 pull-right">
                  <div class="signBlock connectBlock">
                    <div class="signText">
                      <h2>Connect</h2>
                      <p>Use our online video call feature or meet in person with the pet sitter/borrower.</p>
                    </div>
                  </div>
                </div>
                <div class="col-md-offset-2 col-md-2 col-sm-4 col-xs-12 noPad">
                  <div class="signThumb"> <img class="img-responsive" src="images/connect.png" alt="" /> </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="marginBlock noMargin">
                <div class="col-md-8 col-sm-8 col-xs-12">
                  <div class="signBlock shareBlock">
                    <div class="signText">
                      <h2>Book or Share the <i class="fa fa-heart-o" aria-hidden="true"></i></h2>
                      <p>Get notified of activity updates, cute pictures and videos so you never miss your pet when you travel.</p>
                    </div>
                  </div>
                </div>
                <div class="col-md-4 col-sm-4 col-xs-12">
                  <div class="signThumb"> <img class="img-responsive" src="images/share.png" alt="" /> </div>
                </div>
              </div>
            </div>
          </div>
          <!-- #tab1 -->
          <h3 class="tab_drawer_heading" rel="tab2">Pet Sitter</h3>
          <div id="tab2" class="tab_content">
            <div class="row">
              <div class="col-xs-12">
                <div class="stepBg">
                  <div class="thumb"> <img class="img-responsive" src="images/catthumb.png" alt="" /> </div>
                  <div class="text">
                    <h3>Easy three step process</h3>
                    <p>Make money doing what you love.</p>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="marginBlock">
                <div class="col-md-8 col-sm-8 col-xs-12">
                  <div class="signBlock">
                    <div class="signText">
                      <h2>Create a free profile</h2>
                      <p>All details of Palcura registered sitters are verified to make sure every pet has a loving home.</p>
                    </div>
                  </div>
                </div>
                <div class="col-md-4 col-sm-4 col-xs-12">
                  <div class="signThumb"> <img class="img-responsive" src="images/signup.png" alt="" /> </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="marginBlock">
                <div class="col-md-8 col-sm-8 col-xs-12 pull-right">
                  <div class="signBlock connectBlock">
                    <div class="signText">
                      <h2>Connect</h2>
                      <p>Use our online ccvideo call feature or meet in person.</p>
                    </div>
                  </div>
                </div>
                <div class="col-md-offset-2 col-md-2 col-sm-4 col-xs-12 noPad">
                  <div class="signThumb"> <img class="img-responsive" src="images/connect.png" alt="" /> </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="marginBlock noMargin">
                <div class="col-md-8 col-sm-8 col-xs-12">
                  <div class="signBlock shareBlock">
                    <div class="signText">
                      <h2>Get paid</h2>
                      <p>Earn income providing love and care to a pet.</p>
                    </div>
                  </div>
                </div>
                <div class="col-md-4 col-sm-4 col-xs-12">
                  <div class="signThumb"> <img class="img-responsive" src="images/paid.png" alt="" /> </div>
                </div>
              </div>
            </div>
          </div>
          <!-- #tab2 -->
          <h3 class=" tab_drawer_heading" rel="tab3">Pet Borrower</h3>
          <div id="tab3" class="tab_content">
            <div class="row">
              <div class="col-xs-12">
                <div class="stepBg">
                  <div class="thumb"> <img class="img-responsive" src="images/girlthumb.png" alt="" /> </div>
                  <div class="text">
                    <h3>Easy three step process</h3>
                    <p>Share the care. Multiply the love!</p>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="marginBlock">
                <div class="col-md-8 col-sm-8 col-xs-12">
                  <div class="signBlock">
                    <div class="signText">
                      <h2>Create a free profile</h2>
                      <p>Pet lovers get the love of a pet without any commitment.</p>
                      <p>Pet owners can share the love of their pet, travel worry free…..and earn money!</p>
                    </div>
                  </div>
                </div>
                <div class="col-md-4 col-sm-4 col-xs-12">
                  <div class="signThumb"> <img class="img-responsive" src="images/signup.png" alt="" /> </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="marginBlock">
                <div class="col-md-8 col-sm-8 col-xs-12 pull-right">
                  <div class="signBlock connectBlock">
                    <div class="signText">
                      <h2>Get to know each other</h2>
                      <p>Use our online video feature or meet in person.</p>
                    </div>
                  </div>
                </div>
                <div class="col-md-offset-2 col-md-2 col-sm-4 col-xs-12 noPad">
                  <div class="signThumb"> <img class="img-responsive" src="images/connect.png" alt="" /> </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="marginBlock noMargin">
                <div class="col-md-8 col-sm-8 col-xs-12">
                  <div class="signBlock shareBlock">
                    <div class="signText">
                      <h2>Share the <i class="fa fa-heart-o" aria-hidden="true"></i> </h2>
                      <p>Book your sharing experience and welcome a whole lot love and happiness to your home.</p>
                    </div>
                  </div>
                </div>
                <div class="col-md-4 col-sm-4 col-xs-12">
                  <div class="signThumb"> <img class="img-responsive" src="images/share.png" alt="" /> </div>
                </div>
              </div>
            </div>
          </div>
          <!-- #tab3 --> 
          
          <!-- .tab_container --> 
        </div>
      </div>
      </ul>
      </ul>
    </div>
  </div>
</section>
<footer class="defineFloat">
  <div class="container">
    <div class="row">
      <div class="col-xs-12">
        <div class="fLogo"> <img class="img-responsive" src="images/flogo.png" alt="" /> </div>
        <div class="copyOuter"> &copy; Copyright 2017. All rights reserved. </div>
      </div>
    </div>
  </div>
</footer>

<!-- script start--> 

<script src="js/jquery.js"></script> 
<script src="js/jquery.min.js"></script> 
<!--<script src="js/countdown.js"></script> -->
<script src="js/bootstrap.min.js"></script> 
<script src="js/bootstrap.js"></script> 
<script src="js/core.js"></script> 
<script src="js/jquery.validationEngine.js"></script>
<script src="js/jquery.validationEngine-en.js"></script>
<script>
$(document).ready(function(){	
	$("#teaserSignup").validationEngine('attach',{
		autoHidePrompt:true,
		scroll: false, 
		autoHideDelay: 5000	
	});
});
</script>
<!-- script end-->
</body>
</html>

