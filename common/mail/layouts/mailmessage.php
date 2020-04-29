<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\MessageInterface the message being composed */
/* @var $content string main view render result */
?>
<?php $this->beginPage() ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?= Yii::$app->charset ?>" />
<?php $this->head() ?>
<!--title>Palcura :: Email</title-->
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
    <td width="590" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td style="border:solid 1px #ffc588; background:#ffc588; padding:2px;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td bgcolor="#ffffff"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td height="64" style="text-align:center; padding-top:30px; padding-bottom:30px; background:#4D402F"><img style="width:226px;" src="<?php echo SITE_LOGO; ?>" border="0" alt="" /></td>
                    </tr>
                    <tr>
                      <td bgcolor="#ff8447"  style="font-size:14px; color:#ffffff; padding:12px 10px;  "><font style="font-size:17px; font-weight:500; color:#ffffff;   "><?= $subject ?></font> </td>
                    </tr>
                    <tr>
                      <td valign="top" bgcolor="#fcfcfc" style="padding:12px;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <!--tr>
                            <td height="26" style="font-size:15px; font-weight:600; color:#2c2c2c;  ">Hi Barry,</td>
                          </tr>
                          <tr>
                            <td style="font-size:13px; color:#656565; line-height:18px; padding-bottom:10px;">Great News! Aden’s overnight stay with Narinder has been confirmed.</td>
                          </tr>
                          <tr>
                            <td height="5"></td>
                          </tr-->
                          <!--tr>
                            <td align="left">
								<ul style="background-color: #fff3e3; color:#000000; font-size:15px; list-style:none; margin:0; padding:0; font-weight:600; padding: 10px 20px; letter-spacing: 0.3px;">
									<li style="line-height:30px; text-decoration:underline">Aden’s Itinerary:</li>
									<li>Date(s): <span style="font-style:italic; font-weight:400; line-height:30px;">1/18/18-1/20/18</span></li>
									<li>Service Type: <span style="font-weight:400; line-height:30px;">Overnight Stay</span></li>
									<li>Pet Care Provider: <span style="font-weight:400; line-height:30px;">Narinder Singh</span></li>
									<li>Price: <span style="font-weight:400; line-height:30px;">$33.49</span></li>
								</ul>
							</td>
                          </tr-->
                          <!--tr>
                            <td height="15"></td>
                          </tr>
                          <tr style="color:#656565; font-size:13px; line-height:19px;" >
                            <td>Please click here to access your account. We will notify you when Narinder posts activities and photos of your pal.</td>
                          </tr>
						  <tr style="color:#656565; font-size:13px; line-height:19px;" >
                            <td>Please click here to access your account. We will notify you when Narinder posts activities and photos of your pal.</td>
                          </tr-->
                          <?= $content ?>
                          <tr>
                            <td><p style="color:#8c8c8c; font-size:13px;line-height:19px; margin:0 0 12px 0; font-style:italic;">Your friends at PalCura<br/>Share the care…Multiply the love! <img style="width:18px;" src="<?php echo WEBSITE_IMAGES_PATH ?>foot-pic.jpg" border="0" alt="" /></p></td>
								
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
          <td height="37" valign="top" align="center"><pre class="email_footer" style="margin:7px 0 0 0; font-size:10px; font-weight:bold; text-transform:uppercase; color:#611012;"><a href="<?= SITE_URL ?>" style="text-decoration:none; font-family:Arial, Helvetica, sans-serif;  ">Home</a>   |   <a href="<?= SITE_URL.'cms/page/about-us' ?>" style="text-decoration:none; font-family:Arial, Helvetica, sans-serif; ">About us</a>   |  <a href="<?= SITE_URL.'site/contact' ?>" style="text-decoration:none; font-family:Arial, Helvetica, sans-serif; ">Contact Us</a></pre></td>
        </tr>
      </table></td>
    <td>&nbsp;</td>
  </tr>
</table>
</body>
</html>
<?php $this->endPage() ?>
