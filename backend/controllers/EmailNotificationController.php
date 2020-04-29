<?php
namespace backend\controllers;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use common\models\Admin;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

/**
 * EmailNotification controller
 */
class EmailNotificationController extends Controller {
    /**
    * @inheritdoc
    */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [

                    [
                        'actions' => ['send-user-registration-email','demo'],
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

	public function demo() {
		echo'Test';  exit();
	}

	/**
	* Displays a single User model.
	* @param array $postArr
	* @return mixed
	*/
    public function sendUserRegistrationEmail($postArr) {
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
}
