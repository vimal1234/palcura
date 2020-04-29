<?php
namespace backend\controllers;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use backend\models\owners\AddOwner;
use backend\models\owners\OwnerSearch;
use backend\models\owners\UpdateOwner;
use backend\models\owners\Owners;
use common\models\Admin;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\helpers\Html;

##############= FOR FILE UPLOAD =################
use yii\web\UploadedFile;
use backend\models\users\UserProfilePictureUpload;
use backend\models\users\UserIdDocumentUpload;

/**
* Owners controller
*/
class OwnersController extends Controller {
	/**
	* @inheritdoc
	*/
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'delete','reset', 'update', 'view','status','userverification','userverificationbadge'],
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
        $searchModel = Yii::createObject(OwnerSearch::className());
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
        $model = $this->findModel($id);
        if (!$model)
            return $this->redirect(['index']);

        if (isset($model['dob'])) {
            $model['dob'] = date('d-m-Y', strtotime($model['dob']));
        }
        
        if($model['house_size'] == 0) {
			$adults = 0;
		} else if($model['house_size'] == 1) {
			$adults = $model['house_size'].' adult';
		} else {
			$adults = $model['house_size'].' adults';
		}
		$model['house_size'] 	= $adults;
		$model['profile_image'] = Html::img(PROFILE_IMAGE_PATH.$model['profile_image'], ['alt'=>'No Image available','width' => '80px','height' => '80px']);
        return $this->render('view', [
                    'model' => $model,
        ]);
    }

    public function actionCreate() {
        $modelImageUpload = new UserProfilePictureUpload();
        $modelImageUpload->scenario = 'update-profile';

        $data = array();
        $model = new AddOwner();
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
                $res = $this->_sendUserRegistrationEmail(Yii::$app->request->post('AddOwner'));
                Yii::$app->session->setFlash('item', 'Pet Owner has been created successfully!');
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
        $message .='<td height="5" style="font-size:13px; color:#2c1f14; line-height:5px; padding-bottom:10px;">Hi ' . @$postArr['firstname'] . ',</td>';
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
        $updateUser = new UpdateOwner();
        $model = $updateUser->findIdentity($id);
        $userpost = Yii::$app->request->post('UpdateOwner');

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
                Yii::$app->session->setFlash('item', 'Pet Owner has been updated successfully!');
                return $this->redirect(['index']);
            } else {
                Yii::$app->session->setFlash('item', 'Pet Owner has not been updated successfully!');
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
        //$this->findModel($id)->delete();
		$model = $this->findModel($id);
        $model->delete_status = REMOVE;
        $model->save();              
        Yii::$app->session->setFlash('item', 'Selected owner deleted successully!');
        return $this->redirect(['index']);
    }
    
    public function actionReset($id) {
        //$this->findModel($id)->delete();
		$model = $this->findModel($id);
	
        $model->delete_status = 0;
        if($model->save()){  
        $firstname = $model->firstname;
        $useremail = $model->email;
        //get user name and email  
        $this->_sendUserResetAccountEmail($firstname,$useremail);    
        Yii::$app->session->setFlash('item', 'Selected user account reset successully!');
        }else{
        Yii::$app->session->setFlash('item', 'There was some problem to reset account!');
        }
        return $this->redirect(['index']);
    }
    
    /**
	* Displays a single User model.
	* @param array $postArr
	* @return mixed
	*/
    private function _sendUserResetAccountEmail($firstname,$useremail) {

        #############################= Send User Registration Email =##########################
        $fromEmail = $this->getAdminEmailID();
        $subject = "Congratulation! Your Account have been reactivated.";
        $message = '';
        $message .='<tr>';
        $message .='<td height="5" style="font-size:13px; color:#2c1f14; line-height:5px; padding-bottom:10px;">Hi ' . @$firstname . ',</td>';
        $message .='</tr>';
        $message .='<tr>';
        $message .='<td style="font-size:13px; color:#2c1f14; line-height:18px; padding-bottom:10px;">Congratulations! your palcura account have been reactivated. </td>';
        $message .='</tr>';
        /*$message .='<tr>';
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
        $message .='</tr>';*/
        $message .='<tr>';
        $message .='<td height="15"></td>';
        $message .='</tr>';

        $mail = Yii::$app->mailer->compose(['html' => 'layouts/mail'], ['content' => $message, 'subject' => $subject])
                ->setFrom([$fromEmail=>'Palcura'])
                ->setTo($useremail)
                ->setSubject($subject)
                ->send();
        return $mail;
        ###########################################################################################
    }


	/**
	* Finds the CrudTest model based on its primary key value.
	* If the model is not found, a 404 HTTP exception will be thrown.
	* @param integer $id
	* @return CrudTest the loaded model
	* @throws NotFoundHttpException if the model cannot be found
	*/
    protected function findModel($id) {
        if (($model = Owners::findOne($id)) !== null) {
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
    public function actionUserverification($id) {
         $model = $this->findModel($id);
         $postArr = Yii::$app->request->post('update');
         if(isset($postArr) && !empty($postArr)) {
            if (Yii::$app->request->isAjax){
                $model->verified_by_admin = $postArr['verified_by_admin'];
                $st = "rejected";
                if($postArr['verified_by_admin'] == ACTIVE) { 
					$st = "verified";
				}
                if($model->save()) {
                    Yii::$app->session->setFlash('item' ,'Owner(s) profile has been '.$st.' successfully.');
                } else {
                    Yii::$app->session->setFlash('item', 'No status is set for selected images, please try again.');
                }
                die();
            }
        }
    }

	/**
	* Action Status
	* If deletion is successful, the browser will be redirected to the 'index' page.
	* @param integer $id
	* @return mixed
	*/
    public function actionUserverificationbadge($id) {
         $model = $this->findModel($id);
         $postArr = Yii::$app->request->post('update');
         if(isset($postArr) && !empty($postArr)) {
            if (Yii::$app->request->isAjax){
                $model->verification_badge = $postArr['verification_badge'];
                $st = "removed ";
                if($postArr['verification_badge'] == ACTIVE) { 
					$st = "added";
				}
                if($model->save()) {
                    Yii::$app->session->setFlash('item' ,'Verification badge has been '.$st.' successfully.');
                } else {
                    Yii::$app->session->setFlash('item', 'No status is set for selected images, please try again.');
                }
                die();
            }
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
         $postArr = Yii::$app->request->post('update');
         if(isset($postArr) && !empty($postArr)) {
            if (Yii::$app->request->isAjax){
                $model->status = $postArr['status'];
                if($model->save()) {
                    Yii::$app->session->setFlash('item' ,'Owner status has been changed successfully.');
                } else {
                    Yii::$app->session->setFlash('item', 'No status is set for selected images, please try again.');
                }
                die();
            }
        }
    }
}
