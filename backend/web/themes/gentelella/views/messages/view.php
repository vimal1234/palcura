<?php
use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Messages', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="right_col" role="main">
	<div class="">
		<div class="page-title">
			<div class="title_left">
				<h3>Message: <?php echo $this->title; ?></h3>
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
										'attribute' => 'user_from',
										'label' => 'User From',
										'value' => $model->userfrom->firstname. ' '.$model->userfrom->lastname,
									],
									[
										'attribute' => 'user_to',
										'label' => 'User To',
										'value' => $model->userto->firstname. ' '.$model->userto->lastname,
									],
									[
										'attribute' => 'title',
										'label' => 'Title',
									],
									[
										'attribute' => 'message',
										'label' => 'Message',
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
