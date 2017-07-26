<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\web\Controller;
use app\models\Users;
use app\models\Product;
use app\models\LoginFormUser;
/**
 * Default controller for the `admin` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    // public $layout="/admin/admin";
    public function actionIndex()
    {
    	// echo "Hi ! \n This is Admin Panel ";exit();    	
        $this->layout = '/admin/admin';
        $allUser = Users::find()->where(['user_type'=>'U','is_deleted'=>'N'])->count();
        $allAdmin = Users::find()->where(['user_type'=>'A','is_deleted'=>'N'])->count();
        // $allProduct = Product::find()->where(['is_deleted'=>'N'])->count();
        return $this->render('home', [
                    'allUser' => $allUser,
                    'allAdmin' => $allAdmin,
                    // 'allProduct' => $allProduct,
                ]);
    }


    public function actionProfileview()
    {
            $this->layout = '/admin/admin';
            $model = Users::find()->where(['id'=>Yii::$app->user->id])->one();
            // $type =  $model->user_type;
        
            return $this->render('profile_view', [
                'model' => $model,
            ]);
            echo "profile_view"; exit();

    }
    public function actionChangepassword(){

          $this->layout = '/admin/admin';
          $model = new Users();
          
          if($model->load(Yii::$app->request->post()))
          {
               
              $model = Users::find()->where(['id'=>Yii::$app->user->id])->one();
              if($model->password == md5($_POST['Users']['password']))
              {
                 
                  if(trim($_POST['Users']['new_password']) ==trim($_POST['Users']['PasswordConfirm']))
                  {
                      //echo "new password match";
                      //exit;
                      $model->u_by = Yii::$app->user->id;
                      $model->u_date = time();
                      $model->password = md5($_POST['Users']['new_password']);
                       
                  }
              }
              if($model->save(false)){
                  echo "second else )";
                  $flash_msg = \Yii::$app->params['msg_success'].' Password updated successfully '.\Yii::$app->params['msg_end'];
                  \Yii::$app->getSession()->setFlash('flash_msg', $flash_msg);

                  $this->redirect(['index']);
              }
              else{
                echo "first else )";
                  //print_r($model->getErrors());
                  return $this->render('password_edit', [
                      'model' => $model,
                  ]); 
              }

          } else {
              echo "second else )";
              return $this->render('password_edit', [
                  'model' => $model,
              ]);
          }
      }

          public function actionLogin(){

            $this->layout = '/admin/login';
            //print_r(\Yii::$app->user->isGuest); die;            
            $model = new LoginFormUser();
            $user = new Users();
            $user1 = new Users();

           
            
            //print_r($model->login()); die;
            if($model->load(Yii::$app->request->post()) && $model->login())
            {
                $type = Yii::$app->user->identity->user_type;
                // echo $type;exit();
                if($type != "A"){
                 Yii::$app->user->logout(false);
                    $msg = "Your account is User Type. <br>Please <a href='/demo/web/index.php/site/login/''>Login</a> as User";
                    $flash_msg = \Yii::$app->params['msg_error'].$msg.\Yii::$app->params['msg_end'];
                    \Yii::$app->getSession()->setFlash('flash_msg', $flash_msg);
                      return $this->redirect(["/admin/default/login"]);
                }
                if(isset($_POST['LoginForm']['rememberMe']) && $_POST['LoginForm']['rememberMe'] =="1")
                {
                    $cookies = Yii::$app->response->cookies;
                    // add a new cookie to the response to be sent

                    $no = rand(1,9);

                    $v1 = $_POST['LoginForm']['email_id'];
                    $v2 = $_POST['LoginForm']['password'];

                    for($i=1;$i<=$no;$i++){
                        $v1 = base64_encode($v1);
                        $v2 = base64_encode($v2);
                    }

                    $cookies->add(new \yii\web\Cookie([
                        'name' => Yii::$app->params['appcookiename'].'email',
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

                }else{
                    $cookies = Yii::$app->response->cookies;
                    $cookies->remove(Yii::$app->params['appcookiename'].'email');
                    unset($cookies[Yii::$app->params['appcookiename'].'email']);
                     $cookies->remove(Yii::$app->params['appcookiename'].'password');
                    unset($cookies[Yii::$app->params['appcookiename'].'password']);
                    $cookies->remove(Yii::$app->params['appcookiename'].'turns');
                    unset($cookies[Yii::$app->params['appcookiename'].'turns']);

                }
                return $this->redirect(["/admin/default/"]);
                //return $this->goBack();
            } else {

                if($model->load(Yii::$app->request->post()))
                {
                    $msg = "Email Id or Password is wrong";
                    $flash_msg = \Yii::$app->params['msg_error'].$msg.\Yii::$app->params['msg_end'];
                    \Yii::$app->getSession()->setFlash('flash_msg', $flash_msg);
                }
                // echo "asdfs"; exit;
                return $this->render('login', [
                    'model' => $model,
                    'user'=>$user,
                    'user1'=>$user1,
                ]);
            }
      }

    public function actionLogout()
    {
        Yii::$app->user->logout(false);
        return $this->redirect(['index']);
    }
}

