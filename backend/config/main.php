<?php

$params = array_merge(
        require(__DIR__ . '/../../common/config/params.php'), require(__DIR__ . '/../../common/config/params-local.php'), require(__DIR__ . '/params.php'), require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-backend',
    'layout' => '@backend/web/themes/gentelella/views/layouts/gentel.php',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        'products' => [
            'class' => 'backend\modules\products\product',
        ],
        'newsletter' => [
            'class' => 'yii\newsletter\Module',
        ],
        'tools' => [
            'class' => 'yii\newsletter\components\Tools',
        ],
    ],
    'components' => [
        'user' => [
            'authTimeout' => 7200,
            'identityClass' => 'common\models\Admin',
            'enableAutoLogin' => true,
            'loginUrl' => [ 'admin/login'],
            'identityCookie' => [
                'name' => '_backendUser', // unique for backend
            ]
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'session' => [
            'name' => 'PHPBACKSESSID',
            'savePath' => sys_get_temp_dir(),
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '[dw0vq3$YWN#vEIn@3&!]',
            'csrfParam' => '_backendCSRF',
        ],
        'view' => [
            'theme' => [
                'basePath' => '@backend/web/themes/gentelella',
                'pathMap' => ['@backend/views' => '@backend/web/themes/gentelella/views'],
                'baseUrl' => '@backend/web/themes/gentelella',
            ],
        ],
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            // Disable index.php
            'showScriptName' => false,
            // Disable r= routes
            'enablePrettyUrl' => true,
            'rules' => array(
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ),
        ],
        /*'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'localhost',
                'username' => '',
                'password' => '',
                'port' => '587',
                'encryption' => 'tls',
            ],
        ],*/
        'twillio' => [
            'class' => 'yii\twillio\Twillio',
            'sid' 	=> 'AC86579b16f62c0823bc7503f296d31027',
            'token' => 'e6df6a215172cf4f2d2183e0020a0a27',
        ],        
    ],
    'defaultRoute' => 'sitters',
    'params' => $params,
];
