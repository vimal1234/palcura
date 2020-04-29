<?php
use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = "Payment Disbursement";
$this->params['breadcrumbs'][] = ['label' => 'Payment Disbursement', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="right_col" role="main">
	<div class="">
		<div class="page-title">
			<div class="title_left">
				<h3>Payment Disbursement Request: <?php echo $this->title; ?></h3>
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
										'attribute' => 'booking_id',
										'label' => 'Booking Name',
										'value' => $model->booking->name,
									],															
									[
										'attribute' => 'user_id',
										'label' => 'User',
										'value' => $model->user->firstname.' '.$model->user->lastname,
									],
									[
										'attribute' => 'paypal_email',
										'label' => 'Paypal email',
										'value' => $model->user->paypal_email,
									],
									[
										'attribute' => 'booking_credits',
										'label' => 'Amount',
										'value' => $model->booking->booking_credits,
									],
									[
										'attribute' => 'status',
										'label' => 'Status',
										'value' => Yii::$app->commonmethod->getDisbursementStatus($model->status,1),
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
