<?php
//error_reporting(E_ALL);
$con = mysqli_connect("localhost","palcura_usr5745","Ea~C1+8cTT]X","palcura_d032018");
global $con;
// Check connection
if (mysqli_connect_errno()){
	echo "Failed to connect to MySQL: " . mysqli_connect_error();die;
}else{

require("PHPMailer.php");
//require_once("Emailmessage.php");

/*try {

    $conn = new PDO("mysql:host=$servername;dbname=db_palcura", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  	/* connect to server */
	
	/*$hostname = '{mail.palcura.com:110/pop3/novalidate-cert/tls}INBOX';
	$username = 'mailmasking@palcura.com';
	$password = '{m86!_4[~b;r';*/
//$hostname = '{mail.palcura.com:110/pop3/novalidate-cert/tls}INBOX';
//$hostname = '{pop.gmail.com:995/pop3/novalidate-cert/tls}INBOX';
$hostname = '{imap.gmail.com:993/imap/ssl}INBOX';

	$username = 'hellopalcuramasking@palcura.com';
	$password = 'palcura123@@';
		
	/* try to connect */
	$inbox = imap_open($hostname,$username,$password) or die('Cannot connect to Tiriyo: ' . imap_last_error());

	/* grab emails */
	$emails = imap_search($inbox,'ALL');

		if($emails){
		/* begin output var */
	  	$output = '';
	  	/* put the newest emails on top */
	 	rsort($emails);
	  	
	  	/* for every email... */
	  	foreach($emails as $email_number) {
	  	$message = imap_fetchbody($inbox,$email_number,1);
	  	$head_info=imap_headerinfo($inbox,$email_number,0);
	
	 $frommailname = $head_info->from['0']->personal;
	  	$to1=$head_info->to['0']->mailbox;
		$to2=$head_info->to['0']->host;
		$from1=$head_info->from['0']->mailbox;
		$from2=$head_info->from['0']->host;
		$to=$to1."@".$to2;
		$from=$from1."@".$from2;
		$subject = $head_info->subject;
		$mesagedetailsdb = getMessageChatidbyEmail($to);
		if(!empty($mesagedetailsdb)){	
		$getuserinfo = findUserByemail($from);			
			//$senderIdinDb = $mesagedetailsdb['senderId'];
			$fromnameforthisemail = $getuserinfo['firstname'];
			$senderIdinDb = $getuserinfo['id'];	
			if($senderIdinDb == $mesagedetailsdb['user_from']){
			$messageforId = $mesagedetailsdb['user_to'];		
			}else{
			$messageforId = $mesagedetailsdb['user_from'];
			}
			
			$sentbyforbothuser = $to;
		$emailMessage = new EmailMessage($inbox,$email_number);
	  	
	  	$emailMessage->fetch();
		$html_content=$emailMessage->bodyHTML;
		
		$message=imap_fetchbody($inbox,$email_number,1.2);
		if(!strlen($message)>0){
		$message = imap_fetchbody($inbox,$email_number,1);
		}
		$message = strip_tags($message);
		$arr = explode("===== WRITE YOUR REPLY ABOVE THIS LINE =====", $message, 2);

		if(empty($arr)){
imap_delete($inbox,$email_number);
imap_expunge($inbox);

}else{
		$message = $arr[0];
		
		$message = preg_replace('#(^\w.+:\n)?(^>.*(\n|$))+#mi', "", $message);
					
		$msgdata = explode(' ',$message);
		
		$reversed = array_reverse($msgdata, true);
		
		$foundkey = '';
		$i=0;
		foreach($reversed as $key=>$v){		
			if(trim($v) == $sentbyforbothuser || trim($v) == $from){
			$foundkey = $key;
			$i++;
				if($i>0){
				continue;
				}
			}		
		}
		$arrayslickey = '';
		if(!empty($foundkey)){		
		$arrayslickey = $foundkey-7;
		}
		//echo "<pre>"; print_r($msgdata);
		//echo "<pre>"; print_r($arrayslickey); die;
		if($arrayslickey > 0){
		$n=array_keys($msgdata);
		$count=array_search($arrayslickey,$n);
		$message=array_slice($msgdata,0,$count+1,true);
		$message = implode(' ',$message);
		$message = rtrim($message,'On');
		}else{
		$msgdatawithname = explode($frommailname,$message);
		$message = $msgdatawithname[0];
		
		$msgdata = explode('On',$message);
		
		$reversed = array_reverse($msgdata, true);
if(count($reversed)>1){
		$newarray = array_values($reversed);
		unset($newarray[0]);
		$message = $newarray[1];
		}else{
$message = $reversed[0];

}

		}		
	//$message = substr(quoted_printable_decode(imap_fetchbody($inbox,$email_number,1)),0,50);	
//print_r($message); die;
		$messagedata = array(
		'chat_id' 	=> $mesagedetailsdb['chat_id'],
		'message' 	=> $message,
		'user_from' => $senderIdinDb,
		'user_to'	=> $messageforId,		
		);
		$textmessage = $message;
		
		$htmlemailbody = emailtemplate($fromnameforthisemail,$textmessage);
			//get reciever email	
			$getrecieveremail = findUseridentitybyId($messageforId);
			if(!empty($getrecieveremail)){
			$recievermail = $getrecieveremail['email'];
			}else{
			$recievermail = 'puneet@webworldexpertsindia.com';		
			}
			//save message to database and send email	
			$savemessage = addnewMessagetoDb($messagedata);	
			//send email
			//Mark current Email for Deletion.
            imap_delete($inbox,$email_number);
            	
			$mail = new PHPMailer();
				$mail->isMail(); 
				$recepeint_email_address = $recievermail;
				$from = $from;
				
				$subject = 'New message received from '.$fromnameforthisemail;
				$mail->SetFrom("$sentbyforbothuser",'Palcura');
				$mail->Subject  = $subject;
				//$body = $html_content;
				$mail->isHTML(true);
				$mail->MsgHTML($htmlemailbody);
				$mail->AddAddress($recepeint_email_address, 'Palcura');
				
					if($mail->Send()){
					 $status = imap_setflag_full($inbox,$email_number, "\\Seen \\Flagged");
		              // Delete the Emails that are marked for deletion.
		              imap_expunge($inbox);
					echo "email sent"; 
					}else{
					echo "email was not sent";
					}
			
		  }
		}else{
		
		echo  "no user data available";
		}
		
	  	}
	  	imap_close($inbox);
		}else{
		imap_close($inbox);
		echo "no emails"; die;
		}
		
    //echo "Connected successfully";
 /*   }
catch(PDOException $e)
    {
    echo "Connection failed: " . $e->getMessage();
    }*/
    }
 

	 //function to	get users details
	function getMessageChatidbyEmail($email){
		global $con;
		$data = array();
		$sql = ('select messages.* FROM random_email_address join messages on messages.chat_id=random_email_address.chat_id where random_email_address.email="'.$email.'" order by messages.date_created DESC LIMIT 1');
		$result = mysqli_query($con,$sql);
		$data = mysqli_fetch_assoc($result);
		return $data;
	}

	function findUseridentitybyId($userid){
	global $con;
		$data = array();
		$sql = ('select email from user where id='.$userid);
		$result = mysqli_query($con,$sql);
		$data = mysqli_fetch_assoc($result);
		return $data;

	}
	function findUserByemail($email){
		global $con;
		$data = array();
		$sql = 'select id,firstname from user where email="'.$email.'"';
		$result = mysqli_query($con,$sql);
		$data = mysqli_fetch_assoc($result);
		return $data;
	}

	function addnewMessagetoDb($data){
	 global $con;
	 $message =$data['message'];
	if($data['user_from']>0){
	 $sql = "INSERT INTO messages (chat_id,message,user_from,user_to)VALUES('".$data['chat_id']."','".$message."','".$data['user_from']."','".$data['user_to']."')";
	
		if ($con->query($sql) === TRUE) {
			return true;
		} else {
			//echo "Error: " . $sql . "<br>" . $con->error;die;
			return false;
		}
		}else{
		return true;
		}
	}
	
	  function emailtemplate($fromname,$textmessage){
	  
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
<p align="center" style="font-family:Arial, Helvetica, sans-serif; font-size:14px;color:#cccccc;">===== WRITE YOUR REPLY ABOVE THIS LINE =====</p>
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
                      <td bgcolor="#ff8447"  style="font-size:14px; color:#ffffff; padding:12px 10px;  "><font style="font-size:17px; font-weight:500; color:#ffffff;   ">New message received from '.$fromname.'</font> </td>
                    </tr>
                    <tr>
                      <td valign="top" bgcolor="#fcfcfc" style="padding:12px;"><table width="100%" border="0" cellspacing="0" cellpadding="0">';
                         $htmlemail .= '<tr>
                            <td style="font-size:13px; color:#656565; line-height:13px; padding-bottom:10px;" align="center">'.$fromname.' says</td>
                          </tr>                     
                          <tr>
                            <td height="0"></td>
                          </tr>
                          <tr style="color:#656565; font-size:13px; line-height:19px;" align="center" >
                            <td>"'.$textmessage.'"</td>
                          </tr>
                          <tr>
                            <td height="5"></td>
                          </tr>
                          <tr>
                            <td>
                              <p style="color:#656565; font-size:13px; line-height:19px; margin:0 0 12px 0; font-style:italic;">Respond to '.$fromname.' by replying directly to this email or access your PalCura inbox <a href="messages">here. </p></td>
                          </tr>';
                         $htmlemail .='<tr>
                         <td><p style="color:#8c8c8c; font-size:13px;line-height:19px; margin:0 0 12px 0; font-style:italic;">Your friends at PalCura<br/>Share the care…Multiply the love! <img style="width:18px;" src="https://www.palcura.com/images/foot-pic.jpg" border="0" alt="" /></p>
								<!--<ul style="color:#8c8c8c; font-size:13px; list-style:none; margin:0; padding:0; font-weight:600; padding: 10px 0; letter-spacing: 0.3px;">
									<li style="line-height:13px;">Your friends at PalCura</li>
									<li style="line-height:13px;">Share the care'.'...'.'Multiply the love! </li>
								</ul>
								
								 <td valign="top" style="color:#8c8c8c; font-size:13px;letter-spacing: 0.3px;font-weight:600;">Your friends at PalCura</td>
							  <td valign="top" style="color:#8c8c8c; font-size:13px;letter-spacing: 0.3px;font-weight:600;">Share the care…Multiply the love! <img style="width:18px;" src="https://www.palcura.com/images/foot-pic.jpg" border="0" alt="" /></td>-->
								
								
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

class EmailMessage {

	protected $connection;
	protected $messageNumber;
	
	public $bodyHTML = '';
	public $bodyPlain = '';
	public $attachments;
	
	public $getAttachments = true;
	
	public function __construct($connection, $messageNumber) {
	
		$this->connection = $connection;
		$this->messageNumber = $messageNumber;
		
	}

	public function fetch() {
		
		$structure = @imap_fetchstructure($this->connection, $this->messageNumber);
                if(count(@$structure->parts)>0)
                {
			if(!$structure) {
				return false;
			}
			else {
				$this->recurse($structure->parts);
				return true;
			}
                }
		
	}
	
	public function recurse($messageParts, $prefix = '', $index = 1, $fullPrefix = true) {

		foreach($messageParts as $part) {
			
			$partNumber = $prefix . $index;
			
			if($part->type == 0) {
				if($part->subtype == 'PLAIN') {
					$this->bodyPlain .= $this->getPart($partNumber, $part->encoding);
				}
				else {
					$this->bodyHTML .= $this->getPart($partNumber, $part->encoding);
				}
			}
			elseif($part->type == 2) {
				$msg = new EmailMessage($this->connection, $this->messageNumber);
				$msg->getAttachments = $this->getAttachments;
				$msg->recurse($part->parts, $partNumber.'.', 0, false);
				$this->attachments[] = array(
					'type' => $part->type,
					'subtype' => $part->subtype,
					'filename' => '',
					'data' => $msg,
					'inline' => false,
				);
			}
			elseif(isset($part->parts)) {
				if($fullPrefix) {
					$this->recurse($part->parts, $prefix.$index.'.');
				}
				else {
					$this->recurse($part->parts, $prefix);
				}
			}
			elseif($part->type > 2) {
				if(isset($part->id)) {
					$id = str_replace(array('<', '>'), '', $part->id);
					$this->attachments[$id] = array(
						'type' => $part->type,
						'subtype' => $part->subtype,
						'filename' => $this->getFilenameFromPart($part),
						'data' => $this->getAttachments ? $this->getPart($partNumber, $part->encoding) : '',
						'inline' => true,
					);
				}
				else {
					$this->attachments[] = array(
						'type' => $part->type,
						'subtype' => $part->subtype,
						'filename' => $this->getFilenameFromPart($part),
						'data' => $this->getAttachments ? $this->getPart($partNumber, $part->encoding) : '',
						'inline' => false,
					);
				}
			}
			
			$index++;
			
		}
		
	}
	
	function getPart($partNumber, $encoding) {

		$data = imap_fetchbody($this->connection, $this->messageNumber, $partNumber);
		switch($encoding) {
			case 0: return $data; // 7BIT
			case 1: return $data; // 8BIT
			case 2: return $data; // BINARY
			case 3: return base64_decode($data); // BASE64
			case 4: return quoted_printable_decode($data); // QUOTED_PRINTABLE
			case 5: return $data; // OTHER
		}


	}
	
	function getFilenameFromPart($part) {

		$filename = '';

		if($part->ifdparameters) {
			foreach($part->dparameters as $object) {
				if(strtolower($object->attribute) == 'filename') {
					$filename = $object->value;
				}
			}
		}

		if(!$filename && $part->ifparameters) {
			foreach($part->parameters as $object) {
				if(strtolower($object->attribute) == 'name') {
					$filename = $object->value;
				}
			}
		}

		return $filename;

	}

}


?>
