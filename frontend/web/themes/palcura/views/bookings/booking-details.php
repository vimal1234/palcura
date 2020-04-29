<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\bootstrap\ActiveForm;
use common\models\User;

$userId = Yii::$app->user->getId();
$this->title = Yii::t('yii', 'Bookings');
$imagePath = Yii::getAlias('@siteimage');
$profile_image = NOIMAGE107x114;

$username = "";
/* if(isset($bookingInformation['address']) && !empty($bookingInformation['address'])) {
  $profile_image = PROFILE_IMAGE_PATH.$bookingInformation['address'];
  } */

$booking_images = Yii::$app->commonmethod->getBookingImages($bookingInformation['id']);
$today = strtotime(date('Y-m-d'));
//echo "<pre>"; print_r($bookingInformation); die;
?>

    <div class="col-xs-12">
        <h1><?= $this->title ?></h1>
    </div>
</div>
</header>
<section class="contentArea">
    <div class="container">
        <div class="row">
            <?php echo $this->render('//common/sidebar'); ?>
            <div class="col-md-10 col-sm-9 col-xs-12">
                <div class="borderBox">
                    <div id="booking-focus" class="orangeBtn"> <a href="<?= Url::home() . 'bookings' ?>"><i class="fa fa-angle-left" aria-hidden="true"></i>back</a> </div>

                    <?php
                    
                     $vaccinationdetails  = Yii::$app->commonmethod->getUserPetsVaccinatiopn($bookingInformation['pet_owner_id']);
                     $vaccinationdoc = 'NA';
                     $vaccDate = 'NA';
                     if(!empty($vaccinationdetails)){
                     if(!empty($vaccinationdetails['vaccination_doc']) && $vaccinationdetails['vaccination_validity']){
                     $vaccinationdoc = $vaccinationdetails['vaccination_doc'];
                     $vaccDate = date('m/d/y', strtotime($vaccinationdetails['vaccination_validity']));
                     }
                     }
                    
                    if ($userId == $bookingInformation['pet_sitter_id'] && $bookingInformation['pet_sitter_id'] > 0 && $bookingInformation['pet_renter_id'] == 0) {
                    if($bookingInformation['booking_status']=='1' && $bookingInformation['payment_status']=='1' || $bookingInformation['cancelled_by'] != 0){                    
                        $totalamount = $bookingInformation['booking_credits'];
                        }else{
                        $totalamount = $bookingInformation['amount'];
                        }
                        
                        $oppuserinfo = User::find()->select('firstname,lastname,address,profile_image')->where(['id' => $bookingInformation['pet_owner_id']])->One();
                        if (isset($oppuserinfo->profile_image) && !empty($oppuserinfo->profile_image)) {
                            $profile_image = PROFILE_IMAGE_PATH . $oppuserinfo->profile_image;
                            $username = $oppuserinfo->firstname . ' ' . $oppuserinfo->lastname;
                            $address = $oppuserinfo->address;
                        }
                    } elseif ($userId == $bookingInformation['pet_owner_id'] && $bookingInformation['pet_renter_id'] > 0 && $bookingInformation['pet_sitter_id'] == 0) {
                        $oppuserinfo = User::find()->select('firstname,lastname,address,profile_image')->where(['id' => $bookingInformation['pet_renter_id']])->One();
                        if (isset($oppuserinfo->profile_image) && !empty($oppuserinfo->profile_image)) {
                            $profile_image = PROFILE_IMAGE_PATH . $oppuserinfo->profile_image;
                            $username = $oppuserinfo->firstname . ' ' . $oppuserinfo->lastname;
                            $address = $oppuserinfo->address;
                        }
                                                
                        if($bookingInformation['booking_status']=='1' && $bookingInformation['payment_status']=='1' || $bookingInformation['cancelled_by'] != 0){                    
                        $totalamount = $bookingInformation['booking_credits'];
                        }else{
                        $totalamount = $bookingInformation['amount'];
                        }

                        //$totalamount = $bookingInformation['booking_credits'];
                    } elseif ($userId == $bookingInformation['pet_owner_id'] && $bookingInformation['pet_renter_id'] == 0 && $bookingInformation['pet_sitter_id'] > 0) {
                        $totalamount = $bookingInformation['amount'];
                        $oppuserinfo = User::find()->select('firstname,lastname,address,profile_image')->where(['id' => $bookingInformation['pet_sitter_id']])->One();
                        if (isset($oppuserinfo->profile_image) && !empty($oppuserinfo->profile_image)) {
                            $profile_image = PROFILE_IMAGE_PATH . $oppuserinfo->profile_image;
                            $username = $oppuserinfo->firstname . ' ' . $oppuserinfo->lastname;
                            $address = $oppuserinfo->address;
                        }
                    } else {
                        $totalamount = $bookingInformation['amount'];
                        $oppuserinfo = User::find()->select('firstname,lastname,address,profile_image')->where(['id' => $bookingInformation['pet_owner_id']])->One();
                        if (isset($oppuserinfo->profile_image) && !empty($oppuserinfo->profile_image)) {
                            $profile_image = PROFILE_IMAGE_PATH . $oppuserinfo->profile_image;
                            $username = $oppuserinfo->firstname . ' ' . $oppuserinfo->lastname;
                            $address = $oppuserinfo->address;
                        }
                    }
                    ?>        
                </div>
                <div class="greyBox detpage">
                    <div class="borderbottom">
                        <div class="leftColumn">
                            <div class="greyThumb"><img class="img-responsive" src="<?php echo $profile_image; ?>" alt=""></div>

                        </div>
                        <div class="rightColumn">
                            <div class="greyText bookingdetail" style="margin-bottom:20px; ">
                                <div class="head"><?= $username; ?></div>
                                <div> <i class="fa fa-map-marker" aria-hidden="true"></i> <?= $address ?></div>
                                <br/>
                                <div><?php echo '<b>Booking name</b>: ' . $bookingInformation['name']; ?></div> 
                                <br/>
                                <div><?php echo '<b>Pet vaccination</b>: <a target="_blank" href="'.DOCUMENT_DOWNLOAD_PATH.$vaccinationdoc.'">' . $vaccinationdoc.'</a>'; ?></div> 
                                <br/>
                                <div><?php echo '<b>Pet vaccination date</b>: ' . $vaccDate; ?></div> 
                                <br/>
                                <div><?php echo '<b>Status</b>: '; ?>
