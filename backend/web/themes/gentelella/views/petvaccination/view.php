<?php
use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = $model->pet_name;
$this->params['breadcrumbs'][] = ['label' => 'Pet Vaccination', 'url' => ['index','id' => $model->user_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="right_col" role="main">
	<div class="">
		<div class="page-title">
			<div class="title_left">
				<h3>Pet Vaccination: <?php echo $this->title; ?></h3>
			</div>
		</div>
		<div class="clearfix"></div>
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="x_panel" style="height:600px;">
					<div class="x_title">
						<p>
							<?= Html::a('Delete', ['delete', 'id' => $model->id], [
								'class' => 'btn btn-danger',
								'data' => [
									'confirm' => 'Are you sure you want to delete this item?',
									'method' => 'post',
								],
							]) ?>
						</p>
						<?= DetailView::widget([
							'model' => $model,
							'attributes' => [
									[
										'attribute' => 'pet_name',
										'label' => 'Pet Name',
									],															
									[
										'attribute' => 'user_id',
										'label' => 'User',
										'value' => $model->user->firstname.' '.$model->user->lastname,
									],
									[
										'attribute' => 'emergency_contact',
										'label' => 'Phone',
										'encodeLabel' => false,
									],	
									'start_date',
									'end_date',						
									[
										'attribute' => 'date_created',
										'label' => 'Date Created',
										'format' => 'date',
									],
									[
										'attribute' => 'status',
										'label' => 'Status',
										'value' => Yii::$app->commonmethod->getStatus($model->status,1),
									],									
							],
						]) ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php echo $this->render('../includes/footer'); ?>
</div>
