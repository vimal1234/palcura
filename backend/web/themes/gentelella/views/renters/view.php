<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
$this->title = $model->firstname . ' ' . $model->lastname;
$this->params['breadcrumbs'][] = ['label' => 'Renters', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>Renter: <?php echo $this->title; ?></h3>
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
                                    'attribute' => 'profile_image',
                                    'label' => 'Image',
                                    'format' => 'html'
                                ],
                                [
                                    'attribute' => 'firstname',
                                    'label' => 'First Name',
                                ],
                                [
                                    'attribute' => 'lastname',
                                    'label' => 'Last Name',
                                ],
                                [
                                    'attribute' => 'usertype.name',
                                    'label' => 'User Type',
                                ],                                
                                [
                                    'attribute' => 'email',
                                    'label' => 'User Email',
                                ],                                
                               /* [
                                    'attribute' => 'phone',
                                    'label' => 'Phone',
                                ],                                
                                [
									'attribute' => 'dob',
                                    'label' => 'Date Of Birth',
                                ],
                                [
                                    'attribute' => 'gender',
                                    'label' => 'Gender',
                                    'encodeLabel' => false,
                                ],*/
                                [
                                    'attribute' => 'countryname.name',
                                    'label' => 'Country',
                                ],
                                [
                                    'attribute' => 'regionname.name',
                                    'label' => 'Region',
                                ],
                                [
                                    'attribute' => 'cityname.name',
                                    'label' => 'City',
                                ], 
                               /* [
                                    'attribute' => 'residential_status',
                                    'label' => 'Residential Status',
                                    'value' => Yii::$app->commonmethod->residentialStatus($model->residential_status),
                                ],*/
                                [
                                    'attribute' => 'verified_by_admin',
                                    'label' => 'Verification',
                                    'format' => 'html',
                                    'value' => Yii::$app->commonmethod->verifyStatus($model->verified_by_admin,1),
                                ],                                
                               /* [
                                    'attribute' => 'house_size',
                                    'label' => 'Household size',
                                ],
                                [
                                    'attribute' => 'children',
                                    'label' => 'Children',
                                ],
                                [
                                    'attribute' => 'income',
                                    'label' => 'Income',
                                ],
                                [
                                    'attribute' => 'number_of_pets',
                                    'label' => 'No. of pets',
                                ],*/
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
<?php echo $this->render('../includes/footer'); ?>
</div>
