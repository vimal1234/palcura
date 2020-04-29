<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\page */

$this->title = 'Send Message';
$this->params['breadcrumbs'][] = ['label' => 'Message', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
