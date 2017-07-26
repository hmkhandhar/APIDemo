<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Users */

$this->title =  Yii::$app->params['apptitle']." Edit CMS Page";
$this->params['breadcrumbs'][] = ['label' => 'Box', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
//echo 'asd'; die;
?>
<section id="main-content">
          <section class="wrapper">
        <?= $this->render('_form', [
            'model' => $model,
            
        ]) ?>
    </section>
</section>

