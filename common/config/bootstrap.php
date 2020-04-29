<?php
Yii::setAlias('@common', 	dirname(__DIR__));
Yii::setAlias('@frontend', 	dirname(dirname(__DIR__)) . '/frontend');
Yii::setAlias('@backend', 	dirname(dirname(__DIR__)) . '/backend');
Yii::setAlias('@console', 	dirname(dirname(__DIR__)) . '/console');
Yii::setAlias('@basepath',  'http://' . $_SERVER['HTTP_HOST'] . '/');
Yii::setAlias('@siteimage', 'http://' . $_SERVER['HTTP_HOST'] . '/Palcura/frontend/web/themes/palcura/images/');
Yii::setAlias('@site_title','palcura');
#####################################= DEFINE CONSTANT =#####################################
define('SITE_URL', 					'http://' . $_SERVER['HTTP_HOST'] . '/Palcura/');
define('NO_IMAGE', 					SITE_URL."common/images/noimage.png");
define('NOIMAGE107x114', 			SITE_URL."common/images/Noimage107x114.png&text=NO+IMAGE");
define('NO_DISPLAY_IMAGE', 			SITE_URL."common/images/Noimage107x114.png&text=NO+IMAGE");
define('UPLOAD_IMAGE', 				SITE_URL."common/uploads/images/");
define('dummy_image_male', 			SITE_URL."common/images/dummy_male.jpg");
define('dummy_image_female', 		SITE_URL."common/images/face-placeholder-woman.png");
define('SITE_LOGO', 				SITE_URL."common/images/logo.png");
define('PROFILE_IMAGE_PATH', 		SITE_URL."common/uploads/profile/");
define('DOCUMENT_DOWNLOAD_PATH', 	SITE_URL."common/uploads/documents/");
define('BANNER_IMAGE_PATH', 		SITE_URL."common/uploads/banner/");
define('CONTENT_IMAGE_PATH', 		SITE_URL."common/uploads/contentimage/");
define('USER_DOCUMENT_PATH', 		SITE_URL."common/uploads/documents/");
define('WEBSITE_IMAGES_PATH', 		SITE_URL."frontend/web/themes/palcura/images/");
define('WEBSITE_JS_PATH', 		SITE_URL."frontend/web/themes/palcura/js/");
define('WEBSITE_CSS_PATH', 			SITE_URL."frontend/web/themes/palcura/css/");
define('BOOKING_IMAGES', 			SITE_URL."common/uploads/bookings/");
defined('YII_BASEURL')  		or  define('YII_BASEURL', 'http://' . $_SERVER['HTTP_HOST'].'/Palcura/');
defined('ADMIN_EMAIL_ADDRESS')  or  define('ADMIN_EMAIL_ADDRESS', 'testadmin@testmail.com');
defined('CURRENCY_SIGN') 		or define('CURRENCY_SIGN',   '$');
defined('CURRENCY_CODE') 		or define('CURRENCY_CODE',   '&#036;');
defined('CURRENCY_NAME') 		or define('CURRENCY_NAME',   'USD');
defined('SITE_KEY') 		or define('SITE_KEY',   '6LdabEoUAAAAAPvBRfeW5FFluI8ZM1kg90_ba1Oh');
defined('SECRET_KEY') 		or define('SECRET_KEY',   '6LdabEoUAAAAACLOYMD_BfBms8JUkMbHXvswjKeU');
#########################= User Types =############################
defined('OWNER') 		or define('OWNER', '1');
defined('SITTER') 		or define('SITTER', '2');
defined('RENTER') 		or define('RENTER', '3');
defined('BORROWER') 	or define('BORROWER', '3');
defined('OWNER_SITTER') or define('OWNER_SITTER', '4');
defined('BORROWER_OWNER') or define('BORROWER_OWNER', '5'); //do not consider yet
defined('BORROWER_SITTER') or define('BORROWER_SITTER', '5');

defined('OWNER_BORROWER') 	or define('OWNER_BORROWER', '6');
defined('ALL_PROFILES') 	or define('ALL_PROFILES', '7');

