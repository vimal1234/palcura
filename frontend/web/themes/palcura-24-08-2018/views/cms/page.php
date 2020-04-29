<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = $pageTitle;
$this->params['breadcrumbs'][] = $this->title;
$siteimage = Yii::getAlias('@siteimage').'/';
$pageContentEdited	= str_replace("images/",$siteimage,$pageContent);
?>
	<div class="row">
		<div class="col-xs-12"><h1><?= strtoupper($this->title) ?></h1></div>
	</div>
</div>
</header>
<?php echo $pageContentEdited; ?>
