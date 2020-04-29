<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\CategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>

<?php 
$this->title = 'Categories';
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
							<?php if(Yii::$app->session->getFlash('item')):?>
								<div class="alert alert-success alert-dismissible fade in" role="alert">
								<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
								</button>    
									<?php echo Yii::$app->session->getFlash('item'); ?>
								</div>
							<?php endif; ?>
							<p>
								<?= Html::a('<i class="fa fa-plus"></i> Add Category', ['create'], ['class' => 'btn btn-success']) ?>
								<?= Html::button('Apply Filter', ['class' => 'btn btn-primary','id'=>'btnfilterApply','style'=>'float:right']) ?>  
							</p>
	<?php Pjax::begin(['id' => 'Pjax_Menufilter', 'timeout' => false, 'enablePushState' => false, 'clientOptions' => ['method' => 'GET']]); ?> 							
							<?= GridView::widget([
								'dataProvider' => $dataProvider,
								'filterModel' => $searchModel,
								'columns' => [
									['class' => 'yii\grid\SerialColumn'],
								   'category_name',
								   'parent.category_name',
									[
										'attribute'=>'category_date_created',
										'value'=>'category_date_created',
										'filterOptions' => ['id' => 'tdDateCreated'],
									],
									'category_status',
									['class' => 'yii\grid\ActionColumn',
										'template'=>'{view} {update} {delete} {status}',
											'buttons'=>[
														'status' => 
															function ($url,$dataProvider) {
															return Html::dropDownList('action',$dataProvider->category_status,[''=>'Status','Active'=>'Active','Inactive'=>'Inactive'],['class'=>'dropdown','title' => 'Change Status','onchange' =>'updateStatus(this,'.$dataProvider->category_id.')']);
														},                           
												],
										   'header' => 'Actions',
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
	function updateStatus(dis,catId) {
		var post = {'UpdateCategory':{'status':dis.value}};	
		if(dis.value && dis.value != '') {
			$.ajax({
				url:'<?php echo Url::home(); ?>category/status/'+catId,
				type:'post',
				data:post,
				success:function(response) {
					window.location.reload();
				}
			});
		}
	}	
</script>
