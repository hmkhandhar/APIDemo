<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

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
 $this->title = Yii::t('app', 'Admin');
?>

<section class="content-header">
    <?php echo Yii::$app->getSession()->getFlash('flash_msg');?>
        <h1>
            Change Password            
        </h1>
</section>
<section class="content col-md-11">
        <div class="box box-primary col-md-6">
            <div class="box-header with-border">
                <!-- <h2 class="box-title"><?php if($model->isNewRecord) { echo Yii::t('app', 'Create'); } else{ echo Yii::t('app', 'Edit');} ?> User </h2> -->
            </div>
    <?php $form = ActiveForm::begin(
    [
        'id'=>'users-form',
		//'enableAjaxValidation' => true,
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
            <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>

			<?= $form->field($model, 'new_password')->passwordInput(['maxlength' => true]) ?>
	
			<?= $form->field($model, 'PasswordConfirm')->passwordInput(['maxlength' => true]) ?>
			
        </div>
    </div>
</div>
<div class="box-footer">
    <div class="row">
        <div class="col-md-8 col-lg-6">
            <div class="row">
                <div class="col-sm-8 col-sm-offset-4">
                    <?=  Html::submitButton($model->isNewRecord ? Yii::t('app', 'Update') : Yii::t('app', 'Update'), ['class' =>'btn btn-primary load-button']) ?>
					<?=  Html::a(Yii::t('app', 'Cancel'),['default/index'],['class'=>"btn btn-default"]); ?>
                    
                </div>
            </div>
        </div>
    </div>
</div>
    <?php ActiveForm::end(); ?>
</div>
</section>
</div>



<script>


    $( document ).ready(function() {
			

        checkusernamepath = "/admin/users/checkusername";
		checkemail = "/users/checkemail";
		pass_check = "/admin/default/oldpasswordcheck";
		userid=0;
		
        <?php
		if(isset($_REQUEST['id']))
		{
		?>
		      userid=<?php echo $_REQUEST['id']?>;
		<?php
		}
		?>
		
		
		jQuery.validator.addMethod("checkpass", function (value, element) {
                var result = true;
                path = "<?php echo Yii::$app->request->baseUrl; ?>";
				
                $.ajax({
                    type:"POST",
                    async: false,
                    url: path+pass_check, // script to validate in server side
                    data: {pass: value,_csrf: yii.getCsrfToken(),'id':userid},
                    success: function(data) {
						//alert(path+'--'+userid+'--'+data);
                        result = (data == 1) ? true : false;
                    }
                });
                return result;
        }, "Please enter Correct Old Passward! Try another.");
		
		
	   jQuery.validator.addMethod("checkemail", function (value, element) {
                var result = true;
                path = "<?php echo Yii::$app->request->baseUrl; ?>";
				
                $.ajax({
                    type:"POST",
                    async: false,
                    url: path+checkemail, // script to validate in server side
                    data: {email: value,_csrf: yii.getCsrfToken(),'id':userid},
                    success: function(data) {
						//alert(path+'--'+userid+'--'+data);
                        result = (data == 1) ? true : false;
                    }
                });
                return result;
        }, "This email is already taken! Try another.");
		
		
		
    			
    	jQuery.validator.addMethod("imagetype", function(value, element) {
    	return this.optional(element) || /^.*\.(jpg|png|jpeg)$/i.test(value);
        }, "Plese Select .jpg .png or .jpeg Image");
	
	    var form1 = $('#users-form');
	    var error1 = $('.alert-danger', form1);
        var success1 = $('.alert-success', form1);
        form1.validate({
	            errorElement: 'span', //default input error message container
	            errorClass: 'help-block', // default input error message class
	            focusInvalid: true, // do not focus the last invalid input
		        rules: {
				
					"Users[password]": {
						required: true,
						checkpass:true
                       // minlength:6,
						//maxlength:16,
					},
					"Users[new_password]": {
						required:true,
					},
					"Users[PasswordConfirm]": {
						required:true,
						equalTo: "#users-new_password"
					},
				},
                    
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
                    if (element.attr("name") == "User[image]") { // for uniform radio buttons, insert the after the given container
                        error.addClass("no-left-padding").insertAfter("#image-error");
                    }
					if (element.attr("name") == "User[coverimage]") { // for uniform radio buttons, insert the after the given container
                        error.addClass("no-left-padding").insertAfter("#coverimage-error");
                    }else {
                        error.insertAfter(element); // for other inputs, just perform default behavoir
                    }
                },
				
	        });
    });
			
</script>
