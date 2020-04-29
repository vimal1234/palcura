<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;
use yii\bootstrap\ActiveForm;
use common\models\User;

$homeImages = Yii::$app->commonmethod->getUserActiveDocuments($user_information['id'], 2);

function services_completed($ui){
$model = new User();
return $model->getServices($ui);
}

$this->title = 'Sitter details';
$this->params['breadcrumbs'][] = $this->title;
if (isset($user_information['profile_image']) && !empty($user_information['profile_image'])) {
    $profile_pic = PROFILE_IMAGE_PATH . $user_information['profile_image'];
} else {
    $profile_pic = NO_DISPLAY_IMAGE;
}

$user_zipcode = (isset($user_information->zip_code) ? $user_information->zip_code : '');
$user_countryname = (isset($user_information->countryname->name) ? $user_information->countryname->name : '');
$user_cityname = (isset($user_information->cityname->name) ? $user_information->cityname->name : '');

$user_total_rating = (isset($user_ratings['total_rating']) ? $user_ratings['total_rating'] : 0);
$user_reviews_count = (isset($user_ratings['reviews_count']) && $user_ratings['reviews_count'] > 0 ? $user_ratings['reviews_count'] . ' Reviews' : '0 Review');

$session = Yii::$app->session;
$loggedingusertype = $session->get('loggedinusertype');
$loggedinusertype= $session->get('loggedinusertype');

?>

    <div class="col-xs-12">
        <h1><?= $this->title ?></h1>
    </div>

</div>
</header>
<section class="detailArea">
    <div class="container">
      <div class="col-md-12 col-sm-12 col-xs-12 backsrch">
       <?php if ($loggedingusertype == OWNER) { ?>
                      <a class="head" href="<?php echo Url::to(['search/petsitter']) ?>"> < Back to search results</a>
                        <?php } elseif ($loggedingusertype == RENTER) { ?>
                            <a class="head" href="<?php echo Url::to(['search/petrenter']) ?>"> < Back to search results</a>
                        <?php } ?>
     	
     </div>
        <div class="row">
            <?php if (Yii::$app->session->getFlash('item')): ?>
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
                        <i class="glyphicon glyphicon-ok"></i><?php echo Yii::$app->session->getFlash('item'); ?>
                    </div>
                </div>
            <?php endif; ?>			
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="detailAreaInner">
                    <div class="detailAreaLeft">
                        <div id="slider" class="flexslider">
							<ul class="slides">
								<li>
									<img src="<?php echo $profile_pic;?>" alt="">
								</li>
								<?php
								  if (isset($homeImages) && !empty($homeImages) ) { 
									foreach ($homeImages as $data){
								?> 
								
										<li>
										 <img src="<?php echo UPLOAD_IMAGE . $data['name']; ?>" alt="">
										</li>
						   <?php  
									}
									} ?>
								
							</ul>
						</div>
						<div id="carousel" class="flexslider">
							<ul class="slides">
								<li>
									<img src="<?php echo $profile_pic;?>" alt="">
								</li>
								<?php
								  if (isset($homeImages) && !empty($homeImages) ) { 
									foreach ($homeImages as $data){
								?> 
								
										<li>
										 <img src="<?php echo UPLOAD_IMAGE . $data['name']; ?>" alt="">
										</li>
						   <?php  
									}
									} ?>
															
							</ul>
						</div>
                        <!--img class="img-responsive" src="<?php //echo $profile_pic; ?>" alt=""-->
                    </div>
                    <div class="detailAreaRight">
                        <div class="detailAreaHead">
                            <div class="detailAreaHead-left">
                                <div class="detailTitle"><?= (isset($user_information['firstname']) ? $user_information['firstname'] : '') . ' ' . (isset($user_information['lastname']) ? $user_information['lastname'] : '') ?></div>
                            </div>
                            <div class="detailAreaHead-right">
                                <div class="detail-title-right">
                                    <p>
                                        <img class="img-responsive" src="<?php echo WEBSITE_IMAGES_PATH . 'lock.png'; ?>" alt=""> Services completed: <span><?= services_completed($user_information['id']) ?></span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="reviewDetails">
                            <?php if (isset($user_ratings) && !empty($user_ratings)) { ?>		
                                <div class="rating-2">
                                    <?= Yii::$app->commonmethod->generateRatings($user_total_rating); ?>
                                </div>
                                <span><a href="<?= Url::home() . 'reviews/user-ratings/' . $user_information['id'] ?>" style="color:#a3a3a3;"><?= $user_reviews_count ?></a></span>
                            <?php } ?>
                        </div>
                        <div class="location">
                            <p><i class="fa fa-map-marker" aria-hidden="true"></i><?= $user_cityname . ', ' . $user_countryname . ', ' . $user_zipcode ?></p>
                        </div>
                        <div class="contentAreaDetails">
                            <div>"<?= (isset($user_information->serviceprovider->pitch) ? $user_information->serviceprovider->pitch : '--') ?>"</div>
                            <p><?= (isset($user_information->description) ? $user_information->description : '--') ?></p>
                        </div>
                        <div class="bookNow">
                            <?php
                            $form = ActiveForm::begin(
                                            ['id' => 'search-form', 'method' => 'post', 'action' => Url::to(['bookings/book-now']),
                                                'fieldConfig' => [],
                            ]);
                            ?>
                            <input type="hidden" name="book_user_id" id="bookuserid" value="<?= $user_information['id'] ?>" />
                            <?php if ($loggedingusertype == RENTER && $user_information['unsubscribe_owner'] == 1) { ?>
                                <button type="submit" class="orangeBtn" id="bookNow">BOOK NOW</button>
                            <?php } ?>
                            <?php if ($loggedingusertype == OWNER && $user_information['unsubscribe_sitter'] == 1) { ?>
                                <input type="hidden" name="no_of_pals" id="_no_of_pals" value="1" />
                                <button type="submit" class="orangeBtn" id="bookNow">BOOK NOW</button>
                            <?php } ?>
                            <?php ActiveForm::end(); ?>
                            <ul>
                                <li><a href="<?= Url::home() . 'messages/booking-messaging/' . $user_information['id'] ?>"><i class="fa fa-envelope-o" aria-hidden="true"></i>Send a message</a></li>
                                <?php if($loggedingusertype != SITTER){?>
                                <li><a href="<?= Url::home() . 'bookings/schedule-video-call/' . $user_information['id'] ?>"><i class="fa fa-video-camera" aria-hidden="true"></i>Schedule a video call</a></li>
                                <?php } ?>
                            </ul>
                        </div>
                        <div class="detailsBottom">
                            <ul>
                                <?php
