<?php

namespace app\modules\api\controllers;
//namespace app\controllers;
use Yii;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

use app\models\User;
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

class UserController extends \yii\web\Controller
{
	public $enableCsrfValidation = false;

	public function actionIndex()
	{
		$u = 0;
		if(isset($_REQUEST['userid']))
		$u=$_REQUEST['userid'];
		$secretkey = Yii::$app->params['encryption_key'];
		$user = hash_hmac('sha256',$u, $secretkey);
		echo $user;
	}

	private function setHeader($status)
	{
		$status_header = 'HTTP/1.1 ' . $status . ' ' . Yii::$app->params['response_text'][$status];
		$content_type="application/json; charset=utf-8";
		header($status_header);
		header('Content-type: ' . $content_type);
		header('X-Powered-By: ' . "Five Claps");
	}


	public function actionLogin() {
		$this->layout = false;
		if( isset($_POST['device_id']) && $_POST['device_id'] != null &&
		   isset($_POST['device_type']) && $_POST['device_type'] != null &&
		   isset($_POST['email']) && $_POST['email'] != null &&
		   isset($_POST['password']) && $_POST['password'] != null &&

		   isset($_POST['encrypted_data']) && $_POST['encrypted_data'] != null ) 
		{
			// if(isset($_POST['lang_pref']) && $_POST['lang_pref'] != ''&& $_POST['lang_pref'] == "A")
			// Yii::$app->language = "ar";
				
			   $check_user = Yii::$app->mycomponent->validate_user(0,$_POST['encrypted_data']);
			   $data = Users::find()->where(['user_type'=>'U','is_deleted'=>'N','email'=>$_POST['email'],'password'=>md5($_POST['password'])])->one();               
			   if( $data ) 
			   {
					if(isset($_POST['lang_pref']) && $_POST['lang_pref'] != ''&& $_POST['lang_pref'] == "A")
				   		if($data->is_active == 'N') 
				   		{
					   		$this->setHeader(400);
					   		echo json_encode(array('code'=>400,'status'=>'error','message'=>Yii::t('app',Yii::$app->params['account_deactivated'])));
					   		die;
				   		}

				   		if(isset($_POST['device_type']) && $_POST['device_type'] != '') {
					   		$data->device_type = $_POST['device_type'];
				   		}

				   		if(isset($_POST['device_id']) && $_POST['device_id'] != '') {
							$data->device_id = $_POST['device_id'];
				   		}
				   
						$data->save(false);
				  
						$this->setHeader(200);
						$result['code'] = 200;
						$result['message'] = Yii::$app->params['successfully_logged_in'];
					  
						$result['status'] = Yii::$app->params['response_text'][$result['code']];
						$result['user_info']['user_id'] = $data->id;
						$result['user_info']['user_name'] = $data->name;
						$result['user_info']['email'] = $data->email;
						if ($data->image != '') 
						{
							$result['user_info']['profile_pic'] = Yii::getAlias('@web').'/img/uploads/user/'.$data->image;
						}
						$resultstring = json_encode($result);
						$resultstring = str_replace("null",'""',$resultstring);
						//echo $data->image;
						echo $resultstring;
						die;
				} 
				else 
					{
						$this->setHeader(603);
						echo json_encode(array('code'=>603,'status'=>'error','message'=>Yii::t('app',utf8_encode(Yii::$app->params['invalid_login_1']))));
						die;
					}
		} 
		else 
			{
				$this->setHeader(400);
				echo json_encode(array('code'=>400,'status'=>'error','message'=>Yii::t('app',Yii::$app->params['response_text'][400])));
				die;
			}
	}



