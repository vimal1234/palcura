<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use backend\models\coupon\CouponSearch;
use backend\models\coupon\Coupon;
use backend\models\coupon\AddCoupon;
use backend\models\coupon\UpdateCoupon;
use common\models\Admin;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use common\models\User;

/**
 * Feedback controller
 */
class CouponController extends Controller
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
                        'actions' => ['index','create','delete','update','view','status'],
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
		$searchModel  = Yii::createObject(CouponSearch::className());
        $dataProvider = $searchModel->search(Yii::$app->request->get());
		$dataProvider->pagination->pageSize = (isset($_GET['p']) ? $_GET['p'] : PAGE_LIMIT);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel'  => $searchModel,
        ]);
    }

	/**
    * Displays a single Service model.
    * @param integer $id
    * @return mixed
    */
    public function actionView($id) {
		$data 			 				= $this->findModel($id);
		return $this->render('view', [
            'model' => $data,
        ]);
    }
    
    public function actionCreate() {
      
        $data = array();
        $model = new AddCoupon();
		 $usermodel = new User();   
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
        $model->addcoupon();
        $ownerlist = $model->owner_list;
        //send coupon emails to selected owner  users
		    foreach($ownerlist as $key=>$val){
		    $couponuserid = $val;
		    $userinfno =  $usermodel->findIdentity($couponuserid);
		    $dataarray = array(
		    'username' => $userinfno['firstname'].' '.$userinfno['lastname'],
		    'email'	   => $userinfno['email'],
		    'name'	   => $model->coupon_name,
		    'code'	   => $model->coupon_code,	
		    'description' => $model->coupon_description,
		    'validupto'   => $model->coupon_valid_date		   
		    );
		    $this->_sendCouponEmail($dataarray);	
		    }
		     $renterlist = $model->renter_list;
		  	//send coupon emails to selected renter list users
		  	if(!empty($renterlist)){
		    foreach($renterlist as $key=>$val){
		    $couponuserid = $val;
		    $userinfno =  $usermodel->findIdentity($couponuserid);
		    $dataarray = array(
		    'username' => $userinfno['firstname'].' '.$userinfno['lastname'],
		    'email'	   => $userinfno['email'],
		  	'name'	   => $model->coupon_name,
		    'code'	   => $model->coupon_code,	
		    'description' => $model->coupon_description,
		    'validupto'   => $model->coupon_valid_date			   	
		    );
		    $this->_sendCouponEmail($dataarray);
		    }
		    }  
       
         Yii::$app->session->setFlash('item' ,'Coupon has been added successfully.');
         return $this->redirect(['coupon/index']); 
        }

        return $this->render('create', [
            'data' => $data,
            'model' => $model,           
        ]);
    }
    
    
     /**
     * Displays a single User model.
     * @param array $postArr
     * @return mixed
     */
    private function _sendCouponEmail($postArr) {

        #############################= Send User Registration Email =##########################
        $fromEmail = $this->getAdminEmailID();
        $subject = "Congratulations! You have received a coupon from ".$postArr['name']."";
        $message = '';
       
		$message .= '<tr>
                            <td height="26" style="font-size:15px; font-weight:600; color:#2c2c2c; ">Owners deserve a lot more and points are good, but not enough.</td>
                          </tr>
                           <tr>
                            <td style="font-size:13px; color:#656565; line-height:18px; padding-bottom:10px;">Being Pet owners ourselves, we understand!</td>
                          </tr
                          <tr>
                            <td style="font-size:13px; color:#656565; line-height:18px; padding-bottom:10px;">Below are details of a coupon from one of our amazing partners! Use it before it expires.</td>
                          </tr>
                          <tr>
                            <td height="5"></td>
                          </tr>
                          <tr>
                            <td align="left">
								<ul style="background-color: #fff3e3; color:#000000; font-size:15px; list-style:none; margin:0; padding:0; font-weight:600; padding: 10px 20px; letter-spacing: 0.3px;">
									<li>Coupon code: <span style="font-weight:400; line-height:30px;">' . @$postArr['code'] . '</span></li>
									<li>Discount details: <span style="font-weight:400; line-height:30px;">' . @$postArr['description'] . '</span></li>
									<li>Website: <span style="font-style:italic; font-weight:400; line-height:30px;">'. @$postArr['name'] .'</span></li>
									<li>Expiration: <span style="font-weight:400; line-height:30px;">'.@$postArr['validupto'].'</span></li>
								</ul>
							</td>
                          </tr>
                          <tr>
                            <td height="15"></td>
                          </tr>';
        $mail = Yii::$app->mailer->compose(['html' => 'layouts/mail'], ['content' => $message, 'subject' => $subject])
                ->setFrom([$fromEmail=>'Palcura'])
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
    /*public function actionUpdate($id) {
    
        $data = array();
        $Couponmodel = $this->findModel($id);
        $updateCoupon = new UpdateCoupon();
     	$model = $updateCoupon->findIdentity($id);
        $userpost = Yii::$app->request->post('UpdateCoupon');

        if (isset($userpost) && !empty($userpost)) {
           
            if ($model->load(Yii::$app->request->post()) && $model->updateCoupon($id)) {
                Yii::$app->session->setFlash('item', 'User has been updated successfully!');
                return $this->redirect(['index']);
            } else {
                Yii::$app->session->setFlash('item', 'User has not been updated successfully!');
            }
        } else {
             $model->setAttributes($Couponmodel->getAttributes());
             $model->coupon_valid_date = date('d/m/Y', strtotime($Couponmodel->coupon_valid_date) ); 
        }
                
        return $this->render('update', [
            'data' => $data,
            'model' => $model,
         
        ]);
    }*/
	 
    /**
    * Deletes an existing record.
    * If deletion is successful, the browser will be redirected to the 'index' page.
    * @param integer $id
    * @return mixed
    */
    public function actionDelete($id) {
		$this->findModel($id)->delete();
		 Yii::$app->session->setFlash('item', 'Selected coupon deleted successully!');
		return $this->redirect(['index']);
	}
	
	/**
    * Finds the record based on its primary key value.
    * If the model is not found, a 404 HTTP exception will be thrown.
    * @param integer $id
    * @return CrudTest the loaded model
    * @throws NotFoundHttpException if the model cannot be found
    */
    protected function findModel($id) {
        if (($model = Coupon::findOne($id)) !== null) {
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
        
               $model->status = $postArr['status'];
             if (Yii::$app->request->isAjax){
                if($model->save()) {
                    Yii::$app->session->setFlash('item' ,'Coupon status has been changed successfully.');
                } else {
                    Yii::$app->session->setFlash('item', 'No status is set for selected coupon, please try again.');
                }
                die();
          }
        }
    }
}
