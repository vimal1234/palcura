<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\MessageInterface the message being composed */
/* @var $content string main view render result */
?>
<?php $this->beginPage() ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?= Yii::$app->charset ?>" />
    <?php $this->head() ?>
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
    <td width="590" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td style="border:solid 1px #ff8447; background:#ff8447; padding:4px;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td bgcolor="#fff"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td height="64" style="text-align:center; padding-top:30px; padding-bottom:30px; background:#000000"><img style="width:226px;" src="<?php echo SITE_LOGO; ?>" border="0" alt="" /></td>
                    </tr>
                    <tr>
                      <td bgcolor="#ff8447"  style="font-size:14px; color:#ffffff; padding:12px 10px;  "><font style="font-size:17px; font-weight:500; color:#fff;"><?= $subject ?></td>
                    </tr>
                    <tr>
                      <td valign="top" bgcolor="#ffffff" style="padding:12px;">
						  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                         <!--<tr>
                            <td height="26" style="font-size:15px; font-weight:500; color:#2c1f14;  ">Dear Mr Receiver Name</td>
                          </tr>
                          <tr>
                            <td style="font-size:13px; color:#2c1f14; line-height:18px; padding-bottom:10px;">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap int. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</td>
                          </tr> -->
                          <!--<tr>
                            <td height="5"></td>
                          </tr>
                          <tr>
                            <td align="left"><table width="287" border="0" bgcolor="#2c1f14" cellspacing="1" cellpadding="6" style=" color:#2c1f14;">
                                <tr  bgcolor="#2c1f14">
                                  <td colspan="2" style="border-top:#203367 solid 0px; font-size:14px; font-weight:400; color:#ffffff; text-transform:capitalize;   padding:8px;">Password Reset Information</td>
                                </tr>
                                <tr  bgcolor="#ffffff">
                                  <td width="100" >Username</td>
                                  <td width="270" >Username</td>
                                </tr>
                                <tr  bgcolor="#ffffff">
                                  <td>Password</td>
                                  <td >Password</td>
                                </tr>
                                <tr  bgcolor="#ffffff">
                                  <td >Question</td>
                                  <td >What is your Lorem Ipsum ?</td>
                                </tr>
                              </table></td>
                          </tr>
                          <tr>
                            <td height="15"></td>
                          </tr>-->
                           <?= $content ?>
                          <tr>
                            <td height="10"></td>
                          </tr>
                          
                          <!--tr  style="color:#656565; font-size:13px; line-height:19px;">
                            <td>Regards,<br />
                              Customer Support Team<br />
                              Palcura</td>
                          </tr-->
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
