<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = (isset($cmsContent[0]['pageTitle']) ? $cmsContent[0]['pageTitle'] : 'CMS PAGE');
$this->params['breadcrumbs'][] = $this->title;
?>
<section id="titleTop">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                <div class="heading"><?php echo $this->title; ?></div>
            </div>
           
        </div>
    </div>
</section>
<?php echo (isset($cmsContent[0]['pageContent']) ? str_replace("../../../frontend/web/themes/quotenow/images/","./frontend/web/themes/quotenow/images/",$cmsContent[0]['pageContent']) : 'Not found'); ?>
<script type="text/javascript">

    $('ul.nav.nav-tabs  a').click(function (e) {
        e.preventDefault();
        $(this).tab('show');
    });

    (function ($) {
        // Test for making sure event are maintained
        $('.js-alert-test').click(function () {
            alert('Button Clicked: Event was maintained');
        });
        fakewaffle.responsiveTabs(['xs']);
    })(jQuery);

</script> 
