<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\User;

/* @var $this yii\web\View */
/* @var $model backend\models\CrudTest */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Coupon', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$usermodel = new User();
$ownerlist = $model->owner_list;
$ownername = array();
	if(!empty($ownerlist)){
	$explowners = explode(',',$ownerlist);
	
		foreach($explowners as $key=>$val){
		$couponuserid = $val;
		 $owneruserinfo = $usermodel->findIdentity($couponuserid);
		 $ownername[] = $owneruserinfo['firstname'].' '.$owneruserinfo['lastname']; 
		}
		$ownername = implode(',',$ownername);
	}
$renterlist = $model->renter_list;
$rentername = array();
	if(!empty($renterlist) && count($renterlist)>0){
	$explorenters = explode(',',$renterlist);
	
		foreach($explorenters as $key=>$val){
		$couponuserid = $val;
		 $renteruserinfo = $usermodel->findIdentity($couponuserid);
		 $rentername[] = $renteruserinfo['firstname'].' '.$renteruserinfo['lastname']; 
		}
		$rentername = implode(',',$rentername);
	}

?>
	
		<!-- page content -->
	<div class="right_col" role="main">
		<div class="">
			<div class="page-title">
				<div class="title_left">
					<h3>Coupon: <?php echo $this->title; ?></h3>
				</div>

			</div>
			<div class="clearfix"></div>

			<div class="row">

				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="x_panel" style="height:600px;">
						<div class="x_title">
							
					<p>
						<!--?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?-->
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
									'attribute' => 'coupon_name',
									'label' => 'Name',
									'encodeLabel' => false,
								],
								[
									'attribute' => 'coupon_code',
									'label' => 'Coupon code',
									'encodeLabel' => false,
								],								
								[
									'attribute' => 'coupon_valid_date',
									'label' => 'Valid Up To',
									'encodeLabel' => false,
								],
								
								[
									'attribute' => 'coupon_description',
									'label' => 'Description',
									'encodeLabel' => false,
								],
								[
									'attribute' => 'owner_list',
									'label' => 'Owners',
									'encodeLabel' => false,
									'value' => $ownername
									
								],
								[
									'attribute' => 'renter_list',
									'label' => 'Renters',
									'encodeLabel' => false,
									'value' 	=> !empty($rentername)?$rentername:'',
								],	
						],
					]) ?>
							
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
   
