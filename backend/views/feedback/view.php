<?php
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\feedback */

//$this->title = $model->userReceiver->firstname;
$this->title = '';
$this->params['breadcrumbs'][] = ['label' => 'Feedback', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-view right_col">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?php //echo Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']); ?>
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
			[
				'attribute' => 'sender_userid',
				'label' => 'Sender',
			],
			[
				'attribute' => 'receiver_userid',
				'label' => 'Reciever',
			],
            'comment',
            'starrating',
            'date_time',      
            [
                'attribute'=>'status',
                'value'=>$model->statusFormat,
            ],
        ],
    ]) ?>
</div>
