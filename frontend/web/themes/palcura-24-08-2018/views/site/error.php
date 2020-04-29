<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;
$this->title = $name;
?>
	<div class="row">
		<div class="col-xs-12"><h1><?= $name ?></h1></div>
	</div>
</div>
</header>
<section class="contentArea">
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<h2><?= nl2br(Html::encode($message)) ?></h2>
				<p><?php echo Yii::t('yii','The above error occurred while the Web server was processing your request.');?></p>
				<p><?php echo Yii::t('yii','Please contact us if you think this is a server error. Thank you.');?></p>
			</div>
		</div>
	</div>
</section>