defined('NO_RESULT') or define('NO_RESULT', 'Reserve/accept a service to see some activity');
defined('MALE') 		or define('MALE', 'Male');
defined('FEMALE') 		or define('FEMALE', 'Female');

########################= Social Sites =###########################
defined('GPLUS') 		or define('GPLUS', 'GPLUS');
defined('FACEBOOK') 	or define('FACEBOOK', 'FB');
defined('EMAIL') 		or define('EMAIL', 'EMAIL');

########################= User Status (Active,Inactive,Pending) =###########################
defined('PENDING') 		or define('PENDING',  '0');
defined('ACTIVE') 		or define('ACTIVE',   '1');
defined('INACTIVE') 	or define('INACTIVE', '2');
defined('REMOVE') 		or define('REMOVE',   '1');

#######################= Verify user by admin =#######################
defined('VERIFIED') 	or define('VERIFIED', '1');
defined('REJECTED')		or define('REJECTED', '2');
defined('ID_DOCUMENTS')	or define('ID_DOCUMENTS', '1');
defined('USER_IMAGES')	or define('USER_IMAGES', '2');
defined('HOME_IMAGES')	or define('HOME_IMAGES', '3');

defined('FORM_SUGGESTIONS')	or define('FORM_SUGGESTIONS', 	'1');
defined('FORM_CONCERNS')	or define('FORM_CONCERNS', 		'2');
defined('FORM_EXPERIENCE')	or define('FORM_EXPERIENCE', 	'3');
defined('FORM_QUESTIONS')	or define('FORM_QUESTIONS', 	'4');

#######################= limits =#######################
defined('LIMIT_USER_DOCUMENTS')	or define('LIMIT_USER_DOCUMENTS', '3');
defined('LIMIT_USER_IMAGES')	or define('LIMIT_USER_IMAGES', '4');
defined('LIMIT_HOME_IMAGES')	or define('LIMIT_HOME_IMAGES', '2');
defined('LIMIT') 		or define('LIMIT', 10);
defined('PAGE_LIMIT') 	or define('PAGE_LIMIT', 5);

defined('MESSAGE_DATE_FORMAT') 	or define('MESSAGE_DATE_FORMAT',"d M, Y");
defined('DATETIME_FORMAT') 		or define('DATETIME_FORMAT',"Y-m-d H:i:s");
defined('ADMIN_DATE_L') 		or define('ADMIN_DATE_L',"date");
defined('ADMIN_DATE_FORMAT_L') 	or define('ADMIN_DATE_FORMAT_L','php:Y-m-d');

//defined('DATEPICKER_FORMAT_PHP') 	or define('DATEPICKER_FORMAT_PHP',"Y-m-d");
//defined('DATEPICKER_FORMAT_JS') 	or define('DATEPICKER_FORMAT_JS',"yy-mm-dd");

defined('DATEPICKER_FORMAT_PHPCM') 	or define('DATEPICKER_FORMAT_PHPCM',"Y-m-d");
defined('DATEPICKER_FORMAT_JSCM') 	or define('DATEPICKER_FORMAT_JSCM',"yy-mm-dd");
defined('DATEPICKER_FORMAT_PHP') 	or define('DATEPICKER_FORMAT_PHP',"m/d/y");
defined('DATEPICKER_FORMAT_JS') 	or define('DATEPICKER_FORMAT_JS',"mm/dd/yy");

defined('ADD_DAYS_PHP') 	or define('ADD_DAYS_PHP'," +0 days");
defined('ADD_DAYS_JS') 	or define('ADD_DAYS_JS',"+0D");

defined('GOOGLE_MAP_KEY') 	or define('GOOGLE_MAP_KEY',"AIzaSyDhxj1eT2udQsZgkkqkCSkTkAOGv-yiu-Q");
//defined('GOOGLE_MAP_KEY') 	or define('GOOGLE_MAP_KEY',"AIzaSyAhB5Iol_TXEflDtpVcOIuFqlgNDnXHR3w");
defined('USPS_USERNAME') 	or define('USPS_USERNAME',"573PALCU2646");

defined('PAYPAL_METHOD')or define('PAYPAL_METHOD', 'PAYPAL');
defined('BANK_TRANSFER')or define('BANK_TRANSFER', 'BANK');
