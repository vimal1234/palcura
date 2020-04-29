<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\page */

$this->title = 'Create Feedback';
$this->params['breadcrumbs'][] = ['label' => 'Feeds', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
