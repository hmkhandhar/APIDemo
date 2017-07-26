<?php

use yii\helpers\Html;
use yii\helpers\country;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Users */
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
</div>
</section>
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
                              <!--<form class="form-horizontal" role="form">-->
									<?php $form = ActiveForm::begin([
										'id'=>'user-form',
										'options' => ['class' => 'form-horizontal','enctype'=>'multipart/form-data'],
										'fieldConfig' => [
											//'template' => " <div class=\"control-group\">{lable}<div class=\"controls\">{input}</div>\n<div class=\"col-lg-7\">{error}</div></div>",
											'enableClientValidation'=>true,
											'enableAjaxValidation'=>false,
											'template' => "{input}{error}",
											// 'inputOptions' => ['class' => 'm-wrap span6'],
										],
									]);
								?>
                                    <div class="form-group">
                                      <label for="" class="col-lg-2 col-sm-2 control-label">Title<span class="required">*</span></label>
                                      <div class="col-lg-4">
                       					<?= $form->field($model, 'title')->textInput(['maxlength' => 255,'class'=>'form-control'])->label(false); ?>
                                      </div>
                                   </div>
                                    <div class="form-group">
                                      <label for="" class="col-lg-2 col-sm-2 control-label">Content<span class="required">*</span></label>
                                      <div class="col-lg-8">
										<?= $form->field($model, 'content')->textArea(["rows"=>"10",'class'=>'wysihtml5 form-control'])->label(false); ?>
                                      </div>
                                  </div>
								
								<div class="form-group">
                                    <div class="col-lg-offset-2 col-lg-10">
										<button type="submit" class="btn btn-success"><?php echo Yii::t('app', 'Submit'); ?></button>
										<?php echo Html::a('<button type="button" class="btn btn-default">'.Yii::t('app', 'Cancel').'</button>',["cms/index"]); ?>
                                    </div>
                                </div>
								
                              <!--</form>-->
			      <?php ActiveForm::end(); ?>
                          </div>
                      </section>
                      
                  </div>
              </div>
</div>
<script>
	$(function(){
		$('.wysihtml5').wysihtml5();
		$.fn.editable.defaults.ajaxOptions = {type: "PUT"};
})
</script>
