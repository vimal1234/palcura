<?php

$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'), 
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'defaultRoute' => 'site/home',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'sourceLanguage' => 'en',
    'components' => [
        'i18n' => [
            'translations' => [
                'app*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'fileMap' => [
                        'app' => 'yii.php',
                        'app/error' => 'error.php',
                    ],
                ],
            ],
        ],

 'braintree' => [
                                     //'class'=>'application.components.braintree.YiiBraintree',
                                     'class'=>'frontend\components\Braintree',
 
                                      'ENV' => 'sandbox',//sandbox or production
 
                                      'MERCHANT_ID' => 'gmyrqpmhd2qbkspw',
//'MERCHANT_ID' => 'g2ph339mbzqtbqbs',
 
                                     // 'MERCHANT_ACCOUNT_ID'=>'adrenotechonologies',
//'MERCHANT_ACCOUNT_ID'=>'PalCura_instant',
 
                                      //'PUBLIC_KEY' => 'cr99ykxs4hk3x3tf',
'PUBLIC_KEY' => 'bt77xjq4hfncff93',
'PRIVATE_KEY'=>'6c72912247d9208921478d1c2a37284d',
                                      //'PRIVATE_KEY'=>'878aab7f32a00c4a161638f8558c9f1b',
 
  'CSEK'=>'MIIBCgKCAQEArqImGTWJIUDdDHesvF63CSksZVFEB1sqFKp5i+fbfs56glidmE7FpHsDde3T0UxZDcyZ4SZmv35Ui0/tRdWmSlraeReEvbQMD0mLf2PTgMTz6udd8D6hNKSjAi/eVWQpzBC6SDwk6L1ga+YFHm9tu/c2vioCJqujynlamM7UvN6wbRVexyPiigiICT/tHBpbxOQqk7KYbPnpr49aDi4CxdSojWkB7LSHnYV0PTeP28XdatvRI1xHtD60xCljaD9zJ2wFC1fSnmRxHF10yUc8D/QzuAJYaMZCm9EG375zCBMqfliVbkguCJ1se1xCfNV94AwGM/PdWzv7y05ZRVmalQIDAQAB' 
                                   //'CSEK'=>'MIIBCgKCAQEAr/57nwO9QcYKUwYdDA93Qlb+VkRTUj8xnuTdHHwAchFjlURg+PmDKG6nQD0y/8TRfSDX2oPIcD7OHct0xXvRwen+6/XkNkjHcd1KV0IBMsT1NgCeVAHbmFO0pOnCU/Sdj6fB5XilwEKa1Hu1gl4lBLIzU8h5apUUUCOLILwIhIpLebj/EC2i98n4k7DRgOrVrX8YCtvKYcoSYrL2cO6UT/xuem3nv+uyn0ql2qGkmUW+zjpntw3n4PCU2iDgsRrBctEdub7Jd8L6393QrkHkS4vHp8LUGCZJBlQFtdxpIJY0TVjVf1WRUrZRlwl5+Go9RcYbAC6kbkFAzIQ0hewMaQIDAQAB'
 
                                      ],  

