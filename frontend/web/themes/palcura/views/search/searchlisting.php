<?php

use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use frontend\models\FeedbackRating;
use common\models\User;
use yii\bootstrap\ActiveForm;
?>
<?php
Pjax::begin(['id' => 'Pjax_SearchResults', 'timeout' => false, 'enablePushState' => false, 'clientOptions' => ['method' => 'GET']]);
?>	
<?php
function services_completed($ui){
$model = new User();
return $model->getServices($ui);
}
function starRating($val) {
    switch (round($val)) {
        case 1:
            return '<div class="rating-2"> <i class="fa fa-star orangeclr" aria-hidden="true"></i> <i class="fa fa-star greyclr" aria-hidden="true"></i> <i class="fa fa-star greyclr" aria-hidden="true"></i> <i class="fa fa-star greyclr" aria-hidden="true"></i> <i class="fa fa-star greyclr" aria-hidden="true"></i> </div>';
            break;
        case 2:
            return '<div class="rating-2"> <i class="fa fa-star orangeclr" aria-hidden="true"></i> <i class="fa fa-star orangeclr" aria-hidden="true"></i> <i class="fa fa-star greyclr" aria-hidden="true"></i> <i class="fa fa-star greyclr" aria-hidden="true"></i> <i class="fa fa-star greyclr" aria-hidden="true"></i> </div>';
            break;
        case 3:
            return '<div class="rating-2"> <i class="fa fa-star orangeclr" aria-hidden="true"></i> <i class="fa fa-star orangeclr" aria-hidden="true"></i> <i class="fa fa-star orangeclr" aria-hidden="true"></i> <i class="fa fa-star greyclr" aria-hidden="true"></i> <i class="fa fa-star greyclr" aria-hidden="true"></i> </div>';
            break;
        case 4:
            return '<div class="rating-2"> <i class="fa fa-star orangeclr" aria-hidden="true"></i> <i class="fa fa-star orangeclr" aria-hidden="true"></i> <i class="fa fa-star orangeclr" aria-hidden="true"></i> <i class="fa fa-star orangeclr" aria-hidden="true"></i> <i class="fa fa-star greyclr" aria-hidden="true"></i> </div>';
            break;
        case 5:
            return '<div class="rating-2"> <i class="fa fa-star orangeclr" aria-hidden="true"></i> <i class="fa fa-star orangeclr" aria-hidden="true"></i> <i class="fa fa-star orangeclr" aria-hidden="true"></i> <i class="fa fa-star orangeclr" aria-hidden="true"></i> <i class="fa fa-star orangeclr" aria-hidden="true"></i> </div>';
            break;
        default:
            return '<div class="rating-2"> <i class="fa fa-star greyclr" aria-hidden="true"></i> <i class="fa fa-star greyclr" aria-hidden="true"></i> <i class="fa fa-star greyclr" aria-hidden="true"></i> <i class="fa fa-star greyclr" aria-hidden="true"></i> <i class="fa fa-star greyclr" aria-hidden="true"></i> </div>';
    }
}
?>
<div class="col-md-7 col-sm-7 col-xs-12">
    <div class="row">
        <div class="col-md-6 col-sm-4 col-xs-4">
            <div class="headingListing">
                <div class="heading"> Results </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-8 col-xs-8">
            <div class="formBorder">
                <div class="form-group">
                    <label>Sort By</label>
                    <select class="form-control" name="Search[sort_by]" id="sort_by" onchange="searchWithSorting();">
                        <option value="1" <?php
                        if ($sort_by == 1) {
                            echo"selected";
                        }
                        ?>>low to high rating</option>
                        <option value="2" <?php
                        if ($sort_by == 2) {
                            echo"selected";
                        }
                        ?>>high to low rating</option>
                        <option value="3" <?php
                                if ($sort_by == 3) {
                                    echo"selected";
                                }
                                ?>>low to high price</option>
                        <option value="4" <?php
                                if ($sort_by == 4) {
                                    echo"selected";
                                }
                                ?>>high to low price</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <?php
    $mapLocations = array();
    if (!empty($searchResult) && count($searchResult) > 0) {
        $m = 0;
        foreach ($searchResult as $key => $val) {
            $ratingModel = new FeedbackRating();
            $ratingInfo = $ratingModel->getUserRating($val['id']);
            if (!empty($ratingInfo)) {
                $totalreviews = $ratingInfo['totalreviews'];
                $averageRating = $ratingInfo['averagerating'];
                $ratinginfo = $ratingInfo['ratinginfo'];
            } else {
                $totalreviews = 0;
                $averageRating = 0;
                $ratinginfo = array();
            }

            $usermodel = new User();
            $profileimage = $val['profile_image'] ? PROFILE_IMAGE_PATH . $val['profile_image'] : NO_DISPLAY_IMAGE;
            $firstname = $val['firstname'];
            $lastname = $val['lastname'];
            $username = $firstname . ' ' . $lastname;
            $userid = $val['id'];
            $zipcode = (isset($val['zip_code']) ? $val['zip_code'] : '');
            $day_price = (isset($val['price']) ? $val['price'] : 0);

            $user_countryname = (isset($val['u_country_name']) ? $val['u_country_name'] : '');
            $user_cityname = (isset($val['u_city_name']) ? $val['u_city_name'] : '');

            #### creating Map Location Array
            if (isset($val['latitude']) && !empty($val['latitude'])) {
                $mapLocations[$m]['lat'] = (isset($val['latitude']) ? $val['latitude'] : '');
                $mapLocations[$m]['lng'] = (isset($val['longitude']) ? $val['longitude'] : '');
                $mapLocations[$m]['title'] = (isset($val['address']) ? $val['address'] : '--');
                $mapLocations[$m]['label'] = (isset($val['firstname']) ? $val['firstname'] : '--');
                $m++;
            }
            ?>
            <div class="greyBox">
                <div class="detailAreaLeft">
                    <div class="thumb"> <img class="img-responsive" src="<?php echo $profileimage; ?>" alt="">
                        <div class="viewBlk" data-toggle="modal" data-target="#myModal<?php echo $userid; ?>"> <i class="fa fa-search-plus" aria-hidden="true"></i> Quick view </div>
                        <!-- Modal -->
                        <div class="modal fade" id="myModal<?php echo $userid; ?>" role="dialog">
                            <div class="modal-dialog"> 

                                <!-- Modal content-->
                                <div class="modal-content detailAreaInnerModel">
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="leftPopup"> <img class="img-responsive user-img" src="<?php echo $profileimage; ?>" alt=""> </div>
                                                <div class="rightPopup">
                                                    <div class="headPopup">
                                                        <div class="detailTitle"><?php echo $username; ?></div>
                                                        <div class="detailPop">
                                                            <div class="detailAreaHead-right">
                                                                <div class="detail-title-right">
                                                                    <p><img class="img-responsive lockToggle" src="<?php echo WEBSITE_IMAGES_PATH . 'lock.png'; ?>" alt=""> Services completed: <span><?php echo services_completed($userid); /* (isset($val['completed_services']) ? $val['completed_services'] : 0)*/ ?></span> </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="reviewDetailsToggle">
        <?php echo $averageRating.' '. starRating($averageRating); ?>
                                                        <span><?php echo $totalreviews; ?> Reviews</span> </div>
                                                    <div class="locationToggle">
                                                        <p><i class="fa fa-map-marker" aria-hidden="true"></i><?= $user_cityname . ', ' . $user_countryname . ', ' . $zipcode ?></p>
                                                    </div>
                                                    <div class="contentAreaDetails contentAreaToggle">
                                                        <div>"<?= (isset($val['pitch']) ? $val['pitch'] : '--') ?>"</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="contentDetailsPop">
                                                    <p class="userDetailExt"><?= (isset($val['user_description']) ? $val['user_description'] : '') ?></p>
                                                </div>
                                                <div class="bookNow">
                                                    <?php
                                                    $form = ActiveForm::begin(
                                                                    ['id' => 'search-form', 'method' => 'post', 'action' => Url::to(['bookings/book-now']),
                                                                        'fieldConfig' => [],
                                                    ]);
                                                    ?>
                                                    <input type="hidden" name="book_user_id" id="bookuserid" value="<?= $val['id'] ?>" />
                                                    <input type="hidden" name="no_of_pals" id="_no_of_pals_in_popup" value="1">
                                                    <button type="submit" class="orangeBtn _view_details" id="bookNow">BOOK NOW</button>
                                                        <?php ActiveForm::end(); ?>
                                                    <ul>
                                                        <li><a href="<?= Url::home() . 'messages/booking-messaging/' . $val['id'] ?>"><i class="fa fa-envelope-o" aria-hidden="true"></i>Send a message</a></li>
                                                        <li><a href="<?= Url::home() . 'bookings/schedule-video-call/' . $val['id'] ?>"><i class="fa fa-video-camera" aria-hidden="true"></i>Schedule a video call</a></li>
                                                    </ul>
                                                </div>
                                                <div class="detailsBottom">
                                                    <ul>
        <?php
        $service_name = Yii::$app->commonmethod->getUserServices($val['id']);
        if (isset($service_name) && !empty($service_name)) {
            foreach ($service_name as $s_name) {
                echo '<li><p>' . (isset($s_name['service_name']) ? $s_name['service_name'] : '') . '</p><span>' . (isset($s_name['service_price']) ? CURRENCY_SIGN . $s_name['service_price'] : '') . '</span></li>';
            }
        }
        ?>								
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="sliderPopup">
<?php  $user_images = Yii::$app->commonmethod->getUserBookingImages($val['id']);
                                                            if (isset($user_images) && !empty($user_images)) { ?>
                                                    <div class="carousel slide multi-item-carousel" id="theCarousel">
                                                        <div class="carousel-inner">
                                                            <?php
                                                            
                                                                $i = 0;
                                                                foreach ($user_images as $row_uimg) {
                                                                    $u_img = (isset($row_uimg['name']) ? BOOKING_IMAGES. $row_uimg['name'] : '');
                                                                    $ac = '';
                                                                    if ($i == 0) {
                                                                        $ac = "active";
                                                                    }
                                                                    ?>
                                                                    <div class="item <?= $ac ?>">
                                                                        <div class="col-xs-4">
                                                                            <a href="#1">
                                                                                <img class="img-responsive" src="<?php echo $u_img; ?>" alt="">
                                                                            </a> 
                                                                        </div>
                                                                    </div>
                <?php
                $i++;
            }
        
        ?>
                                                        </div>
                                                        <a class="left carousel-control" href="#theCarousel" data-slide="prev"><img src="<?= WEBSITE_IMAGES_PATH . 'toggle-arrow-left.png' ?>"></a> <a class="right carousel-control" href="#theCarousel" data-slide="next"><img src="<?= WEBSITE_IMAGES_PATH . 'toggle-arrow-right.png' ?>"></a> </div><?php } ?>
                                                </div> 
                                            </div>
                                        </div>
                                        <div class="listingPopup">
                                            <div class="row">
                                                <div class="col-md-12 col-sm-12 col-xs-12">
                                                    <div class="head-all">Reviews</div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12 col-sm-12 col-xs-12">
                                                    <?php
                                                    if (!empty($ratinginfo)) {
                                                        foreach ($ratinginfo as $k => $v) {
                                                            $reviewerid = $v['sender_userid'];
                                                            $reviewdesc = $v['comment'];
                                                            $reviewdate = $v['date_time'];
                                                            $reviewerrating = $v['starrating'];
                                                            $userProfileInfo = $usermodel->findIdentity($reviewerid);

                                                            $revierfirstname = $userProfileInfo->firstname;
                                                            $reviewerlastname = $userProfileInfo->lastname;
                                                            $reviewername = $revierfirstname . ' ' . $reviewerlastname;

                                                            $reviewerprofileimage = $userProfileInfo->profile_image ? PROFILE_IMAGE_PATH . $userProfileInfo->profile_image : NO_DISPLAY_IMAGE;
                                                            ?>
                                                            <div class="greyBoxPopup">
                                                                <div class="greyThumb2"> <img class="img-responsive" src="<?php echo $reviewerprofileimage; ?>" alt=""> </div>
                                                                <div class="greyText">
                                                                    <div class="ratingBar">
                                                                        <div class="pull-left">
                <?php echo starRating($reviewerrating); ?>
                                                                        </div>
                                                                        <div class="pull-right">
                                                                            <div class="date"><i class="fa fa-clock-o" aria-hidden="true"></i> <?php echo date('M d Y', strtotime($reviewdate)); ?> </div>
                                                                        </div>
                                                                    </div>
                                                                    <h4><?php echo $reviewername; ?></h4>
                                                                    <p><?php echo $reviewdesc; ?></p>
                                                                </div>
                                                            </div>
                <?php
            }
        } else {
            echo 'No reviews yet.';
        }
        ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Modal --> 
                    </div>
                    <div class="orangeBtn"> <a class="_view_details" href="<?= Url::home() . 'users/testview/' . $val['id'] ?>">View details</a> </div>
                </div>
                <div class="detailAreaRight">
                    <div class="detailAreaHead">
                        <div class="detailAreaHead-left">
                            <div class="detailTitle title-<?php echo $userid; ?>"><?php echo $username; ?></div>
                            <div class="reviewDetails">
                              <!--div class="rating-2"> <i class="fa fa-star" aria-hidden="true"></i> <i class="fa fa-star" aria-hidden="true"></i> <i class="fa fa-star" aria-hidden="true"></i> <i class="fa fa-star" aria-hidden="true"></i> <i class="fa fa-star" aria-hidden="true"></i> </div-->
        <?php echo starRating($averageRating); ?>
                                <span><?php echo $totalreviews; ?> Reviews</span>
                                <div class="location">
                                    <p><i class="fa fa-map-marker" aria-hidden="true"></i><?= $user_cityname . ', ' . $user_countryname . ', ' . $zipcode ?></p>
                                </div>
                            </div>
                            <div class="contentAreaDetails">
                                <div>"<?= (isset($val['pitch']) ? $val['pitch'] : '--') ?>"</div>
                            </div>
                        </div>
                        <div class="detailAreaHead-right">
                            <div class="detail-title-right">
                                <div class="greenBlk"><?php echo CURRENCY_SIGN . $day_price; ?></div>
                            </div>
                            <p style="text-align: center; font-size: 10px; color: #f2b51b;"> per pet </p>
                        </div>
                    </div>
                    <p class="userDetailExt"><?= (isset($val['user_description']) ? $val['user_description'] : '--') ?></p>
                    <div class="tabBlock list<?php echo $userid; ?>">
                        <ul>
        <?php
        $service_name = Yii::$app->commonmethod->getUserServices($val['id']);
        if (isset($service_name) && !empty($service_name)) {
            foreach ($service_name as $s_name) {
                echo '<li><a>' . (isset($s_name['service_name']) ? $s_name['service_name'] : '') . '</a></li>';
            }
        }
        ?>
                        </ul>
                    </div>
                </div>
            </div>
            <!--search listing end-->
        <?php
    }
} else {
    echo '<p>There are currently no sitters available that match your criteria.</p>';
}
?>
    <div class="customPagination">
        <nav aria-label="Page navigation">
            <!--ul class="pagination">
              <li> <a href="javascript:void(0)" aria-label="Previous"> <span aria-hidden="true"><i class="fa fa-angle-left" aria-hidden="true"></i></span> </a> </li>
              <li class="active"><a href="javascript:void(0)">1</a></li>
              <li><a href="javascript:void(0)">2</a></li>
              <li><a href="javascript:void(0)">3</a></li>
              <li><a href="javascript:void(0)">4</a></li>
              <li><a href="javascript:void(0)">5</a></li>
              <li> <a href="javascript:void(0)" aria-label="Next"> <span aria-hidden="true"><i class="fa fa-angle-right" aria-hidden="true"></i></span> </a> </li>
            </ul-->

