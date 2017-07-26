<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

echo "<?php\n";
?>

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->searchModelClass, '\\') ?> */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pull-right filter-bottom-margin">

    <?= "<?php " ?>$form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div class="input-group" style="width: 250px;">
        <?= "<?=" ?> $form->field($model, 'id',['template' => '{input}'])->textInput(['placeholder'=>'Search','class'=>'form-control pull-right','autofocus'=>true])->label(false); ?>
        <div class="input-group-btn">
            <button class="btn btn-default" type="submit" value="Search"><i class="fa fa-search"></i></button>
        </div>
    </div>

    <?= "<?php " ?>ActiveForm::end(); ?>

</div>
