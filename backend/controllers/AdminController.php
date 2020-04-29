<?php 
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use common\models\AdminLoginForm;
use backend\models\PasswordUpdate;
use yii\filters\VerbFilter;

/**
 * Admin controller
 */
class AdminController extends Controller
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
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index','settings','profile'],
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
    /**
     * actionIndex
     * @param integer $id
     * @return mixed
     */
    public function actionIndex()
    {	
        return $this->render('index');
    }

    /**
     * actionLogin
     * @param 
     * @return mixed
     */
    public function actionLogin()
    { 
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }
		
        $model = new AdminLoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
       
            return $this->goBack();
        } else {
			
			$this->layout = '@backend/web/themes/gentelella/views/layouts/none';
            return $this->render('loginform', [
                'model' => $model,
            ]);
        }
    }
    /**
     * actionLogout
     * @param 
     * @return mixed
     */
    public function actionLogout()
    {	
        Yii::$app->user->logout();
        return $this->goHome();
    }
    /**
     * actionSettings
     * @param 
     * @return mixed
     */    
    public function actionSettings()
    {	
		$request = Yii::$app->request;
		$post = $request->post(); 
		
		$data = array();
		 
		$model = new PasswordUpdate();
	
		if($model->load($post) ) {
					
			if($model->updateProfile())
			{
			$data['respmesg'] = "Changes has been saved successfully.";
			$data['class'] = "alert-success";
				Yii::$app->getSession()->setFlash('respmesg', 'Changes has been saved successfully.');
				Yii::$app->getSession()->setFlash('class', 'alert-success');
				return $this->redirect('settings');			
			}else{
			
			$data['respmesg'] = "Please enter valid values for all the fields.";
			$data['class'] = "alert-danger";
			
			}
		
		}
		
        return $this->render('settings', [
                'data' => $data,
                'model' => $model
            ]);
    }

    /**
     * actionProfile
     * @param 
     * @return mixed
     */    
    public function actionProfile()
    {	
        return $this->render('profile');
    }
    
}
