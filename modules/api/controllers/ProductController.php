<?php

namespace app\modules\api\controllers;
//namespace app\controllers;
use Yii;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\models\Product;


class ProductController extends \yii\web\Controller
{
	public $enableCsrfValidation = false;

	public function actionIndex()
	{
		// $u = 0;
		// if(isset($_REQUEST['productid']))
		// $u=$_REQUEST['productid'];
		// $secretkey = Yii::$app->params['encryption_key'];
		// $product = hash_hmac('sha256',$u, $secretkey);
		// echo $product;

		$data = Product::find()->one();

		$result['product_info']['product_id'] = $data->id;
		$result['product_info']['name'] = $data->name;
		$result['product_info']['category'] = $data->category;
		$result['product_info']['price'] = $data->price;


		$resultstring =  json_encode($result);
		$resultstring = str_replace("null",'""',$resultstring);
		echo $resultstring ;
		die;
	}

	private function setHeader($status)
	{
		$status_header = 'HTTP/1.1 ' . $status . ' ' . Yii::$app->params['response_text'][$status];
		$content_type="application/json; charset=utf-8";
		header($status_header);
		header('Content-type: ' . $content_type);
		header('X-Powered-By: ' . "Peerbits");
	}

	public function actionInsert()
	{
		$this->layout = false;
		if(		   
		   isset($_POST['name']) && $_POST['name'] != null &&           
		   isset($_POST['category']) && $_POST['category'] != null &&
		   isset($_POST['price']) && $_POST['price'] != null &&
		   isset($_POST['image']) && $_POST['image'] != null)
		{
		$data = Product::find()->where(['category'=>$_POST['category'],'is_deleted'=>'N'])->one();
		if($data == array())
		{
			$data = new Product();
			
			$data->name = $_POST['name'];
			$data->category = $_POST['category'];
			$data->price = $_POST['price'];
			$data->image = $_POST['image'];

			$data->is_deleted = 'N';

			if($data->save(false))
			{
				$this->setHeader(200);
				$result['code'] = 200;
				
				if(Yii::$app->language == "ar")
					$result['message'] = Yii::t('app',Yii::$app->params['register_success_message']);
				else
					$result['message'] = Yii::$app->params['register_success_message'];
					
					$result['status'] = Yii::$app->params['response_text'][$result['code']];

					$data = Product::find()->where(['id'=>$data->id])->one();

					$result['product_info']['product_id'] = $data->id;
					// $result['user_info']['pay_fort_cus_id'] = $data->pay_fort_cus_id;
					$result['product_info']['name'] = $data->name;

					$result['product_info']['category'] = $data->category;
				   
					$result['product_info']['price'] = $data->price;
				   
					$resultstring =  json_encode($result);
					$resultstring = str_replace("null",'""',$resultstring);
					echo $resultstring ;
					die;
			}
			else
			{
				$this->setHeader(602);
				if(Yii::$app->language == "ar")
					echo json_encode(array('code'=>602,'status'=>'error','message'=>Yii::t('app',Yii::$app->params['error_in_save'])));
				else
					echo json_encode(array('code'=>602,'status'=>'error','message'=>Yii::$app->params['error_in_save']));
				die;
			}
		}
		else
		{
			if($data != array())
			{					
				$this->setHeader(404);
				// if(Yii::$app->language == "ar")
				echo json_encode(array('code'=>404,'status'=>'error','message'=>Yii::t('app',Yii::$app->params['exist_already'])));
					// else
					// echo json_encode(array('code'=>404,'status'=>'error','message'=>Yii::$app->params['email_exist_already']));
				die;
			}
				
		}
		}
		else
		{
			$this->setHeader(400);
			 if(Yii::$app->language == "ar")
			 echo json_encode(array('code'=>400,'status'=>'error','message_ar'=>Yii::t('app',$app->params['response_text'][400])));
			 else
			 echo json_encode(array('code'=>400,'status'=>'error','message'=>Yii::$app->params['response_text'][400]));
			 die;
		}
	}

	

}
