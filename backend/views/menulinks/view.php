<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Menulinks */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Menulinks', 'url' => ['menulinks/links/'.$menuID]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="right_col" role="main">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'title',
            'menuType',
            'menuUrl',
        ],
    ]) ?>

</div>
