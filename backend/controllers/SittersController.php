<?php
namespace backend\controllers;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use backend\models\sitters\AddSitter;
use backend\models\sitters\SitterSearch;
use backend\models\sitters\UpdateSitter;
use backend\models\sitters\Sitters;
use common\models\Admin;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\helpers\Html;
use yii\helpers\Url;
##############= FOR FILE UPLOAD =################
use yii\web\UploadedFile;
use backend\models\sitters\UserProfilePictureUpload;
use backend\models\sitters\Uploads;

/**
* Users controller
*/
class SittersController extends Controller {
	/**
	* @inheritdoc
	*/
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [

                    [
                        'actions' => ['index', 'create', 'delete','reset', 'update', 'view', 'updatestates', 'updatecities', 'registrations-done-report', 'verifyid','status','userverification','userverificationbadge'],
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
        $searchModel = Yii::createObject(SitterSearch::className());
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

		$documents	=	Yii::$app->commonmethod->getUserDocuments($id,ID_DOCUMENTS);		
		$images		=	Yii::$app->commonmethod->getUserDocuments($id,USER_IMAGES);
		$mDocument	=	'';
		if(isset($documents) && !empty($documents)) {
			foreach($documents as $doc) {
				if(isset($doc['name']) && !empty($doc['name'])) {
					$path = "../common/uploads/documents/".$doc['name'];
					$mDocument = $mDocument.' '.Html::a($doc['name'], [$path], ['class' => '', 'target' => '_blank']);
					
				}
			}
		} else {
			$mDocument = 'No Image available';
		}
		$model['user_docuemnts'] = $mDocument;

		$mImage		=	'';
		if(isset($images) && !empty($images)) {
			foreach($images as $img) {
				if(isset($img['name']) && !empty($img['name'])) {
					$mImage = $mImage.'   '.Html::img(SITE_URL."common/uploads/images/".$img['name'], ['alt'=>'No Image available','width' => '80px','height' => '80px']);
				}
			}
		} else {
			$mImage = 'No Image available';
		}
		$model['user_images'] = $mImage;		

        return $this->render('view', [
                    'model' => $model,
        ]);
    }

    public function actionCreate() {
        $modelImageUploadA = new UserProfilePictureUpload();
        $modelImageUploadB = new Uploads();
        $modelImageUploadC = new Uploads();        
        $modelImageUploadA->scenario = 'update-profile';
        $modelImageUploadB->scenario = 'upb';
        $modelImageUploadC->scenario = 'upc';

        $data 	= array();
        $model 	= new AddSitter();

        #####################THIS CODE IS RESPONSIBLE FOR REMOVING THE MISERABLE UX & ADDS THE NICE DATABASE VALID FIELD CHECKS###########
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return \yii\widgets\ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->profile_image 			= '';
			$model->upload_documents 		= '';
			$model->upload_images 			= '';            
			$documentsArr = array();
            if (Yii::$app->request->isPost) {
                $modelImageUploadA->profile_image = UploadedFile::getInstance($modelImageUploadA, 'profile_image');
                if ($modelImageUploadA->profile_image && $uploadedFileNameArray = $modelImageUploadA->upload()) {
                    $model->profile_image = $uploadedFileNameArray['originalImage'];
                }

				$modelImageUploadB->upload_documents = UploadedFile::getInstances($modelImageUploadB, 'upload_documents');
				if ($modelImageUploadB->upload_documents && $uploadedFileNameArray = $modelImageUploadB->uploadB() ) {
					$documentsArr['upload_documents'] = $uploadedFileNameArray['originalImage'];
				}
				
				$modelImageUploadC->upload_images = UploadedFile::getInstances($modelImageUploadC, 'upload_images');
				if ($modelImageUploadC->upload_images && $uploadedFileNameArray = $modelImageUploadC->uploadC() ) {
					$documentsArr['upload_images'] = $uploadedFileNameArray['originalImage'];
				}
            }

            if ($model->savedata($documentsArr)) {
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
            'modelImageUpload' => array('modelImgA'=>$modelImageUploadA,'modelImgB'=>$modelImageUploadB,'modelImgC'=>$modelImageUploadC),
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

    public function verifiedAccountEmail($user) {
		$adminEmail = Yii::$app->commonmethod->getAdminEmailID();
		$sitter   = Sitters::findOne($user);
		$name     = (isset($sitter->firstname) ? $sitter->firstname : '');
		$subject  = "Welcome to PalCura!";
		$message  = '';
		$message .='<tr>';
			$message .='<td height="5" style="font-size:13px; color:#656565; line-height:5px; padding-bottom:10px;">Hi '.$name.',</td>';
		$message .='</tr>';
		$message .='<tr>';
			$message .='<td height="5"></td>';
		$message .='</tr>';
		$message .='<tr>';
			$message .='<td style="font-size:13px; color:#656565; line-height:18px; padding-bottom:10px;">We are stoked to have you as a part of PalCura! We will notify you once you get a booking request or message.</td>';
		$message .='</tr>';
		$message .='<tr>';
			$message .='<td height="5"></td>';
		$message .='</tr>';
		$message .='<tr>';
			$message .='<td style="font-size:13px; color:#656565; line-height:18px; padding-bottom:10px;">Feel free to share the love and spread the message.</td>';
		$message .='</tr>';
		$message .='<tr>';
			$message .='<td height="5"></td>';
		$message .='</tr>';
		$message .='<tr>';
			$message .='<td style="font-size:13px; color:#656565; line-height:18px; padding-bottom:10px;">Thank you and welcome aboard!.</td>';
		$message .='</tr>';

		$message .='<tr>';
			$message .='<td height="15"></td>';
		$message .='</tr>';
		$mail = Yii::$app->mailer->compose(['html' => 'layouts/mail'],['content' => $message, 'subject' => $subject])
		->setFrom([$adminEmail => 'Palcura' ])
		->setTo($sitter->email)
		->setSubject($subject)
		->send();
		$mail;
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
        $modelImageUploadA = new UserProfilePictureUpload();
        $modelImageUploadB = new Uploads();
        $modelImageUploadC = new Uploads();        
        $modelImageUploadA->scenario = 'update-profile';
        $modelImageUploadB->scenario = 'upb';
        $modelImageUploadC->scenario = 'upc';

        $data 		= array();
        $Usersmodel = $this->findModel($id);
        $updateUser = new UpdateSitter();
        $model 		= $updateUser->findIdentity($id);
        $userpost 	= Yii::$app->request->post('UpdateSitter');

        #####################THIS CODE IS RESPONSIBLE FOR REMOVING THE MISERABLE UX & ADDS THE NICE DATABASE VALID FIELD CHECKS###########
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return \yii\widgets\ActiveForm::validate($model);
        }
        			
        if (isset($userpost) && !empty($userpost)) {
            $model->profile_image 			= '';
			$model->upload_documents 		= '';
			$model->upload_images 			= '';
			$documentsArr = array();
            if (Yii::$app->request->isPost) {
				#### profile image
                $modelImageUploadA->profile_image = UploadedFile::getInstance($modelImageUploadA, 'profile_image');
                if ($modelImageUploadA->profile_image && $uploadedFileNameArray = $modelImageUploadA->upload()) {
                    $model->profile_image = $uploadedFileNameArray['originalImage'];
                }
                #### user documents
				$modelImageUploadB->upload_documents = UploadedFile::getInstances($modelImageUploadB, 'upload_documents');
				if ($modelImageUploadB->upload_documents && $uploadedFileNameArray = $modelImageUploadB->uploadB() ) {
					$documentsArr['upload_documents'] = $uploadedFileNameArray['originalImage'];
				}
				#### user images
				$modelImageUploadC->upload_images = UploadedFile::getInstances($modelImageUploadC, 'upload_images');
				if ($modelImageUploadC->upload_images && $uploadedFileNameArray = $modelImageUploadC->uploadC() ) {
					$documentsArr['upload_images'] = $uploadedFileNameArray['originalImage'];
				}
            }

            if ($model->load(Yii::$app->request->post()) && $model->updatedata($id,$documentsArr)) {
                Yii::$app->session->setFlash('item', 'User has been updated successfully!');
                return $this->redirect(['index']);
            } else {
                Yii::$app->session->setFlash('item', 'User has not been updated successfully!');
            }
        } else {
             $model->setAttributes($Usersmodel->getAttributes());
             $model->dob = date('d/m/Y', strtotime($Usersmodel->dob)); 
        }

        return $this->render('update', [
            'data' 	=> $data,
            'model' => $model,
            'modelImageUpload' => array('modelImgA'=>$modelImageUploadA,'modelImgB'=>$modelImageUploadB,'modelImgC'=>$modelImageUploadC),
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
        Yii::$app->session->setFlash('item', 'Selected user deleted successully!');
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
        if (($model = Sitters::findOne($id)) !== null) {
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
         $postArr = Yii::$app->request->post('update');
         if(isset($postArr) && !empty($postArr)) {
            if (Yii::$app->request->isAjax){
				$selectColA = "verified_by_admin";
				$userInfoA	= Yii::$app->commonmethod->getUserColumnsData($id,$selectColA);
				if(isset($userInfoA['userInfoA']) && $userInfoA['userInfoA'] == ACTIVE) {				
					$model->status = $postArr['status'];
					if($model->save()) {
						Yii::$app->session->setFlash('item' ,'User status has been changed successfully.');
					} else {
						Yii::$app->session->setFlash('item', 'No status is set for selected images, please try again.');
					}
				} else {
					Yii::$app->session->setFlash('item' ,'Once verified user(s) account to change the status.');
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
    public function actionUserverification($id) {
         $model = $this->findModel($id);
         $postArr = Yii::$app->request->post('update');
         if(isset($postArr) && !empty($postArr)) {
            if (Yii::$app->request->isAjax){
                $model->verified_by_admin 	= $postArr['verified_by_admin'];
                $st = "rejected";
                if($postArr['verified_by_admin'] == ACTIVE) { 
					$model->status 				= ACTIVE;
					$model->verification_badge  = 1;
					$st = "verified";
				} else {
					$model->status 				= INACTIVE;
					$model->verification_badge  = 0;				
				}
                if($model->save()) {
					if($st == 'verified') { 
						$this->verifiedAccountEmail($id);	
					}
                    Yii::$app->session->setFlash('item' ,'Sitter(s) profile has been '.$st.' successfully.');
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
                //~ if($postArr['verification_badge'] == 1) {
					//~ $model->verified_by_admin = '1';
				//~ } else {
					//~ $model->verified_by_admin = '2';
				//~ }
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
        
}
