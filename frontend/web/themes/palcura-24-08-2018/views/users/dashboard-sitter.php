<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;

//echo "<pre>"; print_r($myBookings); die;
$this->title = 'Dashboard';
$this->params['breadcrumbs'][] = $this->title;
$siteimage = Yii::getAlias('@siteimage');
$attributes = Yii::$app->user->identity->getattributes();
//echo'<pre>'; print_r($attributes); exit();
$profile_picture = $attributes['profile_image'];
$profile_pic = isset($profile_picture) ? Url::home() . 'common/uploads/profile/' . $profile_picture : Yii::getAlias('@webThemeUrl') . '/images/noimage.png';
$session = Yii::$app->session;
$reminder = $session->get('reminedviewed');

$logged_user 	= $session->get('loggedinusertype');
$logUserType = '';
if($logged_user==1){
$logUserType = 'Owner';
}elseif($logged_user==2){
$logUserType = 'Sitter';
}elseif($logged_user==3){
$logUserType = 'Borrower';
}

if(!empty($logUserType)){
$messageText = 'You are currently logged in as <strong>'.$logUserType.'</strong>.';
}else{
$messageText = "";
} 
?>
<div class="row">
    <div class="col-xs-12">
        <h1><?= $this->title ?></h1>
    </div>
</div>
</div>
</header>
<section class="contentArea contentAreaDashboard">
    <div class="container">
        <?php if (Yii::$app->session->getFlash('error')): ?>
            <script>
                $(document).ready(function () {
                    // Handler for .ready() called.
                    $('html, body').animate({
                        scrollTop: $('#scrrollhere').offset().top
                    }, 'slow');
                });

            </script>
            <div class="alert alert-grey alert-dismissible" id="scrrollhere">
                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span></button>
                <i class="fa fa-remove"></i> <?php echo Yii::$app->session->getFlash('error'); ?>
            </div>
        <?php endif; ?>
        <div class="col-lg-12 row"><p style="padding: 20px 0;color: #FF8447;"><?php echo $messageText ;?></p>
		</div>
        <div id="reminder" class="row">				
        </div>
        <div class="row scrolldiv01">
            <?php echo $this->render('//common/sidebar'); ?>
            <?php if (!empty($myBookings) && count($myBookings) > 0) { ?>
                <div class="col-md-4 col-sm-9 col-xs-12" >
                    <label>Select Booking:</label>
                    <select class="form-control" id="selectedbooking">
                        <?php foreach ($myBookings as $k => $v) { ?>
                            <option value="<?php echo $v['id']; ?>"><?php echo $v['name']; ?></option>		
                        <?php } ?>
                    </select>
                </div>

                <div class="col-md-10 col-sm-9 col-xs-12">
                    <section class="dashboardSitterMain">
                        <div class="row">
                            <div class="col-md-6 col-sm-7 col-xs-12">
                                <div id="dashboardlistresult">
                                    <?php
                                    echo $this->render('dashboardlist', ['activityTypelisting' => $activityTypelisting, 'activitydate' => $activitydate]);
                                    ?>
                                </div>	
                                <div class="col-md-6 col-sm-5 col-xs-12">
                                    <?php if (Yii::$app->session->getFlash('item')): ?>
                                        <div class="col-xs-12">
                                            <div class="alert alert-grey alert-dismissible">
                                                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span>
                                                </button>
                                                <i class="glyphicon glyphicon-ok"></i><?php echo Yii::$app->session->getFlash('item'); ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>							
                                    <div class="dashboardUpload">
                                        <div class="dashboardHead">Upload pictures/videos</div>
                                        <div class="greyBox">
                                            <?php
                                            $form = ActiveForm::begin([
                                                        'id' => 'editProfile-form',
                                                        'options' => [
                                                            'enctype' => 'multipart/form-data',
                                                            'tag' => 'span',
                                                        ],
                                                        'fieldConfig' => [
                                                            'template' => "<div class=\"form-group\">\n
															{label}\n
															{input}\n
															<div class=\"col-lg-10\">
															{error} {hint}
															</div>
															</div>",
                                                            'labelOptions' => [],
                                                            'options' => [
                                                                'tag' => 'span',
                                                                'class' => '',
                                                            ],
                                                        ],
                                            ]);
                                            ?>
                                            <?php
                                            echo $form->field($modelImageUpload, 'upload_booking_images[]', ['inputOptions' => [
                                                    'class' => "form-control imageBrowser iamhere",
                                        ]])->fileInput(['multiple' => true, "accept" => "image/*,video/*"])->label('Choose files to upload:');
                                            ?>
                                            <div style="display:none">	
                                                <?php
                                                if (!empty($myBookings) && count($myBookings) > 0) {
                                                    $bookingid = reset($myBookings)['id'];
                                                } else {
                                                    $bookingid = 0;
                                                }
                                                echo $form->field($modelImageUpload, 'bookingid')->hiddenInput(['value' => $bookingid])->label(false);
                                                ?></div>
                                            <!--
                                                                                                                            <div class="form-group">
                                                                                                                                    <label>Choose files to upload:</label>
                                                                                                                                    <input type="file" class="form-control browseFile">
                                                                                                                            </div>
                                            -->
                                            <hr/>
                                            <!--
                                                                                                                            <button type="submit" class="orangeBtn">Upload</button>
                                            -->
                                            <?=
                                            Html::submitButton(Yii::t('yii', 'Submit'), ['class' => 'orangeBtn', 'name' => 'editUser-submit', 'id' => 'editUser-submit'])
                                            ?> 										
                                            <?php ActiveForm::end(); ?>
                                        </div>
                                    </div>
                                </div>
                                <!--div class="col-md-4 col-sm-6 col-xs-12">
                                <div id="datesadded"></div>
                                <input id="updatesitter-availability" class="form-control date datepicker" class="date-pick" name="updateavailability" value="" maxlength="15" autofocus="" readonly="" type="text" >
                                <button type="button" class="orangeBtn" id="addavailability">Add</button>
                                </div>
                                </div-->

                                <div class="bookingDetail bookingDetailDashboard">
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="head-all">Pictures/Videos</div>
                                        </div>
                                    </div>

                                    <!--image slider start-->
                                    <div id="bookingimageslider">
                                        <?php
                                        echo $this->render('bookingimageslider', [
                                            'bookingImages' => $bookingImages
                                        ]);
                                        ?>
                                    </div>
                                    <!--slider end-->	


                                </div>	
                                </section>
                                <div id="search-result">								
                                    <?php
                                    echo $this->render('activitylog', [
                                        'activityDataArray' => $activityDataArray,
                                    ]);
                                    ?>
                                </div>
                            </div><?php
                        } else {
                            echo '<p align="center">' . NO_RESULT . '</p>';
                        }
                        ?>
                    </div>
            </div>
            </section>
            <script src="http://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script>

            <script>
            $(document).ready(function () {
                $('.date').datepicker({
                    multidate: true,
                    format: 'dd-mm-yyyy'
                });

                $('document').ready(function () {
                    var reminder = <?php echo $reminder; ?>;
                    //if(reminder==0){
                    $.ajax({
                        url: "<?php echo Url::to(['users/bookingreminders']); ?>",
                        type: 'post',
                        data: reminder,
                        success: function (response) {
                            $('#reminder').append(response);
                        }
                    });
                    /*}else{
                     console.log('no reminder');
                     return false;	
                     }	*/
                });

                $('#addavailability').click(function () {
                    var availablitydates = $('#updatesitter-availability').val();
                    $.ajax({
                        url: '<?php echo Url::to(['users/addavailability']); ?>',
                        type: 'post',
                        data: {'dates': availablitydates},
                        success: function (response) {
                            return true;
                        }
                    });

                });

                var searchPost = {};
                $('#selectedbooking').on('change', function () {
                    searchPost.bookingid = $(this).val();
                    $('#uploads-bookingid').val($(this).val());
                    filterSearchResult(searchPost);
                });

            });

            function filterSearchResult(searchPost) {
                $.ajax({
                    url: '<?php echo Url::to(['users/activitylog']); ?>',
                    type: 'post',
                    data: {'filter': searchPost},
                    success: function (response) {
                        $('#search-result').html(response);
                        $.ajax({
                            url: '<?php echo Url::to(['users/newdashboardlist']); ?>',
                            type: 'post',
                            data: {'filter': searchPost},
                            success: function (response) {
                                $('#dashboardlistresult').html(response);
                                $.ajax({
                                    url: '<?php echo Url::to(['users/newimagelist']); ?>',
                                    type: 'post',
                                    data: {'filter': searchPost},
                                    success: function (response) {
                                        $('#bookingimageslider').html(response);
                                    }
                                });

                            }
                        });
                    }
                });
            }

            function updateActivity(updateactivityPost) {

                var searchPost = {};
                var activityid = updateactivityPost;
                var bookingid = $('#selectedbooking').val();

                $.ajax({
                    url: '<?php echo Url::to(['users/updateactivity']); ?>',
                    type: 'post',
                    data: {'activityid': updateactivityPost, 'bookingid': bookingid},
                    success: function (response) {
                        if (response != false) {
                            $('#dashboardlistresult').html(response);
                            searchPost.bookingid = bookingid;
                            filterSearchResult(searchPost);
                        } else {
                            return false;
                        }
                    }
                });

            }

            </script>

