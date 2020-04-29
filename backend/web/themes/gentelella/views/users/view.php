<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
/* @var $this yii\web\View */
/* @var $model backend\models\CrudTest */
$this->title = $model->firstname . ' ' . $model->lastname;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
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
                <div class="x_panel" >
                    <div class="x_title">
                        <p>
                            <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                            <?=
								Html::a('Delete', ['delete', 'id' => $model->id], [
									'class' => 'btn btn-danger',
									'data' => [
										'confirm' => 'Are you sure you want to delete this item?',
										'method' => 'post',
									],
								])
                            ?>
                        </p>
                        <?=
                        DetailView::widget([
                            'model' => $model,
                            'formatter' => [
                                'class' => 'yii\i18n\Formatter',
                                'nullDisplay' => '',
                            ],
                            'attributes' => [
                                [
                                    'attribute' => 'firstname',
                                    'label' => 'First Name',
                                    'encodeLabel' => false,
                                ],
                                [
                                    'attribute' => 'lastname',
                                    'label' => 'Last Name',
                                    'encodeLabel' => false,
                                ],
                                [
                                    'attribute' => 'usertype.name',
                                    'label' => 'User Type',
                                    'encodeLabel' => false,
                                ],                                
                                [
                                    'attribute' => 'email',
                                    'label' => 'User Email',
                                    'encodeLabel' => false,
                                ],                                
                                [
                                    'attribute' => 'phone',
                                    'label' => 'Phone',
                                    'encodeLabel' => false,
                                ],                                
                                [
									'attribute' => 'dob',
                                    'label' => 'Date Of Birth',
									'encodeLabel' => false,
                                ],
                                [
                                    'attribute' => 'gender',
                                    'label' => 'Gender',
                                    'encodeLabel' => false,
                                ],
                                [
                                    'attribute' => 'countryname.name',
                                    'label' => 'Country',
                                    'encodeLabel' => false,
                                ],
                                [
                                    'attribute' => 'regionname.name',
                                    'label' => 'Region',
                                ],
                                [
                                    'attribute' => 'cityname.name',
                                    'label' => 'City',
                                ],
                                [
                                    'attribute' => 'currentnation.name',
                                    'label' => 'Nationality',
                                ], 
                                [
                                    'attribute' => 'residency_status',
                                    'label' => 'Residency Status',
                                ],
                                [
                                    'attribute' => 'date_created',
                                    'label' => 'Date Created',
									'value' => date('d.m.Y', strtotime($model->date_created) ),
									'format'=>'raw',
								],
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
