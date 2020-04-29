<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Banner Settings';
?>
<!-- page content -->
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
                        <?php if (Yii::$app->session->getFlash('item')): ?>
                            <div class="alert alert-success alert-dismissible fade in" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                                </button>    
                            <?php echo Yii::$app->session->getFlash('item'); ?>
                            </div>
                        <?php endif; ?>

                    </div>
                    
                    <div class="x_content">
                        <p>All fields are mandatory:</p>
                        <?php
                        $form = ActiveForm::begin(
                            ['id' => 'form-settings',
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
                        $videoHomepage = $model['video'];
                        if(!empty($videoHomepage) )
                        {
                        ?>
                            <div class="editblocks blueprints-row">
                                <div class="row">
                                    <div class="col-xs-12">
                                        <label class="control-label col-md-3">Uploaded Video</label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                               <a target="_blank" href="<?= Url::to(['../common/uploads/banner/'."$videoHomepage"], true); ?>"> <i class="fa fa-paperclip"></i> <?= $videoHomepage; ?></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br/>
                        <?php
                        }
                        ?>
                        
                        <?php echo $form->field($modelBannerVideoUpload, 'bannerVideo')->fileInput()->label('Video'); ?>

                        <?php
                        echo $form->field($model, 'setting_type', ['inputOptions' => [
                                'class' => "form-control col-md-7 col-xs-12",
                        ]])->radioList(['video'=>'Video', 'banner'=>'Banner'])->label('Show on homepage');
                        ?>



                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-3">
                                <button type="button" class="btn btn-primary" onclick="javascript:window.location.href = '<?php echo Url::home(); ?>'">Cancel</button>
                                <?= Html::submitButton('Submit', ['class' => 'btn btn-success', 'name' => 'AddBanner-submit', 'id' => 'AddBanner-submit']) ?>
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
<!-- /page content -->