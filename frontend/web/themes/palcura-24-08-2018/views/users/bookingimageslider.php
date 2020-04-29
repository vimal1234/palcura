<?php

use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use frontend\models\FeedbackRating;
use common\models\User;
use yii\bootstrap\ActiveForm;
?>
<?php
Pjax::begin(['id' => 'Pjax_imageResults', 'timeout' => false, 'enablePushState' => false, 'clientOptions' => ['method' => 'GET']]);
?>

<?php if (isset($bookingImages) && !empty($bookingImages)) { ?>
    <div class="sliderPopup">
        <div class="carousel slide multi-item-carousel" id="theCarousel">
            <div class="carousel-inner">
                <?php
                $i = 0;
              
                foreach ($bookingImages as $row_uimg) {
                    $u_img = (isset($row_uimg['name']) ? BOOKING_IMAGES . $row_uimg['name'] : '');
                    $explodeimage = explode('.', $row_uimg['name']);

                    $ac = '';
                    if ($i == 0) {
                        $ac = "active";
                    }
                    ?>
                    <div class="item <?= $ac ?>">
                        <div class="col-xs-4">
                        <!--<div class="col-xs-4" style="width:auto; margin: 10px; border: 1px solid; background-image: url(https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcREhL9VtWN1f88nyBwMhRqKElo4BJThrbnnCNI5pw1KPMsopi7gkQ); min-height: 270px;">-->
                            <?php if ($explodeimage[1] == 'mp4' || $explodeimage[1] == 'mov') { ?>
                                <a href="" onclick="showimage('<?php echo $u_img ?>', 2, '<?php echo $explodeimage[1] ?>');return false;">
                                <img class="img-responsive" src="<?php echo WEBSITE_IMAGES_PATH.'play.png'; ?>" alt="">
                                    <!--video width="200">
                                        <source src="<?php echo $u_img; ?>" >	
                                       
                                    </video-->
                                </a>	
                            <?php } elseif ($explodeimage[1] == 'mov1') { ?>
                                   
                                <?php
                            } else {
                                ?>
                                <a href="" onclick="showimage('<?php echo $u_img ?>', 1, 1);return false;">
                                    <img class="img-responsive" src="<?php echo $u_img; ?>" alt="">
                                </a> 
                            <?php } ?>	
                        </div>
                    </div>
                    <?php
                    $i++;
                }
                ?>	
            </div>
            <a class="left carousel-control" href="#theCarousel" data-slide="prev"><img src="<?= WEBSITE_IMAGES_PATH . 'toggle-arrow-left.png' ?>"></a> <a class="right carousel-control" href="#theCarousel" data-slide="next"><img src="<?= WEBSITE_IMAGES_PATH . 'toggle-arrow-right.png' ?>"></a> 
        </div>
    </div>
    <?php
} else {
    echo 'No images Uploaded.';
}
?> 

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

<script>
// Instantiate the Bootstrap carousel
    $('.multi-item-carousel').carousel({
        interval: false
    });

// for every slide in carousel, copy the next slide's item in the slide.
// Do the same for the next, next item.
    $('.multi-item-carousel .item').each(function () {
        var next = $(this).next();
        if (!next.length) {
            next = $(this).siblings(':first');
        }
        next.children(':first-child').clone().appendTo($(this));

        if (next.next().length > 0) {
            next.next().children(':first-child').clone().appendTo($(this));
        } else {
            //$(this).siblings(':first').children(':first-child').clone().appendTo($(this));
        }
    });

</script>

<?php Pjax::end(); ?>   
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
            $('#picture').append('<video autoplay class="embed-responsive-item" controls poster="<?php echo WEBSITE_IMAGES_PATH ?>play.png"><source src="' + image + '"></video>');
            }
        }
    }

</script>
