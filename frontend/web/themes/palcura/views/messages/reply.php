<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use frontend\models\Interests;
use common\models\Country;
use common\models\State;
use common\models\City;

$this->title = Yii::t('yii', 'Reply');
$this->params['breadcrumbs'][] = $this->title;
?>
<section>
<?php echo $this->render('//common/searchbox'); ?>
    <div class="searchresult">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-sm-4 col-xs-12">
                    <?php echo $this->render('../common/sidebar'); ?>
                </div>
                <div class="col-xs-12 col-sm-8 col-md-9">
                    <div class="message">
                        <?php if (Yii::$app->session->getFlash('item')): ?>
                            <div class="col-xs-12">
                                <div class="alert alert-grey alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span></button>
                                    <i class="glyphicon glyphicon-ok"></i><?php echo Yii::$app->session->getFlash('item'); ?>
                                </div>
                            </div>																	
                        <?php
                        endif;
                        $form = ActiveForm::begin(
                            [ 'id' => 'editProfile-form',
                                'options' => [
                                    'enctype' => 'multipart/form-data',
                                    'tag' => 'span', ####THIS OPTIONS DISABLES THE DIV.FORM_GROUP ENCLOSER TAG FOR FIELDS
                                ],
                                'fieldConfig' => [
                                    'template' => "<div class=\"fullwidth\">\n
                                                        <div class=\"col-xs-12\">\n
                                                            <div class=\"form-group\">\n
                                                                {label}\n
                                                                    <div class=\"val\">\n
                                                                        <div class=\"controls\">
                                                                            {input}
                                                                            <div class=\"col-lg-10\">
                                                                            {error} {hint}
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                    </div>",
                                    'labelOptions' => ['class' => ''],
                                    'options' => [
                                        'tag' => 'span', ####THIS OPTIONS DISABLES THE DIV.FORM_GROUP ENCLOSER TAG FOR FIELDS
                                        'class' => '', ####DISABLE THE DEFAULT FORM_GROUP CLASS
                                    ],
                                ],
                            ]
                        );
                        ?>
                        <div class="fullwidth">
                            <div class="col-xs-12">
                                <div class="form-group ">
                                    <label><?php echo Yii::t('yii', 'To'); ?></label>
                                    <div class="val">
                                        <div class="controls">
                                            <input type="text" class="form-control textfeild validate[required]"
                                                name="Messages[email]" value="<?= (isset($emailTo) ? $emailTo : '') ?>" readonly />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="Messages[user_from]" value="<?= $model['user_from'] ?>" />
                        <input type="hidden" name="Messages[user_to]" value="<?= $userTo ?>" />
                        <input type="hidden" name="Messages[booking_id]" value="<?= $model['booking_id'] ?>" />
                        <input type="hidden" name="Messages[send_status]" value="<?= (isset($model['send_status']) ? $model['send_status'] : '') ?>" />
                        <?php
                        //echo $form->field($model, 'subject', ['inputOptions' => [
                        //        'class' => "form-control textfeild",
                        //    ]])->textInput(['maxlength' => 80, 'autofocus' => true])->label(Yii::t('yii', 'Subject'));
                        ?>
                        <?php
                        echo $form->field($model, 'message', ['inputOptions' => [
                                'class' => "form-control textarea textfeild",
                            ]])->textarea(array("rows" => "4", 'value' => ''))->label(Yii::t('yii', 'Description'));
                        ?>
                        <div class="fullwidth">
                            <div class="col-xs-12">
                        <?= Html::submitButton(Yii::t('yii', 'Send'), ['class' => 'btn btn-primary orangebtn', 'name' => 'reply-btn', 'id' => 'reply-button']) ?>
                            </div>
                        </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
