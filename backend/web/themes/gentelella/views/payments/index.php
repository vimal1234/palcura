<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;

$this->title = 'Payments';
$this->params['breadcrumbs'][] = $this->title;
?>
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
                            <?= Html::button('Apply Filter', ['class' => 'btn btn-primary','id'=>'btnfilterApply','style'=>'float:right']) ?>
                            <?= Html::button('Reset Filter', ['class' => 'btn btn-primary','id'=>'resetFilter','style'=>'float:right']) ?>
                        </p>
                        <br/>
                        <br/>
                        <?php Pjax::begin(['id' => 'Pjax_usersfilter', 'timeout' => false, 'enablePushState' => false, 'clientOptions' => ['method' => 'GET']]); ?>
                                                
                        <?=
                        GridView::widget([
                            'id' => 'grid-container',
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],
                                [
                                    'attribute' => 'user',
                                    'label' => 'Username',
                                    'value' =>  function ($model) {
										return (isset($model->user->firstname) ? $model->user->firstname : '').' '.(isset($model->user->lastname) ? $model->user->lastname : ''); 
									},
                                ], 
                                [
                                'attribute' => 'amount',
                                'label'		=>'Amount ($)',
                                ],
                                [
                                'attribute' => 'booking_id',
                                'label'		=> 'Admin Fee',
                                'value'		=> function ($model) {
									return $model->booking->admin_fee;
								},
                                ],                             
                                [
								'attribute' => 'trans_id',
                                ],
                                [
                                'attribute' => 'payment_type',
                                'label'=>'Type',
                                'value' => function ($model, $key, $index, $grid) {
								if ($model->payment_type == 'instant') 
									{  return 'Paypal';} 
									else { return $model->payment_type;}
								},
								'filter' => Html::activeDropDownList($searchModel, 'payment_type', array('instant' => 'Paypal', 'Card' => 'Credit Card', 'Bank'=>'Bank Transfer','Visa'=>'Visa'), ['class' => 'form-control', 'prompt' => 'Payment Type']
								),
                                ],
                                [
                                    'attribute' => 'trans_date',
                                    'label'=>'Transaction Date',
                                    'filterOptions' => ['id' => 'tdDateCreated'],
                                    'format' => [ADMIN_DATE_L, ADMIN_DATE_FORMAT_L]
                                ],
								[
								'attribute' => 'payment_status',
									'filter' => Html::activeDropDownList($searchModel, 'payment_status', array('Paid'=>'Paid','Pending'=>'Pending','Failed'=>'Failed','Completed' => 'Completed'),['class'=>'form-control','prompt' => 'Status']),
								],
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'header' => 'Actions',
                                    'template' => '{view} {status}',
                                    'buttons' => [
                                        'status' => function ($url, $dataProvider) {
if($dataProvider->payment_status == 'Completed') {
                                            return Html::dropDownList('action', $dataProvider->payment_status, ['Completed' => 'Completed'], ['class' => 'dropdown', 'title' => 'Completed payment']);
} else {
                                            return Html::dropDownList('action', $dataProvider->payment_status, ['' => 'Status', 'Pending' => 'Pending', 'Paid' => 'Paid', 'Failed' => 'Failed'], ['class' => 'dropdown', 'title' => 'Change Status', 'onchange' => 'updatePaymentStatus(this,' . "'$dataProvider->payment_transaction_id'" . ')']);
} 
                                        },
                                    ],
                                ],
                            ],
                        ]);
                        ?>
						<div class="col-md-3 col-sm-3 col-xs-6 page_limit_layout">
							<select id="propertiessearch-pagesize" name="PropertiesSearch[pagesize]" class="form-control">
								<option value="5">5</option>
								<option value="30" <?php echo (isset($_GET['p']) && $_GET['p'] == 30) ? 'selected' : ''; ?>>30</option>
								<option value="50" <?php echo (isset($_GET['p']) && $_GET['p'] == 50) ? 'selected' : ''; ?>>50</option>
								<option value="100" <?php echo (isset($_GET['p']) && $_GET['p'] == 100) ? 'selected' : ''; ?>>100</option>
							</select>
						</div>	                        
                        <script>
                        $(document).ready(function () {
                            $('#tdDateCreated input').prop('readonly', true);
                            
                            $('#tdDateCreated input').daterangepicker({
                                singleDatePicker: true,
                                calender_style: "picker_4",                                
                                format: 'YYYY-MM-DD'
                            })
                            $('#tdDateCreated input').on('apply.daterangepicker', function(ev, picker) {    
                                $("#grid-container").yiiGridView("applyFilter");                            
                            });
							/*#### pagination limit ####*/
							$( "#propertiessearch-pagesize" ).change(function() {
								changePageLimit();
							});	                            
                        });                      
                        </script>
                        
                        <?php Pjax::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php echo $this->render('../includes/footer'); ?>
</div>