	public function actionSignUp()
	{
		
	  	$this->layout = false;
	  	if(
		    isset($_POST['email']) && $_POST['email'] != null &&
		    isset($_POST['name']) && $_POST['name'] != null &&           
		    isset($_POST['password']) && $_POST['password'] != null &&
		    isset($_POST['device_id']) && $_POST['device_id'] != null &&
		    isset($_POST['device_type']) && $_POST['device_type'] != null &&
			isset($_POST['encrypted_data']) && $_POST['encrypted_data'] != null)
		{
			// $data = Yii::$app->mycomponent->validate_user(0,$_POST('encrypted_data'));
			$data = User::find()->where(['email'=>$_POST['email'],'user_type'=>'U','is_deleted'=>'N'])->one();
			if($data == array())
			{
				$u = new Users();

				$u->email = $_POST['email'];
				$u->name = $_POST['name'];
				$u->password = md5($_POST['password']);

				if($u->save(false))
				{
					$this->setHeader(200);
					$result['code'] = 200;
										
					$result['message'] = Yii::$app->params['register_success_message'];
					
					$result['status'] = Yii::$app->params['response_text'][$result['code']];

					$data = User::find()->where(['id'=>$data->id])->one();

					$result['user_info']['user_id'] = $data->id;
					$result['user_info']['pay_fort_cus_id'] = $data->pay_fort_cus_id;
					$result['user_info']['name'] = $data->name;
					$result['user_info']['email'] = $data->email;				   				
				   
					$resultstring =  json_encode($result);
					$resultstring = str_replace("null",'""',$resultstring);
					echo $resultstring ;
					die;
				}
				else{
					$this->setHeader(602);
					echo json_encode(array('code'=>602,'status'=>'error','message'=>Yii::$app->params['error_in_save']));
					die;

				}

			}
			else{
				if($data != array())
				{
					$this->setHeader(404);
					echo json_encode(array('code'=>404,'status'=>'error','message'=>Yii::$app->params['email_exist_already']));
					die;
				}
			}
		}
		else{
			$this->setHeader(400);
			echo json_encode(array('code'=>400,'status'=>'error','message'=>Yii::$app->params['response_text'][400]));
			die;
		}
	}





	public function actionRegistration()
	{			

		$this->layout = false;
		if(
		   isset($_POST['email']) && $_POST['email'] != null &&
		   isset($_POST['name']) && $_POST['name'] != null &&           
		   isset($_POST['password']) && $_POST['password'] != null &&
		   isset($_POST['device_id']) && $_POST['device_id'] != null &&
		   isset($_POST['device_type']) && $_POST['device_type'] != null &&
		   isset($_POST['encrypted_data']) && $_POST['encrypted_data'] != null)
		{
			
			$check_user = Yii::$app->mycomponent->validate_user(0,$_POST['encrypted_data']);

			$data = Users::find()->where(['email'=>$_POST['email'],'user_type'=>'U','is_deleted'=>'N'])->one();
			
			if($data == array())
			{

				$data = new Users();

				$data->email = $_POST['email'];
				$data->name = $_POST['name'];
				$data->password = md5($_POST['password']);

				$data->user_type = 'U';
				$data->i_date = date('Y-m-d');
				$data->u_date = date('Y-m-d');

				if(isset($_POST['device_type']) && $_POST['device_type'] != '')
				$data->device_type = $_POST['device_type'];

				if(isset($_POST['device_id']) && $_POST['device_id'] != '')
				$data->device_id = $_POST['device_id'];

				if($data->save(false))
				{
					
					$type = 'W';
					$message = 'Welcome to Demo';
					$noti_message = 'Welcome to Demo';

					$this->setHeader(200);
					$result['code'] = 200;
					
					$result['message'] = Yii::$app->params['register_success_message'];
					
					$result['status'] = Yii::$app->params['response_text'][$result['code']];

					$data = Users::find()->where(['email'=>$data->email])->one();

					$result['user_info']['user_id'] = $data->id;

					$result['user_info']['name'] = $data->name;

					$result['user_info']['email'] = $data->email;
				   
					$resultstring =  json_encode($result);
					$resultstring = str_replace("null",'""',$resultstring);
					echo $resultstring ;
					die;
				}
				else
				{
					$this->setHeader(602);
					echo json_encode(array('code'=>602,'status'=>'error','message'=>Yii::$app->params['error_in_save']));
					die;
				}
			}
			else
			{
				if($data != array())
				{					
					$this->setHeader(404);
					echo json_encode(array('code'=>404,'status'=>'error','message'=>Yii::t('app',Yii::$app->params['email_exist_already'])));
					die;
				}
			}
		}
		else
		{
			$this->setHeader(400);
			echo json_encode(array('code'=>400,'status'=>'error','message'=>Yii::$app->params['response_text'][400]));
			 die;
		}
	}


