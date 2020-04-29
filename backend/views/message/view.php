<?php
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\messsage */

$this->title = $model->subject;
$this->params['breadcrumbs'][] = ['label' => 'Messages', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div role="main" class="right_col">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
       
        <?= Html::a('Delete', ['delete', 'id' => $model->message_id], [
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
            'message_id',
            #'conversation_id',
            ['attribute'=>'userTo.fullName', 'label'=> 'Receiver Name'],
            ['attribute'=>'userFrom.fullName', 'label'=> 'Sender Name'],
            'message',
            'booking_id',
            'subject',
            'date_created:dateTime',
            'is_trashed:boolean',
            
            // 'pageDateCreated',
        ],
    ]) ?>

</div>
