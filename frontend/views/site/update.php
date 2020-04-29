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
        
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(); ?>

                <?= $form->field($model, 'username')->textInput(['autofocus' => true,'readOnly'=>true]) ?>
                
                <?= $form->field($model, 'fullname')->textInput() ?>

                <?= $form->field($model, 'email')->textInput(['readOnly'=>true,]) ?>                
                 
                 <?= $form->field($model, 'gender')->radioList(array('M'=>'Male', 'F'=>'Female')) ?>
                 
                 <?= $form->field($model, 'dob') ?>
                 

                <div class="form-group">
                    <?= Html::submitButton('Update', ['class' => 'btn btn-primary']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
