<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\CrudTest */

$this->title = 'Update User: ' . ' ' . $model->fullname;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->fullname, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="user-update">
	
    <?= $this->render('_form', [ 
		'data' => $data,
        'model' => $model,
    ]) ?>

</div>
