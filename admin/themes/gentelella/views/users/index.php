<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;  

use yii\helpers\ArrayHelper;

$this->title = 'Users';
//	$this->params['breadcrumbs'][] = $this->title;

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
						<?= Html::a('<i class="fa fa-plus"></i> Add User', ['create'], ['class' => 'btn btn-success']) ?>
						
						<?= Html::button('Apply Filter', ['class' => 'btn btn-primary','id'=>'btnfilterApply','style'=>'float:right']) ?>
					</p>
					<?php Pjax::begin(['id' => 'Pjax_usersfilter', 'timeout' => false, 'enablePushState' => false, 'clientOptions' => ['method' => 'GET']]); ?>
					<?= GridView::widget([
						'id'=>'grid_container',
						'dataProvider' => $dataProvider,
						'filterModel' => $searchModel,
						
						//'pager' => [],
						'columns' => [
							['class' => 'yii\grid\SerialColumn'],
							
							'fullname',
							'username',
							'email',
							'gender',
							[
								'attribute'=>'created_at',
								'value'=>'created_at',
								'format'=>'date',
								'filterOptions' => ['id' => 'tdDateCreated'],
								
								//'contentOptions' => ['id' => 'text-center'],
								//'headerOptions' => ['class' => 'text-center']
								//'filter'=>[],
							],
							'status',
							
							//'created_at:date',
							[
							'class' => 'yii\grid\ActionColumn',
							'header' => 'Actions',	
							'template' => '{view} {update} {delete} {status}',
								'buttons' => [
									'status' => function ($url,$dataProvider) {
										return Html::dropDownList('action',$dataProvider->status,[''=>'Status','Active'=>'Active','Inactive'=>'Inactive'],['class'=>'dropdown','title' => 'Change Status','onchange' =>'updateStatus(this,'.$dataProvider->id.')']);
										
									},
								],
								
							],
						],
					]); 
					
					
					?>
					
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
			
				
				//$('#grid_container-filters td:last').html('<button type="button" id="btnfilterApply" class="btn btn-primary" style="float:right">Apply Filter</button>');
				
				//$.pjax.defaults.timeout = false;//IMPORTANT
				//$.pjax.reload({container:'#Pjax_usersfilter'});
				
				//~ var submit_form = false;
//~ 
				//~ $('body').on('click', '#btnfilterApply',  function(){
					//~ //enable submit for applyFilter event
					//~ if(submit_form === false) {
						//~ submit_form = true;
						//~ $("#grid_container").yiiGridView("applyFilter");
					//~ }
				//~ });

				//disable default submit

				//~ $("body").on('beforeFilter', "#grid_container" , function(event) {
					//~ return submit_form;
				//~ });
//~ 
				//~ $("body").on('afterFilter', "#grid_container" , function(event) {
					//~ submit_form = false;
				//~ });
				
				//$('.form-control').keypress(function(ev)
					// if (ev.which === 13) { }
				//});					

		});	
		
		$(document).on('pjax:complete', function() {
				$('#tdDateCreated input').daterangepicker({
					singleDatePicker: true,
					calender_style: "picker_4"
				});
		});
	
	function updateStatus(dis,userid)
	{
		var post = {'UpdateUser':{'status':dis.value}};	
		if(dis.value && dis.value != '') {
			$.ajax({
					url:'<?php echo Url::home(); ?>users/update/'+userid,
					type:'post',
					data:post,
					success:function(response){
							window.location.reload();
						}
					
				});	
		}
	}
		
	</script>
	
<!-- 
https://www.youtube.com/watch?v=NKG24GJpZRA&ebc=ANyPxKqCUTyN8weSdpN7jflaiD_La3RWILEVYA-hOEoczIKrRV7HR1TQdBaVpKDmec3d3sLRCq9yfWH5tX91RRzuktaZrM99qg
-->
