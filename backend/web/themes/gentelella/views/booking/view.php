<?php
use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Booking', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="right_col" role="main">
	<div class="">
		<div class="page-title">
			<div class="title_left">
				<h3>Booking: <?php echo $this->title; ?></h3>
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
										'attribute' => 'pet_owner_id',
										'label' => 'Pet Sitter',
										'value' => $model->sitter->firstname. ' '.$model->sitter->lastname,
									],
									[
										'attribute' => 'pet_owner_id',
										'label' => 'Pet Owner',
										'value' => $model->owner->firstname. ' '.$model->owner->lastname,
									],
									[
										'attribute' => 'booking_from_date',
										'label' => 'Booking From',
									],
									[
										'attribute' => 'booking_to_date',
										'label' => 'Booking To',
									],																		
									[
										'attribute' => 'amount',
										'label' => 'Amount',
									],
									[
										'attribute' => 'admin_fee',
										'label' 	=> 'Admin Fee',
									],										
									[
										'attribute' => 'status',
										'label' => 'Status',
										'value' => Yii::$app->commonmethod->getUserStatus($model->status,1),
									],									
									[
										'attribute' => 'date_created',
										'label' => 'Date Created',
										'format' => 'date',
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
