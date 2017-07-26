<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\Users;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UserController implements the CRUD actions for Users model.
 */
class UserController extends Controller
{
    /**
     * @inheritdoc
     */
    public $layout="/admin/admin";
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Users models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Users::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Users model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Users model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {

        $model = new Users();
        $this->layout = '/admin/admin';

        if ($model->load(Yii::$app->request->post()))
        {
            $params = Yii::$app->request->post();
            echo "first If";         
            if(isset($_FILES['Users']['name']['image']) && $_FILES['Users']['name']['image'] != null)
                {
                    echo "if in file";
                    list($width, $height) = getimagesize($_FILES['Users']['tmp_name']['image']);                    
                    $new_image['name'] = $_FILES['Users']['name']['image'];
                    $new_image['type'] = $_FILES['Users']['type']['image'];
                    $new_image['tmp_name'] = $_FILES['Users']['tmp_name']['image'];
                    $new_image['error'] = $_FILES['Users']['error']['image'];
                    $new_image['size'] = $_FILES['Users']['size']['image'];
                    $image = $new_image;
                                        
                    $name = Yii::$app->mycomponent->uploadUserImage($image, Yii::getAlias('@webroot')."/".Yii::$app->params['userimage'], $width, $width);
                    $model->image = Yii::$app->params['userimage'].$name['image'];
                    
                        if($model->save())
                        {
                            echo "first second";
                            return $this->redirect(['index']);
                        }
                }
                 else{
                    echo "<br>first else";
                    $file_msg = 'Please Select File';
                     //Yii::$app->getSession()->setFlash('flashfilr_msg', $file_msg);
                    return $this->render('create', [
                    'model' => $model,
                    ]);
                }
        
            if($model->save())
            {
                echo "first t";
                return $this->redirect(['index']);
            } else {
                echo "second else";
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        } else {
            // echo "last else";
            return $this->render('create', [
                'model' => $model,
            ]);
        }
        
    }

    /**
     * Updates an existing Users model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {

        $model = $this->findModel($id);
        $old_media_path = $model->image;
        $oldPassword = $model->password;

        // echo "Update call ";die();
        if ($model->load(Yii::$app->request->post()))
        {
            // $model->u_by = Yii::$app->Users->id;
            $model->u_date = time();

            // if($model->dob != ""){

            //     $model->dob = str_replace("/", "-", $model->dob);
            //     $model->dob = strtotime($model->dob);
            // }


            // echo "Update call ";die();
            // if(Yii::$app->request->post()['Users']['password'] != ""){
                
            //     $model->password = md5($model->password);

            // }else{
                
            //     $model->password = $oldPassword;
            // }
            

            if(isset($_FILES['Users']['name']['image']) && $_FILES['Users']['name']['image'] != null)
            {
                // echo "Update call ";die();
                if($old_media_path != '' && $old_media_path != null && file_exists(Yii::getAlias('@webroot').'/'.$old_media_path))
                {
                    unlink(Yii::getAlias('@webroot')."/".$old_media_path);
                }
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
             else{
                $model->image = $old_media_path;
            }

            
            if($model->save(false))
            {
                echo "first t";
                return $this->redirect(['index']);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]); 
            }

        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }


        
    }

    /**
     * Deletes an existing Users model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Users model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Users the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Users::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
