<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use frontend\models\Interests;
use common\models\Country;
use common\models\Currency;
use common\models\State;
use common\models\City;
use yii\db\Query;

$this->title = Yii::t('yii', 'Setting Profile');
$this->params['breadcrumbs'][] = $this->title;
$attributes = Yii::$app->user->identity->getattributes();
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
				  <ul class="nav nav-tabs responsive" role="tablist" id="myTab" >
					<li role="presentation" ><a href="<?php echo Url::home().'member/settings'; ?>"><?php echo Yii::t('yii','Account Information');?></a></li>
					<li role="presentation" class="active"><a  href="<?php echo Url::home().'account/settings'; ?>"><?php echo Yii::t('yii','Payout Option');?></a></li>           
				  </ul>					
				  <div class="tab-content responsive">
                    <div class="message">
                        <?php if (Yii::$app->session->getFlash('item')): ?>
                            <div class="col-xs-12">
                                <div class="alert alert-grey alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span>
                                    </button>
                                    <i class="glyphicon glyphicon-ok"></i><?php echo Yii::$app->session->getFlash('item'); ?>
                                </div>
                            </div>																	
                        <?php endif; ?>     
    <?php
    $form = ActiveForm::begin(
            [ 'id' => 'editProfile-form',
                                                                                'options' => [                                                        'class' => 'inner',
                                                                                    'enctype' => 'multipart/form-data',
                                                                                    'tag' => 'span', ####THIS OPTIONS DISABLES THE DIV.FORM_GROUP ENCLOSER TAG FOR FIELDS
                                                                                ],
                                                                                'fieldConfig' => [
                                                                                    'template' => "<div class=\"form-group\">\n
                                                                                    {label}\n
                                                                                       <div class=\"val\">\n
                                                                                              <div class=\"controls\">
                                                                                              {input}
                                                                                                     <div class=\"error-text\">
                                                                                                     {error} {hint}
                                                                                                     </div>
                                                                                              </div>
                                                                                              </div>
                                                                                       </div>",
                                                                                    'labelOptions' => ['class' => ''],
                                                                                    'options' => [
                                                                                            'tag' => 'span', ####THIS OPTIONS DISABLES THE DIV.FORM_GROUP ENCLOSER TAG FOR FIELDS
                                                                                            'class' => 'inner', ####DISABLE THE DEFAULT FORM_GROUP CLASS
                                                                                    ],
                                                                                ],
        ]
        );
    
    ?>
    <div class="fullwidth">
        <div class="col-lg-4 col-md-4 col-sm-4">
            <div class="form-group ">
                <div class="val">
                    <div class="controls">
                        <label>Payment Method</label>
                        <div class="radioButton">
                                                                                            <?php 
                                                                                            $chk    =	'';
                                                                                            if($model->account_type == '') {
                                                                                                    $chk	=	'checked';
                                                                                            }
                                                                                            ?>
            <input type="radio" id="acctr" name="Account[account_type]" value="<?= PAYPAL_METHOD ?>" <?= (isset($model->account_type) && $model->account_type == PAYPAL_METHOD ? 'checked' : '').$chk ?>  />
            <span><?php echo Yii::t('yii', 'Paypal'); ?></span>
            <input type="radio" name="Account[account_type]" value="<?= BANK_TRANSFER ?>" <?= (isset($model->account_type) && $model->account_type == BANK_TRANSFER ? 'checked' : '') ?> />
            <span><?php echo Yii::t('yii', 'Bank Transfer'); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="fullwidth bt">
        <div class="col-lg-4 col-md-4 col-sm-4">
            <?php
                echo $form->field($model, 'account_holder_name', ['inputOptions' => [
                    'class' => "form-control textfeild",
                ]])->textInput(['maxlength' => 60, 'autofocus' => true]);
            ?>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4">
            <?php
                echo $form->field($model, 'bank_name', ['inputOptions' => [
                    'class' => "form-control textfeild",
                ]])->textInput(['maxlength' => 60, 'autofocus' => true]);
            ?>
        </div>

        <div class="col-lg-4 col-md-4 col-sm-4">
                <?php
                        $region = array("INSIDE_EUROPE" => " Inside Europe","OUTSIDE_EUROPE" => "Outside Europe");
                        echo $form->field($model, 'region')->dropDownList($region, ['prompt' => Yii::t('yii', 'Select Currency')]);
                ?>
        </div>
    </div>
    <div class="fullwidth bt">					
        <div class="col-lg-4 col-md-4 col-sm-4">
            <?php
                echo $form->field($model, 'branch_code', ['inputOptions' => [
                    'class' => "form-control textfeild",
                                                    ]])->textInput(['maxlength' => 10, 'autofocus' => true]);
            ?>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4">
            <?php
                echo $form->field($model, 'account_number', ['inputOptions' => [
                    'class' => "form-control textfeild",
                                                    ]])->textInput(['maxlength' => 50, 'autofocus' => true]);
            ?>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4" id="iban">
            <?php
                echo $form->field($model, 'IBAN', ['inputOptions' => [
                    'class' => "form-control",
                ]])->textInput(['maxlength' => 80, 'autofocus' => true]);
            ?>
        </div>
         <div class="fullwidth">
            <div class="col-xs-12">
                <button type="submit" class="btn btn-primary orangebtn"><?php echo Yii::t('yii', 'Save changes'); ?></button>
            </div>
        </div>
    </div>
   
   <?php ActiveForm::end(); ?>
    <?php
    $form2 = ActiveForm::begin(
            [ 'id' => 'editProfile-form2',
                                                                                'options' => [                                                        'class' => 'inner',
                                                                                    'enctype' => 'multipart/form-data',
                                                                                    'tag' => 'span', ####THIS OPTIONS DISABLES THE DIV.FORM_GROUP ENCLOSER TAG FOR FIELDS
                                                                                ],
                                                                                'fieldConfig' => [
                                                                                    'template' => "<div class=\"form-group\">\n
                                                                                    {label}\n
                                                                                       <div class=\"val\">\n
                                                                                              <div class=\"controls\">
                                                                                              {input}
                                                                                                     <div class=\"error-text\">
                                                                                                     {error} {hint}
                                                                                                     </div>
                                                                                              </div>
                                                                                              </div>
                                                                                       </div>",
                                                                                    'labelOptions' => ['class' => ''],
                                                                                    'options' => [
                                                                                            'tag' => 'span', ####THIS OPTIONS DISABLES THE DIV.FORM_GROUP ENCLOSER TAG FOR FIELDS
                                                                                            'class' => 'inner', ####DISABLE THE DEFAULT FORM_GROUP CLASS
                                                                                    ],
                                                                                ],
        ]
        );
    ?>
    <input type="hidden" name="Account[account_type]" value="<?= PAYPAL_METHOD ?>" />
    <div class="fullwidth pt">
        <div class="col-lg-4 col-md-4 col-sm-4">
            <?php
               echo $form2->field($model, 'paypal_email_address', ['inputOptions' => [
                    'class' => "form-control textfeild",
                                                    ]])->textInput(['maxlength' => 100, 'autofocus' => true]);
            ?>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4">
                <?php
                        $currency = ArrayHelper::map(Currency::find()->all(), 'id', 'currency_name');
                        echo $form2->field($model, 'accept_currency')->dropDownList($currency, ['prompt' => Yii::t('yii', 'Select Currency')]);
                ?>
        </div>
        <div class="fullwidth">
        <div class="col-xs-12">
            <button type="submit" class="btn btn-primary orangebtn"><?php echo Yii::t('yii', 'Save changes'); ?></button>
        </div>
    </div>
    </div>
    
    <?php ActiveForm::end(); ?>
                        
                </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    form.inner{
      padding: 0px;  
    }
</style>
<script type="text/javascript">
    $(document).ready(function () {
        var bt	= '<?= BANK_TRANSFER ?>';
        var rdval = $("input[name='Account[account_type]']:checked").val();
        if(rdval == bt) {
                $(".bt").css("display","block");
                $(".pt").css("display","none");
        } else {
                $(".pt").css("display","block");
                $(".bt").css("display","none");
        }

        $("input[type='radio']").on('click',function() {
                var radioValue = $("input[name='Account[account_type]']:checked").val();
                if(radioValue == bt) {
                        $(".bt").css("display","block");
                        $(".pt").css("display","none");
                } else {
                        $(".pt").css("display","block");
                        $(".bt").css("display","none");
                }
        });
        
        if($('#account-region').val()=='OUTSIDE_EUROPE') {
           $('#iban').hide();
           $('#iban input').removeAttr('id');

        } else {
            $('#iban').show();
            $('#iban input').attr('id','account-iban');
        }
       
        $('#account-region').on('change',function(){
            if($(this).val()=='OUTSIDE_EUROPE') {
               $('#iban').hide();
               $('#iban input').removeAttr('id');
               
           } else {
                $('#iban').show();
                $('#iban input').attr('id','account-iban');
            }
        });
        
    });
</script>
</section>
