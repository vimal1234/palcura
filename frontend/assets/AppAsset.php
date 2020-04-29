<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace frontend\assets;
use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'frontend/web/themes/palcura/css/bootstrap.css',
        'frontend/web/themes/palcura/css/bootstrap.min.css',
        'frontend/web/themes/palcura/css/countdown.css',
        'frontend/web/themes/palcura/css/font-awesome.css',
        'frontend/web/themes/palcura/css/star-rating.css',
        'frontend/web/themes/palcura/css/core.css',
        'frontend/web/themes/palcura/css/responsive.css',
        'frontend/web/themes/palcura/css/jquery-ui.css',
        'frontend/web/themes/palcura/css/lightslider.css',       
        '//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css'
    ];

    public $js = [
        'frontend/web/themes/palcura/js/jquery-ui.min-latest.js',
        'frontend/web/themes/palcura/js/jquery.ui.touch-punch.min.js',
        'frontend/web/themes/palcura/js/bootstrap.min.js',
        'frontend/web/themes/palcura/js/countdown.js',
        'frontend/web/themes/palcura/js/owl.carousel.js',
        'frontend/web/themes/palcura/js/owl.carousel.min.js',
        'frontend/web/themes/palcura/js/bootstrap-responsive-tabs.min.js',
        'frontend/web/themes/palcura/js/core.js',
        'frontend/web/themes/palcura/js/star-rating.js',
        'frontend/web/themes/palcura/js/frontend-custom.js',
        'frontend/web/themes/palcura/js/moment.min.js',
        'frontend/web/themes/palcura/js/fullcalendar.min.js',
        'frontend/web/themes/palcura/js/theme-chooser.js',
        'frontend/web/themes/palcura/js/tabcomplete.min.js',
        'frontend/web/themes/palcura/js/livefilter.min.js',
        'frontend/web/themes/palcura/js/bootstrap-select.js',
        'frontend/web/themes/palcura/js/lightslider.js',
        '//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js'
        
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

    public $jsOptions = array(
        'position' => \yii\web\View::POS_HEAD
    );
}