'user' => [
        'identityClass' => 'common\models\User',
        'enableAutoLogin' => false,
        'authTimeout' => 3600, // auth expire 
    ],'session' => [
        'class' => 'yii\web\Session',
        'cookieParams' => ['httponly' => true, 'lifetime' => 3600 * 4],
        'timeout' => 3600*4, //session expire
        'useCookies' => true,
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
            'name' => 'PHPFRONTSESSID',
            'savePath' => sys_get_temp_dir(),
        ],
        'request' => [
            'cookieValidationKey' => '[!@3e2df!#4$erg%4*$fd2&]',
            'csrfParam' => '_frontendCSRF',
        ],
        ######THESE CONFIGS ARE FOR LOGIN####ENDS#################
        'urlManager' => [
            'class' => 'yii\localeurls\UrlManager',
            //'languages' => ['en', 'fr', 'en-US'],
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'rules' => [
                '<alias:signin|signup|contact>' => 'site/<alias>',
				'<alias:near-me>' => 'site/nearme',
                '<alias:bookings>' => 'bookings/',
                '<alias:pal-rewards>' => 'site/rewards/',
                '<alias:become-a-sitter>' => 'site/becomeasitter/',
				'<alias:sitter-thank>' => 'site/sitterthank',
                '<alias:messages>' => 'messages/',
                '<alias:video>' => 'video/',
                '<alias:reviews>' => 'reviews/',
                '<alias:payments>' => 'payments/',
                //'<alias:contact1>' => 'site/<alias>',
				//'<alias:signup>' => 'site/<alias>',
				//'<alias:signin>' => 'site/<alias>',
                'search'=>'search/search-guide',
                '<slug:[a-zA-Z0-9_ -]+>' => 'cms/page',
                ###ALLOW hyphen - sperated param values############################
                '<controller:\w+>/<action:\w+>/<id:[\w\?(\-\w)]+>' => '<controller>/<action>',
                
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\w+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
                '<controller>/<action>/<id:\w+>' => '<controller>/<action>',                
            ],
        ],
        'view' => [
            'theme' => [
                'basePath' => '@frontend/web/themes/palcura',
                'baseUrl' => '@frontend/web/themes/palcura',
                'pathMap' => [
                    '@frontend/views' => '@frontend/web/themes/palcura/views/',
                ],
            ],
        ],
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'facebook' => [
                    'class' => 'yii\authclient\clients\Facebook',
                    'authUrl' => 'https://www.facebook.com/dialog/oauth?display=popup',
                    'clientId' => '1786203434949580',
                    'clientSecret' => 'e95133afa1634eb0bf26009f8403540c',
                ],
                'google' => [
                    'class' => 'yii\authclient\clients\Google',
                    'clientId' => '92034358582-iqin8vhphk17qo9mbp6g36db5n6hsn2n.apps.googleusercontent.com',
                    'clientSecret' => 'NYqjrPWZkq-EzUMElBHRqxHR',
                ],
				'linkedin' => [
					'class' => 'yii\authclient\clients\LinkedIn',
					'clientId' => 'linkedin_client_id',
					'clientSecret' => 'linkedin_client_secret',
				],
				'twitter' => [
					'class' => 'yii\authclient\clients\Twitter',
					'attributeParams' => [
						'include_email' => 'true'
					],
					'consumerKey' => 'twitter_consumer_key',
					'consumerSecret' => 'twitter_consumer_secret',
				],
            ],
        ],
        'Paypal' => [
            
            'class'=>'frontend\components\Paypal',
          'apiUsername' => 'vshekhat-facilitator_api1.sigmasolve.net',
            'apiPassword' => '4JMGXKNFNXPY8FCA',
            'apiSignature' => 'AFcWxV21C7fd0v3bYYYRCpSSRl31AmkXJpMjImChzKCfxkr3vYNb.0LW',
            'apiLive' => true,
            //'appId' => 'APP-80W284485P519543T',

            'returnUrl' => 'http://192.168.1.250:8083/Palcura/payments/confirm', 
            'cancelUrl' => 'http://192.168.1.250:8083/Palcura/payments/cancel', 
            'currency' => 'USD',
        ],
        'twillio' => [
            'class' => 'yii\twillio\Twillio',
            'sid' 	=> 'ACbc471c6120ac2a1e4752ca04b730e449',
            'token' => 'eb231f7fba72a2d3db911a0284947497',
        ],
		'tcpdf' => [
			'class' => 'yii\tcpdf\TCPDF',
		], 
		'reCaptcha' => [
			'name' => 'reCaptcha',
			'class' => 'yii\recaptcha\ReCaptcha',
			'siteKey' => SITE_KEY,
			'secret' => SECRET_KEY,
		],
    ],
     ########ACCESS CONTROL RULES TO FORCE LOGIN STARTS###########################################################
    'as beforeRequest' => [
            'class' => \yii\filters\AccessControl::className(),
        'rules' => [
            [
             //   'site/becomesitter' => 'site/become-a-sitter',
                'actions' => [
                    'site', 'signup','thank','becomeasitter','rewards','become','signin','login', 'error', 'aboutus', 'contact','contact1', 'home',
                    'page', 'getimages', 'forgot-password', 'reset-password', 'terms-and-conditions',
                    'filter', 'authGP', 'verifyemail', 'contact-us','nearme','states','updatestates','updatecities','blog','petsitter','sitterthank'
                ],
                'allow' => true,
            ],
            [
                'allow' => true,
                'roles' => ['@'],
            ],
        ],
    ],
    'params' => $params,
];
