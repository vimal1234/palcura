<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

//print_r($data);
//die;
$this->title = 'Profile';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-signup">
    <?php printf('<h3>WelCome %s</h3>', $model->fullname); ?>    

    <div class="row">
        
        <div class="span12">
            
            <table class="table table-bordered">
                <tr><th>Full Name</th><td><?= $model->fullname ;?></td></tr>
                <tr><th>User Name</th><td><?= $model->username ;?></td></tr>
                <tr><th>Email</th><td><?= $model->email ;?></td></tr>
                <tr><th>Gender</th><td><?= $model->gender ;?></td></tr>
                <tr><th>Date Of Birth</th><td><?= $model->dob ;?></td></tr>
            </table>
          <div class="form-group">
            <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        </div>
        </div>
    </div>
</div>
