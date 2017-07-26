<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Cms */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Cms'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<!-- <div class="content-wrapper"> -->
  <section class="content-header">
    <?php echo Yii::$app->getSession()->getFlash('flash_msg');?>
        <h1>
            CMS
            <small>Control panel</small>
        </h1>
</section>
<section class="content col-lg-11">      
    <div class="box box-primary">
        <div class="row">
                <div class="col-lg-12">
                     
                      <section class="panel">
                          <header class="panel-heading">
                            
							
                          </header>
                          <div class="panel-body">
                            <p>
        <?= Html::a(Yii::t('app', 'Back'), ['index'], ['class' => 'btn btn-primary pull-right']) ?>
        
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            
            'title',
            'content:ntext',
            
        ],
    ]) ?>
                          </div>
                          </section>
                </div>
        </div>
    
    </section>
   

    


</div>
