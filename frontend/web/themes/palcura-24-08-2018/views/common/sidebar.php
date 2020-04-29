<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\db\Query;

$attributes = 	Yii::$app->user->identity->getattributes();
$userId 	= 	Yii::$app->user->getId();
$siteimage 	= 	Yii::getAlias('@siteimage');
$CtrlName	=	Yii::$app->controller->id;
$FunName	=	Yii::$app->controller->action->id;
$profile_picture = $attributes['profile_image'];
if(isset($attributes['profile_image']) && !empty($attributes['profile_image'])) { 
	$profile_pic = PROFILE_IMAGE_PATH . $attributes['profile_image'];
} else {
	$profile_pic = NO_DISPLAY_IMAGE;
}
$session 		= Yii::$app->session;
$logged_user 	= $session->get('loggedinusertype');
?>
<div class="col-md-2 col-sm-3 col-xs-12">
	<div class="leftSidebar">
		<div class="sideThumb">
			<img class="img-responsive" src="<?php echo $profile_pic; ?>" alt="" >
		</div>
		<div class="verifiedMain">
			<?php if(isset($attributes['verification_badge']) && $attributes['verification_badge'] == 1 && $logged_user== SITTER) { ?>
			<div class="verified"><i class="fa fa-check-circle" aria-hidden="true"></i>Verified</div>
			<?php }elseif($attributes['verification_badge'] == 0  && $logged_user== SITTER){ ?>
			<div class="verified"><i class="fa fa-check-circle" aria-hidden="true"></i>Pending</div>
			<?php }?>
			<div class="mobileside"><i class="fa fa-angle-down" aria-hidden="true"></i></div>
		</div>
		<div class="leftSidebarNav">
			<ul>
				<li <?php if($CtrlName == 'users' && $FunName == 'dashboard') { ?> class="active" <?php } ?>>
					<?php echo Html::a(Yii::t('yii','Dashboard'),Url::home().'users/dashboard',['users']);?>
				</li>
				<li <?php if($CtrlName == 'users' && $FunName == 'myprofile') { ?> class="active" <?php } ?>>
					<?php echo Html::a(Yii::t('yii','Your Profile'),Url::home().'users/myprofile',['users']);?>
				</li>
				<li <?php if($CtrlName == 'bookings') { ?> class="active" <?php } ?>>
					<?php echo Html::a(Yii::t('yii','Bookings'),Url::home().'bookings',['booking']);?>
				</li>
				<li <?php if($CtrlName == 'messages') { ?> class="active" <?php } ?>>
					<?php echo Html::a(Yii::t('yii','Messages'),Url::home().'messages',['messages']);?>
				</li>
				<li <?php if($CtrlName == 'payments') { ?> class="active" <?php } ?>>
					<?php echo Html::a(Yii::t('yii','Payments'),Url::home().'payments',['payments']);?>
				</li>
				<li <?php if($CtrlName == 'video') { ?> class="active" <?php } ?>>
					<?php echo Html::a(Yii::t('yii','Video Calls'),Url::home().'video',['video']);?>
				</li>
				<li <?php if($CtrlName == 'reviews') { ?> class="active" <?php } ?>>
					<?php echo Html::a(Yii::t('yii','Reviews'),Url::home().'reviews',['reviews']);?>
				</li>
			</ul>
		</div>
		<div class="formLeftSidebar">
			<?php 
				echo Html::a(Yii::t('yii','Check Calendar').'<span class="glyphicon glyphicon-calendar"></span>',Url::home().'calendar/',['users']);
			?>
		</div>
	</div>
</div>
