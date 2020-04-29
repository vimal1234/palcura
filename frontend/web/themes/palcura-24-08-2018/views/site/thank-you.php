<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
//rentingpetserror case 
                 
$this->title = 'Sign Up';
$this->params['breadcrumbs'][] = $this->title;
$siteimage = Yii::getAlias('@siteimage');
####= common methods =####
$hear_about             = array(1=>'Initial launch',2=>'Google',3=>'Facebook',4=>'Instagram',5=>'Twitter',6=>'Next door',7=>'Other Social',8=>'Event',9=>'Dog Park',10=>'Flyers',11=>'Word of mouth',12=>'Referral',13=>'Other');

$session = Yii::$app->session;
$registered = $session->get('registered');
//echo '---------test-------'.$registered;
 $session->set('registered',0);
?>  <div class="row">
        <div class="col-xs-12">
            <h1>Sign Up</h1>
        </div>
    </div>
</div>
</header>
<section class="contentArea">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <h2 class="text-center contactTilte thank-title">thank you for registering!</h2>
            </div>
            
        </div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">   
                <div class="formContent registerForm" style="background:#fff;">
                    <img class="contact-bg" src="<?= $siteimage ?>/contact-bg.png" alt="">
                    <div class="col-md-12 col-sm-12 col-xs-12 removePad">
                    <?php  if (Yii::$app->session->getFlash('item')): ?>
                   
                        <div class="alert alert-grey alert-dismissible alert-greythnk" id="scrrollhere">
                            
                             <?php echo Yii::$app->session->getFlash('item'); ?>
                        </div>
                    <?php endif; ?>
                        <div class="container">
                            	<div class="row">
								<img src="<?php echo $siteimage; ?>/animal-img.jpg" alt="animal image" title="Palcura">

							</div>
                        </div>
            
        </div>
    </div>
</div>
</section>
