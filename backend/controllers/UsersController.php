<?php
namespace backend\controllers;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use backend\models\users\AddUser;
use backend\models\users\UserSearch;
use backend\models\users\UpdateUser;
use backend\models\users\Users;
use common\models\Admin;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

##############= FOR FILE UPLOAD =################
use yii\web\UploadedFile;
use backend\models\users\UserProfilePictureUpload;
use backend\models\users\UserIdDocumentUpload;

/**
 * Users controller
 */
class UsersController extends Controller {
    /**
    * @inheritdoc
    */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [

                    [
                        'actions' => ['index', 'create', 'delete', 'update', 'view', 'updatestates', 'updatecities', 'registrations-done-report', 'verifyid','status'],
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
    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex() { 
		if(Yii::$app->user->identity->admin_type == '2') { return $this->redirect(['/partners/']); }
        $searchModel = Yii::createObject(UserSearch::className());
        $dataProvider = $searchModel->search(Yii::$app->request->get());
        $dataProvider->pagination->pageSize = (isset($_GET['p']) ? $_GET['p'] : PAGE_LIMIT);
        return $this->render('index', [
                    'dataProvider' => $dataProvider,
                    'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        ########################= VIEW MEMBER DETAIL =######################	
        $model = $this->findModel($id);
        if (!$model)
            return $this->redirect(['index']);        #####################= FILEUPLOAD MODEL =####################
        $modelUserProfilePictureUpload = new UserProfilePictureUpload();
        $modelUserIdDocumentUpload = new UserIdDocumentUpload();

        ############# Date of Birth ##############
        if (isset($model['dob'])) {
            $model['dob'] = date('d.m.Y', strtotime($model['dob']));
        }

        return $this->render('view', [
                    'model' => $model,
        ]);
    }

    public function actionCreate() {
        $modelImageUpload = new UserProfilePictureUpload();
        $modelImageUpload->scenario = 'update-profile';

        $data = array();
        $model = new AddUser();
		$model->user_type = '1';
        #####################THIS CODE IS RESPONSIBLE FOR REMOVING THE MISERABLE UX & ADDS THE NICE DATABASE VALID FIELD CHECKS###########
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return \yii\widgets\ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->profile_image = '';
            ####################= upload profile image =###################
            if (Yii::$app->request->isPost) {
                $modelImageUpload->profile_image = UploadedFile::getInstance($modelImageUpload, 'profile_image');
                if ($modelImageUpload->profile_image && $uploadedFileNameArray = $modelImageUpload->upload()) {
                    $model->profile_image = $uploadedFileNameArray['originalImage'];
                }
            }
            
            $userDetail = Yii::$app->request->post('AddUser');
            $userDetail['profile_image'] = $model->profile_image;
            if ($model->signup($userDetail)) {
                $res = $this->_sendUserRegistrationEmail(Yii::$app->request->post('AddUser'));
                Yii::$app->session->setFlash('item', 'User has been created successfully!');
                return $this->redirect(['index']);
            } else {
                Yii::$app->session->setFlash('item', 'Please enter valid values for all the fields!');
            }
        }

        return $this->render('create', [
            'data' => $data,
            'model' => $model,
            'modelImageUpload' => $modelImageUpload
        ]);
    }

    /**
     * Displays a single User model.
     * @param array $postArr
     * @return mixed
     */
    private function _sendUserRegistrationEmail($postArr) {

        #############################= Send User Registration Email =##########################
        $fromEmail = $this->getAdminEmailID();
        $subject = "Congratulation! You Account has been successfully created.";
        $message = '';
        $message .='<tr>';
        $message .='<td height="26" style="font-size:15px; font-weight:500; color:#2c1f14;  ">Dear ' . @$postArr['firstname'] . ',</td>';
        $message .='</tr>';
        $message .='<tr>';
        $message .='<td style="font-size:13px; color:#2c1f14; line-height:18px; padding-bottom:10px;">Congratulations! You are successfully registered. Please login with your valid credentials in order to access the system. Below are login details:</td>';
        $message .='</tr>';
        $message .='<tr>';
        $message .='<td height="5"></td>';
        $message .='</tr>';
        $message .='<tr>';
        $message .='<td align="left">';
        $message .='<table width="287" border="0" bgcolor="#2c1f14" cellspacing="1" cellpadding="6" style=" color:#2c1f14;">';
        $message .='<tr  bgcolor="#2c1f14">';
        $message .='<td colspan="2" style="border-top:#203367 solid 0px; font-size:14px; font-weight:400; color:#ffffff; text-transform:capitalize;   padding:8px;">Login details</td>';
        $message .='</tr>';
        $message .='<tr  bgcolor="#ffffff">';
        $message .='<td width="100" >Email</td>';
        $message .='<td width="270" >' . @$postArr['email'] . '</td>';
        $message .='</tr>';
        $message .='<tr  bgcolor="#ffffff">';
        $message .='<td>Password</td>';
        $message .='<td >' . @$postArr['password'] . '</td>';
        $message .='</tr>';
        $message .='</table>';
        $message .='</td>';
        $message .='</tr>';
        $message .='<tr>';
        $message .='<td height="15"></td>';
        $message .='</tr>';

        $mail = Yii::$app->mailer->compose(['html' => 'layouts/mail'], ['content' => $message, 'subject' => $subject])
                ->setFrom($fromEmail)
                ->setTo($postArr['email'])
                ->setSubject($subject)
                ->send();
        return $mail;
        ###########################################################################################
    }

    /**
     * get Admin Email
     * @param 
     * @return string
     */
    public function getAdminEmailID() {
        $modelLink = new Admin();
        $AdminEmail = $modelLink->getAdminEmail();
        if (isset($AdminEmail['1']) && !empty($AdminEmail['1'])) {
            $fromEmail = $AdminEmail['1'];
        } else {
            $fromEmail = 'testerdept@gmail.com';
        }
        return $fromEmail;
    }

    /**
     * Update an existing Users model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $modelImageUpload = new UserProfilePictureUpload();
        $modelImageUpload->scenario = 'update-profile';


        $data = array();
        $Usersmodel = $this->findModel($id);
        $updateUser = new UpdateUser();
        $model = $updateUser->findIdentity($id);
        $userpost = Yii::$app->request->post('UpdateUser');

        #####################THIS CODE IS RESPONSIBLE FOR REMOVING THE MISERABLE UX & ADDS THE NICE DATABASE VALID FIELD CHECKS###########
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return \yii\widgets\ActiveForm::validate($model);
        }
        
        if (isset($userpost) && !empty($userpost)) {
            $model->profile_image = '';
            if (Yii::$app->request->isPost) {
                $modelImageUpload->profile_image = UploadedFile::getInstance($modelImageUpload, 'profile_image');
                if ($modelImageUpload->profile_image && $uploadedFileNameArray = $modelImageUpload->upload()) {
                    $model->profile_image = $uploadedFileNameArray['originalImage'];
                }
            }

            if ($model->load(Yii::$app->request->post()) && $model->updateUser($id)) {
                Yii::$app->session->setFlash('item', 'User has been updated successfully!');
                return $this->redirect(['index']);
            } else {
                Yii::$app->session->setFlash('item', 'User has not been updated successfully!');
            }
        } else {
             $model->setAttributes($Usersmodel->getAttributes());
             $model->dob = date('d/m/Y', strtotime($Usersmodel->dob) ); 
        }
                
        return $this->render('update', [
            'data' => $data,
            'model' => $model,
            'modelImageUpload' => $modelImageUpload,
        ]);
    }

    /**
     * Deletes an existing CrudTest model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('item', 'Selected user deleted successully!');
        return $this->redirect(['index']);
    }

    /**
     * Finds the CrudTest model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CrudTest the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Users::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    /**
     * Get States based by country id
     * @return JSON
     */
    public function actionUpdatestates() {
        $countryID = Yii::$app->request->post('id');
        return Yii::$app->commonmethod->updateStates($countryID);
    }

    /**
     * Get City based on State id
     * @return JSON
     */
    public function actionUpdatecities() {
        $stateID = Yii::$app->request->post('id');
        return Yii::$app->commonmethod->updateCities($stateID);
    }

    /**
    * Action Status
    * If deletion is successful, the browser will be redirected to the 'index' page.
    * @param integer $id
    * @return mixed
    */
    public function actionStatus($id) {
         $model = $this->findModel($id);
         $postArr = Yii::$app->request->post('update');
         if(isset($postArr) && !empty($postArr)) {
            if (Yii::$app->request->isAjax){
                $model->status = $postArr['status'];
                if($model->save()) {
                    Yii::$app->session->setFlash('item' ,'User status has been changed successfully.');
                } else {
                    Yii::$app->session->setFlash('item', 'No status is set for selected images, please try again.');
                }
                die();
            }
        }
    }
    
}
