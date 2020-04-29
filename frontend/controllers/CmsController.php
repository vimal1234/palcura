<?php

namespace frontend\controllers;

use yii;
use frontend\models\Cms;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
// use frontend\models\users\AddUserForm;
use frontend\models\BannerImages;
use common\models\Admin;
use yii\helpers\Url;
use yii\web\ErrorAction;
use yii\web\Response;

/**
 * Cms controller
 */
class CmsController extends Controller {
    /**
     * @inheritdoc
     */
    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }
    
    /**
     * gets the content for a cms page by its slug which is unique
     * @param string $id url slug
     */
    public function actionPage($slug=null) 
    { 
        if($slug==null){
        $slugp = $_GET['id'];
        return $this->redirect(['cms/page','slug' => $slugp]);
        }
        $pageContent = \backend\models\page\page::find()
                ->where(['slug'=>$slug])
                ->one();      
        
        if(!empty($pageContent) )
        {
        $p_content = str_ireplace('../../..//admin/', SITE_URL.'admin/', $pageContent->pageContent);
        $p_content = str_ireplace('../../admin/', SITE_URL.'admin/', $pageContent->pageContent);

        $p_content = str_ireplace('../../..//', SITE_URL.'admin/', $pageContent->pageContent);
        $p_content = str_ireplace('../../', SITE_URL.'admin/', $pageContent->pageContent);
            return $this->render('page', [
                    'pageContent' => $p_content, 
                    'pageTitle'=>$pageContent->pageName,
                    //'pageTitle'=>$pageContent->pageTitle,
                    'image'=>$pageContent->image
                ]
            );
        }
        else
        {
            throw new \yii\web\NotFoundHttpException();
        }
    } 
 
    /**
     * gets the content images
     * @param N/A
     */    
    public function actionGetimages() {
        
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = new BannerImages(); 
        
        return $data = $model->getBannerImage();
    }
   
}
