<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',

    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        ####THIS IS THE UNIVERSAL FOMATTER for DATE too########################################
        #########COMMON FORMATTER FOR ALL gridveiw/listveiw/detailveiws widgets#################
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'nullDisplay' => '',
            #'booleanFormat' => ['×', '√'],
            ###DATE fromatter ############
            'dateFormat' => 'dd.MM.yyyy',
            'datetimeFormat' => 'medium',
            ####FORMAT CURRENT CODE################
            'locale' => 'en_US', //ej. 'es-ES'
            'currencyCode' => 'USD',
        ],
        'funcns' => [
            'class'=>'common\components\Func',
        ],
    ],
    'aliases' => [
        '@webThemeUrl' => 'http://palcura.com',
    ],
];