<?php
if ($bookingInformation['booking_status'] == 0 && $bookingInformation['payment_status'] == 0) {
    echo "Not Confirmed";
} elseif ($bookingInformation['booking_status'] == 1 && $bookingInformation['payment_status'] == 0) {
    echo "Payment Pending";
} elseif ($bookingInformation['booking_status'] == 1 && $bookingInformation['payment_status'] == 1 && $bookingInformation['braintree_payment_status']==1) {
    echo "Payment Done";
}elseif($bookingInformation['booking_status'] == 1 && $bookingInformation['payment_status'] == 1 && $bookingInformation['braintree_payment_status']==0){
echo "Payment due on ".date('m/d/y', strtotime($bookingInformation['booking_from_date']));

}
?>
                                </div> <br/>
                                <div><?php echo '<b>Number of pets:</b> ' . count($pets); ?></div>
                            </div>
                            <div class="DateBlk">
                                <p>From</p>
                                <div class="datetext"> <?= date('m-d-Y', strtotime($bookingInformation['booking_from_date'])) ?> </div></div>
                            <div class="DateBlk">
                                <p>To</p>
                                <div class="datetext"> <?= date('m-d-Y', strtotime($bookingInformation['booking_to_date'])) ?> </div></div>
                            <div class="greenBlkMain">

                                <div class="greenBlk"> <?= CURRENCY_SIGN . $totalamount; ?> </div>
                            </div>
                        </div>
                    </div>
                    <div class="tabBlock">
                        <ul>
                            <?php
                            if (isset($bookingInformation['booking_services']) && !empty($bookingInformation['booking_services'])) {

                                $bookingservices = $bookingInformation['booking_services'];

                                $servicenames = Yii::$app->commonmethod->getServicesNamenew($bookingservices);
                                // $userservices = explode("|",$bookingInformation['services_name']);
                                foreach ($servicenames as $s_row) {
                                    echo '<li><a href="javascript:void(0)">' . $s_row . '</a></li>';
                                }
                            }
                            ?>
                        </ul>
                    </div>
                </div>
<?php if (isset($pets) && !empty($pets[0])) { ?>
                    <div class="bookingDetail">
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="head-all">Pet Details:</div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="row">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th> # </th>
                                                <th> Pet Type </th>
                                                <th> Name </th>
                                                <th> Care note </th>
                                            </tr>
                                        </thead>
                                        </tbody>
    <?php
    foreach ($pets as $key => $pet) {
        ?>
                                            <tr>
                                                <td> <?= $key + 1 ?> </td>
                                                <td> <?= $pet->getPetType() ?> </td>
                                                <td> <?= $pet->name ?> </td>
                                                <td> <?= $pet->get_care_note($bookingInformation['id']) ?> </td>
                                            </tr>
        <?php
    }
    ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div> <?php } ?>

<?php echo $this->render('activitylog', ['activityDataArray' => $activityDataArray]); ?>
                <div class="bookingDetail">
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="head-all">Pictures/Videos</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="row">
                                <?php
                                if (isset($booking_images) && !empty($booking_images)) {
                                    $i = 0;
                                    foreach ($booking_images as $mediaRow) {
                                        $b_img = (isset($mediaRow['name']) && !empty($mediaRow['name']) ? BOOKING_IMAGES . $mediaRow['name'] : '');
                                        if (!empty($b_img) && $i < 5) {
                                            echo '<div class="col-md-20"> <img class="img-responsive" src="' . $b_img . '" alt=""> </div>';
                                        }
                                        $i++;
                                    }
                                } else {
                                    echo "<div class=col-md-12 col-sm-12 col-xs-12><p>No images uploaded.<p></div>";
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bookingReview">
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="head-all">Reviews</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <?php
                            if (isset($reviews) && !empty($reviews)) {
                                foreach ($reviews as $feedback_row) {
                                    ?>				
                                    <div class="greyBox">
                                        <div class="bookingTop">
                                            <div class="bookingDate">
                                                <i class="fa fa-clock-o" aria-hidden="true"></i><?= date('m-d-Y', strtotime($feedback_row['date_time'])) ?>
                                            </div>
                                            <div class="rating bookingRating">
        <?php
        echo Yii::$app->commonmethod->generateRatings($feedback_row['starrating']);
        ?>
                                            </div>
                                        </div>
                                        <p><?= $feedback_row['comment'] ?></p>
                                    </div>
                                    <?php
                                }
                            } else {
                                echo "<p>No reviews yet.</p>";
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</section>

