<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

$modelClass = StringHelper::basename($generator->modelClass);
$name = $modelClass;
$title = strtolower($modelClass);

$attr = ['id','is_active','is_deleted','i_by','i_date','u_by','u_date'];

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

/* @var $model \yii\db\ActiveRecord */
$model = new $generator->modelClass();
$safeAttributes = $model->safeAttributes();
if (empty($safeAttributes)) {
    $safeAttributes = $model->attributes();
}

echo "<?php\n";
?>

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */
/* @var $form yii\widgets\ActiveForm */
if($model->isNewRecord)
{
    $a="true";
}
else
{
    $a="false";
}
?>

<div class="content-wrapper">
    <section class="content-header">
    <h1>
      <?= $name; ?>
    </h1>
    <ol class="breadcrumb">
      <li><?= "<?=" ?>Html::a('<i class="fa fa-dashboard"></i> '.Yii::t('app', 'Home'), ['site/index']) ?></li>
      <li><?= "<?=" ?>Html::a('<?=$name?>', ['<?=$title?>/index']) ?></li>
      <li class="active"><?= "<?php" ?> if($model->isNewRecord) { echo Yii::t('app', 'Create'); } else{ echo Yii::t('app', 'Edit');} ?></li>
    </ol>
  </section>
    <!-- Main content -->
	<section class="content">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h2 class="box-title"><?= "<?php" ?> if($model->isNewRecord) { echo Yii::t('app', 'Create'); } else{ echo Yii::t('app', 'Edit');} ?> <?=$name?> </h2>
            </div>
    <?= "<?php " ?>$form = ActiveForm::begin(
    [
        'id'=>'<?=$title?>-form',
        'layout'=>'horizontal',
        'options' => ['class' => 'form-horizontal','enctype'=>'multipart/form-data'],
        'fieldConfig' => [
            'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
            'horizontalCssClasses' => [
                'label' => 'col-sm-4',
                'wrapper' => 'col-sm-8',
                'error' => '',
                'hint' => '',
            ],
        ],
    ]
    ); ?>
<div class="box-body">
    <div class="row">
        <div class="col-md-8 col-lg-6">
        <?php foreach ($generator->getColumnNames() as $attribute) {
            if (!in_array($attribute, $attr)) {
                echo "    <?= " . $generator->generateActiveField($attribute) . " ?>\n\n";
            }
        } ?>
        </div>
    </div>
</div>
<div class="box-footer">
    <div class="row">
        <div class="col-md-8 col-lg-6">
            <div class="row">
                <div class="col-sm-8 col-sm-offset-4">
                    <?= "<?= " ?> Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' =>'btn btn-primary load-button']) ?>
					<?= "<?= " ?> Html::a(Yii::t('app', 'Cancel'),['<?=$title?>/index'],['class'=>"btn btn-default"]); ?>
                    
                </div>
            </div>
        </div>
    </div>
</div>

    <?= "<?php " ?>ActiveForm::end(); ?>

</div>
