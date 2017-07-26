<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Products';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-index">
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        // 'filterModel' => $searchModel,                        
                        'summary' => false,
                        'columns' => [
                            [
                                'attribute'=>'id',
                                // 'class' => 'kartik\grid\SerialColumn',
                                // 'width'=>'5%',
                            ],
                            [
                                // 'width'=>'15%',
                                'attribute'=>'name',
                                // 'class' => '\kartik\grid\DataColumn',
                                'headerOptions' => ['style' => 'text-align:center'],
                                'contentOptions' => ['style' => 'text-align:center;vertical-align:middle'],
                                'filter'=>true,
                            ],
                            [
                                // 'width'=>'15%',
                                'attribute'=>'category',
                                // 'class' => '\kartik\grid\DataColumn',
                                'headerOptions' => ['style' => 'text-align:center'],
                                'contentOptions' => ['style' => 'text-align:center;vertical-align:middle'],
                                'filter'=>true,
                            ],
                            [
                                // 'width'=>'15%',
                                'attribute'=>'price',
                                // 'class' => '\kartik\grid\DataColumn',
                                'headerOptions' => ['style' => 'text-align:center'],
                                'contentOptions' => ['style' => 'text-align:center;vertical-align:middle'],
                                'filter'=>true,
                            ],

                            [
                                                // 'width'=>'15%',
                                'attribute'=>'image',
                                'headerOptions' => ['style' => 'text-align:center'],
                                'contentOptions' => ['style' => 'text-align:center'],
                                'filter'=>false,
                                'label'=>'Image',
                                'format'=>'raw',
                                'value'=> function($model){
                                if($model->image != '') 
                                {
                                $img = '<div class="thumbnail mythumblist" style="max-height: 70px;width: 120px;"><img src="'.Yii::$app->request->baseUrl."/".$model->image .'" /></div>';
                                } else {
                                $img = '<div class="thumbnail mythumblist" style="max-height: 70px;width: 120px;"><img src="http://www.placehold.it/120x70/EFEFEF/AAAAAA?text=no+image" alt="" /></div>';
                                } 
                                return $img;
                                }
                            ],
                        ]
                    ]); ?>
</div>


<!-- <div class="row">
            <div class="col-lg-5">
                <table border="1">
                <tr>                
                    <th>Id</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Image</th>
                </tr>
                <tr>
                    <td>1</td>
                    <td>Abc</td>
                    <td>Abc</td>
                    <td>15000</td>
                    <td>132</td>
                </tr>
                </tr>


                     
                 </table>       
            </div>
        </div> -->