if ($loggedinusertype== OWNER) {
                                if (isset($user_services) && !empty($user_services)) {
                                    foreach ($user_services as $s_name) {
                                        echo '<li><p>' . (isset($s_name['service_name']) ? $s_name['service_name'] : '') . '</p><span>' . (isset($s_name['service_price']) ? CURRENCY_SIGN . $s_name['service_price'] : '') . '</span></li>';
                                    }
                                } } else if ($loggedinusertype== BORROWER) { if (isset($renterprice) && !empty($renterprice)) { echo '<li><p>Per day price</p><span>' . (isset($renterprice) ? CURRENCY_SIGN . $renterprice[0]['per_day_price'] : '') . '</span></li>'; } }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="contentArea2">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="head-all">Pictures/Videos</div>
            </div>
        </div>
        
			
            <?php $user_images = Yii::$app->commonmethod->getUserBookingImages($user_information['id']);
            if (isset($user_images) && !empty($user_images)) { ?>
            <div class="row">
               <?php foreach ($user_images as $row_uimg) {
                    $u_img = (isset($row_uimg['name']) ? BOOKING_IMAGES . $row_uimg['name'] : '');
                    $explodeimage = explode('.', $row_uimg['name']);
                    ?>
                    
                    <div class="col-md-2 col-sm-4 col-xs-4 responsive420" style="margin-top:10px;">
                        <div class="pictures">
                        <?php if ($explodeimage[1] == 'mp4' || $explodeimage[1] == 'mov') { ?>
                       
                        <img class="img-responsive" src="<?php echo WEBSITE_IMAGES_PATH.'play.png'; ?>" alt="" onclick="showimage('<?php echo $u_img ?>', 2, '<?php echo $explodeimage[1] ?>');return false;">
                         
                        <?php }else{?>
                        
                            <img class="img-responsive" src="<?php echo $u_img; ?>" alt="" onclick="showimage('<?php echo $u_img ?>', 1, null);return false;"> 
                           
                         <?php  } ?> 
                        </div>
                    </div>


<script>
$(document).on('hide.bs.modal','#pictureModal', function () {              
 $('video').trigger('pause');
});
    function showimage(image, type, videotype) {

        $("#pictureModal").modal();
        $('#picture').empty();
        if (type == 1) {
            $('#picture').append('<img class="img-responsive" src="' + image + '" alt="">');
        } else {
        	if(videotype == 'mp4'){
            $('#picture').append('<video autoplay class="embed-responsive-item" controls poster="<?php echo WEBSITE_IMAGES_PATH ?>play.png"><source src="' + image + '" type="video/mp4"></video>');
            }else if(videotype == 'mov'){
            $('#picture').append('<video autoplay class="embed-responsive-item" controls poster="<?php echo WEBSITE_IMAGES_PATH ?>play.png"><source src="' + image + '" ></video>');
            }
        }
    }

</script>
                    
                    <?php
                }?>
                </div>
                

            <?php } else{ echo '<p>No uploads yet.</p>'; }
            ?>
            
        <!-- modal starts-->    
  <div class="modal fade" id="pictureModal" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header" style="padding:0px 50px;">
                <!--button type="button" class="close" data-dismiss="modal">&times;</button-->
                <!--h4><span class="glyphicon glyphicon-check"></span> Are you sure you want to cancel this booking...</h4-->
            </div>

            <div class="modal-body" style="padding:40px 50px;" id="showloader">
                <div  id="picture"  align="center" class="embed-responsive embed-responsive-16by9">        
                           <!--img class="img-responsive" src="<?php echo SITE_URL; ?>common/uploads/loader/giphy.gif" alt="" width="70px" height="70px"-->
                </div>         
            </div>
            <!--div class="modal-footer" id="loaderfooter">
            <!--button  class="btn btn-success btn-default" id="acceptconfirmation"><span class="glyphicon glyphicon-check"></span> Yes</button-->
              <!--button type="submit" class="btn btn-danger btn-default pull-right" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> cancel</button-->

            <!--p>Not a member? <a href="#">Sign Up</a></p>
            <p>Forgot <a href="#">Password?</a></p>
          </div-->
        </div>

    </div>
