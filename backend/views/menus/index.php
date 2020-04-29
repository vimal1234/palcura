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
						<p>
							<?= Html::a('<i class="fa fa-plus"></i> Add Menu', ['create'], ['class' => 'btn btn-success']) ?>
						  <?= Html::button('Apply Filter', ['class' => 'btn btn-primary','id'=>'btnfilterApply','style'=>'float:right']) ?>
						</p>
	<?php Pjax::begin(['id' => 'Pjax_Menufilter', 'timeout' => false, 'enablePushState' => false, 'clientOptions' => ['method' => 'GET']]); ?> 
	
    <?= GridView::widget([
		'id'=>'grid_menu_container',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'mnuName',
            'menuSlug',
            'mnuStatus',
				[
					'attribute'=>'mnuDateCreated',
					'value'=>'mnuDateCreated',
					'format'=>'date',
					'filterOptions' => ['id' => 'tdDateCreated'],
				],            
			['class' => 'yii\grid\ActionColumn',
                     'template'=>'{view} {update} {delete} {menulinks} {status}',
                       'buttons'=>[
						'menulinks' => function ($url, $model) { 
								return Html::a('<span class="glyphicon glyphicon-cloud w3-large"></span>', Yii::$app->homeUrl.'menulinks/links/' . $model->mnuId , ['title' => Yii::t('yii', 'Menu Links'),]); 
                            },
							'status' => 
								function ($url,$dataProvider) {
									return Html::dropDownList('action',$dataProvider->mnuStatus,[''=>'Status','Active'=>'Active','Inactive'=>'Inactive'],['class'=>'dropdown','title' => 'Change Status','onchange' =>'updateStatus(this,'.$dataProvider->mnuId.')']);
								},                                  
                           ],
                           'header' => '&nbsp;&nbsp;&nbsp;Actions&nbsp;&nbsp;&nbsp;&nbsp;',	
                 ],
        ],
    ]); ?>
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
		});
		
		$(document).on('pjax:complete', function() {
				$('#tdDateCreated input').daterangepicker({
					singleDatePicker: true,
					calender_style: "picker_4"
				});
		});
		
		function updateStatus(dis,pageid) {
			var post = {'UpdateMenu':{'status':dis.value}};	
			if(dis.value && dis.value != '') {
				$.ajax({
					url:'<?php echo Url::home(); ?>menus/status/'+pageid,
					type:'post',
					data:post,
					success:function(response) {
						window.location.reload();
					}
				});
			}
		}
		
	</script>
	
