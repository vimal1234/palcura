<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\searchPage */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Feedback';
$this->params['breadcrumbs'][] = $this->title;
?>
<!-- page content -->
<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3><?= Html::encode($this->title) ?></h3>
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
                        <p>
                            <?= Html::button('Apply Filter', ['class' => 'btn btn-primary', 'id' => 'btnfilterApply', 'style' => 'float:right']) ?>
                            <?= Html::button('Reset Filter', ['class' => 'btn btn-primary','id'=>'resetFilter','style'=>'float:right']) ?>
                        </p>
                        <?php Pjax::begin(['id' => 'Pjax_Menufilter', 'timeout' => false, 'enablePushState' => false, 'clientOptions' => ['method' => 'GET']]); ?> 
                        <?=
                        GridView::widget([
                            'id' => 'grid-container',
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],
                                [
                                    'attribute' => 'userSender',
                                    'label' => 'Sender',
                                    'value' => 'userSender.firstname'
                                ],
                                [
                                    'attribute' => 'userReceiver', 
                                    'label' => 'Receiver',
                                    'value' => 'userReceiver.firstname'
                                ],
                                [
                                    'attribute' => 'comment', 
                                    'label' => 'Comment'
                                ],
                                [
                                    'attribute' => 'starrating', 
                                    'label' => 'Rating'
                                ],
                                [
                                    'attribute' => 'date_time',
                                    'value' => 'date_time',
                                    'filterOptions' => ['id' => 'tdDateCreated'],
                                    'format' => [ADMIN_DATE_L, ADMIN_DATE_FORMAT_L]
                                ],
                                [
                                    'attribute'=>'status', 
                                    'value'=>function($model){
                                        return ($model->status == '1') ? 'Active' : 'Inactive';                             
                                    },
									'filter' => Html::activeDropDownList($searchModel, 'status', array('1' => 'Active', '0' => 'Inactive'),['class'=>'form-control','prompt' => 'Status']),                                    
                                ],
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'template' => '{view} {delete} {status}',
                                    'buttons' => [
                                        'status' =>
                                        function ($url, $dataProvider) {
                                            return Html::dropDownList('action', $dataProvider->status, ['' => 'Status', '1' => 'Active', '0' => 'Inactive'], ['class' => 'dropdown', 'title' => 'Change Status', 'onchange' => 'updateStatus(this,' . $dataProvider->id . ')']);
                                        },
                                    ],
                                    'header' => '&nbsp;&nbsp;&nbsp;Actions&nbsp;&nbsp;&nbsp;&nbsp;',
                                ],
                            ],
                        ]);
                        ?>
                        <script>
                        $(document).ready(function () {

                            $('#tdDateCreated input').prop('readonly', true);

                            $('#tdDateCreated input').daterangepicker({
                                singleDatePicker: true,
                                calender_style: "picker_4",                                
                                format: 'YYYY-MM-DD'
                            });

                            $('#tdDateCreated input').on('apply.daterangepicker', function(ev, picker) {    
                                $("#grid-container").yiiGridView("applyFilter");                            
                            });
                        });    
                        </script>
                        <?php Pjax::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- footer content -->
    <?php echo $this->render('../includes/footer'); ?>
    <!-- /footer content -->

</div>

<!-- daterangepicker -->
<script type="text/javascript" src="<?php echo Url::home(); ?>/themes/gentelella/js/moment.min2.js"></script>
<script type="text/javascript" src="<?php echo Url::Home(); ?>/themes/gentelella/js/datepicker/daterangepicker.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $('.x_title').on('click', '#resetFilter', function(){
            $('.filters').find('input').val('');
            $('.filters').find('select option:eq(0)').prop('selected', true);
            $("#grid-container").yiiGridView("applyFilter");
        });

        $('.x_title').on('click', '#btnfilterApply', function(){
            $("#grid-container").yiiGridView("applyFilter");
        });
    });
</script>
