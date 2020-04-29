<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('yii','Reviews');
$siteimage  = Yii::getAlias('@siteimage');

?>

			<div class="col-xs-12">
				<h1><?= $this->title ?></h1>
			</div>
	</div>
</header>
<section class="contentArea ratingspage">
  <div class="container">
    <div class="row">
	 <?php echo $this->render('//common/sidebar'); ?>
      <div class="col-md-10 col-sm-9 col-xs-12 scrolldiv01">
				<?php
				Pjax::begin(['id' => 'Pjax_SearchResults', 'timeout' => false, 'enablePushState' => false, 'clientOptions' => ['method' => 'GET']]);

				if(isset($reviews) && !empty($reviews)) {
				
				if(count($reviews)>1){
					$lastKey = count($reviews)-1;
					}else{
					$lastkey = 0;
					}
					
					foreach($reviews as $r_key => $feedback_row) {
					
					$profileimage = isset($feedback_row['profile_image'])?$feedback_row['profile_image']:NO_DISPLAY_IMAGE;
					
				?> 
				<div class="greyBox <?php if($lastKey == $r_key) { echo 'noMargin'; } ?>">
					<div class="greyThumb"> <img class="img-responsive" src="<?php echo PROFILE_IMAGE_PATH.'/'.$profileimage; ?>" alt="" height="107px;" width="114px;"> </div>
					<div class="greyText">
						<div class="ratingBar">
							<div class="ratingBar-left">
								<div class="rating">
									<?php
										for($i=0;$i<$feedback_row['starrating'];$i++) {
											echo '<i class="fa fa-star" aria-hidden="true"></i>';
										}
									?>
								</div>
							</div>
							<div class="ratingBar-right">
								<div class="date">
									<i class="fa fa-clock-o" aria-hidden="true"></i> <?= date('m-d-Y', strtotime($feedback_row['date_time'])) ?>
								</div>
								<div class="orangeBtn">
									<a href="<?=  Url::home().'bookings/booking-details/'.$feedback_row['booking_id'] ?>">Service details 
										<i class="fa fa-angle-right" aria-hidden="true"></i>
									</a>
								</div>
							</div>
						</div>
						<h4><?= $feedback_row['fname'].' '.$feedback_row['lname'] ?></h4>
						<p><?= $feedback_row['comment'] ?></p>
					</div>
				</div>
			<?php 	} 
				} else {
					echo '<p align="center">No reviews yet.</p>';
				}
			?>
			<div class="customPagination">
					<?php
						echo yii\widgets\LinkPager::widget([
							'pagination' => $pages,
							'prevPageLabel' => '<i class="fa fa-caret-left" aria-hidden="true"></i>',
							'nextPageLabel' => '<i class="fa fa-caret-right" aria-hidden="true"></i>',
							'activePageCssClass' => 'active',
							'disabledPageCssClass' => 'disabled',
							'prevPageCssClass' => 'enable prev',
							'nextPageCssClass' => 'enable next',
							'hideOnSinglePage' => true
						]);
					?>
			</div>
		<?php Pjax::end(); ?>
      </div>
    </div>
  </div>
</section>