	public function actionForgotPassword()
    {
        //echo $_POST['email'];
        //exit;
        $this->layout = false;
        if(isset($_POST['email']) && $_POST['email'] != null && isset($_POST['encrypted_data']) && $_POST['encrypted_data'] != null)
        {
            $check_user = Yii::$app->mycomponent->validate_user(0,$_POST['encrypted_data']);

            //check if user exist or not
            $data = Users::find()->where(['email'=>$_POST['email'],'user_type'=>'U','is_deleted'=>'N'])->one();
            if($data)
            {
                // if($data->lang_pref == "A")
                // Yii::$app->language = 'ar';
                if($data->password == '')
                {
                    $this->setHeader(602);
                    echo json_encode(array('code'=>602,'status'=>'error','message'=>utf8_encode(Yii::$app->params['error_forgot_password_social'])));
                    die;
                }

                // set forgot password token, which will passed in url
                $random_str = time().rand(10000,99999);
                $data->forgot_password_token = md5($random_str);

                // set forgot password token timeout, set to 1 hour from now
                $data->forgot_password_token_timeout = time();

                if($data->save(false))
                {
                    Yii::$app->mailer->compose('@app/mail/layouts/forgotpassword', [
                        'username' => $data->name,
                        'link_token' => $data->forgot_password_token,
                    ])
                    ->setTo($_POST['email'])
                    ->setFrom(Yii::$app->params['adminEmail'])
                    ->setSubject(Yii::$app->params['adminEmail'].' : Reset Password Request')
                    ->send();

                    $this->setHeader(200);
                    $result['code'] = 200;
                    if($data->lang_pref == "A")
                    Yii::$app->language = 'ar';
                    if(Yii::$app->language == 'ar')
                    $result['message'] = Yii::t('app',Yii::$app->params['forgot_password_link_sent']);
                    else
                    $result['message'] = Yii::$app->params['forgot_password_link_sent'];
                    $result['status'] = Yii::$app->params['response_text'][$result['code']];
                    $resultstring = json_encode($result);
                    $resultstring = str_replace("null",'""',$resultstring);
                    echo $resultstring ;
                    die;
                }
                else
                {
                    $this->setHeader(602);
                    if(Yii::$app->language == 'ar')
                    echo json_encode(array('code'=>602,'status'=>'error','message'=>Yii::t('app',utf8_encode(Yii::$app->params['error_forgot_password']))));
                    else
                    echo json_encode(array('code'=>602,'status'=>'error','message'=>utf8_encode(Yii::$app->params['error_forgot_password'])));
                    die;
                }
            }
            else
            {
                $this->setHeader(404);
                if(Yii::$app->language == 'ar')
                echo json_encode(array('code'=>404,'status'=>'error','message'=>Yii::t('app',utf8_encode(Yii::$app->params['error_forgot_password_email_not_found']))));
                else
                echo json_encode(array('code'=>404,'status'=>'error','message'=>utf8_encode(Yii::$app->params['error_forgot_password_email_not_found'])));
                die;
            }
        }
        else
        {
            $this->setHeader(400);
            if(Yii::$app->language == 'ar')
            echo json_encode(array('code'=>400,'status'=>'error','message'=>Yii::t('app',Yii::$app->params['response_text'][400])));
            else
            echo json_encode(array('code'=>400,'status'=>'error','message'=>Yii::$app->params['response_text'][400]));
            die;
        }
    }


