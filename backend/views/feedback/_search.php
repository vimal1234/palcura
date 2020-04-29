<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\searchPage */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="feed-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'comment') ?>

    <?= $form->field($model, 'status') ?>

    <?= $form->field($model, 'pageType') ?>

    <?= $form->field($model, 'booking_id') ?>

    <?php // echo $form->field($model, 'metaKeyword') ?>

    <?php // echo $form->field($model, 'metaDescriptions') ?>

    <?php // echo $form->field($model, 'pageContent') ?>
    
	<?php  echo $form->field($model, 'pageDateCreated') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
