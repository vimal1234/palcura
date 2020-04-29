<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\helpers\Url;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
	<?php
		$CtrlName	=	Yii::$app->controller->id;
		$FunName	=	Yii::$app->controller->action->id;
	?>
	<script> 
	  var homeURL   = '<?= Url::home() ?>';	
	  var CtrlName  = '<?= $CtrlName ?>';
	  var FunName   = '<?= $FunName ?>';
	</script>     
</head>
<body class="nav-md">
<?php 
	$this->beginBody(); 
	$this->endBody();
?>
    <div class="container body">
	<div class="main_container">
	<?php if( Yii::$app->controller->id != 'site' ) { ?>	
      <?php 
		echo $this->render('../includes/sidebar_menu');
		echo $this->render('../includes/topnav');
		?>
	<?php } ?>
        <?= $content ?>
	 
	  </div>
    </div>
    <div id="custom_notifications" class="custom-notifications dsp_none">
        <ul class="list-unstyled notifications clearfix" data-tabbed_notifications="notif-group">
        </ul>
        <div class="clearfix"></div>
        <div id="notif-group" class="tabbed_notifications"></div>
    </div>
                
<?php //$this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