    public function actionChangePassword()
    {
        $this->layout = false;
        if(isset($_POST['newpass']) && $_POST['newpass'] != null &&
           isset($_POST['oldpass']) && $_POST['oldpass'] != null &&
           isset($_POST['email']) && $_POST['email'] != null
           && isset($_POST['encrypted_data']) && $_POST['encrypted_data'] != null)
        {
            $check_user = Yii::$app->mycomponent->validate_user($_POST['user_id'],$_POST['encrypted_data']);
            $data = User::find()->where(['email'=>$_POST['email'],'user_type'=>'U','is_deleted'=>'N','password'=>md5($_POST['oldpass'])])->one();

            if($data != array())
            {
                $data->password = md5($_POST['newpass']);
       
                $data->save(false);

                $this->setHeader(200);
                $result['code'] = 200;
                
                $result['message'] = Yii::$app->params['success_password_changed'];
                $result['status'] = Yii::$app->params['response_text'][$result['code']];

                $result['User']['id'] = $data->id;
                $result['User']['name'] = $data->name;
                $result['User']['email'] = $data->email;
                // $result['User']['username'] = $data->username;
               
                $resultstring = json_encode($result);
                $resultstring = str_replace("null",'""',$resultstring);
                echo $resultstring ;
                die;
            }
            else
            {
                $this->setHeader(603);
                echo json_encode(array('code'=>603,'status'=>'error','message'=>utf8_encode(Yii::$app->params['error_invalid_old_password'])));
                
                die;
            }
        }
        else
        {
            $this->setHeader(400);
            echo json_encode(array('code'=>400,'status'=>'error','message'=>Yii::$app->params['response_text'][400]));
            die;
        }
    }