</div> 
   <!--modal ends-->         

        
    </div>
</section>
<section class="contentArea2 contentArea3 reviewRating">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="head-all">Reviews</div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <?php
                Pjax::begin(['id' => 'Pjax_SearchResults', 'timeout' => false, 'enablePushState' => false, 'clientOptions' => ['method' => 'GET']]);

                if (isset($user_reviews) && !empty($user_reviews)) {
                    foreach ($user_reviews as $row_u_feedback) {
                        if (isset($row_u_feedback['profile_image']) && !empty($row_u_feedback['profile_image'])) {
                            $user_pic = PROFILE_IMAGE_PATH . $row_u_feedback['profile_image'];
                        } else {
                            $user_pic = NO_DISPLAY_IMAGE;
                        }
                        ?>
                        <div class="greyBox">
                            <div class="greyThumb"> 
                                <img class="img-responsive" src="<?php echo $user_pic; ?>" alt="" height="107px;" width="114px;"> 
                            </div>
                            <div class="greyText">
                                <div class="ratingBar">
                                    <div class="ratingBar-left">
                                        <div class="rating">
                                            <?php
                                            for ($i = 0; $i < 5; $i++) {
                                                if ($i < $row_u_feedback['starrating']) {
                                                    echo '<i class="fa fa-star" aria-hidden="true"></i>';
                                                } else {
                                                    echo '<i class="fa fa-star greyclr" aria-hidden="true"></i>';
                                                }
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="ratingBar-right">
                                        <div class="date">
                                            <i class="fa fa-clock-o" aria-hidden="true"></i> <?= date('m-d-Y', strtotime($row_u_feedback['date_time'])) ?>
                                        </div>
                                        <div class="orangeBtn">
                                            <a href="<?= Url::home() . 'bookings/booking-details/' . $row_u_feedback['booking_id'] ?>">Service details <i class="fa fa-angle-right" aria-hidden="true"></i></a>
                                        </div>
                                    </div>
                                </div>
                                <h4><?= (isset($row_u_feedback['fname']) ? $row_u_feedback['fname'] : '') . ' ' . (isset($row_u_feedback['lname']) ? $row_u_feedback['lname'] : '') ?></h4>
                                <p><?= (isset($row_u_feedback['comment']) ? $row_u_feedback['comment'] : '') ?></p>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo '<p>No Reviews yet.</p>';
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
<script>
    $(document).ready(function () {
        var noOfPals = 1;
        /*if (sessionStorage.getItem('no_of_pals')) { 
            var noOfPals = sessionStorage.getItem('no_of_pals')
        } else if(localStorage.getItem('no_of_pals')) {
            var noOfPals = localStorage.getItem('no_of_pals');
        }*/
        $("#_no_of_pals").val(noOfPals);
        // storage will be cleard after 20sec.
       /* setTimeout(function(){
            sessionStorage.removeItem('no_of_pals');
            localStorage.removeItem('no_of_pals');
            console.log('storage cleared');
        },20000);*/
    })
</script>
<script type="text/javascript">
					$(window).load(function() {
					  // The slider being synced must be initialized first
					  $('#carousel').flexslider({
						animation: "slide",
						controlNav: false,
						animationLoop: false,
						slideshow: false,
						itemWidth: 85,
						itemMargin: 5,
						asNavFor: '#slider'
					  });
					 
					  $('#slider').flexslider({
						animation: "slide",
						controlNav: false,
						animationLoop: false,
						slideshow: false,
						sync: "#carousel"
					  });
					});
</script>
