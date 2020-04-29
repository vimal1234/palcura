<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use backend\models\settings\AddSetting;

use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\db\Query;
/**
* Settings controller
*/
class SettingsController extends Controller {
	/**
	* @inheritdoc
	*/
	public function behaviors() {
		return [
			'access' => [
				'class' => AccessControl::className(),
				'rules' => [
					[
						'actions' => ['index','basics','exportdatabase'],
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

	/**
	* @inheritdoc
	*/
    public function actionIndex() {
		return $this->redirect(['basics']);
    }

	/**
	* Create new records.
	* @return mixed
	*/	 
	public function actionBasics() {
		$data   = array();
		$model  = new AddSetting();
		$data	= $this->getSettingsRowCount();
		if ($model->load(Yii::$app->request->post()) && $model->validate()) {
			$postval 			= Yii::$app->request->post('AddSetting');
			
			$connection = \Yii::$app->db;
			$website_fee = (isset($postval['website_fee']) ? $postval['website_fee'] : '');
			$family_member_discount 	= (isset($postval['family_member_discount']) ? $postval['family_member_discount'] : '');
			$discount 	= (isset($postval['discount']) ? $postval['discount'] : '');
			$google_analytics = (isset($postval['google_analytics']) ? $postval['google_analytics'] : '');
$facebook_pixel = (isset($postval['facebook_pixel']) ? $postval['facebook_pixel'] : '');
			
			if(isset($data['id']) && $data['id'] > 0) {
				$connection->createCommand()->update('website_settings', ['website_fee' => $website_fee, 'family_member_discount' => $family_member_discount, 'google_analytics' => $google_analytics, 'facebook_pixel' => $facebook_pixel])->execute();				
			} else {
				Yii::$app->db->createCommand()->insert('website_settings', ['website_fee' => $website_fee, 'family_member_discount' => $family_member_discount, 'google_analytics' => $google_analytics, 'status' => ACTIVE, 'facebook_pixel' => $facebook_pixel])->execute();
				$last_id = Yii::$app->db->getLastInsertID();				
			}

			Yii::$app->session->setFlash('item', 'Basic settings have been updated successfully!');
			return $this->redirect(['basics']);
		}
		return $this->render('create', [
			'data' => $data,
			'model' => $model,
		]);
	}

	public function getSettingsRowCount() {
		$query = new Query;
		$query->select('*')->from('website_settings');
		return $query->createCommand()->queryOne();
	}

	#### backup the db OR just a table
	public function actionExportdatabase() {
	
		$host	= "localhost";
		$user	= "palcura_usr5745";
		$pass	= "Ea~C1+8cTT]X";
		$name   = "palcura_d032018";
		$tables = "*";
		//$link = mysql_connect($host,$user,$pass);
		$link   = mysqli_connect($host, $user, $pass, $name);	

		//mysql_select_db($name,$link);

		//get all of the tables
		if($tables == '*')
		{
			$tables = array();
			$result = mysqli_query($link,'SHOW TABLES');
			while($row = mysqli_fetch_row($result))
			{
				$tables[] = $row[0];
			}
		}
		else
		{
			$tables = is_array($tables) ? $tables : explode(',',$tables);
		}
		$return = '';
		//cycle through
		foreach($tables as $table)
		{
			$result = mysqli_query($link,'SELECT * FROM '.$table);
			$num_fields = mysqli_num_fields($result);
			
			$return.= 'DROP TABLE '.$table.';';
			$row2 = mysqli_fetch_row(mysqli_query($link,'SHOW CREATE TABLE '.$table));
			$return.= "\n\n".$row2[1].";\n\n";
			
			for ($i = 0; $i < $num_fields; $i++) 
			{
				while($row = mysqli_fetch_row($result))
				{
					$return.= 'INSERT INTO '.$table.' VALUES(';
					for($j=0; $j < $num_fields; $j++) 
					{
						$row[$j] = addslashes($row[$j]);
						//$row[$j] = ereg_replace("\n","\\n",$row[$j]);
						if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }
						if ($j < ($num_fields-1)) { $return.= ','; }
					}
					$return.= ");\n";
				}
			}
			$return.="\n\n\n";
		}
		$uploadPath = Yii::getAlias('@common') .'/uploads/database/';
		//save file
		$handle = fopen($uploadPath.'db-backup-'.time().'-'.(md5(implode(',',$tables))).'.sql','w+');
		fwrite($handle,$return);
		fclose($handle);
		$name = $uploadPath.'db-backup-'.time().'-'.(md5(implode(',',$tables))).'.sql';
		if (file_exists($name)) {
			header('Content-Description: File Transfer');
			header('Content-Type: application/force-download');
			header("Content-Disposition: attachment; filename=\"" . basename($name) . "\";");
			header('Content-Transfer-Encoding: binary');
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			header('Content-Length: ' . filesize($name));
			ob_clean();
			flush();
			readfile($name); //showing the path to the server where the file is to be download
			exit;
		}
		//unlink($name);
	}
	
	/**
	* Finds the record based on its primary key value.
	* If the model is not found, a 404 HTTP exception will be thrown.
	* @param integer $id
	* @return CrudTest the loaded model
	* @throws NotFoundHttpException if the model cannot be found
	*/
	protected function findModel($id) {
		if (($model = Settings::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
}
