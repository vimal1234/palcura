<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\widgets\LinkPager;
use common\models\User;

$this->title 	= Yii::t('yii','Payments/Earnings');
$siteimage  	= Yii::getAlias('@siteimage');

?>
    <div class="row">
      <div class="col-xs-12">
        <h1><?= $this->title ?></h1>
      </div>
    </div>
  </div>
</header>
<section class="contentArea contentAreaPayments">
  <div class="container">
    <div class="row">
    <?php if (Yii::$app->session->getFlash('error')): ?>
		
						<script>
						$(document).ready(function () {
						// Handler for .ready() called.
						$('html, body').animate({
							scrollTop: $('#scrrollhere').offset().top
						}, 'slow');
						});
												
						</script>
							<div class="col-xs-12" id="scrrollhere">
								<div class="alert alert-grey alert-dismissible">
									<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span>
									</button>									
									<i class="glyphicon glyphicon-remove"></i><?php echo Yii::$app->session->getFlash('error'); ?>
								</div>
							</div>
						<?php endif; ?>
    <div class="alert alert-grey alert-dismissible" style="display:none;" id="paymentrequest">
		<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span>
		</button>
		<i class="fa fa-check"></i>Payment request was made successfully.
	</div>
	 <?php echo $this->render('//common/sidebar'); ?>
     <?php echo $this->render('listing',[
	'pages' => $pages,
	'listing' => $listing	
	]);
	?>
    </div>
  </div>
</section>
<script>
function toggleIcon(e) {
    $(e.target)
        .prev('.panel-heading')
        .find(".more-less")
        .toggleClass('glyphicon-plus glyphicon-minus');
}
$('.panel-group').on('hidden.bs.collapse', toggleIcon);
$('.panel-group').on('shown.bs.collapse', toggleIcon);
</script>