    public function actionSocialRegistration()
    {
        if(isset($_POST['social_type']) && $_POST['social_type'] != null &&
        isset($_POST['social_id']) && $_POST['social_id'] != null &&
        isset($_POST['device_id']) && $_POST['device_id'] != null &&
        isset($_POST['device_type']) && $_POST['device_type'] != null &&
        isset($_POST['email']) && $_POST['email'] != null)
        {	                
            if(!in_array($_POST['social_type'],["G","F"]))
            {
            	$this->setHeader(404);
            	echo json_encode(array('code'=>404,'status'=>'error','message'=>Yii::t('app',Yii::$app->params['social_type_not_found'])));
            	die;
            }
            $data = $result = array();
        
            if($_POST['social_type']=="G"){
            	$data = Users::find()->where(['google_id'=>$_POST['social_id']])->andwhere(['is_deleted'=>'N'])->one();
            }else if($_POST['social_type']=="F"){
            	$data = Users::find()->where(['facebook_id'=>$_POST['social_id']])->andwhere(['is_deleted'=>'N'])->one();
            }

            if(isset($data) && $data!=array()) 
            {
            	// echo "Data not found";
            	// alert("Hello");
            	if(isset($_POST['device_id']) && !empty($_POST['device_id']))
                	$data->device_id = urldecode($_POST['device_id']);
    
            	if(isset($_POST['device_type']) && !empty($_POST['device_type']))
                	$data->device_type = $_POST['device_type'];              
            	
            	$data->save(false);
                     
            	$model = $data;
    
            	$result['user_info']['user_id'] = isset($model->id)?$model->id:"";
              	$result['user_info']['user_name'] = $data->name;
              	$result['user_info']['email'] = isset($model->email)?$model->email:"";
              	// $result['user_info']['social_type'] = $_POST['social_type'];

              	if($_POST['social_type'] =='G')
					$result['user_info']['social_type'] = 'Google';
                else
                	$result['user_info']['social_type'] = 'Facebook';

              	if($_POST['social_type'] == 'G')
              	$result['user_info']['social_id'] = isset($model->google_id)?$model->google_id:"";
              	else
              	$result['user_info']['social_id'] = isset($model->facebook_id)?$model->facebook_id:"";
             
              	$this->setHeader(200);
		        $result['code'] = 200;
        	    $result['status'] = Yii::$app->params['response_text'][$result['code']];
              
            	$result['message'] = "You are successfully logged in.";
            
            }
            else{
                // echo "Data found";
            	if(isset($_POST['email']) && $_POST['email'] != '')
              	{
              		// echo "Email not empty";
                    $data = Users::find()->where(['email'=>$_POST['email']])->andwhere(['is_deleted'=>'N'])->one();

                    if(isset($data) && count($data)>0)
                    {
                    	// echo "Data count found";
                        $model = $data;
                            
                        if($_POST['social_type'] == 'G')
                        {
                            $model->google_id = $_POST['social_id'];
                            if(isset($_POST['pic_url']))
                            $model->google_image = $_POST['pic_url'];
                        }
                        else
                        {
                        	$model->facebook_id = $_POST['social_id'];
                            if(isset($_POST['pic_url']))
                            $model->facebook_image = $_POST['pic_url'];
                        }
                    	// $model->name = $_POST['user_name'];
                       	$model->email = $_POST['email'];
                       	$model->u_date = ('Y-m-d');

                            	if($model->save(false))
                                {
                                	echo "Data count found save false";
                                	$data = Users::find()->where(['id'=>$model->id])->one();
                                    $result['user_info']['user_id'] = isset($data->id)?$data->id:"";
                                    // $result['user_info']['user_name'] = isset($_POST['user_name'])?$_POST['user_name']:$data->name;
                                    $result['user_info']['email'] = isset($data->email)?$data->email:"";

                                    if($_POST['social_type'] =='G')
                                    	$result['user_info']['social_type'] = 'Google';
                                    else
                                  		$result['user_info']['social_type'] = 'Facebook';

                                    if($_POST['social_type'] == 'G')
                                      	$result['user_info']['social_id'] = isset($data->google_id)?$data->google_id:"";
                                   	else
                                     	$result['user_info']['social_id'] = isset($data->twitter_id)?$data->facebook_id:"";
                                     
                                    $this->setHeader(200);
                                    $result['code'] = 200;
                                    $result['status'] = Yii::$app->params['response_text'][$result['code']];
                                    $result['message'] = "You are successfully logged in.";

                                }
                    }
                    else{
                    		// echo "New User Create";

                            $model = new Users();
          
                            // $model->name = $_POST['user_name'];
                            $model->email = $_POST['email'];
                            $model->name = $_POST['first_name'] .' '. $_POST['last_name'];
                            $model->device_type = $_POST['device_type'];
                            $model->device_id = $_POST['device_id'];
                            $model->first_name = $_POST['first_name'];
                            $model->last_name = $_POST['last_name'];

                            if($_POST['social_type'] == 'G')
                            {
                              	$model->google_id = $_POST['social_id'];
                             	$model->google_image = $_POST['pic_url'];
                            }
                            else
                            {
                              	$model->facebook_id = $_POST['social_id'];
                             	$model->facebook_image = $_POST['pic_url'];
                            }
                            $model->user_type ='U';
                            $model->is_deleted = "N";
                            $model->is_active = "Y";
                            $model->i_date = date('Y-m-d');
                            $model->u_date = date('Y-m-d');

                            if($model->save(false))
                               {
                                	$data = Users::find()->where(['id'=>$model->id])->one();
                                  	$result['user_info']['user_id'] = $data->id;
                                  	$result['user_info']['user_name'] = isset($_POST['user_name'])?$_POST['user_name']:$data->name;
                                  	$result['user_info']['email'] = $data->email;
                                  	$this->setHeader(200);
                                  	$result['code'] = 200;
                                  	$result['status'] = Yii::$app->params['response_text'][$result['code']];

                                    $result['message'] = "Registration success.";
                               }
            
                         }
            	}
            	else{
             			$this->setHeader(400);
             			echo json_encode(array('is_exist'=>'N','code'=>400,'status'=>'error','message'=>'Email does not found'));
            			die;
          			}
	    	}
	    	$resultstring =  json_encode($result);
	        $resultstring = str_replace("null",'""',$resultstring);
	        echo $resultstring ;
			die;
	    }
	    else
		{
	        $this->setHeader(400);
	        echo json_encode(array('code'=>400,'status'=>'error','message'=>Yii::$app->params['response_text'][400]));
			die;
	    }
	}
	


