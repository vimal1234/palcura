<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace backend\assets;

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
        'themes/gentelella/css/bootstrap.min.css',
        'themes/gentelella/fonts/css/font-awesome.min.css',
        'themes/gentelella/css/animate.min.css',
        'themes/gentelella/css/custom.css',
        'themes/gentelella/css/icheck/flat/green.css',
    ];
    public $js = [
      'themes/gentelella/js/bootstrap.min.js',
      'themes/gentelella/js/menus.js',
      'themes/gentelella/js/nicescroll/jquery.nicescroll.min.js',
      'themes/gentelella/js/icheck/icheck.min.js',
      'themes/gentelella/js/custom.js',
      'themes/gentelella/js/commonmethods.js',
    
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
