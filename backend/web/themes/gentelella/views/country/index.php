<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

$this->title = 'Countries';
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
                        <?php if (Yii::$app->session->getFlash('item')): ?>
                        <div class="alert alert-success alert-dismissible fade in" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                            </button>    
                        <?php echo Yii::$app->session->getFlash('item'); ?>
                        </div>
                        <?php endif; ?>

                        <!--<h1><?= Html::encode($this->title) ?></h1>-->
                        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
                        <p>
                            <?= Html::a('Add Country', ['create'], ['class' => 'btn btn-success']) ?>
                        </p>
                        <?php Pjax::begin(['id' => 'PjaxState', 'timeout' => false, 'enablePushState' => false, 'clientOptions' => ['method' => 'GET']]); ?>
                        <?= GridView::widget([
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],

                                'id',
                                'sortname',
                                'name',

                                ['class' => 'yii\grid\ActionColumn'],
                            ],
						]); ?>
							<div class="col-md-3 col-sm-3 col-xs-6 page_limit_layout">
								<select id="propertiessearch-pagesize" name="PropertiesSearch[pagesize]" class="form-control">
									<option value="5">5</option>
									<option value="30" <?php echo (isset($_GET['p']) && $_GET['p'] == 30) ? 'selected' : ''; ?>>30</option>
									<option value="50" <?php echo (isset($_GET['p']) && $_GET['p'] == 50) ? 'selected' : ''; ?>>50</option>
									<option value="100" <?php echo (isset($_GET['p']) && $_GET['p'] == 100) ? 'selected' : ''; ?>>100</option>
								</select>
							</div>
							<script>
								$(document).ready(function () {
									/*#### pagination limit ####*/
									$( "#propertiessearch-pagesize" ).change(function() {
										changePageLimit();
									});								
								});                      
							</script>																    
						<?php Pjax::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php echo $this->render('../includes/footer'); ?>
</div>