	public function actionSociallogin()
    {

    	// echo "<hr>";
    	// print_r($_POST);
    	// exit();
        if(isset($_POST['social_type']) && $_POST['social_type'] != null &&
           isset($_POST['social_id']) && $_POST['social_id'] != null &&
           isset($_POST['device_id']) && $_POST['device_id'] != null &&
           isset($_POST['device_type']) && $_POST['device_type'] != null)
        {
      		//echo "<hr>";
	    	// print_r($_POST);
	    	// exit();
            //$check_user = Yii::$app->mycomponent->validate_user(0,$_POST['encrypted_data']);
            if($_POST['social_type'] == 'F')
            {
                $data = Users::find()->where(['is_deleted'=>'N','facebook_id'=>$_POST['social_id']])->one();          	
            }
            else{
                $data = Users::find()->where(['is_deleted'=>'N','google_id'=>$_POST['social_id']])->one();
            }
            if($data == array())
            {
            	// echo "<pre>";
            	// print_r($_POST);
            	// exit();
                $result['is_exist'] = 'N';
                $result['success'] = true;
                $this->setHeader(603);
                echo json_encode(array('code'=>603,'status'=>'error','message'=>utf8_encode(Yii::$app->params['error_invalid'])));
                
                die;
            }
            else
            {
                if(isset($_POST['device_id']))
                    $data->device_id = $_POST['device_id'];
                
                if(isset($_POST['device_type']))
                    $data->device_type = $_POST['device_type'];
                    
                $data->is_online='Y';
                
                $data->access_token = \Yii::$app->security->generateRandomString();
                
                $data->save(false);
                
                //Yii::$app->mycomponent->onlinestatus($data->id,'Y');
                
                if(isset($_POST['device_id']) && $_POST['device_id'] != '')
                {
                    $data1 = Users::updateAll(['device_id' => null],'device_id = :device_id and id <> :user_id',array(':device_id'=>$_POST['device_id'],':user_id'=>$data->id));
                }
                $result = Yii::$app->mycomponent->userResponse($data->id);
                $result['message'] = Yii::t('app',Yii::$app->params['successfully_logged_in']);
                $result['success'] = true;
                $result['is_exist'] = 'Y';
            }
        }
        else
        {
            $this->setHeader(400);
            echo json_encode(array('code'=>400,'status'=>'error','message'=>Yii::$app->params['response_text'][400]));
            die;
        }
		// echo "<pre>";
		// echo "Outer ";       
 		$this->setHeader(200);
		$result['code'] = 200;
        $resultstring = json_encode($result);
		$resultstring = str_replace("null",'""',$resultstring);
        echo $resultstring ;
        die;
        // return $result;
    }


