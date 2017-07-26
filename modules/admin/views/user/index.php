<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<section class="content-header">
    <?php echo Yii::$app->getSession()->getFlash('flash_msg');?>
        <h1>
            User
            <small>Control panel</small>
        </h1>
</section>
<section class="content col-lg-11">      
    <div class="box box-primary">
      <!-- <div class="row">
      <div class="col-lg-11 col-xs-6"> -->

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
                            ['class' => 'yii\grid\ActionColumn'],
                        ]   
    ]); ?>
</div>
</section>

