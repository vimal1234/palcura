<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\CrudTest */

$this->title = 'Create User';
//$this->params['breadcrumbs'][] = ['label' => 'User', 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-create">

    <?= $this->render('_form', [
		'data' => $data,
        'model' => $model,
        'usersmodel' => $usersmodel,
    ]) ?>

</div>
