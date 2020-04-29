<?php

namespace backend\controllers;

use Yii;
use backend\models\page\page;
use yii\filters\AccessControl;
use backend\models\page\searchPage;
use backend\models\page\UpdatePage;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\behaviors\SluggableBehavior;



###############FOR FILE UPLOAD#################
use yii\web\UploadedFile;
use backend\models\page\ImageUpload;

/**
 * PageController implements the CRUD actions for page model.
 */
class PageController extends Controller {

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'delete', 'update', 'view','status'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all page models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new searchPage();
        $dataProvider = $searchModel->search(Yii::$app->request->get());
        $dataProvider->pagination->pageSize = (isset($_GET['p']) ? $_GET['p'] : PAGE_LIMIT);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single page model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Displays a single page model.
     * @param integer $id
     * @return mixed
     */
    public function actionSlug($id) {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new page model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new page();
        if ($model->load(Yii::$app->request->post())) {
            $slug = str_replace(' ', '-', $_POST['page']['pageName']);
            $model->pageDateCreated = date('y-m-d h:i:s');
            $model->slug = $slug;
            if ($model->save()) {
                Yii::$app->session->setFlash('item', 'Page has been created successfully!');
            }
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Update an existing page model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $data = array();
        $Pagemodel = $this->findModel($id);
        $updatePage = new UpdatePage();
        $model = $updatePage->findIdentity($id);
        
        ######################FILEUPLOAD MODEL############################
        if (strtolower($model->slug) == 'tips' ) {
            $modelImageUpload = new ImageUpload();
            $modelImageUpload->scenario = 'update-profile';
        }
        else
        {
            $modelImageUpload = '';
        }
        
        $userpost = Yii::$app->request->post('UpdatePage');
        if (isset($userpost) && !empty($userpost)) {
            if (Yii::$app->request->isAjax) {
                $Pagemodel->status = $userpost['status'];
                if ($Pagemodel->save()) {
                    Yii::$app->session->setFlash('item', $Pagemodel->status . ' status set for selected page successfully.');
                } else {
                    Yii::$app->session->setFlash('item', 'No status is set for selected page, please try again.');
                }
                die();
            }
            
            ###########################FILE UPLOAD ONLY FOR TIPS PAGE#################################################################################
            if (Yii::$app->request->isPost && strtolower($model->slug) == 'tips' ) {
               
                $modelImageUpload->imageUpload = UploadedFile::getInstance($modelImageUpload, 'imageUpload');                
                
                if ($modelImageUpload->imageUpload && $uploadedFileNameVal = $modelImageUpload->uploadfile()) {
                    $model->image = $uploadedFileNameVal;
                }
            }
            #####################################################################################################################
            
            if ($model->load(Yii::$app->request->post()) && $model->updatePage($id)) {
                //return $this->redirect(['view', 'id' => $id]);
                Yii::$app->session->setFlash('item', 'Page has been updated successfully!');
                return $this->redirect(['index']);
            } else {
                $data['respmesg'] = "Please enter valid values for all the fields.";
                $data['class'] = "alert-danger";
            }
        } else {
            $model->setAttributes($Pagemodel->getAttributes());
        }
        return $this->render('update', [
            'data' => $data,
            'model' => $model,
            'modelImageUpload' => $modelImageUpload
        ]);
    }

    /**
     * Deletes an existing page model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('item', 'Selected page deleted successully!');
        return $this->redirect(['index']);
    }

    /**
     * Finds the page model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return page the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {


        if (($model = page::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Activate an existing page model.
     * If active is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionActive($id) {

        $model = $this->findModel($id);

        //        print_r($model);
        //        print_r($_POST);
        //        exit();
        if ($model->load(Yii::$app->request->post())) {
            $model->status = 1;
            $model->save();
            return $this->redirect(['index']);
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
         $menupost = Yii::$app->request->post('update');
         if(isset($menupost) && !empty($menupost)) {
            if (Yii::$app->request->isAjax){
                $model->status = $menupost['status'];
                if($model->save()) {
                    Yii::$app->session->setFlash('item' ,'Page status has been changed successfully.');
                } else {
                    Yii::$app->session->setFlash('item', 'No status is set for selected page, please try again.');
                }
                die();
            }
        }
    }  
}