    public function actionSocial()
    {
        if(isset($_POST['social_type']) && $_POST['social_type'] != null &&
           isset($_POST['social_id']) && $_POST['social_id'] != null &&
           isset($_POST['device_id']) && $_POST['device_id'] != null &&
           isset($_POST['email']) && $_POST['email'] != null &&
           isset($_POST['device_type']) && $_POST['device_type'] != null)
        {
            
            if($_POST['social_type'] == 'F')
            {
                $data = Users::find()->where(['is_deleted'=>'N','facebook_id'=>$_POST['social_id']])->one();
            }
            else{
                $data = Users::find()->where(['is_deleted'=>'N','google_id'=>$_POST['social_id']])->one();
            }
            
  		

            if($data == array())
            {            	
                $exist = array();
                if(isset($_POST['email']) && $_POST['email'] != '')
                $exist = Users::find()->where(['user_type'=>'U','is_deleted'=>'N','email'=>$_POST['email']])->one();
                if($exist == array())
                {
                	// echo "<pre>";
                	// echo "If";
                	// print_r($exist);
                	// exit();
                    $data = new Users();
                    if($_POST['social_type'] == 'F')
                    {
                        $data->facebook_id = $_POST['social_id'];
                        if(isset($_POST['social_image']))
                        $data->facebook_image = $_POST['social_image'];
                    }else{
                        $data->google_id = $_POST['social_id'];
                        if(isset($_POST['social_image']))
                        $data->google_image = $_POST['social_image'];
                    }
                    
                    if(isset($_POST['first_name']) && $_POST['first_name'] != '')
                        $data->first_name = $_POST['first_name'];
                    
                    if(isset($_POST['last_name']) && $_POST['last_name'] != '')
                        $data->last_name = $_POST['last_name'];
                        
                    // if(isset($_POST['email']) && $_POST['email'] != '')
                    //     $data->email = $_POST['email'];
                    
                    // if(isset($_POST['dial_code']) && $_POST['dial_code'] != '')
                    //     $data->dial_code = $_POST['dial_code'];
                    
                    // if(isset($_POST['phone_no']) && $_POST['phone_no'] != '')
                    //     $data->phone_no = $_POST['phone_no'];
                    
                    if(isset($_POST['device_id']))
                        $data->device_id = $_POST['device_id'];
                    
                    if(isset($_POST['device_type']))
                        $data->device_type = $_POST['device_type'];
                    
                    $data->access_token = \Yii::$app->security->generateRandomString();
                    
                    // $data->created_date = date('Y-m-d H:i:s');
                    // $data->updated_date = date('Y-m-d H:i:s');
                    
                    $data->user_type = 'U';
                    
                    // $data->company_id = $_POST['company_id'];
                    
                    $data->save(false);
                    
                    if(isset($_POST['device_id']) && $_POST['device_id'] != '')
                    {
                        $data1 = Users::updateAll(['device_id' => null],'device_id = :device_id and id <> :user_id',array(':device_id'=>$_POST['device_id'],':user_id'=>$data->id));
                    }
                    $result = Yii::$app->mycomponent->userResponse($data->id);
                    

                    $this->setHeader(200);
                	$result['code'] = 200;
                    $resultstring = json_encode($result);
	                $resultstring = str_replace("null",'""',$resultstring);
    	            echo $resultstring ;
        	        die;
                    // $result['success'] = true;
                }
                else
                {
                	
                    if($_POST['social_type'] == 'F')
                    {
                        $exist->facebook_id = $_POST['social_id'];
                    }else{
                        $exist->google_id = $_POST['social_id'];
                    }
                    if(isset($_POST['device_id']))
                        $exist->device_id = $_POST['device_id'];
                
                    if(isset($_POST['device_type']))
                        $exist->device_type = $_POST['device_type'];
                    
                    $exist->access_token = \Yii::$app->security->generateRandomString();
                    
                    $exist->save(false);
                    
                    if(isset($_POST['device_id']) && $_POST['device_id'] != '')
                    {
                        $data1 = Users::updateAll(['device_id' => null],'device_id = :device_id and id <> :user_id',array(':device_id'=>$_POST['device_id'],':user_id'=>$exist->id));
                    }
                    
                    $result = Yii::$app->mycomponent->userResponse($exist->id);

                    $resultstring = json_encode($result);
                	$resultstring = str_replace("null",'""',$resultstring);
                	echo $resultstring ;
                	die;
                }
            }
            else
            {
    

                if(isset($_POST['device_id']))
                    $data->device_id = $_POST['device_id'];
                
                if(isset($_POST['device_type']))
                    $data->device_type = $_POST['device_type'];
                
                $data->access_token = \Yii::$app->security->generateRandomString();
                    
                $data->save(false);
                
                if(isset($_POST['device_id']) && $_POST['device_id'] != '')
                {
                    $data1 = Users::updateAll(['device_id' => null],'device_id = :device_id and id <> :user_id',array(':device_id'=>$_POST['device_id'],':user_id'=>$data->id));
                }
                
                $result = Yii::$app->mycomponent->userResponse($data->id);
                

                $resultstring = json_encode($result);
                $resultstring = str_replace("null",'""',$resultstring);
                echo $resultstring ;
                die;
                // $result['success'] = true;
            }
        }
        else
        { 
        	$this->setHeader(400);
            echo json_encode(array('code'=>400,'status'=>'error','message'=>Yii::$app->params['response_text'][400]));
            die;
        }
        return $result;
    }



}
