<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use backend\models\banner\AddBannerForm;
use backend\models\banner\BannerSearch;
use backend\models\banner\UpdateBanner;
use backend\models\banner\Banner;
use common\models\Admin;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

##############= FOR FILE UPLOAD =################
use yii\web\UploadedFile;
use backend\models\banner\BannerPictureUpload;
use backend\models\banner\BannerVideoUpload;

/**
 * Banner controller
 */
class BannerController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index','create','delete','update','view','status', 'settings'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
		$searchModel  = Yii::createObject(BannerSearch::className());
        $dataProvider = $searchModel->search(Yii::$app->request->get());
		$dataProvider->pagination->pageSize = (isset($_GET['p']) ? $_GET['p'] : PAGE_LIMIT);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel'  => $searchModel,
        ]);
    }

	 /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
	return $this->render('view', [
            'model' => $this->findModel($id),
        ]);	
    }
	 
    public function actionCreate()
    {
        #####################= FILEUPLOAD MODEL =####################
        $modelBannerPictureUpload = new BannerPictureUpload();

        ######= VALIDATION RULE TO MAKE FILE UPLOAD MENDATORY =######
        $modelBannerPictureUpload->scenario = 'update-profile';

        $data = array();
        $model = new AddBannerForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            #######################= FILE UPLOAD =######################
            $model->bannerImage = '';
            if (Yii::$app->request->isPost) {
                $modelBannerPictureUpload->bannerImage = UploadedFile::getInstance($modelBannerPictureUpload, 'bannerImage');
                if ($modelBannerPictureUpload->bannerImage && $uploadedFileNameArray = $modelBannerPictureUpload->upload()) {
                    $model->bannerImage = $uploadedFileNameArray['originalImage'];
                    ####= return array('originalImage'=>$fileNameWithExtension); =####
                }
            }

            ############################################################
            if ($model->signup()) {

                Yii::$app->session->setFlash('item', 'Banner has been created successfully!');
                return $this->redirect(['index']);
            } else {
                Yii::$app->session->setFlash('item', 'Please enter valid values for all the fields!');
            }
        }
        return $this->render('signup', [
            'data' => $data,
            'model' => $model,
            'modelBannerPictureUpload' => $modelBannerPictureUpload,
        ]);
    }

    
    
    public function actionSettings()
    {
        
        $settingsVideo = \backend\models\Settings::find()->one();
        
           
        if(!empty($settingsVideo) )
        {
            $settingsModel = \backend\models\Settings::findOne($settingsVideo['setting_id']);
        }
        else
        {
            $settingsModel = new \backend\models\Settings();
        }
        #die("TESTING");
        
        #####################= FILEUPLOAD MODEL =####################
        $modelBannerVideoUpload = new BannerVideoUpload();

        ######= VALIDATION RULE TO MAKE FILE UPLOAD MENDATORY =######
        if(!empty($settingsVideo) )
        {
           $modelBannerVideoUpload->scenario = 'update-video';  
        }
        else {
            $modelBannerVideoUpload->scenario = 'update-banner';
        }
        
   
        if ($settingsModel->load(Yii::$app->request->post()) && $settingsModel->validate()) {
            #######################= FILE UPLOAD =######################
            
            if (Yii::$app->request->isPost) {
                $modelBannerVideoUpload->bannerVideo = UploadedFile::getInstance($modelBannerVideoUpload, 'bannerVideo');
                if ($modelBannerVideoUpload->bannerVideo && $uploadedFileName = $modelBannerVideoUpload->upload()) {
                    
                    $settingsModel->video = $uploadedFileName;
                    ####= return array('originalImage'=>$fileNameWithExtension); =####
                }
            }

            ############################################################
            if ($settingsModel->save() ) {
                Yii::$app->session->setFlash('item', 'Settings Saved successfully!');
                return $this->redirect(['index']);
            } else {
                Yii::$app->session->setFlash('item', 'Please enter valid values for all the fields!');
            }
        }
        return $this->render('settings', [
            'model' => $settingsModel,
            'modelBannerVideoUpload' => $modelBannerVideoUpload,
        ]);
    }
    

		    
    /**
     * Update an existing Users model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {

		#####################= FILEUPLOAD MODEL =####################
        $modelBannerPictureUpload = new BannerPictureUpload();

		
		##################= UPDATE CUSTOMER DETAILS =##################
		$data = array();
        $Bannermodel = $this->findModel($id);

        $updateBanner = new UpdateBanner();

        $model = $updateBanner->findIdentity($id);
        $userpost = Yii::$app->request->post('UpdateBanner');

        if(isset($userpost) && !empty($userpost)) {

            #######################= FILE UPLOAD =######################
				$model->bannerImage = '';
				if (Yii::$app->request->isPost) {
					$modelBannerPictureUpload->bannerImage = UploadedFile::getInstance($modelBannerPictureUpload, 'bannerImage');
					if ($modelBannerPictureUpload->bannerImage && $uploadedFileNameArray = $modelBannerPictureUpload->upload() ) {
						$model->bannerImage = $uploadedFileNameArray['originalImage'];
			####= return array('originalImage'=>$fileNameWithExtension); =####
					}
				}
			############################################################
							
			 if($model->load(Yii::$app->request->post()) && $model->updateBanner($id)) {
		
				Yii::$app->session->setFlash('item', 'Banner has been updated successfully!');
				return $this->redirect(['index']);				
			}else{
				Yii::$app->session->setFlash('item', 'Please enter valid values for all the fields!');
			}
		
		}else{ 
			$model->setAttributes($Bannermodel->getAttributes());
		}
            return $this->render('update', [
                'data' => $data,
				'model' => $model,
				'modelBannerPictureUpload' => $modelBannerPictureUpload,
            ]);
    }
    
     /**
     * Deletes an existing CrudTest model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
     
    public function actionDelete($id){
		$this->findModel($id)->delete();
		 Yii::$app->session->setFlash('item', 'Selected banner deleted successully!');
		return $this->redirect(['index']);
	}
	
	 /**
     * Finds the CrudTest model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CrudTest the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Banner::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
     /**
     * Action Status
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */    
    public function actionStatus($id) {
        $model = $this->findModel($id);
        
         $menupost = Yii::$app->request->post('Updatebanner');
         if(isset($menupost) && !empty($menupost)) {
            if (Yii::$app->request->isAjax){
                $model->status = $menupost['status'];
                if($model->save()) {
                    if($model->status == '1') { $st = 'Active'; } else { $st = 'Inactive'; }
                    Yii::$app->session->setFlash('item' ,$st.' status set for selected banner successfully.');
                } else {
                    Yii::$app->session->setFlash('item', 'No status is set for selected banner, please try again.');
                }
                die();
            }
        }
    }  
    
}
