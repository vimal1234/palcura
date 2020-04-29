<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\CrudTest */

$this->title = 'Payment ID ' . $model->payment_transaction_id;
$this->params['breadcrumbs'][] = ['label' => 'Payments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<!-- page content -->
<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>User: <?php echo $this->title; ?></h3>
            </div>

        </div>
        <div class="clearfix"></div>

        <div class="row">

            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel" style="height:600px;">
                    <div class="x_title">
                        <?=
                        DetailView::widget([
                            'model' => $model,
                            'attributes' => [
								[
								'attribute' => 'user_id',
								'label'		=> 'Username',
								'value'		=> (isset($model->user->firstname) ? $model->user->firstname : '').' '.(isset($model->user->lastname) ? $model->user->lastname : ''),
								],
                                'user.firstname',
                                'trans_id',
                                'amount',#:currency
								[
								'attribute' => 'booking.admin_fee',
								'label'		=> 'Admin Fee',
								],                             
                                'trans_date:datetime',
                                 [
                                    'attribute' => 'payment_type',
                                    'label' => 'Payment type',
                                    'value' => (isset($model->payment_type) && $model->payment_type == 'instant' ? 'PayPal' : $model->payment_type),
                                 ],
                                'payment_status',
                            ],
                        ])
                        ?>
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

