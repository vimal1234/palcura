<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;  

/* @var $this yii\web\View */
/* @var $searchModel app\models\MenusSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Menus';
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
				<?php if(Yii::$app->session->getFlash('item')):?>
					<div class="alert alert-success alert-dismissible fade in" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
					</button>    
						<?php echo Yii::$app->session->getFlash('item'); ?>
					</div>
				<?php endif; ?>							
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('<i class="fa fa-plus"></i> Add menu item', ['menulinks/create', 'id' =>$menuID] , ['class' => 'btn btn-success']) ?>
        <?php //echo Html::button('Apply Filter', ['class' => 'btn btn-primary','id'=>'btnfilterApply','style'=>'float:right']); ?>
    </p>
    
	<?php //Pjax::begin(['id' => 'Pjax_menusfilter', 'timeout' => false, 'enablePushState' => false, 'clientOptions' => ['method' => 'POST']]); ?> 
	   
    <?= GridView::widget([
		'id'=>'grid_container',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'title',
            'menuType',
            'menuUrl',
				[
					'attribute'=>'dateCreated',
					'value'=>'dateCreated',
					'format'=>'date',
					'filterOptions' => ['id' => 'tdDateCreated'],
				],            
			['class' => 'yii\grid\ActionColumn',
                           'header' => '&nbsp;&nbsp;&nbsp;Actions&nbsp;&nbsp;&nbsp;&nbsp;',	
                 ],
        ],
    ]); ?>
    <?php //Pjax::end(); ?>

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
<!-- daterangepicker -->
	<script type="text/javascript" src="<?php echo Url::home(); ?>/themes/gentelella/js/moment.min2.js"></script>
	<script type="text/javascript" src="<?php echo Url::Home(); ?>/themes/gentelella/js/datepicker/daterangepicker.js"></script>
    <script type="text/javascript">
		
		 $(document).ready(function () {
			 
				$('#tdDateCreated input').daterangepicker({
					singleDatePicker: true,
					calender_style: "picker_4"
				});
				
				var submit_form = false;

				$('body').on('click', '#btnfilterApply',  function(){
					
					//enable submit for applyFilter event
					if(submit_form === false) {
						submit_form = true;
						$("#grid_container").yiiGridView("applyFilter");
					}
				});

				//disable default submit

				//~ $("body").on('beforeFilter', "#grid_container" , function(event) {
					//~ return submit_form;
				//~ });
//~ 
				//~ $("body").on('afterFilter', "#grid_container" , function(event) {
					//~ submit_form = false;
				//~ });

		});	
		
		$(document).on('pjax:complete', function() {
				$('#tdDateCreated input').daterangepicker({
					singleDatePicker: true,
					calender_style: "picker_4"
				});
		});
	
	</script>
	
