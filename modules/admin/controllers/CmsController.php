<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\Cms;
use app\models\Cmssearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

/**
 * CmsController implements the CRUD actions for Cms model.
 */
class CmsController extends Controller
{
    public $layout="/admin/admin";
    
    public function behaviors()
    {
        return [
            
             'access' => [
                'class' => AccessControl::className(),
                 'only' => ['create','index','update','view','view-invoice'],
                'rules' => [
                    [
                        'actions' => ['create','index','update','view','view-invoice'],
                        'allow' => true,
                        'roles' => ['@'],
                         'matchCallback' => function ($rule, $action)
                        {
                            // $response = Yii::$app->mycomponent->authenticate($action->controller->id,$action->id);
                            $response = true;
                            //return $response;
                            if(!$response)
                               {
                                    $msg = 'Not allowed to perform this action.';
                                    $flash_msg = \Yii::$app->params['msg_error'].$msg.\Yii::$app->params['msg_end'];
                                    \Yii::$app->getSession()->setFlash('flash_msg', $flash_msg);

                                    return $this->redirect(['site/index']);
                               }
                                else
                                {
                                   return $response;
                                }
                        
                        },
                    ],
                ],
                
                /*'denyCallback' => function () {
                        return $this->redirect(\Yii::$app->request->getReferrer());
                    },*/
            ],
            
            
            
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    //'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Cms models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new Cmssearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Cms model.
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
     * Creates a new Cms model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Cms();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Cms model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        
        $model = $this->findModel($id);
        
        if ($model->load(Yii::$app->request->post()))
        {
            $model->u_by = Yii::$app->user->id;
            $model->u_date = time();
            if($model->save())
            {
                $msg = '<div class="alert alert-dismissable alert-success fade in">
                                  <button data-dismiss="alert" class="close close-sm" type="button">
                                      <i class="fa fa-times"></i>
                                  </button>
                                  <strong>Success!</strong> '.' CMS Page has been successfully updated.'.'</div>';
                Yii::$app->getSession()->setFlash('flash_msg', $msg);
                return $this->redirect('index');
            }
            else
            {
                $msg = Yii::$app->params['msg_error'].' Enable to update CMS Page. Please try again later.'.Yii::$app->params['msg_end'];
                Yii::$app->getSession()->setFlash('flash_msg', $msg);
                return $this->redirect('index');
            }
            
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Cms model.
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
     * Finds the Cms model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Cms the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Cms::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    /*
     *  Set Page Number for paggination
     */
    public function actionPage()
    {
        if(isset($_REQUEST['size']) && $_REQUEST['size']!=null)
        {
         \Yii::$app->session->set('user.size',$_REQUEST['size']);
        }
    }
    
    public function actionPrivacy()
    {
        $this->layout = false;
        return $this->render('privacy');
    }
    public function actionHelp()
    {
        $this->layout = false;
        return $this->render('help');
    }
    public function actionTerms()
    {
        $this->layout = false;
        return $this->render('terms');
    }
}
