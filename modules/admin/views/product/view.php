<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\User */

// $this->params['breadcrumbs'][] = $this->title;

// $this->params['breadcrumbs'][] = 'View User :';
?>
<section class="content-header">
    <?php echo Yii::$app->getSession()->getFlash('flash_msg');?>
        <h1>
            Product
            <small>Control panel</small>
        </h1>
</section>
<!-- <div class="content-wrapper"> -->
    <section class="content col-md-11">
        <div class="box box-primary col-md-6">      
    <!-- <div class="row"> -->
        <!-- <div class="col-lg-11 col-xs-6"> -->
        <?=  Html::a(Yii::t('app', 'Back'),['product/index'],['class'=>"btn btn-default"]); ?>
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [            
            [
                                'attribute'=>'id',
                                // 'class' => 'kartik\grid\SerialColumn',
                                // 'width'=>'5%',
                            ],
                            [
                                // 'width'=>'15%',
                                'attribute'=>'name',
                                // 'class' => '\kartik\grid\DataColumn',
                                // 'headerOptions' => ['style' => 'text-align:center'],
                                // 'contentOptions' => ['style' => 'text-align:center;vertical-align:middle'],
                                'filter'=>true,
                            ],
                            [
                                // 'width'=>'15%',
                                'attribute'=>'category',
                                // 'class' => '\kartik\grid\DataColumn',
                                // 'headerOptions' => ['style' => 'text-align:center'],
                                // 'contentOptions' => ['style' => 'text-align:center;vertical-align:middle'],
                                'filter'=>true,
                            ],
                            [
                                // 'width'=>'15%',
                                'attribute'=>'price',
                                // 'class' => '\kartik\grid\DataColumn',
                                // 'headerOptions' => ['style' => 'text-align:center'],
                                // 'contentOptions' => ['style' => 'text-align:center;vertical-align:middle'],
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
        ]) ?>

        </div>
    </div>

</section>

