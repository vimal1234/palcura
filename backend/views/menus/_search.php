<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\MenusSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="right_col menus-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'mnuId') ?>

    <?= $form->field($model, 'mnuName') ?>

    <?= $form->field($model, 'menuSlug') ?>

    <?= $form->field($model, 'mnuStatus') ?>

    <?= $form->field($model, 'mnuDateCreated') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
