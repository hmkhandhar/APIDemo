<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\User;
use app\models\Users;
use app\models\LoginFormUser;

class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['get'],
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
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $this->layout = 'front/dashboard';
        return $this->render('home');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        $user1 = new Users();
		if(isset(Yii::$app->user->identity->id) && Yii::$app->user->identity->id!=null){
        	return $this->redirect(["/site/index"]);
		}
		else{
        	$this->layout = 'front/dashboard';
			return $this->render('login', [
				'user1' => $user1,
			]);
		}
    }

    public function actionPerformlogin()
     {
		$this->layout = 'front/dashboard';

        $model = new LoginFormUser();

        if($model->load(Yii::$app->request->post()) && $model->login())
        {
            // echo "Login Per";exit();
			// $isVarified = Yii::$app->user->identity->is_email_verified;
   //          if($isVarified == "N"){
   //          	Yii::$app->user->logout(false);
   //              $msg = "Please verify your account to login";
   //              $flash_msg = \Yii::$app->params['msg_error'].$msg.\Yii::$app->params['msg_end'];
   //              \Yii::$app->getSession()->setFlash('flash_msg', $flash_msg);
   //                return $this->redirect(["/site/login"]);
			// }

   //          $isActive = Yii::$app->user->identity->is_active;
   //          if($isActive == "N"){
   //          	Yii::$app->user->logout(false);
   //              $msg = "Your account is inactive. Please contact admin to reactivate your account";
   //              $flash_msg = \Yii::$app->params['msg_error'].$msg.\Yii::$app->params['msg_end'];
   //              \Yii::$app->getSession()->setFlash('flash_msg', $flash_msg);
   //                return $this->redirect(["/site/login"]);
			// }


            if(isset($_POST['LoginFormUser']['rememberMe']) && $_POST['LoginFormUser']['rememberMe'] =="1")
            {
            	$cookies = Yii::$app->response->cookies;
                  
                $no = rand(1,9);

                $v1 = $_POST['LoginFormUser']['email_id'];
                $v2 = $_POST['LoginFormUser']['password'];

                for($i=1;$i<=$no;$i++){
                	$v1 = base64_encode($v1);
                    $v2 = base64_encode($v2);
				}

                $cookies->add(new \yii\web\Cookie([
                	'name' => Yii::$app->params['appcookiename'].'email_id',
                    'value' => $v1,
				]));

                $cookies->add(new \yii\web\Cookie([
                	'name' => Yii::$app->params['appcookiename'].'password',
                    'value' => $v2,
				]));

                $cookies->add(new \yii\web\Cookie([
                	'name' => Yii::$app->params['appcookiename'].'turns',
                    'value' => $no,
				]));
				echo "Set Ok If"; exit();

			}else{

            	$cookies = Yii::$app->response->cookies;
                $cookies->remove(Yii::$app->params['appcookiename'].'email_id');
                unset($cookies[Yii::$app->params['appcookiename'].'email_id']);
                $cookies->remove(Yii::$app->params['appcookiename'].'password');
                unset($cookies[Yii::$app->params['appcookiename'].'password']);
                $cookies->remove(Yii::$app->params['appcookiename'].'turns');
                unset($cookies[Yii::$app->params['appcookiename'].'turns']);
                return $this->redirect(["/site/login"]);
			}
		} 
		else
		{
			if($model->load(Yii::$app->request->post()))
			{
            	$msg = "Email id or password is wrong";
                $flash_msg = \Yii::$app->params['msg_error'].$msg.\Yii::$app->params['msg_end'];
                \Yii::$app->getSession()->setFlash('flash_msg', $flash_msg);
			}
            return $this->redirect(["/site/login"]);
              /*
              return $this->render('login', [
                  'model' => $model,
              ]);
              */
       	}
	}

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
    	$this->layout = 'front/dashboard';
        Yii::$app->user->logout(false);
        return $this->redirect(['index']);
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        $this->layout = 'front/dashboard';
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */

    
     





    public function actionAbout()
    {
        $this->layout = 'front/dashboard';
        return $this->render('about');
    }

    public function actionSignup()
    {
		if(isset(Yii::$app->user->identity->id) && Yii::$app->user->identity->id!=null){
			return $this->redirect(["/site/index"]);
		}else{
            // echo "Errror";
			$this->layout = 'front/dashboard';
			return $this->render('signup');
		}
	}

    public function actionPerformsignup()
    {    
    	// echo "hellll";exit();
		$post = Yii::$app->request->post();
		if(isset($post['full_name']) && $post['full_name']!= "" && isset($post['email_id']) && $post['email_id']!= "" && isset($post['mobile_number']) && $post['mobile_number']!= "" && isset($post['password']) && $post['password']!= ""){
				$model = new Users();
                $model->name = $post['full_name'];
                $model->email = $post['email_id'];
                $model->password = md5($post['password']);
                $model->mobile = $post['mobile_number'];

                $random_str = time().rand(10000,99999);
                
                $model->user_type = "U";
                $model->i_date = time();
                $model->u_date = time();
                $model->save(false);
                
                $flash_msg = \Yii::$app->params['msg_success'].' Registered successfully, check your email to verify your account.'.\Yii::$app->params['msg_end'];
                \Yii::$app->getSession()->setFlash('flash_msg', $flash_msg);
                return $this->redirect(['login']);                

		}else{
			echo "Errrorrrrrrrrrrrrrr"; exit();
            return $this->redirect(['signup']);

		}
	}




    public function actionEditprofile()
    {
        echo "Edit Call <br>";
        $this->layout = 'front/dashboard';
        $model = new Users();
        if(isset(Yii::$app->user->identity->id) && Yii::$app->user->identity->id!=null){

            echo "Edit Call if<br> ";
            $model = Users::find()->where(['id'=>Yii::$app->user->identity->id])->one();
         
               if ($model->load(Yii::$app->request->post()))
               {

                echo "Edit Call if Second <br>";
                   if(isset($_FILES['Users']['name']['image']) && $_FILES['Users']['name']['image'] != null)
                    {   
                        echo "Edit Call third <br>";
                         list($width, $height) = getimagesize($_FILES['Users']['tmp_name']['image']);
     
                         $new_image['name'] = $_FILES['Users']['name']['image'];
                         $new_image['type'] = $_FILES['Users']['type']['image'];
                         $new_image['tmp_name'] = $_FILES['Users']['tmp_name']['image'];
                         $new_image['error'] = $_FILES['Users']['error']['image'];
                         $new_image['size'] = $_FILES['Users']['size']['image'];
                         $image = $new_image;
     
                         $name = Yii::$app->mycomponent->uploadUserImage($image, Yii::getAlias('@webroot')."/".Yii::$app->params['userimage'], $width, $width);
                         $model->image = Yii::$app->params['userimage'].$name['image'];
                    } 
                   
                   $model->u_by = Yii::$app->user->id;
                   $model->u_date = time();
                    // if($model->dob != ""){
                    //      $model->dob = str_replace("/", "-", $model->dob);
                    //      $model->dob = strtotime($model->dob);
                     // }
                   if($model->save(false)){
                        echo "Edit Call save <br>";
                       return $this->redirect(['index']);
                   }
                   else{
                        echo " not saved <br>";
                       //exit;
                       return $this->render('edit_profile', [
                           'model' => $model,
                       ]); 
                   }
               } else {
                   echo "Edit Call last else";
                   return $this->render('edit_profile', [
                       'model' => $model,                       
                   ]);
               }
         }else{
            return $this->redirect(['login']);
          }
     }


  public function actionChangepassword()
      {
        $this->layout = 'front/dashboard';
        $model = new Users();
        
        if($model->load(Yii::$app->request->post()))
        {
           $model = Users::find()->where(['id'=>Yii::$app->user->id])->one();
            if($model->password == md5($_POST['Users']['password']))
            {
                if(trim($_POST['Users']['new_password']) ==trim($_POST['Users']['PasswordConfirm']))
                {
                    $model->u_by = Yii::$app->user->id;
                    $model->u_date = time();
                    $model->password = md5($_POST['Users']['new_password']);
                }
            }
            if($model->save(false)){
                return $this->redirect(['site/index']);
            }
            else{
                // echo "first error chng psw";exit();
                return $this->render('changepassword', [
                    'model' => $model,
                ]); 
            }
         } else {
            // echo "Second error chng psw";
            return $this->render('changepassword', [
                'model' => $model,
            ]);
        }
    }
    
    public function actionOldpasswordcheck(){
        $this->layout = false;
        if(isset($_REQUEST["pass"])){
            
            $getpass = md5($_REQUEST['pass']);
            $model = Users::find()->where(['id'=>Yii::$app->user->id])->one();
            
            if($getpass == $model->password){
                echo true;
                die;
            }else{
                echo false;
                die;
            }
        }
        die;
    }

  
}
