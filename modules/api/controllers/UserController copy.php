<?php

namespace app\modules\api\controllers;
//namespace app\controllers;
use Yii;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

use app\models\User;
use app\models\Post;
use app\models\Follow;
use app\models\Notification;
use app\models\Images;
use app\models\Blockuser;
use app\models\Category;
require_once(Yii::getAlias('@vendor').'/start-php-master/Start.php');
require_once(Yii::getAlias('@vendor').'/autoload.php'); # At the top of your PHP file

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
    
    public function actionRegisterPayFortUser()
    {
        if(isset($_POST['device_id']) && $_POST['device_id'] != null &&
           isset($_POST['device_type']) && $_POST['device_type'] != null &&
           isset($_POST['user_id']) && $_POST['user_id'] != null &&
           //isset($_POST['card_no']) && $_POST['card_no'] != null &&
           //isset($_POST['exp_month']) && $_POST['exp_month'] != null &&
           //isset($_POST['exp_year']) && $_POST['exp_year'] != null &&
           //isset($_POST['cvc']) && $_POST['cvc'] != null &&
           isset($_POST['encrypted_data']) && $_POST['encrypted_data'] != null)
        {
            $check_user = Yii::$app->mycomponent->validate_user($_POST['user_id'],$_POST['encrypted_data']);
           
            $data = User::find()->where(['id'=>$_POST['user_id'],'user_type'=>'U','is_deleted'=>'N'])->one();
            
            if(!empty($data))
            {
                if($data->pay_fort_cus_id == null)
                {
                    try{
                \Start::setApiKey("test_sec_k_6f8d68513f1bbdf7ab9dc");

                $pay_fort_result=\Start_Customer::create(array(
                                            "name" => $data->username,
                                            "email" => $data->email,
                                            "card" => array(),
                                            "description" => "Signed up at the Trade Show in Dec 2014"
                                          ));
                                                }catch(Start_Error_Banking $e) {
                              // Since it's a decline, Start_Error_Banking will be caught
                              print('Status is:' . $e->getHttpStatus() . "\n");
                              print('Code is:' . $e->getErrorCode() . "\n");
                              print('Message is:' . $e->getMessage() . "\n");
                            
                            } catch (Start_Error_Request $e) {
                              echo "Invalid parameters were supplied to Start\'s API";
                            
                            } catch (Start_Error_Authentication $e) {
                              echo "Invalid API key";
                            
                            } catch (Start_Error_Processing $e) {
                               echo "Something wrong on Start's end";
                            
                            } catch (Start_Error $e) {
                              echo "Display a very generic error to the user, and maybe send yourself an email";
                              
                            
                            } catch (Exception $e) {
                              echo "Something else happened, completely unrelated to Start";
                            
                            }
                    
                if(!empty($pay_fort_result))
                {
                   
                    $data->pay_fort_cus_id = $pay_fort_result['id'];
                    
                       
                    if($data->save(false))
                    {
                        $this->setHeader(200);
                        $result['code'] = 200;
                        $result['message'] = Yii::$app->params['register_success_message'];
                        $result['status'] = Yii::$app->params['response_text'][$result['code']];
                        
                        //$data = User::find()->where(['id'=>$data->id])->one();
                      

                        $result['user_info']['user_id'] = $data->id;
                        $result['user_info']['pay_fort_cus_id'] = $data->pay_fort_cus_id;
                        $result['user_info']['user_name'] = $data->username;
   
                        $result['user_info']['email'] = $data->email;
                        $result['user_info']['country_code'] = $data->country_code;
                        $result['user_info']['mobile'] = $data->mobile;
                        $result['user_info']['lang_pref'] = $data->lang_pref;
                        //$result['user_info']['image'] = Yii::$app->homeUrl.'img/uploads/user/'.$data->image;;
    
                        //$result['user_info']['is_social'] = 'N';
    
    
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
                    
                    $this->setHeader(602);
                    echo json_encode(array('code'=>602,'status'=>'error','message'=>Yii::$app->params['something_going_wrong']));
                    die;
                }
                
                } else{
                    
                     $this->setHeader(602);
                    echo json_encode(array('code'=>602,'status'=>'error','message'=>Yii::$app->params['already_pay_fort_account']));
                    die;
                    
                }
                
            }
            else{
                 $this->setHeader(603);
                 echo json_encode(array('code'=>603,'status'=>'error','message'=>utf8_encode(Yii::$app->params['error_no_follower_user_found'])));
                 die;
            }
        }else
        {
            $this->setHeader(400);
            echo json_encode(array('code'=>400,'status'=>'error','message'=>Yii::$app->params['response_text'][400]));
            die;
        }   
    }
    
    public function actionRetriveCard()
    {
        if(isset($_POST['device_id']) && $_POST['device_id'] != null &&
           isset($_POST['device_type']) && $_POST['device_type'] != null &&
           isset($_POST['user_id']) && $_POST['user_id'] != null &&
           isset($_POST['card_no']) && $_POST['card_no'] != null &&
           isset($_POST['exp_month']) && $_POST['exp_month'] != null &&
           isset($_POST['exp_year']) && $_POST['exp_year'] != null &&
           isset($_POST['cvc']) && $_POST['cvc'] != null &&
           isset($_POST['encrypted_data']) && $_POST['encrypted_data'] != null)
        {
            $check_user = Yii::$app->mycomponent->validate_user($_POST['user_id'],$_POST['encrypted_data']);
           
            $data = User::find()->where(['id'=>$_POST['user_id'],'user_type'=>'U','is_deleted'=>'N'])->one();
            
            if(!empty($data))
            {
                if($data->pay_fort_id != null)
                {
                \Start::setApiKey("test_sec_k_6f8d68513f1bbdf7ab9dc");

                $pay_fort_result=\Start_Customer::create(array(
                                            "name" => $data->username,
                                            "email" => $data->email,
                                            "card" => array("number" => $_POST['card_no'],
                                              "exp_month" => $_POST['exp_month'],
                                              "exp_year" => $_POST['exp_year'],
                                              "cvc" => $_POST['cvc']),
                                            "description" => "Signed up at the Trade Show in Dec 2014"
                                          ));
                if(!empty($pay_fort_result))
                {
                    
                    $data->pay_fort_cus_id = $pay_fort_result['id'];
                    
                    
                    if($data->save(false))
                    {
                        $this->setHeader(200);
                        $result['code'] = 200;
                        $result['message'] = Yii::$app->params['register_success_message'];
                        $result['status'] = Yii::$app->params['response_text'][$result['code']];
                        
                        $data = User::find()->where(['id'=>$data->id])->one();

                        $result['user_info']['user_id'] = $data->id;
                        $result['user_info']['user_name'] = $data->username;
    
                        $result['user_info']['email'] = $data->email;
                        $result['user_info']['country_code'] = $data->country_code;
                        $result['user_info']['mobile'] = $data->mobile;
                        $result['user_info']['lang_pref'] = $data->lang_pref;
                        //$result['user_info']['image'] = Yii::$app->homeUrl.'img/uploads/user/'.$data->image;;
    
                        //$result['user_info']['is_social'] = 'N';
    
    
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
                    
                    $this->setHeader(602);
                    echo json_encode(array('code'=>602,'status'=>'error','message'=>Yii::$app->params['something_going_wrong']));
                    die;
                }
                
                } else{
                    
                     $this->setHeader(602);
                    echo json_encode(array('code'=>602,'status'=>'error','message'=>Yii::$app->params['already_pay_fort_account']));
                    die;
                    
                }
                
            }
            else{
                 $this->setHeader(603);
                 echo json_encode(array('code'=>603,'status'=>'error','message'=>utf8_encode(Yii::$app->params['error_no_follower_user_found'])));
                 die;
            }
        }else
        {
            $this->setHeader(400);
            echo json_encode(array('code'=>400,'status'=>'error','message'=>Yii::$app->params['response_text'][400]));
            die;
        }   
    }


    /*
     *  API Name : Register
     *  Created By : Aadil
     *  Creation Date : 4-11-2015
     *  Updated By :
     *  Updated Date :
     *  Input : name,email,password,gender,encrypted_data
     *  Output :
     */


    public function actionDoNativeRegistration()
    {
        //echo "<pre>";
        //print_r($_POST);
        //exit;

        $this->layout = false;
        if(
           isset($_POST['email']) && $_POST['email'] != null &&
           isset($_POST['name']) && $_POST['name'] != null &&
           isset($_POST['country_code']) && $_POST['country_code'] != null &&
           isset($_POST['mobile']) && $_POST['mobile'] != null &&
           isset($_POST['password']) && $_POST['password'] != null &&
           isset($_POST['device_id']) && $_POST['device_id'] != null &&
           isset($_POST['device_type']) && $_POST['device_type'] != null &&
           isset($_POST['encrypted_data']) && $_POST['encrypted_data'] != null)
        {
            $check_user = Yii::$app->mycomponent->validate_user(0,$_POST['encrypted_data']);
            //$data1 = User::find()->where(['username'=>$_POST['user_name'],'user_type'=>'U','is_deleted'=>'N'])->one();
            $data = User::find()->where(['email'=>$_POST['email'],'user_type'=>'U','is_deleted'=>'N'])->one();
            if($data == array())
            {
                $data = new User();
                
               \Start::setApiKey("test_sec_k_6f8d68513f1bbdf7ab9dc");

                $pay_fort_result=\Start_Customer::create(array(
                                            "name" => $_POST['name'],
                                            "email" => $_POST['email'],
                                            "card" => array(),
                                            "description" => "Signed up at the Trade Show in Dec 2014"
                                          ));
            
                $data->pay_fort_cus_id = $pay_fort_result['id'];
                
                $data->email = $_POST['email'];
                $data->username = $_POST['name'];
                $data->country_code = $_POST['country_code'];
                $data->mobile = $_POST['mobile'];
                $data->password = md5($_POST['password']);

                $data->user_type = 'U';
                $data->i_date = date('Y-m-d');
                $data->u_date = date('Y-m-d');

                if(isset($_POST['device_type']) && $_POST['device_type'] != '')
                $data->device_type = $_POST['device_type'];

                if(isset($_POST['device_id']) && $_POST['device_id'] != '')
                $data->device_id = $_POST['device_id'];
                
               if(isset($_POST['lang_pref']) && $_POST['lang_pref'] != '')
                $data->lang_pref = $_POST['lang_pref'];
               

                if($data->save(false))
                {
                    $type = 'W';
                    $message = 'Welcome to Donation App';
                    $noti_message = 'Welcome to Donation App';
                   // Yii::$app->common->addnotification(null,$data->id,$type,$message,$noti_message,null,null);

                    $this->setHeader(200);
                    $result['code'] = 200;
                    $result['message'] = Yii::$app->params['register_success_message'];
                    $result['status'] = Yii::$app->params['response_text'][$result['code']];

                    $data = User::find()->where(['id'=>$data->id])->one();

                    $result['user_info']['user_id'] = $data->id;
                    $result['user_info']['pay_fort_cus_id'] = $data->pay_fort_cus_id;
                    $result['user_info']['user_name'] = $data->username;

                    $result['user_info']['email'] = $data->email;
                    $result['user_info']['country_code'] = $data->country_code;
                    $result['user_info']['mobile'] = $data->mobile;
                    $result['user_info']['lang_pref'] = $data->lang_pref;
                    //$result['user_info']['image'] = Yii::$app->homeUrl.'img/uploads/user/'.$data->image;;

                    //$result['user_info']['is_social'] = 'N';


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
                    echo json_encode(array('code'=>404,'status'=>'error','message'=>Yii::$app->params['email_exist_already']));
                    die;
                }
                //if($data1 != array())
                //{
                //    $this->setHeader(404);
                //    echo json_encode(array('code'=>404,'status'=>'error','message'=>Yii::$app->params['username_exist_already']));
                //    die;
                //}
            }
        }
        else
        {
            $this->setHeader(400);
            echo json_encode(array('code'=>400,'status'=>'error','message'=>Yii::$app->params['response_text'][400]));
            die;
        }
    }


    /*
     *  API Name : Login
     *  Created By : aadil
     *  Creation Date : 04-11-2015
     *  Updated By :
     *  Updated Date :
     *  Input : email,password
     *  Output :
     */

     public function actionUpdateDeviceId() {

         $this->layout = false;
         if(isset($_POST['user_id']) && $_POST['device_id'] != null
          && isset($_POST['encrypted_data']) && $_POST['encrypted_data'] != null)
         {
            $check_user = Yii::$app->mycomponent->validate_user(0,$_POST['encrypted_data']);
            $data = User::find()->where(['user_type'=>'U','is_deleted'=>'N','id'=>$_POST['user_id']])->one();

         if($data)
         {
           if($data->is_active == 'N')
                {
                    $this->setHeader(400);
                    echo json_encode(array('code'=>400,'status'=>'error','message'=>Yii::$app->params['account_deactivated']));
                    die;
                }

                $data->save(false);

                $this->setHeader(200);
                $result['code'] = 200;
                $result['message'] = 'Updated successfully';
                $result['status'] = Yii::$app->params['response_text'][$result['code']];


                $resultstring = json_encode($result);
                $resultstring = str_replace("null",'""',$resultstring);
                echo $resultstring ;
                die;
          }
          else
            {
                $this->setHeader(603);
                echo json_encode(array('code'=>603,'status'=>'error','message'=>utf8_encode(Yii::$app->params['invalid_login_1'])));
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

    public function actionDoNativeLogin() {
        $this->layout = false;
        if( isset($_POST['device_id']) && $_POST['device_id'] != null &&
           isset($_POST['device_type']) && $_POST['device_type'] != null &&
           isset($_POST['email']) && $_POST['email'] != null &&
           isset($_POST['password']) && $_POST['password'] != null &&
           
           isset($_POST['encrypted_data']) && $_POST['encrypted_data'] != null ) {
               $check_user = Yii::$app->mycomponent->validate_user(0,$_POST['encrypted_data']);
               $data = User::find()->where(['user_type'=>'U','is_deleted'=>'N','email'=>$_POST['email'],'password'=>md5($_POST['password'])])->one();
               if( $data ) {
                   if($data->is_active == 'N') {
                       $this->setHeader(400);
                       echo json_encode(array('code'=>400,'status'=>'error','message'=>Yii::$app->params['account_deactivated']));
                       die;
                   }

                   if(isset($_POST['device_type']) && $_POST['device_type'] != '') {
                       $data->device_type = $_POST['device_type'];
                   }

                   if(isset($_POST['device_id']) && $_POST['device_id'] != '') {
                       $data->device_id = $_POST['device_id'];
                   }

                   if(isset($_POST['lang_pref']) && $_POST['lang_pref'] != '') {
                       $data->lang_pref = $_POST['lang_pref'];
                   }

                   $data->save(false);

                    $this->setHeader(200);
                    $result['code'] = 200;
                    $result['message'] = Yii::$app->params['successfully_logged_in'];
                    $result['status'] = Yii::$app->params['response_text'][$result['code']];
                    $result['user_info']['user_id'] = $data->id;
                    $result['user_info']['user_name'] = $data->username;
                    $result['user_info']['email'] = $data->email;
                    $result['user_info']['mobile'] = $data->mobile;
                    $result['user_info']['code'] = $data->country_code;
                    $result['user_info']['lang_pref'] = $data->lang_pref;
                    if ($data->image != '') {
                        $result['user_info']['profile_pic'] = Yii::getAlias('@web').'/img/uploads/user/'.$data->image;
                    }
                    $resultstring = json_encode($result);
                    $resultstring = str_replace("null",'""',$resultstring);
                    //echo $data->image;
                    echo $resultstring;
                    die;
                } else {
                    $this->setHeader(603);
                    echo json_encode(array('code'=>603,'status'=>'error','message'=>utf8_encode(Yii::$app->params['invalid_login_1'])));
                    die;
                }
            } else {
                $this->setHeader(400);
                echo json_encode(array('code'=>400,'status'=>'error','message'=>Yii::$app->params['response_text'][400]));
                die;
            }
        }

    /*
     *  API Name : Social Login
     *  Created By : aadil
     *  Creation Date : 05-08-2016
     *  Updated By :
     *  Updated Date :
     *  Input : userid
     *  Output :
     */
    public function actionDoSocialLogin()
    {
        //echo '<pre>';print_r($_POST);die;
        $this->layout = false;
       if(isset($_POST['social_type']) && $_POST['social_type'] != null &&
           isset($_POST['social_id']) && $_POST['social_id'] != null &&
           isset($_POST['email']) && $_POST['email'] != null &&
           isset($_POST['device_id']) && $_POST['device_id'] != null &&
           isset($_POST['device_type']) && $_POST['device_type'] != null &&
           isset($_POST['lang_pref']) && $_POST['lang_pref'] != null &&
           isset($_POST['user_name']) && $_POST['user_name'] != null &&
           isset($_POST['encrypted_data']) && $_POST['encrypted_data'] != null)
        {
            $check_user = Yii::$app->mycomponent->validate_user(0,$_POST['encrypted_data']);

            if($_POST['social_type'] == 'T')
            {
                $data = User::find()->where(['is_deleted'=>'N','twitter_id'=>$_POST['social_id']])->one();
            }
            else{
                $data = User::find()->where(['is_deleted'=>'N','google_id'=>$_POST['social_id']])->one();
            }

            if($data == array())
            {
                $exist = array();
                if(isset($_POST['email']) && $_POST['email'] != '')
                $exist = User::find()->where(['user_type'=>'U','is_deleted'=>'N','email'=>$_POST['email']])->one();
                if($exist == array())
                {
                    $data = new User();
                    if($_POST['social_type'] == 'T')
                    {
                        $data->twitter_id = $_POST['social_id'];

                        if(isset($_POST['type_image']) && $_POST['type_image'] != '')
                            $data->twitter_image = $_POST['type_image'];
                    }else{
                        $data->google_id = $_POST['social_id'];

                        if(isset($_POST['type_image']) && $_POST['type_image'] != '')
                            $data->google_image = $_POST['type_image'];
                    }




                    if(isset($_POST['email']) && $_POST['email'] != '')
                        $data->email = $_POST['email'];

                    if(isset($_POST['device_id']))
                        $data->device_id = $_POST['device_id'];

                    if(isset($_POST['device_type']))
                        $data->device_type = $_POST['device_type'];
                        
                        if(isset($_POST['lang_pref']) && $_POST['lang_pref'] != '') {
                       $data->lang_pref = $_POST['lang_pref'];
                   }

                    $data->i_date = date('Y-m-d');
                    $data->u_date = date('Y-m-d');

                    $data->save(false);

                    $data = User::find()->where(['id'=>$data->id])->one();

                    $result['User']['id'] = $data->id;
                    $result['User']['email'] = $data->email;
                    $result['User']['name'] = $data->name;
                    //$result['User']['last_name'] = $data->last_name;
                    //$result['User']['dial_code'] = $data->dial_code;
                    $result['User']['phone_no'] = $data->mobile;
                    $result['User']['lang_pref'] = $data->lang_pref;
                    //$result['User']['phone_verified'] = $data->phone_verified;

                    $result['User']['image'] = Yii::getAlias('@web').'/img/uploads/user/'.$data->image;
                    $result['User']['payment_mode'] = $data->payment_mode;

                    if(isset($_POST['device_id']) && $_POST['device_id'] != '')
                    {
                        $data1 = User::updateAll(['device_id' => null],'device_id = :device_id and id <> :user_id',array(':device_id'=>$_POST['device_id'],':user_id'=>$data->id));
                    }

                    $this->setHeader(200);
                    $result['code'] = 200;
                    $result['is_completed'] = "Y";
                    $result['is_exists'] = "Y";
                    $result['status'] = Yii::$app->params['response_text'][$result['code']];
                    $resultstring = json_encode($result);
                    $resultstring = str_replace("null",'""',$resultstring);
                    echo $resultstring ;
                    die;
                }
                else
                {
                    if(isset($_POST['device_id']))
                        $exist->device_id = $_POST['device_id'];

                    if(isset($_POST['device_type']))
                        $exist->device_type = $_POST['device_type'];
                        
                        if(isset($_POST['lang_pref']))
                        $exist->lang_pref = $_POST['lang_pref'];

                    $exist->save(false);

                    $result['User']['id'] = $exist->id;
                    $result['User']['email'] = $exist->email;
                    $result['User']['name'] = $exist->name;
                    //result['User']['last_name'] = $exist->last_name;
                    //$result['User']['dial_code'] = $data->dial_code;
                    $result['User']['phone_no'] = $exist->mobile;
                    $result['User']['lang_pref'] = $exist->lang_pref;
                    //$result['User']['phone_verified'] = $data->phone_verified;
                    $result['User']['image'] = Yii::getAlias('@web').'/img/uploads/user/'.$exist->image;
                    $result['User']['payment_mode'] = $exist->payment_mode;
                    $this->setHeader(200);
                    $result['code'] = 200;
                    $result['user_id'] = $exist->id;
                    $result['is_completed'] = $exist->is_completed;
                    $result['is_exists'] = "N";

                    $result['status'] = Yii::$app->params['response_text'][$result['code']];
                    $result['message'] = 'Do you want to merge your account with '.$exist->email;
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
                    
                     if(isset($_POST['lang_pref']))
                    $data->lang_pref = $_POST['lang_pref'];

                $data->save(false);

                $result['User']['id'] = $data->id;
                $result['User']['email'] = $data->email;
                $result['User']['name'] = $data->name;
                //$result['User']['last_name'] = $data->last_name;
                //$result['User']['dial_code'] = $data->dial_code;
                $result['User']['phone_no'] = $data->mobile;
                 $result['User']['lang_pref'] = $data->lang_pref;
                //$result['User']['phone_verified'] = $data->phone_verified;

                $result['User']['image'] = Yii::getAlias('@web').'/img/uploads/user/'.$data->image;
                $result['User']['payment_mode'] = $data->payment_mode;

                if(isset($_POST['device_id']) && $_POST['device_id'] != '')
                {
                    $data1 = User::updateAll(['device_id' => null],'device_id = :device_id and id <> :user_id',array(':device_id'=>$_POST['device_id'],':user_id'=>$data->id));
                }

                $this->setHeader(200);
                $result['code'] = 200;
                $result['is_completed'] = $data->is_completed;
                $result['is_exists'] = "N";
                $result['status'] = Yii::$app->params['response_text'][$result['code']];
                $resultstring = json_encode($result);
                $resultstring = str_replace("null",'""',$resultstring);
                echo $resultstring ;
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


    /*
     *  API Name : Merge Account
     *  Created By : aadil
     *  Creation Date : 05-08-2016
     *  Updated By :
     *  Updated Date :
     *  Input : userid
     *  Output :
     */
    public function actionMerge()
    {
        //echo '<pre>';print_r($_POST);die;
        $this->layout = false;
        if(isset($_POST['type']) && $_POST['type'] != null &&
           isset($_POST['type_id']) && $_POST['type_id'] != null &&
           isset($_POST['encrypted_data']) && $_POST['encrypted_data'] != null &&
           isset($_POST['userid']) && $_POST['userid'] != null)
        {
            $check_user = Yii::$app->mycomponent->validate_user($_POST['userid'],$_POST['encrypted_data']);

            $data = Users::find()->where(['is_deleted'=>'N','id'=>$_POST['userid']])->one();

            if($data != array())
            {

                if($_POST['type'] == 'T')
                {
                    $data->twitter_id = $_POST['type_id'];

                    if(isset($_POST['type_image']) && $_POST['type_image'] != '')
                        $data->twitter_image = $_POST['type_image'];
                }else{
                    $data->google_id = $_POST['type_id'];

                    if(isset($_POST['type_image']) && $_POST['type_image'] != '')
                        $data->google_image = $_POST['type_image'];
                }


                $data->u_date = date('Y-m-d');

                $data->save(false);

                $data = Users::find()->where(['id'=>$data->id])->one();

                $result['User']['id'] = $data->id;
                $result['User']['email'] = $data->email;
                $result['User']['name'] = $data->name;

                //$result['User']['dial_code'] = $data->dial_code;
                $result['User']['phone_no'] = $data->mobile;
                //$result['User']['phone_verified'] = $data->phone_verified;
                $result['User']['is_completed'] = $data->is_completed;
                $result['User']['image'] = Yii::getAlias('@web').'/img/uploads/users/'.$data->image;;

                if(isset($_POST['device_id']) && $_POST['device_id'] != '')
                {
                    $data1 = Users::updateAll(['device_id' => null],'device_id = :device_id and id <> :user_id',array(':device_id'=>$_POST['device_id'],':user_id'=>$data->id));
                }

                $this->setHeader(200);
                $result['code'] = 200;
                $result['status'] = Yii::$app->params['response_text'][$result['code']];
                $resultstring = json_encode($result);
                $resultstring = str_replace("null",'""',$resultstring);
                echo $resultstring ;
                die;

            }
            else
            {
                $this->setHeader(400);
                echo json_encode(array('code'=>400,'status'=>'error','message'=>Yii::t('app',Yii::$app->params['response_text'][400])));
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


    /*
     *  API Name : Social Registeration
     *  Created By : Indra
     *  Creation Date : 25-10-2016
     *  Updated By :
     *  Updated Date :
     *  Input : email,password,full_name,social_type,social_id,social_image
     *  Output :
     */
    public function actionSocialRegistration(){

            //echo "<pre>";
            //print_r($_POST);
            //exit;

         if(isset($_POST['social_type']) && $_POST['social_type'] != null &&
         isset($_POST['social_id']) && $_POST['social_id'] != null &&
         isset($_POST['device_id']) && $_POST['device_id'] != null &&
         isset($_POST['device_type']) && $_POST['device_type'] != null &&
        // isset($_POST['email']) && $_POST['email'] != null &&
         isset($_POST['user_name']) && $_POST['user_name'] != null &&
         isset($_POST['encrypted_data']) && $_POST['encrypted_data'] != null)
         {
            if(!in_array($_POST['social_type'],["G","F"])) {
             $this->setHeader(404);
             echo json_encode(array('code'=>404,'status'=>'error','message'=>Yii::t('app',Yii::$app->params['social_type_not_found'])));
             die;
        }
        $data = $result = array();



        if($_POST['social_type']=="G"){
          $data = User::find()->where(['google_id'=>$_POST['social_id']])->andwhere(['is_deleted'=>'N'])->one();
        }else if($_POST['social_type']=="F"){
          $data = User::find()->where(['twitter_id'=>$_POST['social_id']])->andwhere(['is_deleted'=>'N'])->one();
        }

        //echo "<pre>";
        //print_r($data);
        //exit;

         // code by Yasin
			  // If, social_id already exist, Do allow login directly
        if(isset($data) && $data!=array()) {

  				// Added by Paresh: 21-01-2017
  				// Saving language pref of user
  //				if(isset($_POST['pref_lang']) && !empty($_POST['pref_lang'])) {
  //  				  if($_POST['pref_lang']=='A')
  //  					$data->language = 'A';
  //  				  elseif($_POST['pref_lang']=='E')
  //  					$data->language = 'E';
  //  				  else
  //  					$data->language = 'E';
  //				}

          if(isset($_POST['device_id']) && !empty($_POST['device_id']))
              $data->device_id = urldecode($_POST['device_id']);

          if(isset($_POST['device_type']) && !empty($_POST['device_type']))
              $data->device_type = $_POST['device_type'];
              
               

          $data->save(false);
				  // End of code (Paresh): 21-01-2017

          $model = $data;

        //  echo "<pre>";
        //print_r($model);
        //exit;
          $result['user_info']['user_id'] = isset($model->id)?$model->id:"";
          $result['user_info']['user_name'] = isset($_POST['user_name'])?$_POST['user_name']:$data->username;
          $result['user_info']['email'] = isset($model->email)?$model->email:"";
          $result['user_info']['social_type'] = $_POST['social_type'];
          if($_POST['social_type'] == 'G')
          $result['user_info']['social_id'] = isset($model->google_id)?$model->google_id:"";
          else
          $result['user_info']['social_id'] = isset($model->facebook_id)?$model->facebook_id:"";
          //$result['user_info']['name'] =isset($model->name)?$model->name:"";;
          //$result['user_info']['user_pic'] = isset($model->full_image)?$model->full_image:"";

          $result['user_info']['pref_lang'] = (int)isset($model->lang_pref)?$model->lang_pref:"E";

          $this->setHeader(200);
          $result['code'] = 200;
          $result['status'] = Yii::$app->params['response_text'][$result['code']];
          $result['message'] = "You are successfully logged in";
        }
        //end

        else{

              if(isset($_POST['email']) && $_POST['email'] != '')
              {
                    $data = User::find()->where(['email'=>urldecode($_POST['email'])])->andwhere(['is_deleted'=>'N'])->one();
                    if(isset($data) && count($data)>0)
                    {
                       //if(!isset($_POST['is_merge']) || $_POST['is_merge'] == "false")
                       //{
                       //
                       //    $result['user_info']['user_id'] = isset($data->id)?$data->id:"";
                       //    $result['user_info']['user_name'] = isset($_POST['user_name'])?$_POST['user_name']:$data->username;
                       //    $result['user_info']['email'] = isset($data->email)?$data->email:"";
                       //    $result['user_info']['social_type'] = $_POST['social_type'];
                       //    if($_POST['social_type'] == 'G')
                       //     $result['user_info']['social_id'] = isset($data->google_id)?$data->google_id:"";
                       //    else
                       //     $result['user_info']['social_id'] = isset($data->facebook_id)?$data->facebook_id:"";
                       //   //$result['user_info']['pref_lang'] = (int)isset($data->lang_pref)?$data->lang_pref:"E";
                       //
                       //  $this->setHeader(601);
                       //  $result['code'] = 601;
                       //  $result['status'] = Yii::$app->params['response_text'][$result['code']];
                       //  $result['message'] = "An account is already associated with this email. If It's you, you can merge the account";
                       //}
       
                       ///else{
       
                            $model = $data;
                            //echo "<pre>";
                            //print_r($model);
                            //exit;
       
                           if($_POST['social_type'] == 'G')
                               {
                                   $model->google_id = $_POST['social_id'];
                                   if(isset($_POST['pic_url']))
                                   $model->google_image = $_POST['pic_url'];
                               }
       
                               else{
       
                                   $model->facebook_id = $_POST['social_id'];
                                   if(isset($_POST['pic_url']))
                                   $model->facebook_image = $_POST['pic_url'];
                               }
       
                               $model->username = $_POST['user_name'];
                               $model->email = $_POST['email'];
                               $model->u_date = ('Y-m-d');
       
                               if($model->save(false))
                                  {
       
                                      $data = User::find()->where(['id'=>$model->id])->one();
                                      //echo "<pre>";
                                      //print_r($data);
                                      //exit;
                                     $result['user_info']['user_id'] = isset($data->id)?$data->id:"";
                                     $result['user_info']['pay_fort_cus_id'] = isset($data->pay_fort_cus_id)?$data->pay_fort_cus_id:"";
                                     $result['user_info']['user_name'] = isset($_POST['user_name'])?$_POST['user_name']:$data->username;
                                     $result['user_info']['email'] = isset($data->email)?$data->email:"";
                                     if($_POST['social_type'] =='G')
                                     $result['user_info']['social_type'] = 'G';
                                     else
                                      $result['user_info']['social_type'] = 'T';
                                    if($_POST['social_type'] == 'G')
                                      $result['user_info']['social_id'] = isset($data->google_id)?$data->google_id:"";
                                   else
                                     $result['user_info']['social_id'] = isset($data->twitter_id)?$data->twitter_id:"";
                                     $result['user_info']['pref_lang'] = (int)isset($data->lang_pref)?$data->lang_pref:"E";
                                    $this->setHeader(200);
                                    $result['code'] = 200;
                                    $result['status'] = Yii::$app->params['response_text'][$result['code']];
                                    $result['message'] = "You are successfully logged in";
       
                                  }
                       //}
             }
             else{

                $model = new User();

                $model->name = $_POST['user_name'];
                $model->email = $_POST['email'];
                $model->username = $_POST['user_name'];
                $model->social_type = $_POST['social_type'];
                $model->lang_pref = (isset($_POST['lang_pref'])?$_POST['lang_pref']:'E');
                if($model->social_type == 'G')
                {
                  $model->google_id = $_POST['social_id'];
                 // $model->google_image = $_POST['pic_url'];
                }
                else
                {
                  $model->facebook_id = $_POST['social_id'];
                 // $model->facebook_image = $_POST['pic_url'];
                 }
                $model->is_deleted = "N";
                $model->is_active = "Y";
                $model->i_date = date('Y-m-d');
                $model->u_date = date('Y-m-d');
                 \Start::setApiKey("test_sec_k_6f8d68513f1bbdf7ab9dc");

                $pay_fort_result=\Start_Customer::create(array(
                                            "name" => $model->username,
                                            "email" => $model->email,
                                            "card" => array(),
                                            "description" => "Signed up at the Trade Show in Dec 2014"
                                          ));
                
                $model->pay_fort_cus_id = $pay_fort_result['id'];

                if($model->save(false))
                   {
                     $data = User::find()->where(['id'=>$model->id])->one();
                      $result['user_info']['user_id'] = $data->id;
                      $result['user_info']['pay_fort_cus_id'] = $data->pay_fort_cus_id;
                      $result['user_info']['user_name'] = isset($_POST['user_name'])?$_POST['user_name']:$data->username;
                      $result['user_info']['email'] = $data->email;
                      $this->setHeader(200);
                      $result['code'] = 200;
                      $result['status'] = Yii::$app->params['response_text'][$result['code']];
                      $result['message'] = "You are successfully logged in";
                   }

             }
          }
          else
          {

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



    /*
     *  API Name : Socail Register
     *  Created By : Aadil
     *  Creation Date : 4-11-2015
     *  Updated By :
     *  Updated Date :
     *  Input : social_type,email,name,socail_id,socail_image,gender
     *  Output :
     */

    public function actionSocialregister()
    {
        $this->layout = false;
        if(isset($_POST['social_type']) && $_POST['social_type'] != null &&
           isset($_POST['social_id']) && $_POST['social_id'] != null &&
           isset($_POST['encrypted_data']) && $_POST['encrypted_data'] != null)
        {
            $check_user = Yii::$app->mycomponent->validate_user(0,$_POST['encrypted_data']);

            if($_POST['social_type'] == 'F')
            $data = User::find()->where(['facebook_id'=>$_POST['social_id'],'user_type'=>'U','is_deleted'=>'N'])->one();
            elseif($_POST['social_type'] == 'T')
            $data = User::find()->where(['twitter_id'=>$_POST['social_id'],'user_type'=>'U','is_deleted'=>'N'])->one();
            else
            $data = User::find()->where(['google_id'=>$_POST['social_id'],'user_type'=>'U','is_deleted'=>'N'])->one();

            if(isset($_POST['email']) && $_POST['email'] != '' && $data == array())
            {
                $data = User::find()->where(['email'=>$_POST['email'],'user_type'=>'U','is_deleted'=>'N'])->one();
            }

            if($data == array())
            {
                $data = new User();

                if(isset($_POST['email']) && $_POST['email'] != '')
                $data->email = $_POST['email'];
                if(isset($_POST['name']) && $_POST['name'] != '')
                $data->name = $_POST['name'];
                if(isset($_POST['gender']) && $_POST['gender'] != '')
                $data->gender = $_POST['gender'];
                if(isset($_POST['username']) && $_POST['username'] != '')
                $data->username = $_POST['username'];

                if($_POST['social_type'] == 'F')
                {
                    $data->facebook_id = $_POST['social_id'];
                    if(isset($_POST['social_image']) && $_POST['social_image'] != '')
                    $data->facebook_image = $_POST['social_image'];
                }
                elseif($_POST['social_type'] == 'T')
                {
                    $data->twitter_id = $_POST['social_id'];
                    if(isset($_POST['social_image']) && $_POST['social_image'] != '')
                    $data->twitter_image = $_POST['social_image'];
                }
                else
                {
                    $data->google_id = $_POST['social_id'];
                    if(isset($_POST['social_image']) && $_POST['social_image'] != '')
                    $data->google_image = $_POST['social_image'];
                }
                $data->user_type = 'U';
                $data->i_date = time();
                $data->u_date = time();

                if(isset($_POST['device_type']) && $_POST['device_type'] != '')
                $data->device_type = $_POST['device_type'];

                if(isset($_POST['device_id']) && $_POST['device_id'] != '')
                $data->device_id = $_POST['device_id'];

                if($data->save(false))
                {
                    $type = 'W';
                    $message = 'Welcome to five claps';
                    $noti_message = 'Welcome to five claps';
                    Yii::$app->common->addnotification(null,$data->id,$type,$message,$noti_message,null,null);

                    $this->setHeader(200);
                    $result['code'] = 200;
                    $result['message'] = Yii::$app->params['register_success_message'];
                    $result['status'] = Yii::$app->params['response_text'][$result['code']];

                    $data = User::find()->where(['id'=>$data->id])->one();

                    $result['User']['id'] = $data->id;
                    $result['User']['name'] = $data->name;
                    $result['User']['email'] = $data->email;
                    $result['User']['username'] = $data->username;
                    $result['User']['image'] = $data->full_image;
                    $result['User']['gender'] = $data->gender;
                    $result['User']['location'] = $data->location;
                    $result['User']['latitude'] = $data->latitude;
                    $result['User']['longitude'] = $data->longitude;
                    $result['User']['is_social'] = 'Y';
                    $result['User']['set_privacy'] = $data->set_privacy;
                    $result['User']['notification'] = $data->receive_notification;
                    $result['User']['distance_type'] = $data->distance_type;
                    $result['User']['default_radius'] = $data->default_radius;
                    $result['User']['group_by_place'] = $data->group_by_place;

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
                if($data->is_active == 'N')
                {
                    $this->setHeader(400);
                    echo json_encode(array('code'=>400,'status'=>'error','message'=>Yii::$app->params['account_deactivated']));
                    die;
                }
                if($_POST['social_type'] == 'F')
                {
                    $data->facebook_id = $_POST['social_id'];
                    if(isset($_POST['social_image']) && $_POST['social_image'] != '')
                    $data->facebook_image = $_POST['social_image'];
                }
                elseif($_POST['social_type'] == 'T')
                {
                    $data->twitter_id = $_POST['social_id'];
                    if(isset($_POST['social_image']) && $_POST['social_image'] != '')
                    $data->twitter_image = $_POST['social_image'];
                }
                else
                {
                    $data->google_id = $_POST['social_id'];
                    if(isset($_POST['social_image']) && $_POST['social_image'] != '')
                    $data->google_image = $_POST['social_image'];
                }

                if(isset($_POST['device_type']) && $_POST['device_type'] != '')
                $data->device_type = $_POST['device_type'];

                if(isset($_POST['device_id']) && $_POST['device_id'] != '')
                $data->device_id = $_POST['device_id'];

                $data->save(false);

                $this->setHeader(200);
                $result['code'] = 200;
                $result['status'] = Yii::$app->params['response_text'][$result['code']];

                $data = User::find()->where(['id'=>$data->id])->one();

                $result['User']['id'] = $data->id;
                $result['User']['name'] = $data->name;
                $result['User']['email'] = $data->email;
                $result['User']['username'] = $data->username;
                $result['User']['image'] = $data->full_image;
                $result['User']['gender'] = $data->gender;
                $result['User']['location'] = $data->location;
                $result['User']['latitude'] = $data->latitude;
                $result['User']['longitude'] = $data->longitude;
                $result['User']['is_social'] = 'Y';
                $result['User']['set_privacy'] = $data->set_privacy;
                $result['User']['notification'] = $data->receive_notification;
                $result['User']['distance_type'] = $data->distance_type;
                $result['User']['default_radius'] = $data->default_radius;
                $result['User']['group_by_place'] = $data->group_by_place;

                $resultstring =  json_encode($result);
                $resultstring = str_replace("null",'""',$resultstring);
                echo $resultstring ;
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


    /*
     * Logout
     */
    public function actionLogout()
    {
        $this->layout = false;
        if(isset($_POST['userid']) && $_POST['userid'] != null
           && isset($_POST['encrypted_data']) && $_POST['encrypted_data'] != null)
        {
            $check_user = Yii::$app->mycomponent->validate_user($_POST['userid'],$_POST['encrypted_data']);
            User::updateAll(['device_id' => null,'device_type' => null],['id'=>$_POST['userid']]);

            $this->setHeader(200);
            $result['code'] = 200;
            $result['status'] = Yii::$app->params['response_text'][$result['code']];
            $resultstring = json_encode($result);
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

     /*
     *  API Name : Forgot Password
     *  Created By : aadil
     *  Creation Date : 4-11-2015
     *  Updated By :
     *  Updated Date :
     *  Input : email
     *  Output :
     */
    public function actionForgotPassword()
    {
        //echo $_POST['email'];
        //exit;
        $this->layout = false;
        if(isset($_POST['email']) && $_POST['email'] != null && isset($_POST['encrypted_data']) && $_POST['encrypted_data'] != null)
        {
            $check_user = Yii::$app->mycomponent->validate_user(0,$_POST['encrypted_data']);

            //check if user exist or not
            $data = User::find()->where(['email'=>$_POST['email'],'user_type'=>'U','is_deleted'=>'N'])->one();
            if($data)
            {
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
                        'username' => $data->username,
                        'link_token' => $data->forgot_password_token,
                    ])
                    ->setTo($_POST['email'])
                    ->setFrom(Yii::$app->params['adminEmail'])
                    ->setSubject(Yii::$app->params['adminEmail'].' : Reset Password Request')
                    ->send();

                    $this->setHeader(200);
                    $result['code'] = 200;
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
                    echo json_encode(array('code'=>602,'status'=>'error','message'=>utf8_encode(Yii::$app->params['error_forgot_password'])));
                    die;
                }
            }
            else
            {
                $this->setHeader(404);
                echo json_encode(array('code'=>404,'status'=>'error','message'=>utf8_encode(Yii::$app->params['error_forgot_password_email_not_found'])));
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

    /*
     *  API Name : Edit Profile
     *  Created By : aadil
     *  Creation Date : 05-11-2015
     *  Updated By :
     *  Updated Date :
     *  Input : userid,user's filed(which you want to edit)
     *  Output :
     */
    public function actionEditprofile()
    {
        
        $this->layout = false;
        if(isset($_POST['user_id']) && $_POST['user_id'] != null &&
           isset($_POST['email']) && $_POST['email'] != null &&
           isset($_POST['name']) && $_POST['name'] != null &&
           isset($_POST['country_code']) && $_POST['country_code'] != null &&
           isset($_POST['mobile']) && $_POST['mobile'] != null &&
           isset($_POST['encrypted_data']) && $_POST['encrypted_data'] != null)
        {
           
            $check_user = Yii::$app->mycomponent->validate_user($_POST['user_id'],$_POST['encrypted_data']);
            $data = User::find()->where(['id'=>$_POST['user_id'],'user_type'=>'U','is_deleted'=>'N'])->one();
    //echo '<pre>';
    //       print_r($data);
    //       exit;
            if($data != array())
            {
                //if(isset($_POST['username']) && $_POST['username'] != '')
                //{
                //    $exist = User::find()->where(['username'=>$_POST['username'],'user_type'=>'U',"is_deleted" => "N"])->andwhere('id <> '.$_POST['userid'])->all();
                //    if($exist != array())
                //    {
                //        $this->setHeader(404);
                //        echo json_encode(array('code'=>404,'status'=>'error','message'=>Yii::$app->params['username_exist_already']));
                //        die;
                //    }
                //    $data->username = $_POST['username'];
                //}

                //if(isset($_POST['name']) && $_POST['name'] != '')
                $data->name = $_POST['name'];
                $data->email = $_POST['email'];
                $data->country_code = $_POST['country_code'];
                $data->mobile = $_POST['mobile'];
                

                if(isset($_POST['username']) && $_POST['username'] != '')
                $data->username = $_POST['username'];
                
                if(isset($_POST['lang_pref']) && $_POST['lang_pref'] != '')
                $data->lang_pref = $_POST['lang_pref']; 
                
                if(isset($_FILES['image']['tmp_name']) && $_FILES['image']['tmp_name'] != null)
                {
                    if($data->image != '' && $data->image != null && file_exists(Yii::getAlias('@webroot').'/'.$data->image))
                    {
                        unlink(Yii::getAlias('@webroot')."/".$data->image);
                    }
                    //if($data->thumb_image != '' && $data->thumb_image != null && file_exists(Yii::getAlias('@webroot').'/'.$data->thumb_image))
                    //{
                    //    unlink(Yii::getAlias('@webroot')."/".$data->thumb_image);
                    //}

                    $image = $_FILES['image']['name'];
                    $ext = substr(strrchr($image, "."), 1);
                    $fileName = md5(rand() * time()) . ".$ext";
                    $data->image = Yii::$app->params['userimage'].$fileName;

                    //$name = Yii::$app->mycomponent->uploadUserImage($_FILES['image'], Yii::getAlias('@webroot')."/".Yii::$app->params['userimage'], 150, 150);
                    //$data->thumb_image = Yii::$app->params['userimage'].$name['image'];

                    move_uploaded_file($_FILES['image']['tmp_name'],Yii::getAlias('@webroot')."/".$data->image);
                }


                $data->u_by = $data->id;
                $data->u_date = date('Y-m-d');
                $data->save(false);

                $this->setHeader(200);
                $result['code'] = 200;
                $result['status'] = Yii::$app->params['response_text'][$result['code']];
                $result['message'] = "Profile Successfully Updated";

                $data = User::find()->where(['id'=>$data->id])->one();

                $result['user_info']['user_id'] = $data->id;
                $result['user_info']['user_name'] = $data->name;
                $result['user_info']['email'] = $data->email;
               // $result['user_info']['username'] = $data->username;
                $result['user_info']['mobile'] = $data->mobile;
                $result['user_info']['code'] = $data->country_code;
                //$result['User']['image'] = $data->full_image;
               $result['user_info']['lang_pref'] = $data->lang_pref;

                $resultstring = json_encode($result);
                $resultstring = str_replace("null",'""',$resultstring);
                echo $resultstring ;
                die;
            }else{
                $this->setHeader(400);
                echo json_encode(array('code'=>400,'status'=>'error','message'=>Yii::$app->params['error_user_not_found']));
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

    /*
     *  API Name : Change Password
     *  Created By : aadil
     *  Creation Date : 05-11-2015
     *  Updated By :
     *  Updated Date :
     *  Input : userid,oldpass,newpass
     *  Output :
     */
    public function actionChangepass()
    {
       
        $this->layout = false;
        if(isset($_POST['newpass']) && $_POST['newpass'] != null &&
           isset($_POST['oldpass']) && $_POST['oldpass'] != null &&
           isset($_POST['userid']) && $_POST['userid'] != null
           && isset($_POST['encrypted_data']) && $_POST['encrypted_data'] != null)
        {
            $check_user = Yii::$app->mycomponent->validate_user($_POST['userid'],$_POST['encrypted_data']);
            $data = User::find()->where(['id'=>$_POST['userid'],'user_type'=>'U','is_deleted'=>'N','password'=>md5($_POST['oldpass'])])->one();
             
            if($data != array())
            {
                $data->password = md5($_POST['newpass']);
        //        echo "<pre>";
        //print_r($data->password);
        //exit;
                $data->save(false);

                $this->setHeader(200);
                $result['code'] = 200;
                $result['message'] = Yii::$app->params['success_password_changed'];
                $result['status'] = Yii::$app->params['response_text'][$result['code']];

                $result['User']['id'] = $data->id;
                $result['User']['name'] = $data->name;
                $result['User']['email'] = $data->email;
                $result['User']['username'] = $data->username;
                //$result['User']['image'] = $data->full_image;
                $result['User']['mobile'] = $data->mobile;
                $result['User']['code'] = $data->country_code;
                
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
    
    public function actionCreateCard()
    {
        //echo "<pre>";
        //print_r($_POST);
        //exit;

        $this->layout = false;
        if(
           isset($_POST['user_id']) && $_POST['user_id'] != null &&
           isset($_POST['card_no']) && $_POST['card_no'] != null &&
           isset($_POST['exp_year']) && $_POST['exp_year'] != null &&
           isset($_POST['exp_month']) && $_POST['exp_month'] != null &&
           isset($_POST['cvc']) && $_POST['cvc'] != null &&
           isset($_POST['device_id']) && $_POST['device_id'] != null &&
           isset($_POST['device_type']) && $_POST['device_type'] != null &&
           isset($_POST['encrypted_data']) && $_POST['encrypted_data'] != null)
        {
            $check_user = Yii::$app->mycomponent->validate_user($_POST['user_id'],$_POST['encrypted_data']);
            //$data1 = User::find()->where(['username'=>$_POST['user_name'],'user_type'=>'U','is_deleted'=>'N'])->one();
            $data = User::find()->where(['id'=>$_POST['user_id'],'user_type'=>'U','is_deleted'=>'N'])->one();
            if(!empty($data))
            {
                $data = new User();
                
               \Start::setApiKey("test_sec_k_6f8d68513f1bbdf7ab9dc");

               $customer = \Start_Customer::get($data->pay_fort_cus_id);

                    $customer->cards->create(array(
                      "card" => array(
                        "name" => $data->username,
                        "number" => $_POST['card_no'],
                        "exp_month" => $_POST['exp_month'],
                        "exp_year" => $_POST['exp_year'],
                        "cvc" => $_POST['cvc']
                      )
                    ));
                    
                    echo "<pre>";
                    print_r($customer);
                    exit;
            
                $data->pay_fort_cus_id = $pay_fort_result['id'];
                
                $data->email = $_POST['email'];
                $data->username = $_POST['name'];
                $data->country_code = $_POST['country_code'];
                $data->mobile = $_POST['mobile'];
                $data->password = md5($_POST['password']);

                $data->user_type = 'U';
                $data->i_date = date('Y-m-d');
                $data->u_date = date('Y-m-d');

                if(isset($_POST['device_type']) && $_POST['device_type'] != '')
                $data->device_type = $_POST['device_type'];

                if(isset($_POST['device_id']) && $_POST['device_id'] != '')
                $data->device_id = $_POST['device_id'];
                
               if(isset($_POST['lang_pref']) && $_POST['lang_pref'] != '')
                $data->lang_pref = $_POST['lang_pref'];
               

                if($data->save(false))
                {
                    $type = 'W';
                    $message = 'Welcome to Donation App';
                    $noti_message = 'Welcome to Donation App';
                   // Yii::$app->common->addnotification(null,$data->id,$type,$message,$noti_message,null,null);

                    $this->setHeader(200);
                    $result['code'] = 200;
                    $result['message'] = Yii::$app->params['register_success_message'];
                    $result['status'] = Yii::$app->params['response_text'][$result['code']];

                    $data = User::find()->where(['id'=>$data->id])->one();

                    $result['user_info']['user_id'] = $data->id;
                    $result['user_info']['pay_fort_cus_id'] = $data->pay_fort_cus_id;
                    $result['user_info']['user_name'] = $data->username;

                    $result['user_info']['email'] = $data->email;
                    $result['user_info']['country_code'] = $data->country_code;
                    $result['user_info']['mobile'] = $data->mobile;
                    $result['user_info']['lang_pref'] = $data->lang_pref;
                    //$result['user_info']['image'] = Yii::$app->homeUrl.'img/uploads/user/'.$data->image;;

                    //$result['user_info']['is_social'] = 'N';


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
                    $this->setHeader(404);
                    echo json_encode(array('code'=>404,'status'=>'error','message'=>Yii::$app->params['error_user_not_found']));
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



    /*
     *  API Name : Change Password
     *  Created By : aadil
     *  Creation Date : 05-11-2015
     *  Updated By :
     *  Updated Date :
     *  Input : userid,oldpass,newpass
     *  Output :
     */
    public function actionCmspages()
    {
        $this->layout = false;
        //if(isset($_POST['userid']) && $_POST['userid'] != null
        //   && isset($_POST['encrypted_data']) && $_POST['encrypted_data'] != null)
        //{
        //    $check_user = Yii::$app->mycomponent->validate_user($_POST['userid'],$_POST['encrypted_data']);
        //    $data = User::find()->where(['id'=>$_POST['userid'],'user_type'=>'U','is_deleted'=>'N'])->one();
        //
        //    if($data != array())
        //    {
                $result['CMS']['privacy'] = Url::to(['/cms/privacy'], true);
                $result['CMS']['help'] = Url::to(['/cms/help'], true);
                $result['CMS']['terms'] = Url::to(['/cms/terms'], true);

                $this->setHeader(200);
                $result['code'] = 200;
                $result['status'] = Yii::$app->params['response_text'][$result['code']];
                $resultstring = json_encode($result);
                $resultstring = str_replace("null",'""',$resultstring);
                echo $resultstring ;
                die;
        //    }
        //    else
        //    {
        //        $this->setHeader(400);
        //        echo json_encode(array('code'=>400,'status'=>'error','message'=>Yii::$app->params['response_text'][400]));
        //        die;
        //    }
        //}
        //else
        //{
        //    $this->setHeader(400);
        //    echo json_encode(array('code'=>400,'status'=>'error','message'=>Yii::$app->params['response_text'][400]));
        //    die;
        //}
    }

    /*
     *  API Name : Follow / Unfollow User
     *  Created By : aadil
     *  Creation Date : 23-01-2016
     *  Updated By :
     *  Updated Date :
     *  Input : userid,oldpass,newpass
     *  Output :
     */
    public function actionFollow()
    {
        $this->layout = false;
        if(isset($_POST['userid']) && $_POST['userid'] != null
           && isset($_POST['encrypted_data']) && $_POST['encrypted_data'] != null
           && isset($_POST['to_id']) && $_POST['to_id'] != null
           && isset($_POST['is_follow']) && $_POST['is_follow'] != null)
        {
            $check_user = Yii::$app->mycomponent->validate_user($_POST['userid'],$_POST['encrypted_data']);
            $data = User::find()->where(['id'=>$_POST['userid'],'user_type'=>'U','is_deleted'=>'N'])->one();

            if($data != array())
            {
                if($_POST['to_id'] != $_POST['userid'])
                {
                    $to = User::find()->where(['id'=>$_POST['to_id'],'user_type'=>'U','is_deleted'=>'N'])->one();
                    $follow = Follow::find()->where(['from_id'=>$_POST['userid'],'to_id'=>$_POST['to_id']])->one();
                    if($follow == array())
                    {
                        $follow = new Follow();
                        $follow->from_id = $_POST['userid'];
                        $follow->to_id = $_POST['to_id'];
                    }
                    if($to->set_privacy == 'Y' && $_POST['is_follow'] == 'Y')
                    {
                        $follow->is_follow = 'R';
                        $type = 'R';
                        $message = '{from_user} requested to follow you.';
                        $noti_message = '@'.$data->username.' requested to follow you.';
                        Yii::$app->common->addnotification($_POST['userid'],$_POST['to_id'],$type,$message,$noti_message,null,null);
                    }else{
                        $follow->is_follow = $_POST['is_follow'];
                        if($_POST['is_follow'] == 'Y')
                        {
                            $type = 'F';
                            $message = '{from_user} started following you.';
                            $noti_message = '@'.$data->username.' started following you.';
                            Yii::$app->common->addnotification($_POST['userid'],$_POST['to_id'],$type,$message,$noti_message,null,null);
                        }
                    }
                    $follow->datetime = time();
                    $follow->save();


                    $result['is_follow'] = $follow->is_follow;
                    $this->setHeader(200);
                    $result['code'] = 200;
                    $result['status'] = Yii::$app->params['response_text'][$result['code']];
                    $resultstring = json_encode($result);
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
            else
            {
                $this->setHeader(400);
                echo json_encode(array('code'=>400,'status'=>'error','message'=>Yii::$app->params['response_text'][400]));
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

    /*
     *  API Name : Follower List
     *  Created By : aadil
     *  Creation Date : 2-02-2016
     *  Updated By :
     *  Updated Date :
     *  Input : userid
     *  Output :
     */
    public function actionFollowerlist()
    {
        $this->layout = false;
        if(isset($_POST['userid']) && $_POST['userid'] != null
           && isset($_POST['encrypted_data']) && $_POST['encrypted_data'] != null
           && isset($_POST['start']) && $_POST['start'] != null
           && isset($_POST['limit']) && $_POST['limit'] != null)
        {
            $check_user = Yii::$app->mycomponent->validate_user($_POST['userid'],$_POST['encrypted_data']);
            $data = User::find()->where(['id'=>$_POST['userid'],'user_type'=>'U','is_deleted'=>'N'])->one();

            $id = $_POST['userid'];
            if(isset($_POST['otherid']) && $_POST['otherid'] != null)
            $id = $_POST['otherid'];

            $start = $_POST['start'];
            $limit = $_POST['limit']+1;

            if($data != array())
            {
                $result['User'] = array();
                $result['is_last'] = 'Y';

                $block_id = Yii::$app->mycomponent->get_block_list($_POST['userid']);

                $follower = Follow::find()->where(['to_id'=>$id,'is_follow'=>'Y'])
                ->andwhere(['not in','from_id',$block_id])->all();

                $ids = ArrayHelper::map($follower,'from_id','from_id');
                $follower = User::find()->where(['id'=>$ids,'user_type'=>'U','is_deleted'=>'N'])->limit($limit)->offset($start)->all();
                if($follower != array())
                {
                    $i = 0;
                    foreach($follower as $user)
                    {
                        $result['User'][$i]['id'] = $user->id;
                        $result['User'][$i]['name'] = $user->username;
                        $result['User'][$i]['email'] = $user->email;
                        $result['User'][$i]['image'] = $user->full_image;

                        $result['User'][$i]['is_follow'] = 'N';
                        $follow = Follow::find()->where(['from_id'=>$_POST['userid'],'to_id'=>$user->id])->one();
                        if($follow != array())
                        $result['User'][$i]['is_follow'] = $follow->is_follow;

                        $i++;
                    }
                    if($i == $limit)
                    {
                        unset($result['User'][$i-1]);
                        $result['is_last'] = 'N';
                    }
                }


                $this->setHeader(200);
                $result['code'] = 200;
                $result['status'] = Yii::$app->params['response_text'][$result['code']];
                $resultstring = json_encode($result);
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
        else
        {
            $this->setHeader(400);
            echo json_encode(array('code'=>400,'status'=>'error','message'=>Yii::$app->params['response_text'][400]));
            die;
        }
    }

    /*
     *  API Name : Following List
     *  Created By : aadil
     *  Creation Date : 2-02-2016
     *  Updated By :
     *  Updated Date :
     *  Input : userid
     *  Output :
     */
    public function actionFollowinglist()
    {
        $this->layout = false;
        if(isset($_POST['userid']) && $_POST['userid'] != null
           && isset($_POST['encrypted_data']) && $_POST['encrypted_data'] != null
           && isset($_POST['start']) && $_POST['start'] != null
           && isset($_POST['limit']) && $_POST['limit'] != null)
        {
            $check_user = Yii::$app->mycomponent->validate_user($_POST['userid'],$_POST['encrypted_data']);
            $data = User::find()->where(['id'=>$_POST['userid'],'user_type'=>'U','is_deleted'=>'N'])->one();

            $id = $_POST['userid'];
            if(isset($_POST['otherid']) && $_POST['otherid'] != null)
            $id = $_POST['otherid'];

            $start = $_POST['start'];
            $limit = $_POST['limit']+1;

            if($data != array())
            {
                $result['User'] = array();
                $result['is_last'] = 'Y';

                $block_id = Yii::$app->mycomponent->get_block_list($_POST['userid']);
                $follower = Follow::find()->where(['from_id'=>$id,'is_follow'=>'Y'])
                ->andwhere(['not in','to_id',$block_id])->all();

                $ids = ArrayHelper::map($follower,'to_id','to_id');
                $follower = User::find()->where(['id'=>$ids,'user_type'=>'U','is_deleted'=>'N'])->limit($limit)->offset($start)->all();
                if($follower != array())
                {
                    $i = 0;
                    foreach($follower as $user)
                    {
                        $result['User'][$i]['id'] = $user->id;
                        $result['User'][$i]['name'] = $user->username;
                        $result['User'][$i]['email'] = $user->email;
                        $result['User'][$i]['image'] = $user->full_image;

                        $result['User'][$i]['is_follow'] = 'N';
                        $follow = Follow::find()->where(['from_id'=>$_POST['userid'],'to_id'=>$user->id])->one();
                        if($follow != array())
                        $result['User'][$i]['is_follow'] = $follow->is_follow;

                        $i++;
                    }
                    if($i == $limit)
                    {
                        unset($result['User'][$i-1]);
                        $result['is_last'] = 'N';
                    }
                }
                $this->setHeader(200);
                $result['code'] = 200;
                $result['status'] = Yii::$app->params['response_text'][$result['code']];
                $resultstring = json_encode($result);
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
        else
        {
            $this->setHeader(400);
            echo json_encode(array('code'=>400,'status'=>'error','message'=>Yii::$app->params['response_text'][400]));
            die;
        }
    }

    /*
     *  API Name : Requested List
     *  Created By : aadil
     *  Creation Date : 2-02-2016
     *  Updated By :
     *  Updated Date :
     *  Input : userid
     *  Output :
     */
    public function actionRequestedlist()
    {
        $this->layout = false;
        if(isset($_POST['userid']) && $_POST['userid'] != null
           && isset($_POST['encrypted_data']) && $_POST['encrypted_data'] != null
           && isset($_POST['start']) && $_POST['start'] != null
           && isset($_POST['limit']) && $_POST['limit'] != null)
        {
            $check_user = Yii::$app->mycomponent->validate_user($_POST['userid'],$_POST['encrypted_data']);
            $data = User::find()->where(['id'=>$_POST['userid'],'user_type'=>'U','is_deleted'=>'N'])->one();

            $start = $_POST['start'];
            $limit = $_POST['limit']+1;

            if($data != array())
            {
                $result['User'] = array();
                $result['is_last'] = 'Y';

                $block_id = Yii::$app->mycomponent->get_block_list($_POST['userid']);

                $follower = Follow::find()->where(['to_id'=>$_POST['userid'],'is_follow'=>'R'])
                ->andwhere(['not in','from_id',$block_id])->all();

                $ids = ArrayHelper::map($follower,'from_id','from_id');
                $follower = User::find()->where(['id'=>$ids,'user_type'=>'U','is_deleted'=>'N'])->limit($limit)->offset($start)->all();
                if($follower != array())
                {
                    $i = 0;
                    foreach($follower as $user)
                    {
                        $result['User'][$i]['id'] = $user->id;
                        $result['User'][$i]['name'] = $user->username;
                        $result['User'][$i]['email'] = $user->email;
                        $result['User'][$i]['image'] = $user->full_image;
                        $i++;
                    }
                    if($i == $limit)
                    {
                        unset($result['User'][$i-1]);
                        $result['is_last'] = 'N';
                    }
                }
                $this->setHeader(200);
                $result['code'] = 200;
                $result['status'] = Yii::$app->params['response_text'][$result['code']];
                $resultstring = json_encode($result);
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
        else
        {
            $this->setHeader(400);
            echo json_encode(array('code'=>400,'status'=>'error','message'=>Yii::$app->params['response_text'][400]));
            die;
        }
    }

    /*
     *  API Name : Facebook Friend List
     *  Created By : aadil
     *  Creation Date : 2-02-2016
     *  Updated By :
     *  Updated Date :
     *  Input : userid
     *  Output :
     */
    public function actionFbfriend()
    {
        $this->layout = false;
        if(isset($_POST['userid']) && $_POST['userid'] != null
           && isset($_POST['encrypted_data']) && $_POST['encrypted_data'] != null)
           /*&& isset($_POST['friend_ids']) && $_POST['friend_ids'] != null*/
        {
            $check_user = Yii::$app->mycomponent->validate_user($_POST['userid'],$_POST['encrypted_data']);
            $data = User::find()->where(['id'=>$_POST['userid'],'user_type'=>'U','is_deleted'=>'N'])->one();
            $result['Friend'] = array();

            if($data != array())
            {
                if(isset($_POST['friend_ids']) && $_POST['friend_ids'] != null)
                {
                    $friends = Follow::find()->where(['from_id'=>$_POST['userid']])->all();
                    $status = ArrayHelper::map($friends,'to_id','is_follow');

                    $ids = json_decode($_POST['friend_ids'],true);
                    //echo '<pre>';print_r($ids);die;

                    $block_id = Yii::$app->mycomponent->get_block_list($_POST['userid']);

                    $follower = User::find()->where(['facebook_id'=>$ids,'user_type'=>'U','is_deleted'=>'N'])
                    ->andwhere(['not in','id',$block_id])->all();
                    if($follower != array())
                    {
                        $i = 0;
                        foreach($follower as $user)
                        {
                            $result['Friend'][$i]['id'] = $user->id;
                            $result['Friend'][$i]['name'] = $user->name;
                            $result['Friend'][$i]['email'] = $user->email;
                            $result['Friend'][$i]['image'] = $user->full_image;

                            $result['Friend'][$i]['is_follow'] = 'N';
                            if(isset($status[$user->id]))
                            $result['Friend'][$i]['is_follow'] = $status[$user->id];

                            $i++;
                        }
                    }
                }
                $this->setHeader(200);
                $result['code'] = 200;
                $result['status'] = Yii::$app->params['response_text'][$result['code']];
                $resultstring = json_encode($result);
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
        else
        {
            $this->setHeader(400);
            echo json_encode(array('code'=>400,'status'=>'error','message'=>Yii::$app->params['response_text'][400]));
            die;
        }
    }

    /*
     *  API Name : Search User
     *  Created By : aadil
     *  Creation Date : 23-01-2016
     *  Updated By :
     *  Updated Date :
     *  Input : userid,keyword
     *  Output :
     */
    public function actionSearch()
    {
        $this->layout = false;
        if(isset($_POST['userid']) && $_POST['userid'] != null
           && isset($_POST['encrypted_data']) && $_POST['encrypted_data'] != null
           && isset($_POST['keyword']) && $_POST['keyword'] != null)
        {
            $check_user = Yii::$app->mycomponent->validate_user($_POST['userid'],$_POST['encrypted_data']);
            $data = User::find()->where(['id'=>$_POST['userid'],'user_type'=>'U','is_active'=>'Y','is_deleted'=>'N'])->one();
            if($data != array())
            {
                $friends = Follow::find()->where(['from_id'=>$_POST['userid'],'is_follow'=>'Y'])->all();
                $fids = ArrayHelper::map($friends,'to_id','to_id');
                $search_name = $_POST['keyword'];
                $result['User'] = array();

                $block_id = Yii::$app->mycomponent->get_block_list($_POST['userid']);

                $query = User::find()->where(['or',['like', 'username', $search_name],['like', 'name', $search_name]])->andwhere(['user_type'=>'U',"is_deleted" => "N"])
                ->andwhere('id <> '.$_POST['userid'])->andwhere(['not in','id',$block_id]);
                if(isset($_POST['type']) && $_POST['type'] == 'S')
                {
                    $suggest = Follow::find()->where(['from_id'=>$fids,'is_follow'=>'Y'])->all();
                    $sids = ArrayHelper::map($suggest,'to_id','to_id');
                    $fids[$_POST['userid']] = $_POST['userid'];
                    $sids = array_diff($sids,$fids);
                    $query->andwhere(['id'=>$sids]);
                    $query->limit(25);
                }
                if(isset($_POST['type']) && $_POST['type'] == 'P')
                {
                    $friends = Follow::find()->select('count(*) as id,to_id')->where(['is_follow'=>'Y'])->groupBy('to_id')->all();
                    $fids = ArrayHelper::map($friends,'to_id','id');
                    arsort($fids);
                    $sids = array_keys($fids);
                    $query->andwhere(['id'=>$sids]);
                    $str = implode(',',$sids);
                    //$query->orderBy('FIELD(id,'.$str.')');
                    $query->limit(25);
                }else
                {
                    //->orderBy('LOCATE("'.$search_name.'", `name`)')
                    $query->orderBy('CASE WHEN `name` LIKE "'.$search_name.'%" THEN 1 WHEN `name` LIKE "%'.$search_name.'" THEN 2 ELSE 3 END');
                }
                $users = $query->all();
                if($users != array())
                {
                    $i = 0;
                    foreach($users as $user)
                    {
                        $result['User'][$i]['id'] = $user->id;
                        $result['User'][$i]['name'] = $user->username;
                        $result['User'][$i]['email'] = $user->email;
                        $result['User'][$i]['image'] = $user->full_image;
                        $result['User'][$i]['is_follow'] = 'N';
                        if(in_array($user->id,$fids))
                        $result['User'][$i]['is_follow'] = 'Y';

                        if(isset($_POST['type']) && $_POST['type'] == 'P')
                        {
                            //$result['User'][$i]['count'] = Follow::find()->where(['to_id'=>$user->id,'is_follow'=>'Y'])->count();
                            $result['User'][$i]['count'] = $fids[$user->id];
                        }
                        $i++;
                    }
                    //echo '<pre>';print_r($result);die;
                    if(isset($_POST['type']) && $_POST['type'] == 'P' && $result['User'] != array())
                    {
                        ArrayHelper::multisort($result['User'],'count',SORT_DESC);
                    }
                }


                $this->setHeader(200);
                $result['code'] = 200;
                $result['status'] = Yii::$app->params['response_text'][$result['code']];
                $resultstring = json_encode($result);
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
        else
        {
            $this->setHeader(400);
            echo json_encode(array('code'=>400,'status'=>'error','message'=>Yii::$app->params['response_text'][400]));
            die;
        }
    }

    /*
     *  API Name : Deactivate Account
     *  Created By : aadil
     *  Creation Date : 23-01-2016
     *  Updated By :
     *  Updated Date :
     *  Input : userid,keyword
     *  Output :
     */
    public function actionDeactivate()
    {
        $this->layout = false;
        if(isset($_POST['userid']) && $_POST['userid'] != null
           && isset($_POST['encrypted_data']) && $_POST['encrypted_data'] != null)
        {
            $check_user = Yii::$app->mycomponent->validate_user($_POST['userid'],$_POST['encrypted_data']);
            $data = User::find()->where(['id'=>$_POST['userid'],'user_type'=>'U','is_deleted'=>'N'])->one();

            if($data != array())
            {
                $data->is_active = 'N';
                $data->save(false);

                Post::updateAll(['is_deleted' => 'Y'],'user_id = '.$data->id);
                Notification::updateAll(['is_deleted' => 'Y'],'from_id = '.$data->id);

                Follow::updateAll(['is_follow' => 'N'],'from_id = '.$data->id);
                Follow::updateAll(['is_follow' => 'N'],'to_id = '.$data->id);

                $this->setHeader(200);
                $result['code'] = 200;
                $result['status'] = Yii::$app->params['response_text'][$result['code']];
                $resultstring = json_encode($result);
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
        else
        {
            $this->setHeader(400);
            echo json_encode(array('code'=>400,'status'=>'error','message'=>Yii::$app->params['response_text'][400]));
            die;
        }
    }

    /*
     *  API Name : Follow / Unfollow User
     *  Created By : aadil
     *  Creation Date : 23-01-2016
     *  Updated By :
     *  Updated Date :
     *  Input : userid,from_id,accept
     *  Output :
     */
    public function actionAccept()
    {
        $this->layout = false;
        if(isset($_POST['userid']) && $_POST['userid'] != null
           && isset($_POST['encrypted_data']) && $_POST['encrypted_data'] != null
           && isset($_POST['from_id']) && $_POST['from_id'] != null
           && isset($_POST['accept']) && $_POST['accept'] != null)
        {
            $check_user = Yii::$app->mycomponent->validate_user($_POST['userid'],$_POST['encrypted_data']);
            $data = User::find()->where(['id'=>$_POST['userid'],'user_type'=>'U','is_deleted'=>'N'])->one();

            if($data != array())
            {
                $from = User::find()->where(['id'=>$_POST['from_id'],'user_type'=>'U','is_deleted'=>'N'])->one();
                $follow = Follow::find()->where(['from_id'=>$_POST['from_id'],'to_id'=>$_POST['userid']])->one();
                if($follow != array())
                {
                    $follow->is_follow = $_POST['accept'];
                    $follow->datetime = time();
                    $follow->save();

                    Notification::updateAll(['is_read' => 'Y'], 'from_id = '.$_POST['from_id'].' and to_id = '.$_POST['userid'].' and type = "R"');

                    $this->setHeader(200);
                    $result['code'] = 200;
                    $result['status'] = Yii::$app->params['response_text'][$result['code']];
                    $resultstring = json_encode($result);
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
            else
            {
                $this->setHeader(400);
                echo json_encode(array('code'=>400,'status'=>'error','message'=>Yii::$app->params['response_text'][400]));
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

    /*
     *  API Name : User Notification List
     *  Created By : aadil
     *  Creation Date : 29-03-2016
     *  Updated By :
     *  Updated Date :
     *  Input : userid,
     *  Output :
     */
    public function actionNotifications()
    {
        $this->layout = false;
        if(isset($_POST['userid']) && $_POST['userid'] != null
           && isset($_POST['encrypted_data']) && $_POST['encrypted_data'] != null
           && isset($_POST['start']) && $_POST['start'] != null
           && isset($_POST['limit']) && $_POST['limit'] != null)
        {
            $check_user = Yii::$app->mycomponent->validate_user($_POST['userid'],$_POST['encrypted_data']);
            $data = User::find()->where(['id'=>$_POST['userid'],'user_type'=>'U','is_deleted'=>'N'])->one();

            if($data != array())
            {
                $result['Notification'] = array();
                $result['is_last'] = 'Y';
                $start = $_POST['start'];
                $limit = $_POST['limit']+1;
                $block_id = Yii::$app->mycomponent->get_block_list($_POST['userid']);
                $notification = Notification::find()->where(['to_id'=>$_POST['userid'],'is_deleted'=>'N'])
                ->andwhere(['not in','from_id',$block_id])
                ->offset($start)->limit($limit)->orderBy('id desc')->all();

                if($notification != array())
                {
                    $i = 0;
                    foreach($notification as $noti)
                    {
                        $result['Notification'][$i]['id'] = $noti->id;

                        $result['Notification'][$i]['from_id'] = $noti->from_id;
                        $result['Notification'][$i]['from_name'] = '';
                        $result['Notification'][$i]['from_image'] = '';
                        $from = User::find()->where(['id'=>$noti->from_id,'is_deleted'=>'N'])->one();
                        if($from != array())
                        {
                            $result['Notification'][$i]['from_name'] = '@'.$from->username;
                            $result['Notification'][$i]['from_image'] = $from->full_image;
                        }

                        $result['Notification'][$i]['post_id'] = $noti->post_id;
                        $result['Notification'][$i]['post_name'] = '';
                        $result['Notification'][$i]['post_image'] = '';
                        if($noti->post_id != '')
                        {
                            $post = Post::find()->where(['id'=>$noti->post_id,'is_deleted'=>'N'])->one();
                            if($post != array())
                            $result['Notification'][$i]['post_name'] = $post->description;

                            $image = Images::find()->where(['post_id'=>$noti->post_id,'is_deleted'=>'N'])->one();
                            if($image != array())
                            $result['Notification'][$i]['post_image'] = $image->full_image;
                        }
                        $result['Notification'][$i]['comment_id'] = $noti->comment_id;

                        $result['Notification'][$i]['type'] = $noti->type;
                        $result['Notification'][$i]['is_read'] = $noti->is_read;
                        $result['Notification'][$i]['datetime'] = $noti->datetime;
                        //$result['Notification'][$i]['message'] = $noti->message;
                        $i++;
                    }
                    if($i == $limit)
                    {
                        unset($result['Notification'][$i-1]);
                        $result['is_last'] = 'N';
                    }
                }
                $this->setHeader(200);
                $result['code'] = 200;
                $result['status'] = Yii::$app->params['response_text'][$result['code']];
                $resultstring = json_encode($result);
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
        else
        {
            $this->setHeader(400);
            echo json_encode(array('code'=>400,'status'=>'error','message'=>Yii::$app->params['response_text'][400]));
            die;
        }
    }

    /*
     *  API Name : Read Notification
     *  Created By : aadil
     *  Creation Date : 29-03-2016
     *  Updated By :
     *  Updated Date :
     *  Input : userid,noti_id
     *  Output :
     */
    public function actionReadnoti()
    {
        $this->layout = false;
        if(isset($_POST['userid']) && $_POST['userid'] != null
           && isset($_POST['encrypted_data']) && $_POST['encrypted_data'] != null
           && isset($_POST['noti_id']) && $_POST['noti_id'] != null)
        {
            $check_user = Yii::$app->mycomponent->validate_user($_POST['userid'],$_POST['encrypted_data']);
            $data = User::find()->where(['id'=>$_POST['userid'],'user_type'=>'U','is_deleted'=>'N'])->one();

            if($data != array())
            {
                $notification = Notification::find()->where(['id'=>$_POST['noti_id'],'to_id'=>$_POST['userid'],'is_deleted'=>'N'])->one();

                if($notification != array())
                {
                    $notification->is_read = 'Y';
                    $notification->save();

                    $this->setHeader(200);
                    $result['code'] = 200;
                    $result['status'] = Yii::$app->params['response_text'][$result['code']];
                    $resultstring = json_encode($result);
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
            else
            {
                $this->setHeader(400);
                echo json_encode(array('code'=>400,'status'=>'error','message'=>Yii::$app->params['response_text'][400]));
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

    /*
     *  API Name : Notification Count
     *  Created By : aadil
     *  Creation Date : 15-04-2016
     *  Updated By :
     *  Updated Date :
     *  Input : userid,
     *  Output :
     */
    public function actionNoticount()
    {
        $this->layout = false;
        if(isset($_POST['userid']) && $_POST['userid'] != null
           && isset($_POST['encrypted_data']) && $_POST['encrypted_data'] != null)
        {
            $check_user = Yii::$app->mycomponent->validate_user($_POST['userid'],$_POST['encrypted_data']);
            $data = User::find()->where(['id'=>$_POST['userid'],'user_type'=>'U','is_deleted'=>'N'])->one();

            if($data != array())
            {
                $block_id = Yii::$app->mycomponent->get_block_list($_POST['userid']);
                $notification = Notification::find()->where(['to_id'=>$_POST['userid'],'is_read'=>'N','is_deleted'=>'N'])
                ->andwhere(['not in','from_id',$block_id])
                ->count();

                $result['noti_count'] = $notification;
                $this->setHeader(200);
                $result['code'] = 200;
                $result['status'] = Yii::$app->params['response_text'][$result['code']];
                $resultstring = json_encode($result);
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
        else
        {
            $this->setHeader(400);
            echo json_encode(array('code'=>400,'status'=>'error','message'=>Yii::$app->params['response_text'][400]));
            die;
        }
    }

    /*
     *  API Name : Block User
     *  Created By : aadil
     *  Creation Date : 22-03-2017
     *  Updated By :
     *  Updated Date :
     *  Input : userid,
     *  Output :
     */
    public function actionBlock()
    {
        $this->layout = false;
        if(isset($_POST['userid']) && $_POST['userid'] != null
           && isset($_POST['encrypted_data']) && $_POST['encrypted_data'] != null
           && isset($_POST['to_id']) && $_POST['to_id'] != null
           && isset($_POST['is_block']) && $_POST['is_block'] != null)
        {
            $check_user = Yii::$app->mycomponent->validate_user($_POST['userid'],$_POST['encrypted_data']);
            $data = User::find()->where(['id'=>$_POST['userid'],'user_type'=>'U','is_deleted'=>'N'])->one();

            if($data != array())
            {
                $block = Blockuser::find()->where(['from_id'=>$_POST['userid'],'to_id'=>$_POST['to_id'],'is_deleted'=>'N'])->one();
                if($block == array())
                {
                    $block = new Blockuser();
                    $block->from_id = $_POST['userid'];
                    $block->to_id = $_POST['to_id'];
                    $block->i_by = $_POST['userid'];
                    $block->i_date = time();
                }
                $block->is_block = $_POST['is_block'];
                $block->u_by = $_POST['userid'];
                $block->u_date = time();
                $block->save(false);

                $this->setHeader(200);
                $result['code'] = 200;
                $result['status'] = Yii::$app->params['response_text'][$result['code']];
                $resultstring = json_encode($result);
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
        else
        {
            $this->setHeader(400);
            echo json_encode(array('code'=>400,'status'=>'error','message'=>Yii::$app->params['response_text'][400]));
            die;
        }
    }

    /*
     *  API Name : Block User List
     *  Created By : aadil
     *  Creation Date : 22-03-2017
     *  Updated By :
     *  Updated Date :
     *  Input : userid,
     *  Output :
     */
    public function actionBlocklist()
    {
        $this->layout = false;
        if(isset($_POST['userid']) && $_POST['userid'] != null
           && isset($_POST['encrypted_data']) && $_POST['encrypted_data'] != null)
        {
            $check_user = Yii::$app->mycomponent->validate_user($_POST['userid'],$_POST['encrypted_data']);
            $data = User::find()->where(['id'=>$_POST['userid'],'user_type'=>'U','is_deleted'=>'N'])->one();

            if($data != array())
            {
                $result['User'] = array();
                $blocks = Blockuser::find()->where(['from_id'=>$_POST['userid'],'is_block'=>'Y','is_deleted'=>'N'])->all();
                if($blocks != array())
                {
                    $i = 0;
                    foreach($blocks as $list)
                    {
                        $user = User::find()->where(['id'=>$list->to_id])->one();

                        if($user != array())
                        {
                            $result['User'][$i]['id'] = $user->id;
                            $result['User'][$i]['name'] = $user->username;
                            $result['User'][$i]['image'] = $user->full_image;
                            $i++;
                        }
                    }
                }

                $this->setHeader(200);
                $result['code'] = 200;
                $result['status'] = Yii::$app->params['response_text'][$result['code']];
                $resultstring = json_encode($result);
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
        else
        {
            $this->setHeader(400);
            echo json_encode(array('code'=>400,'status'=>'error','message'=>Yii::$app->params['response_text'][400]));
            die;
        }
    }

    /*
     *  API Name : Home Page
     *  Created By : aadil
     *  Creation Date : 22-03-2017
     *  Updated By :
     *  Updated Date :
     *  Input : userid,
     *  Output :
     */
    public function actionHomepage()
    {
        $this->layout = false;
        if(isset($_POST['userid']) && $_POST['userid'] != null
           && isset($_POST['encrypted_data']) && $_POST['encrypted_data'] != null
           && isset($_POST['latitude']) && $_POST['latitude'] != null
           && isset($_POST['longitude']) && $_POST['longitude'] != null
           && isset($_POST['friend_ids']) && $_POST['friend_ids'] != null)
        {
            $check_user = Yii::$app->mycomponent->validate_user($_POST['userid'],$_POST['encrypted_data']);
            $data = User::find()->where(['id'=>$_POST['userid'],'user_type'=>'U','is_deleted'=>'N'])->one();

            $lat = $_POST['latitude'];
            $lng = $_POST['longitude'];
            $distance = 25;//miles

            if($data != array())
            {
                $block_id = Yii::$app->mycomponent->get_block_list($_POST['userid']);
                //echo '<pre>';print_r($block_id);die;
                //category data
                $result['Category'] = array();
                $categoryData = Category::find()->where(['is_active'=>'Y','is_deleted'=>'N'])->limit(6)->all();/*limit($limit)->offset($start)->*/
                if($categoryData != array())
                {
                    $i=0;
                    foreach($categoryData as $data)
                    {
                        $result['Category'][$i]['id'] = $data->id;
                        $result['Category'][$i]['name'] = $data->name;
                        $result['Category'][$i]['description'] = $data->description;
                        $result['Category'][$i]['color_code'] = $data->color_code;
                        $result['Category'][$i]['image'] = $data->full_image;

                        $count = Post::find()->select("*,( 3959 * acos( cos( radians($lat) ) * cos( radians( latitude) ) * cos( radians( longitude ) - radians($lng) ) + sin( radians($lat) ) * sin( radians( latitude ) ) ) ) AS distance")
                                ->where(['category_id'=>$data->id,'is_deleted'=>'N'])
                                ->andwhere(['not in','user_id',$block_id])
                                ->andHaving("distance < $distance")->count();

                        $result['Category'][$i]['count'] = $count;
                        $i++;
                    }
                }

                //friend suggestion data

                $friends = Follow::find()->where(['from_id'=>$_POST['userid'],'is_follow'=>'Y'])->all();
                $fids = ArrayHelper::map($friends,'to_id','to_id');
                $follow_ids = ArrayHelper::map($friends,'to_id','to_id');

                $suggest = Follow::find()->where(['from_id'=>$fids,'is_follow'=>'Y'])->all();
                $sids = ArrayHelper::map($suggest,'to_id','to_id');
                $fids[$_POST['userid']] = $_POST['userid'];
                $sids = array_diff($sids,$fids);

                $query = User::find()->where(['user_type'=>'U',"is_deleted" => "N"])->andwhere('id <> '.$_POST['userid']);
                $query->andwhere(['id'=>$sids]);
                $query->andwhere(['not in','id',$block_id]);
                $users = $query->limit(5)->all();

                $result['User'] = array();

                if($users != array())
                {
                    $i = 0;
                    foreach($users as $user)
                    {
                        $result['User'][$i]['id'] = $user->id;
                        $result['User'][$i]['name'] = $user->username;
                        $result['User'][$i]['image'] = $user->full_image;
                        $i++;
                    }
                    if(count($users) < 5)
                    {
                        $exist = ArrayHelper::map($users,'id','id');
                        $query = User::find()->where(['user_type'=>'U',"is_deleted" => "N"])->andwhere('id <> '.$_POST['userid']);
                        //$sids = array_keys(array_diff($fids,$exist));
                        //$query->andwhere(['id'=>$sids]);
                        $block_id = array_merge($fids,$exist,$block_id);
                        $query->andwhere(['not in','id',$block_id]);
                        $str = implode(',',$sids);
                        $users = $query->limit(5-count($users))->all();
                        foreach($users as $user)
                        {
                            $result['User'][$i]['id'] = $user->id;
                            $result['User'][$i]['name'] = $user->username;
                            $result['User'][$i]['image'] = $user->full_image;
                            $i++;
                        }
                    }
                }else{
                    $query = User::find()->where(['user_type'=>'U',"is_deleted" => "N"])->andwhere('id <> '.$_POST['userid']);
                    $sids = array_keys($follow_ids);
                    $query->andwhere(['id'=>$sids]);
                    $query->andwhere(['not in','id',$block_id]);
                    $str = implode(',',$sids);
                    //$query->orderBy('FIELD(id,'.$str.')');
                    $users = $query->limit(5)->all();
                    $i = 0;
                    foreach($users as $user)
                    {
                        $result['User'][$i]['id'] = $user->id;
                        $result['User'][$i]['name'] = $user->username;
                        $result['User'][$i]['image'] = $user->full_image;
                        $i++;
                    }
                }

                //activity

                $fb_ids = json_decode($_POST['friend_ids'],true);
                if($fb_ids != array())
                {
                    $friends = User::find()->where(['facebook_id'=>$fb_ids,'user_type'=>'U','is_deleted'=>'N'])->all();
                    $fids = ArrayHelper::map($friends,'id','id');
                    $follow_ids = array_merge($fids,$follow_ids);
                }

                $result['Activity'] = array();

                $days = 7;
                $start_date = strtotime("-$days days");

                $activity = Post::find()->select("*,( 3959 * acos( cos( radians($lat) ) * cos( radians( latitude) ) * cos( radians( longitude ) - radians($lng) ) + sin( radians($lat) ) * sin( radians( latitude ) ) ) ) AS distance")
                ->where(['user_id'=>$follow_ids,'is_deleted'=>'N'])
                ->andwhere(['not in','user_id',$block_id])
                ->andwhere('i_date > '.$start_date)
                ->andHaving("distance < $distance")
                ->limit(5)->orderBy('distance asc')->all();

                if($activity != array())
                {
                    $i = 0;
                    foreach($activity as $post)
                    {
                        $result['Activity'][$i]['id'] = $post->id;
                        $result['Activity'][$i]['category_id'] = $post->category_id;

                        $result['Activity'][$i]['color_code'] = '';
                        if(isset($category[$post->category_id]))
                        $result['Activity'][$i]['color_code'] = $category[$post->category_id];

                        $result['Activity'][$i]['category_image'] = '';
                        if(isset($category_image[$post->category_id]))
                        $result['Activity'][$i]['category_image'] = $category_image[$post->category_id];

                        $result['Activity'][$i]['category_name'] = '';
                        if(isset($category_name[$post->category_id]))
                        $result['Activity'][$i]['category_name'] = $category_name[$post->category_id];

                        $result['Activity'][$i]['description'] = $post->description;
                        $result['Activity'][$i]['location'] = $post->location;
                        $result['Activity'][$i]['latitude'] = $post->latitude;
                        $result['Activity'][$i]['longitude'] = $post->longitude;
                        $result['Activity'][$i]['datetime'] = $post->i_date;
                        $result['Activity'][$i]['no_of_like'] = $post->no_of_like;
                        $result['Activity'][$i]['no_of_comment'] = $post->no_of_comment;

                        $user = User::find()->where(['id'=>$post->user_id])->one();

                        $result['Activity'][$i]['User']['id'] = $user->id;
                        $result['Activity'][$i]['User']['name'] = $user->username;
                        $result['Activity'][$i]['User']['image'] = $user->full_image;
                        $i++;
                    }
                }

                //featured places
                $query = Post::find()->select("*,GROUP_CONCAT( id ) AS id_list,count(*) as id,sum(no_of_like) as no_of_like,sum(no_of_comment) as no_of_comment,( 3959 * acos( cos( radians($lat) ) * cos( radians( latitude) ) * cos( radians( longitude ) - radians($lng) ) + sin( radians($lat) ) * sin( radians( latitude ) ) ) ) AS distance")
                ->where(['is_deleted'=>'N'])->andwhere('i_date > '.$start_date);
                //$query->select("*,GROUP_CONCAT( id ) AS id_list,count(*) as id,sum(no_of_like) as no_of_like,sum(no_of_comment) as no_of_comment");
                $query->andwhere(['not in','user_id',$block_id]);
                $query->andHaving("distance < $distance");
                $query->orderBy(['no_of_like'=>SORT_DESC,'id' => SORT_DESC]);
                $data = $query->limit(5)->groupBy('latitude,longitude')->all();

                $result['Post'] = array();

                if($data != array())
                {
                    $category = Category::find()->where(['is_active'=>'Y','is_deleted'=>'N'])->all();
                    $category_image = ArrayHelper::map($category,'id','full_image');
                    $category_name = ArrayHelper::map($category,'id','name');
                    $category = ArrayHelper::map($category,'id','color_code');
                    $i = 0;
                    foreach($data as $post)
                    {
                        $result['Post'][$i]['distance'] = round($post->distance,2);
                        //if(isset($_POST['cur_latitude']) && $_POST['cur_latitude'] != '' && isset($_POST['cur_longitude']) && $_POST['cur_longitude'] != '')
                        //{
                        //    $result['Post'][$i]['distance'] = round(Yii::$app->common->distance($post->latitude,$post->longitude,$_POST['cur_latitude'],$_POST['cur_longitude'],$dis_type),1).' '.$dis_type;
                        //}

                        $result['Post'][$i]['category_id'] = $post->category_id;

                        $result['Post'][$i]['category_image'] = '';
                        if(isset($category_image[$post->category_id]))
                        $result['Post'][$i]['category_image'] = $category_image[$post->category_id];


                        $result['Post'][$i]['id'] = $post->id_list;

                        $result['Post'][$i]['location'] = $post->location;
                        $result['Post'][$i]['latitude'] = $post->latitude;
                        $result['Post'][$i]['longitude'] = $post->longitude;
                        $result['Post'][$i]['no_of_post'] = $post->id;
                        $result['Post'][$i]['no_of_like'] = $post->no_of_like;
                        $result['Post'][$i]['no_of_comment'] = $post->no_of_comment;
                        $ids = explode(',',$post->id_list);
                        $count = Images::find()->where(['post_id'=>$ids,'is_deleted'=>'N'])->count();
                        $result['Post'][$i]['image_count'] = $count;
                        $i++;
                    }
                }


                $this->setHeader(200);
                $result['code'] = 200;
                $result['status'] = Yii::$app->params['response_text'][$result['code']];
                $resultstring = json_encode($result);
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
        else
        {
            $this->setHeader(400);
            echo json_encode(array('code'=>400,'status'=>'error','message'=>Yii::$app->params['response_text'][400]));
            die;
        }
    }

}
