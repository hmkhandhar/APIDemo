<?php

namespace app\controllers;

use Yii;
use app\models\Product;
use app\models\ProductSearch;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProductController implements the CRUD actions for Product model.
 */
class ProductController extends Controller
{
    /**
     * @inheritdoc
     */
    // public $layout="/front/dashboard";
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
     * Lists all Product models.
     * @return mixed
     */
    public function actionIndex()
    {
        $this->layout = 'front/dashboard';
        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Product model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $this->layout = 'front/dashboard';
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Product model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Product();
        $this->layout = 'front/dashboard';

        if ($model->load(Yii::$app->request->post()))
        {
            $params = Yii::$app->request->post();
            echo "first If";         
            if(isset($_FILES['Product']['name']['image']) && $_FILES['Product']['name']['image'] != null)
                {
                    echo "if in file";
                    list($width, $height) = getimagesize($_FILES['Product']['tmp_name']['image']);                    
                    $new_image['name'] = $_FILES['Product']['name']['image'];
                    $new_image['type'] = $_FILES['Product']['type']['image'];
                    $new_image['tmp_name'] = $_FILES['Product']['tmp_name']['image'];
                    $new_image['error'] = $_FILES['Product']['error']['image'];
                    $new_image['size'] = $_FILES['Product']['size']['image'];
                    $image = $new_image;
                                        
                    $name = Yii::$app->mycomponent->uploadUserImage($image, Yii::getAlias('@webroot')."/".Yii::$app->params['productimage'], $width, $width);
                    $model->image = Yii::$app->params['productimage'].$name['image'];
                    
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
        
            if($model->save(false))
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
     * Updates an existing Product model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $this->layout = 'front/dashboard';

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Product model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        $this->layout = 'front/dashboard';

        return $this->redirect(['index']);
    }

    /**
     * Finds the Product model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $this->layout = 'front/dashboard';
        if (($model = Product::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
