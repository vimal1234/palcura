<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\page */
/* @var $form yii\widgets\ActiveForm */
?>
<script type="text/javascript" src="<?php echo Url :: Home(); ?>/themes/gentelella/js/tiny_mce/tinymce.js"></script>
<script type="text/javascript" src="<?php echo Url :: Home(); ?>/themes/gentelella/js/tiny_mce/addediter.js"></script>
<div class="right_col" role="main">

    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>
                    <?= Html::encode($this->title) ?>
                </h3>
            </div>
        </div>
        <div class="clearfix"></div>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">

                <div class="x_panel">
                    <div class="x_title">
                        <?php if (isset($data['respmesg'])) { ?>	
                            <div class="alert <?= $data['class'] ?> alert-dismissible fade in" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                                </button>
                                <?php echo $data['respmesg']; ?>
                            </div>
                        <?php } ?>  

                    </div>
                    <div class="x_content">
                        <p>* All fields are mandatory</p>

                        <?php
                        $form = ActiveForm::begin(
                            [ 
                                'id' => 'form-createuser',
                                'options' => ['class' => 'form-horizontal form-label-left', 'enctype' => 'multipart/form-data'],
                                'fieldConfig' => [
                                    'template' => "<div class=\"item form-group\">\n{label}\n<div class=\"col-md-6 col-sm-6 col-xs-12\">
                                                                            {input}<div class=\"col-lg-10\">
                                                                            {error}</div></div></div>",
                                    'labelOptions' => ['class' => 'control-label col-md-3'],
                                ],
                        ]);
                        ?>


                        <?php
                        echo $form->field($model, 'pageName', ['inputOptions' => [
                                'class' => "form-control col-md-7 col-xs-12",
                            ]])->textInput(['autofocus' => true])->label('Page Name <span class="required">*</span>');
                        ?>

                        <?php
                        echo $form->field($model, 'pageTitle', ['inputOptions' => [
                                'class' => "form-control col-md-7 col-xs-12",
                            ]])->textInput(['maxlength' => true])->label('Page Title <span class="required">*</span>');
                        ?>
                        <?php
                        $model->pageType = 2;
                        $list = [1 => 'Module', 2 => 'Page'];
                        echo $form->field($model, 'pageType', ['inputOptions' => [
                                'class' => "form-control col-md-7 col-xs-12",
                            ]])->radioList($list)->label('Page Type <span class="required">*</span>');
                        ;
                        ?>

                        <div id = "Metafeilds">
                            <?php
                            echo $form->field($model, 'metaTitle', ['inputOptions' => [
                                    'class' => "form-control col-md-7 col-xs-12",
                                ]])->textInput(['maxlength' => true]);
                            ?>

                            <?php
                            echo $form->field($model, 'metaKeyword', ['inputOptions' => [
                                    'class' => "form-control col-md-7 col-xs-12",
                                ]])->textInput(['maxlength' => true]);
                            ?>

                            <?php
                            echo $form->field($model, 'metaDescriptions', ['inputOptions' => [
                                    'class' => "form-control col-md-7 col-xs-12",
                                ]])->textInput(['maxlength' => true]);
                            ?>
                        </div>
                        
                        <?php 
                        if(Yii::$app->controller->action->id == 'update' && strtolower($model->slug) == 'tips' )
                            echo $form->field($modelImageUpload, 'imageUpload')->fileInput()->label('Top Image'); 
                        ?>
                        
                        <?php
                        echo $form->field($model, 'pageContent', ['inputOptions' => [
                                'class' => "form-control col-md-7 col-xs-12",
                            ]])->textarea(['rows' => 6])->label('Page Content <span class="required">*</span>');
                        ?>							

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-3">
                                <button type="button" class="btn btn-primary" onclick="javascript:window.location.href = '<?php echo Url::home() . "page"; ?>'">Cancel</button>
                                <?php echo Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>

                            </div>
                        </div>


                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- footer content -->
<?php echo $this->render('../includes/footer'); ?>
    <!-- /footer content -->

</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("input[name$='page[pageType]']").click(function () {
            var test = $(this).val();
            if (test == 2) {
                $("div #Metafeilds").show();
            } else {
                $("div #Metafeilds").hide();
            }
        });
    });
</script>
