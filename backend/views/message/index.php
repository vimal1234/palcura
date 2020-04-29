<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\searchPage */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Messages';
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
						<?= Html::a('<i class="fa fa-plus"></i> Send Message', ['create'], ['class' => 'btn btn-success']) ?>
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
                                    'attribute' => 'userFrom',
                                    'value' => function($model){ 
                                        return ($model->send_status == '1' && $model->user_from == 1) ? 'Admin' : $model->userFrom->fullName;                                         
                                    },
                                ],
                                [
                                    'attribute' => 'userTo',
                                    'value' => function($model){ 
                                        return ($model->send_status == '1' && $model->user_to == 1) ? 'Admin' : $model->userTo->fullName;                                         
                                    },
                                ],
                                [
                                    'attribute' => 'country',
                                    'label' => 'Country',
                                    'value' => 'userFrom.usrCountry'
                                ], 
                                
                                'booking_id',
                                [
                                    'attribute' => 'message',
                                    'value' => function($model){ 
                                        return (strlen($model->message) > 50) ? substr($model->message, 0, 50)."..." : $model->message;                                         
                                    },
                                ],
                                [
                                    'attribute' => 'subject',
                                    'value' => function($model){ 
                                        return (strlen($model->subject) > 50) ? substr($model->subject, 0, 50)."..." : $model->subject;                                         
                                    },
                                ],
                                [
                                    'attribute' => 'date_created',
                                    'value' => 'date_created',
                                    'format' => 'date',                                    
                                    'filterOptions' => ['id' => 'tdDateCreated'],
                                ],
                                [
                                    'attribute' => 'status',
                                    'value' => function($model){ 
                                        return ($model->status == '1') ? 'Active' : 'Inactive';                                          
                                    },
                                ],
                                ['class' => 'yii\grid\ActionColumn',
                                    'template' => '{view} {delete} {status}',
                                    'buttons' => [
                                        'status' =>
                                        function ($url, $dataProvider) {
                                            return Html::dropDownList('action', $dataProvider->status, ['' => 'Status', 'Active' => 'Active', 'Inactive' => 'Inactive'], ['class' => 'dropdown', 'title' => 'Change Status', 'onchange' => 'updateStatus(this,' . $dataProvider->message_id . ')']);
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

        //~ var submit_form = false;
        //~ $('body').on('click', '#btnfilterApply',  function(){
        //~ //enable submit for applyFilter event
        //~ if(submit_form === false) {
        //~ submit_form = true;
        //~ $("#grid_page_container").yiiGridView("applyFilter");
        //~ }
        //~ });

        //disable default submit

        //~ $("body").on('beforeFilter', "#grid_page_container" , function(event) {
        //~ return submit_form;
        //~ });
//~ 
        //~ $("body").on('afterFilter', "#grid_page_container" , function(event) {
        //~ submit_form = false;
        //~ });

        //$('.form-control').keypress(function(ev)
        // if (ev.which === 13) { }
        //});	


//    $(document).on('pjax:complete', function () { });

    function updateStatus(dis, pageid)
    {
        var post = {'Updatemessage': {'status': dis.value}};
        if (dis.value && dis.value != '') {
            $.ajax({
                url: '<?php echo Url::home(); ?>message/status/' + pageid,
                type: 'post',
                data: post,
                success: function (response) {
                    window.location.reload();
                }

            });
        }
    }

</script>
