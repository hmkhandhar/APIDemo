<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <!-- <p>
        <?= Html::a('Create User', ['create'], ['class' => 'btn btn-success']) ?>
    </p> -->
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
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
                                'attribute'=>'email',
                                // 'class' => '\kartik\grid\DataColumn',
                                'headerOptions' => ['style' => 'text-align:center'],
                                'contentOptions' => ['style' => 'text-align:center;vertical-align:middle'],
                                'filter'=>true,
                            ],
                            [
                                // 'width'=>'15%',
                                'attribute'=>'mobile',
                                // 'class' => '\kartik\grid\DataColumn',
                                'headerOptions' => ['style' => 'text-align:center'],
                                'contentOptions' => ['style' => 'text-align:center;vertical-align:middle'],
                                'filter'=>true,
                            ],
                            [
                                // 'width'=>'15%',
                                'attribute'=>'dob',
                                // 'class' => '\kartik\grid\DataColumn',
                                'headerOptions' => ['style' => 'text-align:center'],
                                'contentOptions' => ['style' => 'text-align:center;vertical-align:middle'],
                                'filter'=>true,
                            ],
                            [
                                // 'width'=>'15%',
                                'attribute'=>'address',
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
