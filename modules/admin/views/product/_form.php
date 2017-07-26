<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Product */
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
<section class="content-header">
    <?php echo Yii::$app->getSession()->getFlash('flash_msg');?>
        <h1>
            Product
            <small>Control panel</small>
        </h1>
</section>
<section class="content col-md-11">
        <div class="box box-primary col-md-6">
            <div class="box-header with-border">
                <!-- <h2 class="box-title"><?php if($model->isNewRecord) { echo Yii::t('app', 'Create'); } else{ echo Yii::t('app', 'Edit');} ?> User </h2> -->
            </div>
            <?php $form = ActiveForm::begin(
            [
                'id'=>'product-form',
                'layout'=>'horizontal',
                'options' => ['class' => 'form-horizontal','enctype'=>'multipart/form-data'],
                'fieldConfig' => [
                    'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
                    'horizontalCssClasses' => [
                        'label' => 'col-sm-2',
                        'wrapper' => 'col-sm-8',
                        'error' => '',
                        'hint' => '',
                    ],
                ],
            ]
            ); ?>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-8 col-lg-10">
                    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'category')->textInput(['maxlength' => true])?>

                    <?= $form->field($model, 'price')->textInput(['maxlength' => true]) ?>

                        <div class="form-group field-advertise-media_path has-success" id ="image">
                            <label class="control-label col-sm-2" for="advertise-media_path">Image</label>
                            <div class="col-sm-10">
                                    <div class="fileupload fileupload-new" data-provides="fileupload">
                                        <div class="fileupload-new thumbnail mythumbnail" style="width: 200px; height: 150px;">
                                            <?php  if($model->image != '') { ?>
                                            <img src="<?= Yii::$app->request->baseUrl."/".$model->image ?>" />
                                            <?php } else { ?>
                                            <img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=no+image" alt="" />
                                            <?php } ?>

                                            <!-- <?php  if($model->image != '') { ?>
                                                <a href="javascript:void(0);" data-name="image" data-id="<?=$model->id?>" data-path="<?=$model->image?>" class="btn btn-danger btn-icon-only myremovebtn"><i class="fa fa-close"></i></a>
                                        <?php } ?> -->

                                        </div>
                                        
                                        <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
                                        <div>
                                         <span class="btn btn-white btn-file">
                                         <span class="fileupload-new"><i class="fa fa-paper-clip"></i> Select image</span>
                                         <span class="fileupload-exists"><i class="fa fa-undo"></i> Change</span>
                                         <input type="file" id="productimage" class="default" name="Product[image]" />
                                         <input type="hidden" id="hiddenimage" value="<?=$model->image?>" class="default" />
                                         </span>
                                            <a href="#" class="btn btn-danger fileupload-exists btnremovefile" data-dismiss="fileupload" id = "remove"><i class="fa fa-trash"></i> Remove</a>
                                        </div>
                                    </div>
                                    <div class="help-block help-block-error"></div>
                                <?=  Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), ['class' =>'btn btn-primary load-button submit']) ?>
                                <?=  Html::a(Yii::t('app', 'Cancel'),['product/index'],['class'=>"btn btn-default"]); ?>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php ActiveForm::end(); ?>

</div>

</section>
</div>

<!-- <script type="text/javascript">

    $("body").on("click",".btnremovefile",function(){
        $("#productimage").attr('name','Product[image]');
        $("#productimage").val("")
    });

    productid=0;
    <?php
    if(!$model->isNewRecord)
    {
        //echo "productid = ".$model->id."; \n";
    }
    
    ?>
    
    // <?php if($model->isNewRecord){?>
    //         $('.submit').attr('disabled',true);
    // <?php } ?>

    var form = $('#product-form');
    var error1 = $('.alert-danger', form);
    var success1 = $('.alert-success', form);
    form.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: true, // do not focus the last invalid input
            rules: {
                "Product[name]": {
                    required: true,
                },                
                "Product[price]": {
                    required: true,
                },
                "Product[category]": {
                    required: true,
                    digits:true
                },                
            },
            
            onkeyup: false,

            invalidHandler: function (event, validator) { //display error alert on form submit
                success1.hide();
                error1.show();
            },

            highlight: function (element) { // hightlight error inputs
                $(element)
                    .closest('.form-group').addClass('has-error'); // set error class to the control group
            },

            success: function (label) {
                label
                    .closest('.form-group').removeClass('has-error'); // set success class to the control group

            },
            errorPlacement: function (error, element) { // render error placement for each input type
                if (element.attr("name") == "Product[image]") { // for uniform radio buttons, insert the after the given container
                    error.addClass("no-left-padding").insertAfter("#image-error");
                }                
            },

   });

    /* remove user image start*/
    $('body').on('click','.myremovebtn',function(){

        var a=confirm("Are you sure want to delete this image?");
        if (a) {

            var $this = $(this);
            var image_path = $this.attr('data-path');
            var image_name = $this.attr('data-name');
            var id = $this.attr('data-id');
            var blank_image = '<img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image" alt="">';
            var target_url = "<?=Yii::$app->request->baseUrl?>/img/";

            $.ajax({type: "GET",
                url: target_url,
                type:"POST",
                dataType:'json',
                data: { id: id,image_name:image_name,image_path:image_path},
                success:function(response){
                    $this.parent().parent().find('.mythumbnail').html(blank_image);
                    $this.hide();
                }
            });

            
        }
    });
    /* remove user image end*/

</script>
 -->