<?php
echo LinkPager::widget([
    'pagination' => $pages,
]);
?>
            <!--?php
   // display pagination
   if($pages !== null):
   echo yii\widgets\LinkPager::widget([
           'pagination' => $pages,
           'prevPageLabel' => '<i class="fa fa-angle-left" aria-hidden="true"></i>',
           'nextPageLabel' => '<i class="fa fa-angle-right" aria-hidden="true"></i>',
           'activePageCssClass' => 'active',
           'disabledPageCssClass' => 'disabled',
           'prevPageCssClass' => 'enable',
           'nextPageCssClass' => 'enable',

   ]);
   endif;
   ?-->
        </nav>
    </div>
</div>
<div class="col-md-5 col-sm-5 col-xs-12">
    <div class="headingListing">
        <div class="heading transform-none"> Location on map </div>
    </div>
    <div id="map_canvas"></div>
<?php
if (isset($mapLocations['0']['lat']) && !empty($mapLocations['0']['lat'])) {
    ?>
        <script>
            var map;
            function initMap() {
                var uluru = {lat: <?= (isset($mapLocations['0']['lat']) ? $mapLocations['0']['lat'] : '') ?>, lng: <?= (isset($mapLocations['0']['lng']) ? $mapLocations['0']['lng'] : '') ?>};
                var map = new google.maps.Map(document.getElementById('map_canvas'), {
                    zoom: 12,
                    center: uluru,
                    mapTypeId: google.maps.MapTypeId.ROADMAP,
                    mapTypeControl: false
                });
                var bounds = new google.maps.LatLngBounds();
    <?php
    foreach ($mapLocations as $mp_row) {
        $maparea_lat = $mp_row['lat'];
        $maparea_lng = $mp_row['lng'];
        $LLng = "$maparea_lat,$maparea_lng";
        ?>
                    var latlng = new google.maps.LatLng(<?php echo $LLng; ?>);
                    bounds.extend(latlng);
                    var marker = new google.maps.Marker({
                        position: {lat: <?= $maparea_lat ?>, lng: <?= $maparea_lng ?>},
                        map: map,
                        title: "<?= $mp_row['title'] ?>",
                        label: "<?= $mp_row['label'] ?>"
                    });
    <?php } ?>

            }
            initMap();
        </script>
<?php } elseif (!empty($zipaddress)) { ?>
        <script>
            var map;
            function initMap() {
                var mapOptions = {
                    center: new google.maps.LatLng("<?php echo $zipaddress['lat'] ?>", "<?php echo $zipaddress['lng'] ?>"),
                    zoom: 12,
                    mapTypeId: google.maps.MapTypeId.ROADMAP,
                    mapTypeControl: false
                }
                var map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
            }
            initMap();
        </script>	
<?php } else { ?>
        <script>
            var map;
            function initMap() {
                var mapOptions = {
                    center: new google.maps.LatLng(33.6366956, -117.6096075),
                    zoom: 12,
                    mapTypeId: google.maps.MapTypeId.ROADMAP,
                    mapTypeControl: false
                }
                var map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
            }
            initMap();
        </script>
<?php } ?>
    <script src="https://maps.googleapis.com/maps/api/js?key=<?= GOOGLE_MAP_KEY ?>&callback=initMap"
    async defer></script>
</div>
<?php Pjax::end(); ?>    
