<?php 
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;
$data = Url::to(['coupon/randomcode']); 
?>
<script>
  var randomcodeurl  = '<?php echo $data ; ?>';
</script> 
 <!-- top navigation -->
            <div class="top_nav">

                <div class="nav_menu">
                    <nav class="" role="navigation">
                        <div class="nav toggle">
                            <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                        </div>
				
		<?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
                        <ul class="nav navbar-nav navbar-right">
							
                            <li class="">
                                <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                    <?php echo Yii::$app->user->identity->username;?>
                                    <span class=" fa fa-angle-down"></span>
                                </a>
                                <ul class="dropdown-menu dropdown-usermenu animated fadeInDown pull-right">
                                   
                                    <li>
										<?php echo Html::a('Settings',['admin/settings']);?>
                                    </li>
                                  
                                  <li><?php echo Html::a('Log Out <i class="fa fa-sign-out pull-right"></i>',['admin/logout']);?>
                                    </li>
                                </ul>
                            </li>


                        </ul>
                    </nav>
                </div>

            </div>
            <!-- /top navigation -->
