<?php
namespace frontend\controllers; 

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Controller;
use yii\helpers\Url;
use frontend\models\Banner;
use common\models\Admin;
use yii\db\Query;
class BannerController extends Controller {
    private $limit = 10;
	public function beforeAction($action) { 
		return true;
	}  
}	
