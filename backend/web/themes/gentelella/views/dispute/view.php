<?php
use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Dispute Resolutions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="right_col" role="main">
	<div class="">
		<div class="page-title">
			<div class="title_left">
				<h3>Dispute Resolutions: <?php echo $this->title; ?></h3>
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
										'attribute' => 'title',
										'label' => 'Title',
									],
									[
										'attribute' => 'description',
										'label' => 'Descriptions',
									],
									[
										'attribute' => 'admin_comment',
										'label' => 'Admin Comment',
									],
									[
										'attribute' => 'paid_charges',
										'label' => 'Plenty',
										'value' => (isset($model->paid_charges) && $model->paid_charges == 1 ? 'Yes' : 'No'),
									],									
									[
										'attribute' => 'verified_by_admin',
										'label' => 'Verified',
										'value' => Yii::$app->commonmethod->verifyStatus($model->verified_by_admin,1),
										'format'=> 'html',
									],
									[
										'attribute' => 'form_type',
										'label' => 'Form',
										'value' => Yii::$app->commonmethod->getFormType($model->form_type,1),
									],																												
									[
										'attribute' => 'status',
										'label' => 'Status',
										'value' => Yii::$app->commonmethod->getStatus($model->status,1),
									],									
									[
										'attribute' => 'date_created',
										'label' => 'Date Created',
										//'format' => 'date',
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
