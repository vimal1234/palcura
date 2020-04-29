<?php
//print_r($_SERVER);
//if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR']=='112.196.26.99'){
//echo 'aa';
//defined('YII_DEBUG') or define('YII_DEBUG', true);
//defined('YII_ENV') or define('YII_ENV', 'dev');
//}else{
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');
//}
//echo $_SERVER['REQUEST_URI'];
$seourl = $_SERVER['REQUEST_URI'];
switch($seourl){
    
    case '/cms/page/why-palcura':
        header("HTTP/1.1 301 Moved Permanently"); 
        header("Location: https://www.palcura.com/why-palcura"); 
        exit();
    case '/cms/page/faq':
        header("HTTP/1.1 301 Moved Permanently"); 
        header("Location: https://www.palcura.com/faq"); 
        exit();
    case '/cms/page/mission':
        header("HTTP/1.1 301 Moved Permanently"); 
        header("Location: https://www.palcura.com/mission"); 
        exit();
    case '/cms/page/terms-and-conditions':
        header("HTTP/1.1 301 Moved Permanently"); 
        header("Location: https://www.palcura.com/terms-and-conditions"); 
        exit();
    case '/site/contact':
        header("HTTP/1.1 301 Moved Permanently"); 
        header("Location: https://www.palcura.com/contact"); 
        exit();
     case '/site/home':
        header("HTTP/1.1 301 Moved Permanently"); 
        header("Location: https://www.palcura.com/"); 
        exit();
    
}


require(__DIR__ . '/vendor/autoload.php');
require(__DIR__ . '/vendor/yiisoft/yii2/Yii.php');
require(__DIR__ . '/common/config/bootstrap.php');
require(__DIR__ . '/frontend/config/bootstrap.php');

$config = yii\helpers\ArrayHelper::merge(
    require(__DIR__ . '/common/config/main.php'),
    require(__DIR__ . '/common/config/main-local.php'),
    require(__DIR__ . '/frontend/config/main.php'),
    require(__DIR__ . '/frontend/config/main-local.php')
);
 
$application = new yii\web\Application($config);
//date_default_timezone_set("America/New_York");
$application->run();
