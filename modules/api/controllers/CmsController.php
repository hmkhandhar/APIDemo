<?php

namespace app\modules\api\controllers;
//namespace app\controllers;
use Yii;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

use app\models\Cms;
//// echo "<pre>";
// print_r($model);
// exit;
use app\models\Follow;
use app\models\Users;
// use yii\db\ActiveRecord;

// require_once(Yii::getAlias('@vendor').'/start-custom-php-master/vendor/payfort/start/Start.php');
// require_once(Yii::getAlias('@vendor').'/start-custom-php-master/vendor/autoload.php'); 
# At the top of your PHP file

 //Initialize Start object
//require_once("path-to-start-php/Start.php");

class CmsController extends \yii\web\Controller
{
	public $enableCsrfValidation = false;

	public function actionIndex()
	{
		echo "CMS";
	}

	private function setHeader($status)
	{
		$status_header = 'HTTP/1.1 ' . $status . ' ' . Yii::$app->params['response_text'][$status];
		$content_type="application/json; charset=utf-8";
		header($status_header);
		header('Content-type: ' . $content_type);
		header('X-Powered-By: ' . "Five Claps");
	}

	public function actionTermsCondition() 
	{
		$this->layout = false;		
		$data = Cms::find()->where(['title'=>'Terms&Conditions'])->one();

		if($data != array())
		{
			$result['id'] = $data->id;
			$result['Title'] = $data->title;
			$result['Content'] = $data->content;
			
			$resultstring =  json_encode($result);
			$resultstring = str_replace("null",'""',$resultstring);
			echo $resultstring ;
			die;
		}
		else{
			echo json_encode(array('code'=>400,'status'=>'error','message'=>Yii::$app->params['response_text'][400]));
		}
	}

	public function actionHelpCenter() 
	{
		$this->layout = false;		
		$data = Cms::find()->where(['title'=>'HelpCenter'])->one();

		if($data != array())
		{
			$result['id'] = $data->id;
			$result['Title'] = $data->title;
			$result['Content'] = $data->content;
			
			$resultstring =  json_encode($result);
			$resultstring = str_replace("null",'""',$resultstring);
			echo $resultstring ;
			die;
		}
		else{
			echo json_encode(array('code'=>400,'status'=>'error','message'=>Yii::$app->params['response_text'][400]));
		}
	}

	public function actionPrivacyPolicy() 
	{
		$this->layout = false;		
		$data = Cms::find()->where(['title'=>'PrivacyPolicy'])->one();

		if($data != array())
		{
			$result['id'] = $data->id;
			$result['Title'] = $data->title;
			$result['Content'] = $data->content;
			
			$resultstring =  json_encode($result);
			$resultstring = str_replace("null",'""',$resultstring);
			echo $resultstring ;
			die;
		}
		else{
			echo json_encode(array('code'=>400,'status'=>'error','message'=>Yii::$app->params['response_text'][400]));
		}
	}	
}
