<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Menulinks */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="menulinks-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput() ?>

	<?= $form->field($model, 'menu_id')->hiddenInput(['value'=>21])->label(false); ?>
	
	<?php echo $form->field($model, 'Type')->dropDownList(['0' => 'Select Page Type' , '1' => 'Custom URL', '2' => 'Page']); ?>	
		<div id="pageItems" class="pageNone">
			
		<?= $form->field($model, 'Page')->dropDownList( $list , ['class'=>'form-control']) ?>
    	<?= $form->field($model, 'customPage')->dropDownList( $plist , ['prompt' => 'Select Parent']  , ['class'=>'form-control']) ?> 
		</div>
    <div id="customUrl" class="pageNone">

        <?= $form->field($model, 'Custom')->textInput(['class'=>'form-control']) ?>
 		<?= $form->field($model, 'customURL')->dropDownList( $plist , ['prompt' => 'Select Parent']  , ['class'=>'form-control']) ?> 
    </div>
    <div class="form-group">
        <?= Html::submitButton( $model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary'] ) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
